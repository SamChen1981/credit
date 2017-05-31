<article class="right_top sub_ContainerHeight mar_left_right app_top_tree">
    <span class="font14 color"><i class="btv_ico pointMall_big"></i>积分商城</span>
</article>
<ul class="edit_title mar_left_right clearfix sub_ContainerHeight">
    <li <?php if($type==1) echo "class='now'"?>><a href="<?php  echo $this->Html->url('/products/index/')?>?type=1"> 商品管理</a></li>
    <!-- <li><a href="<?php echo $this->Html->url('/ResultStatistics/index');?>">效果统计</a></li>
    <li><a href="<?php echo $this->Html->url('/ExchangeLogs/index');?>">商品兑换</a></li> -->
</ul>
<article class="table_top mar_left_right sub_ContainerHeight">
    <div class="fl_right">
        <div class="search_box ver_top">
            <input   type="text" name="activityName" class="search-item"    value="<?php echo $this->request->query['activityName'];?>" >
            <input class="btv_ico"  id="sreach"  type="submit" value="" >
        </div>
    </div>
    <div>
        <div class="select m_right ver_top m_right" style="float: left;">
            <ul style="display:none;" class="choiceStatus">
                <li value="1" >进行中</li>
                <li value="2" >已结束</li>
                <li value="3" >已兑完</li>
                <li value="4" >已撤销</li>
                <li value="5" >草稿箱</li>
            </ul>
            <input type="hidden" name="sort" value="">
            <span style="display:none;">
                <input class="search-item"  type="hidden" name="status" value="<?php echo $this->request->query['status'];?>">
            </span>
            <a href="javascript:void(0);"  name="<?php echo $this->request->query['status'];?>"><?php echo $ststusName[$this->request->query['status']]; ?></a>
        </div>
        <div class=" m_right ver_top m_right" style="float: left;">
            <input  class="Wdate search-item"  placeholder="&nbsp;&nbsp;开始时间" type="text" name="start_time" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" style="width:140px;height: 26px;" value="<?php echo $this->request->query['start_time'];?>" onchange="search();" >
        </div>
        <div>
          <input  class="Wdate search-item" placeholder=" &nbsp;&nbsp;结束时间" type="text" name="end_time" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" style="width:140px;height: 26px;"  value="<?php echo $this->request->query['end_time'];?>" onchange="search();">
        </div>
    </div>
</article>
<article class="table_top mar_left_right sub_ContainerHeight">
    <div class="fl_right">
        <span class="form_btnblue btv_btn" id="daochu">导出EXCEL表格</span>
    </div>
    <div>
        <span class="form_btnblue btv_btn m_right" onclick="add()">新增</span>
        <span class="form_btnblue btv_btn" onclick="delmore()">删除</span>&nbsp;&nbsp;&nbsp;
        <?php if($status==4){?>
        <span class="form_btnblue btv_btn" onclick="fabumore()">发布</span>
        <?php }?>
    </div>
