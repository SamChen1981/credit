 <body>
     <article class="right_top sub_ContainerHeight mar_left_right app_top_tree">
         <span class="font14 color"><i class="btv_ico pointMall_big"></i>积分商城</span>
         <span><b class="app_direction_arrow">></b>商品管理</span>
		 <span><b class="app_direction_arrow">></b>添加商品</span>
		    <input type="hidden" name="x" class="imageCut"/>
            <input type="hidden" name="y" class="imageCut"/>
            <input type="hidden" name="w" class="imageCut"/>
            <input type="hidden" name="h" class="imageCut"/>
     </article>
     <form class="right_middle registerform" action="" method="" name="productForm">
        <article class="right_main pad_left_right scrollparent ">
		<!--以下section为内容区域-->
            <section class="overscroll">
                <section class="text-box">
					<span class="text_red">*</span>
					商品名称：
				</section>
				<section class="text-box">
					<span class="text_red"></span>
					<input type="text"  name="data[Product][activity_name]" class="cominput w300"  value="<?php echo $prodateDetails['Product']["activity_name"]?>"  datatype="*1-60" errormsg="名称30个字以内"/>
					<div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
				</section>
				<input type="hidden" value="" name="editType" class="editType" value="11">
				<section class="text-box">
					<span class="text_red">*</span>
					商品简介：
				</section>
				<section class="text-box">
					<span class="text_red"></span>
					<textarea name="data[Product][product_name]" class="cominput w300" datatype="*1-600" errormsg="商品名称250个字以内" ><?php echo $prodateDetails['Product']["product_name"];?></textarea>
					<div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
				</section>
				<section class="text-box">
					<span class="text_red">*</span>
					商品数量：
				</section>
                <section class="text-box">
                    <span class="text_red"></span>
                    <input type="text"  name="data[Product][quantity]" class="cominput w100" datatype="/^\+?[1-9]\d{0,5}$/" errormsg="请填1-5位的正整数" value="<?php echo $prodateDetails['Product']["quantity"]?>" >
                    <div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
                    <span class="small_description">最小数量为1</span>
                </section>
                <section class="text-box">
                    <span class="text_red"></span>
                    市场价：
                </section>
                <section class="text-box">
                    <span class="text_red"></span>
                    <input type="text"  name="data[Product][market_price]" class="cominput w100" datatype="n" errormsg="请填正整数" value="<?php echo $prodateDetails['Product']["market_price"]?>" >
                    <div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
                    <span class="small_description">最小数量为1</span>
                </section>
                 <section class="text-box">
                    <span class="text_red"></span>
                    推荐排序:
                </section>
                <section class="text-box">
                    <span class="text_red"></span>
                    <input type="text"  name="data[Product][rank]" class="cominput w100" datatype="n" errormsg="请填正整数" value="<?php echo $prodateDetails['Product']["rank"]?>" >
                    <div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
                    <span class="small_description">请输入小于等于99999的正整数，数值越大，排位越靠前</span>
                </section>
				<section class="text-box">
					<span class="text_red">*</span>
					兑换积分：
				</section>
				<section class="text-box" class="hideNum">
					<span class="text_red"></span>
					<input type="checkbox" id="jf" name="jifen"/>
                    <label for="jf"></label>
                    execlet1(积分)
                    <div class="displayInline">
                        所需数量:
                        <input type="text" id="credits1"  readonly ignore="ignore"   datatype="n1-10" errormsg="请填1-10位的正整数"  name="data[Product][credits1]" class="cominput w100" value="<?php echo $prodateDetails['Product']["credits1"]?>"/>
                        <div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
                    </div>
				</section>
                <section class="text-box" class="hideNum">
                    <span class="text_red"></span>
                    <input type="checkbox" id="jf1"  name="jifen"/>
                    <label for="jf1"></label>
                    execlet2(金币)
                    <div class="displayInline">
                        所需数量:
                        <input type="text" id="credits2" readonly datatype="execlet2" errormsg="兑换积分，至少选择一种填入1-10位的正整数"  nullmsg="兑换积分，至少选择一种填入1-10位的正整数" name="data[Product][credits2]"  class="cominput w100"  value="<?php echo $prodateDetails['Product']["credits2"]?>" />
                        <div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
                    </div>
                </section>
				<section class="text-box">
					<span class="text_red">*</span>
					每个用户可兑换次数：
				</section>
				<section class="text-box">
					<span class="text_red"></span>
					<input type="text" name="data[Product][exchange_times]"  class="cominput w100" datatype="n1-3" errormsg="只能填写数字"  value="<?php echo $prodateDetails['Product']["exchange_times"]?>"/>
					<div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
				    <span class="small_description">只能填写整数，0为不限次数</span>
                </section>
                <!--上传商品缩略图-->
                <input type="hidden" name="data[Product][thumb_img]" value=""/>
                <section class="text-box">
                    <span class="text_red">*</span>
                    商品缩略图：
                </section>
                <section class="text-box">
                    <span class="text_red"></span>
                    <div class="displayInline">
                        <div class="mall_upImg_box">
                            <div id="breviary"><img src="../images/bg_icon.png" name="thumb"/></div>
                            <div class="mall_operate_img">
                                <span class="fl_right up_sp_sl">重新上传</span>
                                <span class="mall_edit_img" imageName="thumb">编辑</span>
                            </div>
                        </div>
                        <div>
                            <span class="up_sp_sl form_btn change_scroll">点击上传</span>
                            <p class="small_description">支持jpg、png、jpeg格式，图片标准尺寸465*465，最小不小于标准尺寸</p>
                        </div>
                    </div>
                </section>
                <!--上传商品banner图-->
                <input type="hidden" name="data[Product][banner_img]" value=""/>
                <section class="text-box">
                    <span class="text_red">*</span>
                    商品banner图：
                </section>
                <section class="text-box">
                    <span class="text_red"></span>
                    <div class="displayInline">
                        <div class="mall_upImg_box" >
                            <div id="banner"><img src="" name="banner"/></div>
                            <div class="mall_operate_img">
                                <span class="fl_right up_sp_banner">重新上传</span>
                                <span class="mall_edit_img" imageName="banner">编辑</span>
                            </div>
                        </div>
                        <div>
                            <span class="up_sp_banner form_btn change_scroll" >点击上传</span>
                            <p class="small_description">支持jpg、png、jpeg格式，图片标准尺寸960*480，最小不小于标准尺寸</p>
                        </div>
                    </div>
                </section>
               <section class="text-box">
                    <span class="text_red">*</span>
                    商品详细图：
                </section>
                <div class="filePicWrap clearfix">
                <a id="filePic" href="javascript:void(0);" class="fileUpBtn ml5">上传图片</a>
                <p>至少上传2张图片,图片标准尺寸为320x160,支持png,jpg,jpeg,gif格式</p>
              </div>
                <ul id="source_list" class="ml5">
                  
                </ul>
                <br/>
                <section class="text-box" style="margin-top:100px">
                    <span class="text_red">*</span>
                    是否顶到banner位置：
                </section>
                <section class="text-box" name="data[Product][sort[">
                    <span class="text_red"></span>
                    <div class="displayInline m_right50">
                        <input type="radio" <?php if($prodateDetails['Product']['sort']==1){ echo "checked";}?>  checked id="yes" name="data[Product][sort]" value="1" />
                        <label for="yes"></label>
                        是
                    </div>
                    <div class="displayInline">
                        <input type="radio" id="no"  <?php if($prodateDetails['Product']['sort']==0){ echo "checked";}?>   name="data[Product][sort]" value="0"/>
                        <label for="no"></label>
                        否
                    </div>
                </section>
                
                <section class="text-box">
                    <span class="text_red">*</span>
                    上架时间：
                </section>
                <section class="text-box" style="padding-left:30px">
                    <div class=" m_right ver_top m_right" style="float: left;">
                        <input  class="Wdate search-item"  placeholder="&nbsp;&nbsp;开始时间" type="text" name="data[Product][start_time]" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" style="width:140px;height: 26px;" value="" >
                    </div>
                    <div>
                      <input  class="Wdate search-item" placeholder=" &nbsp;&nbsp;结束时间" type="text" name="data[Product][end_time]" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" style="width:140px;height: 26px;"  value="">
                    </div>
                </section>
                <section class="text-box" style="height:100px">
                </section>
                <!-- <section class="text-box">
                    <span class="text_red">*</span>
                    商品兑换有效期：
                </section>
                <section class="text-box"  >
                    <span class="text_red"></span>
                    <input type="text" class="cominput w100 m_right50" datatype="n1-5" errormsg="只能填写数字" value="0" name="data[Product][validity_date]"/>
                    <div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
                    <span class="small_description">从中奖之日开始计算，0为不限日期</span>
                </section> -->
                <section class="text-box">
                    <span class="text_red">*</span>
                    商品领取方式：
                </section>
                <section class="text-box" name="data[Product][accept_way]">
                    <span class="text_red"></span>
                    <div class="displayInline m_right50">
                        <input class="self_receive change_scroll" type="radio" id="zq"   checked  name="data[Product][accept_way]" value="1"/>
                        <label for="zq"></label>
                        自取
                    </div>
                    <div class="displayInline">
                        <input type="radio" class="fast_mail change_scroll" id="kd"  name="data[Product][accept_way]" value="2"/>
                        <label for="kd"></label>
                        快递
                    </div>
                </section>
                <section class="self_get_mall" name="data[Product][accept_addr]" >
                    <article class="text-box">
                        <span class="text_red">*</span>
                        商品领取地址：
                    </article>
                    <article class="text-box">
                        <span class="text_red"></span>
                        <textarea name="data[Product][accept_addr]" class="cominput mall_address" datatype="*1-600" errormsg="地址不能超过100个字符" ><?php echo $prodateDetails['Product']['accept_addr'];?></textarea>
                        <div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
                    </article>
                    <section class="text-box">
                    <span class="text_red">*</span>
                    领取时间：
                    </section>
                <section class="text-box" style="padding-left:30px">
                    <div class=" m_right ver_top m_right" style="float: left;">
                        <input  class="Wdate search-item"  placeholder="&nbsp;&nbsp;开始时间" type="text" name="data[Product][order_start_time]" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:140px;height: 26px;" value="" >
                    </div>
                    <div>
                      <input  class="Wdate search-item" placeholder=" &nbsp;&nbsp;结束时间" type="text" name="data[Product][order_end_time]" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" style="width:140px;height: 26px;"  value="">
                    </div>
                </section>
                <section class="text-box">
                    <!-- <article class="text-box" >
                        <span class="text_red">*</span>
                        领取时间：
                    </article>
                    <article class="text-box">
                        <span class="text_red"></span>
                        <div class="select m_right ver_top w100">
                            <ul  id="time" style="display:none;">
                                <li value="1">工作日</li>
                                <li value='2'>不限</li>
                            </ul>
                            <input type="hidden" name="data[Product][accept_time]" value="1" >
                            <span style="display:none;">
                            </span>
                            <a href="javascript:void(0);" >工作日</a>
                        </div>
                        <input type="text" name="data[Product][accept_time_desc]" value="<?php echo $prodateDetails['Product']['accept_time_desc'];?>" class="cominput w100"/>
                    </article> -->
                    <article class="text-box">
                        <span class="text_red"></span>
                        联系人：
                    </article>
                    <article class="text-box">
                        <span class="text_red"></span>
                        <input type="text" name="data[Product][link_man]" datatype="*1-5" errormsg="五个字以内"  value="<?php echo $prodateDetails['Product']['link_man'];?>"  class="cominput mall_name w100"/>
                        <div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
                    </article>
                    <article class="text-box">
                        <span class="text_red"></span>
                        联系电话：
                    </article>
                    <article class="text-box">
                        <span class="text_red"></span>
                        <input type="text"  name="data[Product][link_phone]"  value="<?php echo $prodateDetails['Product']['link_phone'];?>" datatype="/(^(\d{3,4}-)?\d{7,8})$|(13[0-9]|15[012356789]|17[0678]|18[0-9]|14[57])[0-9]{8}$/" errormsg="电话号码格式不正确"  class="cominput mall_phone w100"/>
                        <div class="info"><span class="Validform_checktip"></span><span class="dec"><s class="dec1">&#9670;</s><s class="dec2">&#9670;</s></span></div>
                    </article>
                </section>
            </section>
        </article>
        <footer class="rigth_bottom sub_ContainerHeight pad_left_right t_right">
            <input type="button" id="release" class="form_btnblue btv_btn" value="发布" onclick="tijiao('<?php  echo CRED_URL;?>/Products/AddNewProduct',1)"/>
            <input type="button" id="draft"  class="form_btnblue btv_btn" value="存草稿" onclick="saveToDraft('<?php  echo CRED_URL;?>/Products/AddNewProduct',2)"/>
            <input type="button" id="preview"  class="form_btnblue btv_btn" value="返回" onclick="fanhui()"/>
        </footer>
    </form>
     <!--弹出的缩略图编辑图片层-->
    <div id="editImgTipThumb" style="display:none;">
        <div>
            拖动鼠标选择图片区域
        </div>
        <div style="margin-top:5px; width:600px;">
            <img id="edit_img1" class="edit_img_tipthumb" src="../images/a.png" />
        </div>
    </div>
      <!--弹出的banner编辑图片层-->
        <div id="editImgTipBanner" style="display:none;">
            <div>
                拖动鼠标选择图片区域
            </div>
            <div style="margin-top:5px; width:600px;">
                <img id="edit_img2" class="edit_img_tipbanner" src="../images/a.png" />
            </div>
        </div>
 </body>
    <script>
        $(document).ready(function(){
        //兑换积分至少填入一项
        $("form[name='productForm']").Validform({
            datatype:{
                "z1-30": /^[\u4E00-\u9FA5\uf900-\ufa2d]{1,30}$/,
                "z1-250": /^[\u4E00-\u9FA5\uf900-\ufa2d]{1,250}$/,
                "execlet2":function(gets,obj,curform,regxp){
                    /*参数gets是获取到的表单元素值，
                      obj为当前表单元素，
                      curform为当前验证的表单，
                      regxp为内置的一些正则表达式的引用。*/

                    var reg1=/^\d{1,10}$/;
                    
                    if(reg1.test($("#credits1").attr("value"))){return true;}
                    if(reg1.test(gets)){return true;}
                    return false;
                }   
            },
             ajaxPost:true
        });
            $("input[type='checkbox']").click(function(){

                if($(this).prop("checked")==false){
                    $(this).parent().find("#credits1").prop("readonly",true);
                    $(this).parent().find("#credits1").removeAttr("datatype");
                    $(this).parent().find("#credits1").attr("value","");
                    $(this).parent().find("#credits2").prop("readonly",true);
                    $(this).parent().find("#credits2").attr("value","");
                }
                if($(this).prop("checked")==true){
                    $(this).parent().find("#credits1").prop("readonly",false);
                   $(this).parent().find("#credits1").attr("datatype","n1-10");
                   $(this).parent().find("#credits2").prop("readonly",false);
                }

            });
            //滚动条
            $(".right_main").getContainerHeight({
                parentContainer:".right_middle"
            });
            $(".right_main").showScroll({
                change_tag:".change_scroll",
                objright:"20px",
                background:"#eeeeee"
            });
            //实例化商品缩略图上传
            selectUpImg.selectBreviary();

            //实例化商品banner图上传
            selectUpImg.selcelBanner();


            //自取和快递的交互
            $(".self_receive").click(function(){
                $(".self_get_mall").show();
                $(".mall_name").attr("datatype","s1-5");
                $(".mall_address").attr("datatype","s1-100");
                $(".mall_phone").removeAttr("datatype","/^(0|86|17951)?(13[0-9]|15[012356789]|17[0678]|18[0-9]|14[57])[0-9]{8}$/");
            });
            $(".fast_mail").click(function(){
                $(".self_get_mall").hide();
                 $(".mall_name").removeAttr("datatype");
                $(".mall_address").removeAttr("datatype");
                $(".mall_phone").removeAttr("datatype");
                });
            //编辑图片
            $(".mall_edit_img").click(function(){
                var imageName = $(this).attr('imageName');
                if(imageName=="banner"){
                     selectUpImg.editUpImgbanner(imageName);
                }else if(imageName=="thumb"){
                    selectUpImg.editUpImgthumb(imageName);
                }
            });

        });
        //缩略图的方法
        var slFlag=true,banFlag=true;//判断是否显示隐藏
        var selectUpImg={
            //添加缩略图片
            selectBreviary:function(){
                var goodsSl= WebUploader.create({
                    // 选完文件后，是否自动上传。
                    auto: true,
                    // 文件接收服务端。
                    server: '<?php echo $this->Html->url("/Upload/upload")?>',
                    pick: '.up_sp_sl',
                    accept:{
                        title: 'Images',
                        extensions: 'jpg,png,jpeg'
                    },
                    thumb:{
                        width:90,
                        height:90,
                        crop: true
                    }
                });
                var $preid,$that=this;
                goodsSl.on( 'fileQueued', function(file) {
                    if($preid){
                        goodsSl.removeFile($preid,true);
                    }
                    $preid=file.id;
                    var $li = $('<div id="' + file.id + '" class="file-item thumbnail thumbImage">' +
                            '<img name="thumb">' +
                            '</div>'),
                            $img=$li.find("img");
                    $("#breviary").html($li);
                    goodsSl.makeThumb( file, function( error, src ) {
                        if ( error ) {
                            $img.replaceWith('<span>不能预览</span>');
                            return;
                        }
                        $img.get(0).src=src;
                    });

                    if(slFlag==true){
                        $("#breviary").parent().show();
                        $("#breviary").parent().next().hide();
                        slFlag=false;
                        $that.selectBreviary();
                    }
                });
                  goodsSl.on('uploadSuccess',function(file,json){
                    $('.edit_img_tipthumb').attr("src",json.theUrl);
                    $('input[name="data[Product][thumb_img]"]').val(json.theUrl);
                  })
            },
            selcelBanner:function(){
                var goods_banner=WebUploader.create({
                    // 选完文件后，是否自动上传。
                    auto: true,
                    // 文件接收服务端。
                    server: '<?php echo $this->Html->url("/Upload/upload")?>',
                    pick: '.up_sp_banner',
                    accept:{
                        title: 'Images',
                        extensions: 'jpg,png,jpeg'
                    },
                    thumb:{
                        width:180,
                        height:90,
                        allowMagnify: true,
                        crop: true
                    }
                });
                var $banner,$that=this;
                goods_banner.on( 'fileQueued', function(file) {
                    if($banner){
                        goods_banner.removeFile($banner,true);
                    }
                    $banner=file.id;
                    var $li = $('<div id="' + file.id + '" class="file-item thumbnail bannerImage">' +
                            '<img name="banner">' +
                            '</div>'),
                            $img=$li.find("img");
                    $("#banner").html($li);
                    goods_banner.makeThumb( file, function( error, src ) {
                        if ( error ) {
                            $img.replaceWith('<span>不能预览</span>');
                            return;
                        }
                        $img.get(0).src=src;
                    });
                    if(banFlag==true){
                        $("#banner").parent().show();
                        $("#banner").parent().next().hide();
                        banFlag=false;
                        $that.selcelBanner();
                    }
                });
                 goods_banner.on('uploadSuccess',function(file,json){
                    $('.edit_img_tipbanner').attr("src",json.theUrl);
                    $('input[name="data[Product][banner_img]"]').val(json.theUrl);
                 })
            },
            editUpImgthumb:function(imageName){
                //获取图片地址
                 var imageUrl = $('input[name="data[Product][thumb_img]"]').attr('value');

              //  $('img[id="edit_img"]').attr('src',imageUrl);
                $.btvLayer({
                        content:"#editImgTipThumb",//dd为要弹出的容器id
                    width:"700px",//可选项，不选默认为300px
                    ok: function() {
                      //获取坐标
                       var config = {};
                       $('.imageCut').each(function(index,ement){
                           if($(this).val() != ''){
                               config[$(this).attr('name')] = $(this).val();
                           }
                       });
                        //提交图片裁剪
                      if( count(config) > 0){
                        var imgUrl = $('.edit_img_tipthumb').attr("src");
                       config['imgPath'] = imgUrl;
                       config["imageUrl"]=imageUrl;
                       config['imageName']=imageName;
                       var cutUrl = "<?php echo $this->html->url('/image/imageCut');?>";
                       $.post(cutUrl,config,function(data){
                           data = eval('('+data+')');
                           if(data.returnCode == 1){
                               var thumb = data.thumb;
                               //console.log(data.imageName);
                               thumb = thumb.replace('\\','');
                              $(".thumbImage").find('img').get(0).src = thumb;
                              $("input[name='data[Product][thumb_img]']").val(thumb);
                              $(".thumbImage > img").css({"width":"90px","height":"90px"});

                           }
                       });
                      }
                    }
                });
                //初始化裁剪图片
                $("#edit_img1").Jcrop({
                    aspectRatio:1.8,
                    boxHeight:600,
                    boxWidth:600,
                    minSelect:[280,280],
                    aspectRatio:1,
                    onSelect:function(c){
                        $('input[name="x"]').val(c.x);
                        $('input[name="y"]').val(c.y);
                        $('input[name="w"]').val(c.w);
                        $('input[name="h"]').val(c.h);
                    }
                });
            },
            editUpImgbanner:function(imageName){
                //获取图片地址
                var imageUrl = $('input[name="data[Product][banner_img]"]').attr('value');
               // $('img[id="edit_img"]').attr('src',imageUrl);
                $.btvLayer({
                        content:"#editImgTipBanner",//dd为要弹出的容器id
                    width:"700px",//可选项，不选默认为300px
                    ok: function() {
                      //获取坐标
                       var config = {};
                       $('.imageCut').each(function(index,ement){
                           if($(this).val() != ''){
                               config[$(this).attr('name')] = $(this).val();
                           }
                       });
                        //提交图片裁剪
                      if( count(config) > 0){
                        var imgUrl = $('.edit_img_tipbanner').attr("src");
                       config['imgPath'] = imgUrl;
                       config["imageUrl"]=imageUrl;
                       config['imageName']=imageName;
                       var cutUrl = "<?php echo $this->html->url('/image/imageCut');?>";
                       $.post(cutUrl,config,function(data){
                           data = eval('('+data+')');
                           if(data.returnCode == 1){
                               var thumb = data.thumb;
                               console.log(data.imageName);
                               thumb = thumb.replace('\\','');
                               $(".bannerImage").find('img').get(0).src = thumb;
                               $("input[name='data[Product][banner_img]']").val(thumb);
                               $(".bannerImage > img").css({"width":"90px","height":"90px"});

                           }
                       });
                      }
                    }
                });
        var picUp= WebUploader.create({
        // 文件接收服务端
        server: 'post_file.php',
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
            width: 120,
            height: 90,

            // 图片质量，只有type为`image/jpeg`的时候才有效。
            //quality: 90,

            // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
            allowMagnify: false,

            // 是否允许裁剪。
            crop: true,

            // 是否保留头部meta信息。
            preserveHeaders:false,

            // 如果发现压缩后文件大小比原来还大，则使用原来图片
            // 此属性可能会影响图片自动纠正功能
            noCompressIfLarger:true,

            // 单位字节，如果图片大小小于此值，不会采用压缩。
            compressSize: 1024
        }
        
    });
                //初始化裁剪图片
                $("#edit_img2").Jcrop({
                    aspectRatio:1.8,
                    boxHeight:600,
                     boxWidth:600,
                    minSelect:[280,280],
                    aspectRatio:1,
                    onSelect:function(c){
                        $('input[name="x"]').val(c.x);
                        $('input[name="y"]').val(c.y);
                        $('input[name="w"]').val(c.w);
                        $('input[name="h"]').val(c.h);
                    }
                });
            }
        };
       //填充自取工作时间段。
        $("#time li").click(function(){
            var value=$(this).attr("value");
            $("input[name='data[Product][accept_time]']").val(value);
        });
        //提交修改数据或则预览
       var tijiao=function(url,type){

           // if(($("input[name='data[Product][order_start_time]'']").val=='' &&  $("input[name='data[Product][order_end_time]]'").val!='') || ($("input[name='data[Product][order_start_time]]'").val!='' &&  $("input[name='data[Product][order_end_time]]'").val=='')){
           //      alert(123);
           // }
           // return false;

            if($("input[name='data[Product][activity_name]']").val().length>30){
                alert("名称在30个字以内");
                return false;
            }
            if($("#source_list li").length<2){
                alert("至少上传两张详情图");
                return false;
            }
            if(type===undefined){
                type=1;
            }
            if($("input[name='data[Product][start_time]']").val()==""||$("input[name='data[Product][end_time]']").val()==""){
                alert('上架时间不能为空');
                return false;
            }
            $(".editType").attr("value",type);
            $("form[name='productForm']").attr({'action':url,'method':'POST'}).submit();
       }
       var  saveToDraft=function(url,type){
           if(type==undefined){
               type=2;
           }
           $(".editType").attr("value",type);
           var data=$("form[name='productForm']").serialize();
           $.post(url,data,function(data){
               data = eval('('+data+')');
               if(data.code == 200){
                   window.location.href="<?php  echo $this->Html->URL('/products/index');?>";
               }
           })
       }

       var fanhui=function(){
         window.location.href="<?php  echo $this->Html->URL('/products/index');?>"
       };
            /**
                * 取String 或者 object的长度
                *
                * */
       		function count(o){
       			var t = typeof o;
       			if(t == 'string'){
       				return o.length;
       			}else if(t == 'object'){
       				var n = 0;
       				for(var i in o){
       					n++;
       				}
       				return n;
       			}
       			return false;
       		};

        $("input[name='data[Product][rank]']").blur(function(){
                  if($(this).val()<0||$(this).val()>99999){
                         alert('排序号最大不能超过99999');
                         $(this).val('');
                  }
        });    
    </script>

