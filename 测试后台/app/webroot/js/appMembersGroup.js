$(function(){
/*
 * 如果页面body id为app_Group,则进入
 * 为了坚决个子页面事件相互影响
 */


/********************************************************
 ********************************************************
 *  分组添加页面
 ********************************************************
 ********************************************************/

//图片web上传
    var picUp= WebUploader.create({
        // 文件接收服务端。
        server: '/Upload/upload',
        resize: true,
        pick:'#filePic',
        accept:{
             title: 'Images',
             extensions: 'jpg,png,gif,jpeg'
        },
        thumb:{
            width:120,
            height:90,
            crop: true
        },
        auto : true, // 选完文件后，是否自动上传。
        threads : 10, //上传并发数 
        fileNumLimit : 10,//文件总数
        duplicate : true,
        compress : {//只适合jpg格式的文件
            //width: 120,
            //height: 90,

            // 图片质量，只有type为`image/jpeg`的时候才有效。
            //quality: 90,

            // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
            allowMagnify: false,

            // 是否允许裁剪。
            crop: false,

            // 是否保留头部meta信息。
            preserveHeaders:false,

            // 如果发现压缩后文件大小比原来还大，则使用原来图片
            // 此属性可能会影响图片自动纠正功能
            noCompressIfLarger:true,

            // 单位字节，如果图片大小小于此值，不会采用压缩。
            compressSize: 1024
        }
        
    });

            picUp.on( 'beforeFileQueued', function( file) {
                /*console.log(file);
                console.log( picUp.getStats() );*/
            })

            picUp.on( 'fileQueued', function( file ) {
                //console.log(file);
                var id = file.id+'_';
                var $list=$("#source_list");
                var addli=$('<li id="' + id + '" class="source_img">'+
                                '<img src=" "/><input type="hidden" value="" name="data[Product][img][]"/>'+
                                '<a class="up_del" href="javascript:void(0);">×</a>'+
                                '<div class="filename">'+file.name+'</div>'+
                                '<div class="up_status">失败</div>'+
                            '</li>'
                            );
                var $img=addli.find("img");
                $list.append(addli);
                picUp.makeThumb( file, function( error, src ) {
                    if ( error ) {
                        $img.replaceWith('<span>不能预览</span>');
                        return;
                    }
                    $img.get(0).src=src;
                }, 120, 90);
            });
        //上传进度  
        picUp.on( 'uploadProgress', function( file, percentage ){
            upLoad.uploadProgress(file, percentage);
        });
        //上传出错
        picUp.on( 'uploadError', function(file) {
            upLoad.uploadError(file);
        });
        //错误类型
        picUp.on( 'error', function(type) {
            var errorMes = '';
            if(type == 'F_DUPLICATE'){
                errorMes = '请勿重复上传';
            }else if( type == 'Q_TYPE_DENIED'){
                errorMes = '请传入jpg,png,gif,jpeg格式的图片文件';
            }else if(type == 'Q_EXCEED_NUM_LIMIT'){
                errorMes = '最多允许上传图片的数量为10';
            }
            if(!errorMes){
                return;
            }
            var d = dialog({
                    id : 'errorIntegral2',//避免重复打开
                    content: errorMes,
                    onshow: function () {
                        this.__popup.css({'top':'50px'});
                    }
                });

                d.show();

                setTimeout(function(){
                    d.close().remove();
                },5000);
            return;

        });
        //完成上传
        picUp.on("uploadSuccess",function(file,json){
            upLoad.uploadComplete(file,picUp,json);
        });
        //上传时删除文件
        $(document).on("click",".up_url",function(event){
            event.stopPropagation();
            var _id=$(this).parent().get(0).id;
            $(this).parent().remove();
            picUp.removeFile(_id,true);
        });
         //删除物理文件
        $(document).on("click",".up_url_del",function(event){
            $(this).parent().remove();
            //删除物理文件
            
        });
        //开始上传
        /*$(document).on("click",".beginup",function(){
            upLoad.beginUpload(this,picUp);
        });*/
        //暂停
        /*$(document).on("click",".pause",function(){
            picUp.stop();
            $(this).html("继续上传");
            $(this).removeClass("pause");
            $(this).addClass("goon");
        });*/
        //继续上传
        /*$(document).on("click",".goon",function(){
            picUp.upload();
            $(this).html("暂停");
            $(this).addClass("pause");
            $(this).removeClass("goon");
        });*/


    var upLoad={
    //上传文件时创建进度条
    uploadProgress:function( file, percentage ) {
        var id = file.id+'_';
        var $li = $( '#'+id ),
            $percent = $li.find('.progress .progress-bar');

        // 避免重复创建
        if ( !$percent.length ) {
            $percent = $('<div class="progress progress-striped active" style="height:4px;border:1px solid #69c1ff; background:#fff;">' +
              '<div class="progress-bar" role="progressbar" style="width: 0%;height:100%; background:#69c1ff;">' +
              '</div>' +
            '</div>').appendTo( $li ).find('.progress-bar');
        }
        $percent.css( 'width', percentage * 100 + '%' );
    },
    //上传文件出错
    uploadError:function( file ) {
        var id = file.id+'_';
        $( '#'+id ).find('.up_status').text('失败').show().addClass('up_error').removeClass('up_success');
        $( '#'+id ).find('.progress').remove();
    },
    //开始上传
    beginUpload:function(obj,upObj){
        if($(".source_list>li").size()==0){
            var d = dialog({
                    id : 'errorIntegral2',//避免重复打开
                    content: '请选择文件',
                    onshow: function () {
                        this.__popup.css({'top':'50px'});
                    }
                });

                d.show();

                setTimeout(function(){
                    dChange.close().remove();
                },5000);
            return;
        }
        upObj.upload();
    },
    //上传完成
    uploadComplete:function(file,upObj,json){
        var id = file.id+'_';
        console.log($( '#'+id ).length);
        console.log(json);
        $( '#'+id ).find('.progress').remove();
        $( '#'+id ).find('.up_status').text('成功').show().addClass('up_success').removeClass('up_error');
        $( '#'+id ).find('.up_del ').addClass('up_url_del').removeClass('up_del');
        $( '#'+id ).find('input').val(json.theUrl);
    }
};
})