</article>
<article class="right_middle pad_left_right scrollparent">
    <!--以下section为内容区域-->
    <section class="overscroll">
        <table class="table_list">
            <thead>
            <tr>
                <th>
                    <input  type="checkbox" id="1"/>
                    <label  id="all_check" for="1"></label>
                    ID
                </th>
                <th>
                    商品名称
                </th>
                <th style="width:300px">
                    商品简介
                </th>
                <th>
                    商品数量
                </th>
                <th>
                    商品库存
                </th>
                <th>
                    兑换开始时间
                </th>
                <th>
                    兑换结束时间
                </th>
                <th>
                    操作人
                </th>
                <th>
                    状态
                </th>
                <th>
                    操作
                </th>
            </tr>
            </thead>
            <tbody>
            <?php  foreach($activties as $k=> $activty){?>
            <tr>
                <td>
                    <input  class="goods_check" type="checkbox"  id="<?php echo ($k+2);?>" data="<?php echo $activty['Product']["id"]; ?>"/>
                    <label for="<?php echo ($k+2);?>" ></label>
                    <?php echo $activty['Product']["id"];?>
                </td>
                <td>
                    <?php echo $activty['Product']["activity_name"];?>
                </td>
                <td>
                    <?php echo mb_substr($activty['Product']["product_name"],0,50);?>
                </td>
                <td>
                    <?php echo $activty['Product']["quantity"];?>
                </td>
                <td>
                    <?php echo $activty['Product']["quantity"]-$activty['Product']["exchange_quantity"];?>
                </td>
                <td>
                    <?php echo date("Y-m-d h-i",$activty['Product']["start_time"]);?>
                </td>
                <td>
                    <?php echo date("Y-m-d h-i",$activty['Product']["end_time"]);?>
                </td>
                <td>
                    <?php echo $activty['Product']["create_by"] ;?>
                </td>
                <td>
                    <?php echo $ststusName[$this->request->query['status']];?>
                </td>
                <!--进行中-->
                <?php  if($activty['Product']["status"]==1){ ?>
                <td>
                    <a href="javascript:void(0)" class="btv_ico edit" title="编辑"  data="<?php echo  $activty['Product']['id'] ;?>"  onclick="edit($(this))"></a>
                    <a href="javascript:void(0)" class="btv_ico ico_bac" title="撤销" data="<?php echo  $activty['Product']['id'] ;?>" onclick="revokActivity(<?php echo $activty['Product']['id']; ?>)"></a>
                    <a href="javascript:void(0)" class="btv_ico del" title="删除" onclick="delone(<?php echo $activty['Product']['id'] ; ?>)"></a>
                </td>
                <?php }?>
                <!--已结束 ，已兑完-->
                <?php  if($activty['Product']["status"]==2 ||  $activty['Product']["status"]==3){ ?>
                <td>
                    <a href="javascript:void(0)" class="btv_ico ico_look" title="查看" data="<?php echo  $activty['Product']['id'] ;?>" onclick="seeAbout($(this))"></a>
                    <a href="javascript:void(0)" class="btv_ico del" title="删除" onclick="delone(<?php echo $activty['Product']['id'] ; ?>)" data="<?php echo  $activty['Product']['id'] ;?>"></a>
                </td>
                <?php }?>
                <!--已撤销-->
                <?php  if($activty['Product']["status"]==4){ ?>
                <td>
                    <a href="javascript:void(0)" class="btv_ico edit" title="编辑"  data="<?php echo  $activty['Product']['id'] ;?>" onclick="edit($(this))"></a>
                    <a href="javascript:void(0)" class="btv_ico ico_publish" title="发布" data="<?php echo  $activty['Product']['id'] ;?>"  onclick="fabuone(<?php echo $activty['Product']['id']; ?>)"></a>
                    <a href="javascript:void(0)" class="btv_ico del" title="删除" onclick="delone(<?php echo $activty['Product']['id'] ; ?>)"  data="<?php echo  $activty['Product']['id'] ;?>" ></a>
                </td>
                <?php }?>
                <!--草稿箱-->
                <?php  if($activty['Product']["status"]==5){ ?>
                <td>
                    <a href="javascript:void(0)" class="btv_ico edit" title="编辑"  data="<?php echo  $activty['Product']['id'] ;?>" onclick="edit($(this))"></a>

                    <a href="javascript:void(0)" class="btv_ico del" title="删除" onclick="delone(<?php echo $activty['Product']['id'] ; ?>)" data="<?php echo  $activty['Product']['id']; ?>"></a>
                </td>
                <?php }?>

            </tr>
            <?php } ?>
            </tbody>
        </table>
    </section>
</article>

<footer class="rigth_bottom pad_left_right sub_ContainerHeight t_right">
    <div class="fl_right" id="myform"></div>
