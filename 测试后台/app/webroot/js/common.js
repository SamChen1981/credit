/*
****滚动条
	父层必须给个class   scrollparent,要滚动的层必须加个class overscroll,另外可以再加一个class来调用如user
	可选参数change_tag:".classname"，就是点击某个标签时会改变滚动层的宽高
	$(".user").showScroll({change_tag:".classname"});
	计算高度
	父层的class 没有的时候为浏览器的高度，
	有的话class为parentContainer,需要减去高度层的class为sub_ContainerHeight,设计算容器的class为set_child

	$(".set_child").getContainerHeight({
		parentContainer:".parentContainer"
	});

***提示窗口
		提示层用法实例
		//弹出内容
		$.btvLayer({
			content:"#dd",//dd为要弹出的容器id
			width:"700px",//可选项，不选默认为300px
			ok: function() {
				btv.alert("操作成功");
			}
		});
		//alert和confirm
		btv.alert("dd");
		btv.confirm("确定删除",function(){
			btv.alert("删除成功");
		});
		title:"提示信息",//弹出框的标题
		append: "showmsg",
		msg: "这是提示信息",//弹出的消息提示
		content:null,//给弹出对象的id,如"#$id"
		width:"300px",
		time: 3,
		onlyShow:false,//是否只是显示弹出的信息，不需要确定的回调函数
		//倒计时
		justTip: false,//只是alert函数
		//当仅仅是tip没有其他操作时设置成true;
*/
//定义公用的alert和comfire
btv={
	alert:function(msg){
		$.btvLayer({
			append:"showmsg",
			msg:msg,
			justTip:true
		});
	},
	confirm:function(msg,fun){
		$.btvLayer({
			append:"showmsg",
			msg:msg,
			ok:function(){
				fun();
			}
		});
	}
};

