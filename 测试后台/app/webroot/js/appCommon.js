$(document).ready(function(){
	$(".rightcontent").getContainerHeight({
		parentContainer:""
	});
	
	$(".right_middle").getContainerHeight({
		parentContainer:""
	});
	//侧边栏导航
	$(".column_slider li").click(function(){
		var now_class=$(this).get(0).className;
		if(now_class.indexOf("now")!=-1){
			return;
		}
		$(".column_slider li.now").removeClass("now");
		$(this).addClass("now");
	});
    $('.select').each(function(){
        $(this).selectCopy();
    });
    if( $(".registerform").size()>0){
        $(".registerform").Validform({
            tiptype:function(msg,o,cssctl){
                //msg：提示信息;
                //o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
                //cssctl:内置的提示信息样式控制函数，该函数需传入两个参数：显示提示信息的对象 和 当前提示的状态（既形参o中的type）;
                if(!o.obj.is("form")){//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
                    var objtip=o.obj.parent().find(".Validform_checktip");
                    cssctl(objtip,o.type);
                    objtip.text(msg);
                    var infoObj=o.obj.parent().find(".info");
                    if(o.type==2){
                        infoObj.fadeOut(200);
                    }else{
                        if(infoObj.is(":visible")){return;}
                        var left=o.obj.position().left+ o.obj.get(0).offsetWidth,
                            top=o.obj.get(0).offsetHeight+35,
                            margintop=top+20;
                        infoObj.css({
                            "left":left-30,
                            "margin-top":"-30px"
                        }).show().animate({
                            "margin-top":"-10px"
                        },200);
                    }

                }
            }
        });
    }
});