</footer>
<?php echo $this->Page->pages($this->Paginator->param('pageCount'), $this->request->query);?>
</body>
<script>
    $(document).ready(function(){

        $(".scrollparent").showScroll({
            change_tag:".change_scroll",
            objright:"5px",
            background:"#eeeeee"

        });

        $(".choiceStatus >li").on('click',function(){
            $('input[name="status"]').val($(this).attr('value'));
            search();
        });
        $("#sreach").on("click",function(){
            search();
        })
        //导出
      $("#daochu").on("click",function(){
      var data1={};
       $(".search-item").each(function(){
          if($(this).attr('value') != ''){
             data1[$(this).attr('name')]=$(this).val();
          }
       });
        $.ajax({
           url:'<?php echo $this->html->url("/Products/JudgeNum");?>',// 跳转到 action
           data:data1,
           type:'post',
           cache:false,
           dataType:'json',
           success:function(data ,textStatus) {
           //小于1000
               if(data.message==1){
                 window.location.href="<?php echo $this->html->url('/products/index');?>?export=1&"+data.data;
               }
           //大于1000
               else if(data.message==2){
                   var dChange = dialog({
                           id : 'promptInformation',//避免重复打开
                           title: '所下载数据大于1000条',
                           content:'点击确定后将<b style="color:red">导出</b>最近的1000条' ,
                           okValue: '确定',
                           cancelValue:'取消',
                           ok : function(){
                               var url="<?php echo $this->html->url('/products/index');?>?export=1&"
                               window.location.href=url+data.data;
                           },
                           cancel: function () {

                           }
                           });
                   dChange.show();
               }
           },
            error : function(data) {
            }
           });
        })
    })
  //全选，反选
   $("#all_check").on("click",function(){
                 $(".goods_check").each(function(index,domEle){
                    $(domEle).click();
                 });
        });

    //单个删除
    var  delone=function(oid){
        del(oid);
    }

    var delmore=function(){

      var data=new Array();
      $("input:checked").each(function(index,domEle){
      if($(domEle).attr("data")!=undefined){
       data.push($(domEle).attr("data"));
      }
      })
      del(data);
    }
    // 查看活动详情
    var seeAbout=function($this){
        var id=$this.attr("data");
        var page=$("#laypage_0 > .laypage_curr").text();
        var param = "";
        var url = "<?php echo $this->html->url('/products/ShowProductDetails');?>?type=<?php echo $type;?>&id="+id+"&page="+page;
        $(".search-item").each(function(){
            if($(this).attr('value') != ''){
                param += '&'+$(this).attr('name')+'='+$(this).val();
            }
        });
        window.location.href=url+param;

    }
    //编辑活动
    var  edit=function($this){
        var id=$this.attr("data");
        window.location.href ="<?php echo $this->Html->url('/products/EditProduct'); ?>?id="+id;
    }
    //删除活动
    var del=function(acids){
    var dChange = dialog({
                id : 'errorIntegral4',//避免重复打开
                title: '确定发布吗?',
                content: '点击确定后将<b style="color:red">删除</b>此活动',
                okValue: '确定',
                cancelValue:'取消',
                ok : function(){
                    var id=acids;
                    var url = '<?php echo $this->html->url("/Products/DeleteProducts");?>';
                    $.post(url,{id:id},function(data){
                        data = eval('('+data+')');
                        if(data.code == 200){
                            location.reload();
                        }else{
                            showMessage(data.message);
                        }
                    })
                },
                cancel: function () {
                }
            });
            dChange.show();


    }
    //跳转到新增页面
    var add=function(){
        window.location.href ="<?php echo $this->Html->url('/products/AddNewProduct'); ?>";
    }
    // 条件查询
    var search = function(){
        var param = "";
        var url = "<?php echo $this->html->url('/products/index');?>?type=<?php echo $type;?>";
        $(".search-item").each(function(){
            if($(this).attr('value') != ''){
                param += '&'+$(this).attr('name')+'='+$(this).val();
            }
        });
        window.location.href = url+param;
    }
    //发布一个
     //单个删除
        var  fabuone=function(oid){
            releaseActivity(oid);
        }
    //批量发布
    var fabumore=function(){
         var data=new Array();
          $("input:checked").each(function(index,domEle){
          if($(domEle).attr("data")!=undefined){
           data.push($(domEle).attr("data"));
          }
          })
      releaseActivity(data);
    }
    //发布
    var releaseActivity = function(id){
        var dChange = dialog({
            id : 'errorIntegral4',//避免重复打开
            title: '确定发布吗?',
            content: '点击确定后将<b style="color:red">发布</b>此活动',
            okValue: '确定',
            cancelValue:'取消',
            ok : function(){
                var url = '<?php echo $this->html->url("/Products/ReleaseProduct");?>';
                $.post(url,{id:id},function(data){
                    data = eval('('+data+')');
                    if(data.code == 200){
                        location.reload();
                    }else{
                        showMessage(data.message);
                    }
                })
            },
            cancel: function () {
            }
        });
        dChange.show();
    }

    //撤销
	var revokActivity = function(acid){
		var dChange = dialog({
            id : 'errorIntegral4',//避免重复打开
            title: '确定撤销吗?',
            content: '点击确定后将<b style="color:red">撤销</b>此活动',
            okValue: '确定',
            cancelValue:'取消',
            ok : function(){
            	var id = acid;
            	var url = '<?php echo $this->html->url('/Products/revok');?>';
            	$.post(url,{id:id},function(data){
            		data = eval('('+data+')');
            		if(data.code == 200){
            			location.reload();
            		}else{
            			showMessage(data.message);
            		}
            	})
            },
            cancel: function () {
            }
		 });
         dChange.show();
	}
	//导出文件
	var exportExcel1=function(){
        var param = "";
        var url = "<?php echo $this->html->url('/products/index');?>?type=<?php echo $type;?>";
        $(".search-item").each(function(){
            if($(this).attr('value') != ''){
                param += '&'+$(this).attr('name')+'='+$(this).val();
            }
        });
        param+="&export=1";
        window.location.href = url+param;
	}

</script>