(function($) {
	/*计算列表条数*/
	$.pageNum=function(options){
		var dafaults={
			height:30,//单行的高度
			box:".content>.set_set_child"//盒子class
		};
		var opts = $.extend(defaults, options);
		var total_height=$(opt.box).get(0).offsetHeight,single_height=parseInt(opts.height);
		return parseInt(total_height/single_height)-3;
	}
	/*下拉框模拟*/
	$.fn.selectCopy = function (options){
		var $t=$(this),ul;
		 $t.find("a").click(function(){
			ul=$(this).parent().children("ul");
			$t=ul.parent();
			if(ul.is(":hidden")){
				$(".select>ul").slideUp('fast');
				$(".select>ul").next("span").hide();
				ul.slideDown('fast');
				$t.children("span").show();
			}else{
				ul.slideUp('fast',function(){
					$t.children("span").hide();
				});
			}		
			$(document).bind("click", function(event) {
				if($(event.target).parents().hasClass("select")){
					return;
				} else {	
					ul.slideUp(function(){
						$t.children("span").hide();
					});
				}
			});
		});
		$t.find("li").live("click",function(event){
			var $t = $(this).closest(".select");
			if(event.target.className.indexOf("switch")!=-1){
				return;
			}
			if($t.children("ul").get(0).className.indexOf("ztree")!=-1){
				$t.children("a").text($(this).children("a").text());
				$t.children("a").click();
				return false;
			}
			else{
				$t.find("a").text($(this).text());
				$t.find("a").attr("name",this.getAttribute("value"));
			}
			$t.children("a").click();
		});
	}
	/*计算高度*/
	var array={
				"obj":[],
				"opts":[]
			},t1,first=true;
	$.fn.getContainerHeight = function(options) {
		var defaults = {
			parentContainer: null,
			childContainer: ".sub_ContainerHeight"
		};
		var opts = $.extend(defaults, options);
		if(first==true){
			array.opts.push(options);
			array.obj.push($(this));
		}
		this.each(function() {
			var parentContainer = opts.parentContainer,
			childContainer = $(this).parent().children(opts.childContainer),
			subHei = 0,
			parentHei;
			if(childContainer.size()>=1){
				for (i = 0; i < childContainer.size(); i++) {
					subHei = childContainer.eq(i).get(0).offsetHeight + subHei;
				}
			}
			if (!parentContainer) {
				parentHei = document.documentElement.clientHeight;
			} else {
				var obj = window.getComputedStyle($(this).parent(parentHei).get(0), null);
				var subt = parseInt(obj.paddingTop),
				subb = parseInt(obj.paddingBottom),
				bort = parseInt(obj.borderTopWidth),
				borb = parseInt(obj.borderBottomWidth);
				parentHei = $(this).parent(parentHei).get(0).offsetHeight - subt - subb - bort - borb;
			}
			var this_bor_top=0,this_bor_bottom=0;//盒子本身的border
			var thisobj=window.getComputedStyle(this, null);
		
			if(thisobj.borderTopWidth){
				 this_bor_top=parseInt(thisobj.borderTopWidth);
			}
			if(thisobj.borderBottomWidth){
				this_bor_bottom=parseInt(thisobj.borderBottomWidth);
			}
			var result=parentHei-subHei-this_bor_top-this_bor_bottom;
			$(this).get(0).style.height = result + "px";
		});
		window.onresize=function(){
			first=false;
			if(t1){
				clearTimeout(t1);
			}
			var objs=array.obj,opts=array.opts,len=array.opts.length;
			t1=setTimeout(function(){
				for(var i=0;i<len;i++){
					objs[i].getContainerHeight(opts[i]);
				}
			},100);		
		}
	}
	/*公用的alert comfirm*/
	$.btvLayer = function(options) {
		clearTimeout(t);
		var Layer;
		var defaults = {
			title:"提示信息",//弹出框的标题
			append: "showmsg",
			msg: "这是提示信息",//弹出的消息提示
			content:null,//给弹出对象的id,如"#$id"
			width:"300px",
			time: 3,
			onlyShow:false,//是否只是显示弹出的信息，不需要确定的回调函数
			//倒计时
			justTip: false,//只是alert函数
			//当仅仅是tip没有其他操作时设置成true;
			ok: function() {
				Layer.remove();
			},
			cancel: function() {
				Layer.remove();
			},
			removeContent:function(){
				if(_content){
					$("body").append($(this.content));
					$(this.content).hide();
				}
				Layer.remove();
			}
		};
		var opts = $.extend(defaults, options),
		$_id = $("#" + opts.append),
		_tip = opts.justTip,
		_onlyShow=opts.onlyShow,
		_time = opts.time,
		_content=$(opts.content),
		mleft=parseInt(opts.width)/2+"px",//margin偏移量
		t;
		var delay = function() {
			_time = parseInt(_time) - 1;
			$(".time").html(_time);
			if (_time == 0) {
				Layer.fadeOut();
				Layer.remove();
				clearTimeout(t);
				return;
			}
			t = setTimeout(delay, 1000)
		}		
		var layer = '<div class="layer_parent" style="position:fixed;width:100%;height:100%;top:0px; left:0px;background: rgba(255,255,255,0);z-index:100"><div class="layer" style="position:absolute;width:'+opts.width+';top:100px; left:50%;margin-left:-'+mleft+'">' + 
								'<div class="top" style="background:#f8f8f8; padding:10px 10px; height:18px; border-bottom:1px solid #dedede;"><div class="close_layer" style="float:right;"><i class="btv_ico deljs"></i></div><p class="title">'+opts.title+'</p></div>'+
								'<div class="content" style="padding:15px 10px;" ></div>' + 
								'<p class="djs" style="text-align:center; padding-bottom:10px;"><span class="time">' + _time + '</span>秒后自动关闭</p>' + 
								'<div class="t_right operate" style="background:#f8f8f8; padding:10px 10px; margin-top:8px; border-top:1px solid #dedede;">' + 
									'<a class="btv_btn form_btnblue ok" style=" margin-right:5px;">确定</a>' + 
									'<a class="btv_btn  form_btnh cancel">取消</a>' +
								'</div>' +
						 '</div></div>';
		if ($_id.children().size() != 0) {
			return;
		}
		$("body").append(layer);
		if(opts.content!=null){
			_content.show();
			$(".djs").remove();
			$(".layer").find(".content").append(_content);
		}
		if(opts.content==null){
			if(_tip == true){
				$(".layer").find(".content").css("text-align","center");
			}
			$(".layer").find(".content").html(opts.msg);
		}
		if (_tip == true) {
			$(".top").remove();
			$(".operate").remove();
			$(".djs").show();
			t = setTimeout(delay, 1000);
		}
		if(_tip==false){
			$(".djs").remove();
		}
		if(_onlyShow==true){
			$(".title").hide();
			$(".operate").hide();
		}
		Layer = $(".layer_parent");
		$(".ok").on("click",
		function() {
			opts.removeContent();
			opts.ok();
		});

		$(".cancel,.close_layer").on("click",
		function() {
			opts.removeContent();
			opts.cancel();
		});
		
	}
	/*
	各种操作引起的是否显示树形滚动条
	传入点击的展开显示class如：change_tag:".classname"
	*/
	$.fn.showScroll = function(options) {
		var defaults = {
			change_tag: ' ',
			comw: 7,
			background: "#c3c3c3",
			objright: "5px"
		};
		this.each(function() {
			var opts = $.extend(defaults, options),
			childhei,
			childwidth,
			mainh = this.offsetHeight,
			mainw = this.offsetWidth,
			childbox = $(this).children(".overscroll"),
			changeid = opts.change_tag,
			comw = opts.comw,
			back_color = opts.background,
			right = opts.objright;
			if (childbox.size() == 0) {
				return;
			}
			var verh, vern, horw, horn
			var versrcoll, horisrcoll;
			childhei = childbox.get(0).offsetHeight;
			childwidth = childbox.get(0).offsetWidth;
			var methods = {
				/*初始化垂直方向的滚动条*/
				init: function(obj) {
					$(obj).children("span.verscroll_line").remove();
					$(obj).children("span.horiscroll_line").remove();
					if (childhei > mainh && $(obj).children("span.verscroll_line").size() == 0) {
						verh = childhei / mainh;
						vern = ((mainh - 10) / verh) < 50 ? 50 : ((mainh - 10) / verh);
						methods.createveLine(obj, vern);

					}
					/*初始化水平方向的滚动条*/
					if (childwidth > mainw && $(obj).children("span.horiscroll_line").size() == 0) {
						horw = childwidth / mainw;
						horn = mainw / horw;
						methods.createhorLine(obj, horn);

					}
				},
				/*创建垂直滚动条*/
				createveLine: function(obj, size) {
					versrcoll = document.createElement("span");
					versrcoll.className = "verscroll_line";
					versrcoll.style.display = "inline-block";
					versrcoll.style.cursor = "pointer";
					versrcoll.style.borderRadius = "5px";
					versrcoll.style.width = comw + "px";
					versrcoll.style.height = size + "px";
					versrcoll.style.minHeight = "50px";
					versrcoll.style.position = "absolute";
					versrcoll.style.top = "5px";
					versrcoll.style.right = right;
					versrcoll.style.backgroundColor = back_color;
					obj.appendChild(versrcoll);
					var martop = Math.abs(parseFloat(childbox.css("margin-top")));
					if (martop > 0) {
						versrcoll.style.top = martop * size / mainh + "px";
						$(obj).find(".verscroll_line").animate({
							"top": martop * size / mainh + "px"
						});
					}
				},
				/*创建水平滚动条*/
				createhorLine: function(obj, size) {
					horisrcoll = document.createElement("span");
					horisrcoll.className = "horiscroll_line";
					horisrcoll.style.display = "inline-block";
					horisrcoll.style.cursor = "pointer";
					horisrcoll.style.height = comw + "px";
					horisrcoll.style.width = size + "px";
					horisrcoll.style.borderRadius = "5px";
					horisrcoll.style.position = "absolute";
					horisrcoll.style.left = "5px";
					horisrcoll.style.bottom = "5px";
					horisrcoll.style.backgroundColor = back_color;

					obj.appendChild(horisrcoll);
					var marLeft = Math.abs(parseFloat(childbox.css("margin-left")));
					if (marLeft) {
						$(obj).find(".horiscroll_line").animate({
							"margin-left": marLeft * size / mainw + "px"
						});
					}

				},
				/*点击节点改变滚动条的高度和宽度*/
				changeline: function(obj) {

					/*改变垂直滚动条*/
					childhei = obj.children(".overscroll").get(0).offsetHeight;
					mainh = obj.get(0).offsetHeight;
					mainw = obj.get(0).offsetWidth;
					childwidth = obj.children(".overscroll").get(0).offsetWidth;
					if (obj.children("span.verscroll_line").size() == 0) {
						if (childhei <= mainh) {
							return;
						}
						verh = childhei / mainh;
						//vern=mainh/verh;
						vern = (mainh / verh) < 50 ? 50 : (mainh / verh);
						methods.createveLine(obj.get(0), vern);
					} else {
						var hei1 = childhei;
						childhei = obj.children(".overscroll").get(0).offsetHeight;
						if (childhei <= mainh) {
							$(obj).children("span.verscroll_line").remove();
							return;
						}
						var cha = childhei - hei1,
						pretop = parseFloat(versrcoll.style.top);
						verh = childhei / mainh;
						vern = mainh / verh;
						versrcoll.style.height = vern + "px";
						versrcoll.style.top = pretop - cha + "px";
					}
					/*改变水平滚动条*/
					if (obj.children("span.horiscroll_line").size() == 0) {

						if (childwidth <= mainw) {
							return;
						}
						horw = childwidth / mainw;
						horn = mainw / horw;
						methods.createhorLine(obj.get(0), horn);
					} else {
						var width1 = childwidth;
						childwidth = obj.children(".overscroll").get(0).offsetWidth;
						if (childwidth <= mainw) {
							obj.children("span.horiscroll_line").remove();
							return;
						}
						var cha = childwidth - width1,
						preleft = parseFloat(horisrcoll.style.left);
						horw = childwidth / mainw;
						horn = mainw / horw;
						horisrcoll.style.width = vern + "px";
						horisrcoll.style.left = preleft - cha + "px";
					}
				},
				/*拖动滚动条*/
				mousefun: function(obj, event) {
					mainh = $(obj).parent().get(0).offsetHeight;
					var e = event || window.event,
					child = $(obj).parent().children(".overscroll");
					if (e.preventDefault) {
						e.preventDefault(),
						e.stopPropagation();
					} else {
						e.returnValue = false,
						e.cancelBubble = true;
					}
					if (obj.setCapture) {
						obj.setCapture();
					} else if (window.captureEvents) {
						window.captureEvents(event.mousemove | event.mouseup);
					}
					var clientx = event.clientX,
					clienty = event.clientY;
					var elobj = e.srcElement || e.target,
					elem = elobj.className;
					var pretop = parseFloat(elobj.style.top) || 0,
					preleft = parseFloat(elobj.style.left) || 0;
					document.onmousemove = function(event) {
						var event = event || window.event
						var nowclientx = event.clientX,
						nowclienty = event.clientY;
						if (elem == "verscroll_line") {
							var nowtop = pretop + (nowclienty - clienty);
							if (nowtop <= 5) {
								child.css("margin-top", "0px");
								return;
							} else if (nowtop + elobj.offsetHeight >= mainh - 15) {
								child.css("margin-top", -(childhei - mainh + 5) + 'px');
								return;
							}
							elobj.style.top = nowtop + "px";
							var newmar = nowtop * childhei / mainh;
							child.css("margin-top", -newmar + "px");
						}
						if (elem == "horiscroll_line") {
							var nowleft = preleft + (nowclientx - clientx);
							if (nowleft <= 5) {
								child.css("margin-left", "0px");
								return;
							} else if (nowleft + elobj.offsetWidth >= mainw - 15) {
								child.css("margin-left", -(childwidth - mainw + 5) + 'px');
								return;
							}
							elobj.style.left = nowleft + "px";
							var newmar = nowleft * childwidth / mainw;
							child.css("margin-left", -newmar + "px");
						}
					}
					document.onmouseup = function() {
						if (!obj) return;
						if (obj.setCapture) {
							obj.releaseCapture();
						} else if (window.captureEvents) {
							window.captureEvents(event.mousemove | event.mouseup);
						}
						document.onmousemove = null;
						document.onmouseup = null;
					}
					return false;
				},
				/*滚轮滚动*/
				mouseScroll: function(obj, event) {

					mainh = $(obj).get(0).offsetHeight;
					childhei = childbox.get(0).offsetHeight;
					childwidth = childbox.get(0).offsetWidth;
					verh = childhei / mainh;
					vern = ((mainh - 10) / verh) < 50 ? 50 : ((mainh - 10) / verh);
					if ($(obj).children("span.verscroll_line").size() == 0) {
						return;
					}
					var versrcoll = $(obj).children("span.verscroll_line").get(0);
					versrcoll.style.height = vern + 'px';
					var margin_top = parseFloat(childbox.get(0).style.marginTop) || 0;
					var top = parseInt($(obj).children("span.verscroll_line").get(0).style.top) || 0;
					var sroollh = $(obj).children("span.verscroll_line").get(0).offsetHeight;
					var direct = 0;
					var event = event || window.event;
					if (event.wheelDelta) { //IE/Opera/Chrome
						direct = event.wheelDelta / 120;
					} else if (event.detail) { //Firefox
						direct = event.detail / 120;
					}
					if (direct < 0) {

						if (top + sroollh >= (mainh - 20)) {
							childbox.css("margin-top", -(childhei - mainh + 5) + 'px');
							versrcoll.style.top = (mainh - vern - 5) + 'px';
							return;
						} else {
							margin_top = margin_top - 5;
							childbox.css("margin-top", margin_top + "px");
							versrcoll.style.top = Math.abs(margin_top) * mainh / childhei + "px";
						}
					}
					if (direct > 0) {
						if (top <= 10) {
							childbox.css("margin-top", 0);
							return;
						} else {
							margin_top = margin_top + 10;
							childbox.css("margin-top", margin_top + "px");
							versrcoll.style.top = Math.abs(margin_top) * mainh / childhei + "px";
						}
					}
				}
			};
			methods.init(this);
			var that = this;
			this.addEventListener("DOMMouseScroll",
			function(event) {
				methods.mouseScroll(that, event)
			},
			false);
			this.addEventListener("mousewheel",
			function(event) {
				methods.mouseScroll(that, event)
			},
			false);
			$(changeid).unbind("click");
			$(changeid).bind("click",
			function() {
				var parent = $(this).parent();
				while (parent.get(0).className.indexOf("scrollparent") == -1) {
					parent = parent.parent();
				}
				setTimeout(function() {
					methods.changeline(parent);
				},
				500);

			});
			$("span", this).live("mousedown",
			function(event) {
				methods.mousefun(this, event);
			});
		});
	}
	/*
	* 参数e为出发事件的对象 如:$('.demo');
	* 调用 $.smartScroll($('.demo'));
	*/
	  $.extend({ smartScroll : function(obj){
        var _this = obj instanceof jQuery ? obj : $(obj),
            _thisPositionTop = _this.position().top,
            _thisPositionLeft = _this.position().left,
            childhei,
            parents = _this.parents('.scrollparent').eq(0),
            mainH = parents.outerHeight(),
            mainW = parents.outerWidth(),
            scrollBox = parents.children(".overscroll"),
            scrollBoxH = scrollBox.outerHeight(),
            scrollBoxW = scrollBox.outerWidth(),
            scrollBox_offsetT = Math.abs( parseInt( scrollBox.css('marginTop') ) ),
        scrollBox_offsetL = Math.abs( parseInt( scrollBox.css('marginLeft') ) ),
        versrcoll =parents.children(".verscroll_line"),//竖向
        horiscroll = parents.children(".horiscroll_line");//横向
        if(scrollBoxH > mainH || scrollBoxW > mainW ){
            var verh=scrollBoxH/mainH,
                vern=((mainH-10)/verh)<50? 50 : ((mainH-10)/verh),
                horw = scrollBoxW/mainW,
                horn = ((mainW-10)/horw)<50? 50 : ((mainW-10)/horw);
            //竖向滚动条
            if(scrollBoxH > mainH  && versrcoll.length){
                var newscrollBox_offsetT = scrollBox_offsetT + _thisPositionTop;

                //判断剩余的部分是否够可视区的高度
                if (newscrollBox_offsetT <= mainH/2 ) {
                    scrollBox_offsetT = 0;
                    scrollBox.animate({'marginTop':0});
                }else if(scrollBoxH - newscrollBox_offsetT >= mainH || _this.outerHeight() >= mainH){
                    scrollBox_offsetT = newscrollBox_offsetT;
                    scrollBox.animate({'marginTop':'-' + newscrollBox_offsetT + 'px'});
                }else{
                    scrollBox_offsetT = scrollBoxH - mainH;
                    scrollBox.animate({'marginTop':'-' + ( scrollBoxH - mainH ) + 'px'});
                }

                versrcoll.css({
                    'top' : ((scrollBox_offsetT*vern/mainH)+5) + 'px',
                    'height' : vern+"px"
                });

            }else{
                parents.showScroll();
            }

            //横向滚动条
            if(scrollBoxW > mainW  && horiscroll.length){
                horiscroll.css({
                    'width' : horn+"px"
                });
            }else{
                setTimeout(function() {
						parents.showScroll();
					},
					500);
            }

        }else{
            parents.children(".overscroll").css("margin-top","0px");
            versrcoll.remove();
            horiscroll.remove();
        }

	 }
    });

	/*
	栏目返回
	传入树形菜单需要调整位置的id
	*/
	$.fn.returnColumn = function(options) {
		var defaults = {
			click_id: ""
		};
		this.each(function() {
			var opts = $.extend(defaults, options),
			childhei,
			mainh = this.offsetHeight,
			childbox = $(this).children(".overscroll"),
			clickid = opts.click_id,
			versrcoll = $(this).children("span.verscroll_line").get(0);
			childhei = childbox.get(0).offsetHeight;
			if (!versrcoll) {
				return;
			}
			var parentnode = document.getElementById(clickid).offsetParent,
			parentHei = document.getElementById(clickid).offsetTop;
			/*
			do{
				parentHei=parentHei+parentnode.offsetTop;
				parentnode=parentnode.offsetParent;
			}while(parentnode!=this)
			*/
			var martop = Math.abs(parseFloat(childbox.get(0).style.marginTop)) || 0;
			if (parentHei > martop + mainh) {
				var c = parentHei - martop - mainh,
				cn = Math.ceil(c / mainh);
				if (cn < 1) {
					var newt = martop + c / 2;
				} else {
					var newt = martop + mainh * cn;
				}
				childbox.css("margin-top", -newt + "px");
				versrcoll.style.top = newt * mainh / childhei + "px";
			}
		});
	}
})(jQuery)