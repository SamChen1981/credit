<?php
echo $this->Html->css('appCommon');
 echo $this->Html->script('/lib/datapicter/WdatePicker.js');
 echo $this->Html->script('/lib/laypage.js');
?>

<article class="right_top mar_left_right sub_ContainerHeight app_top_tree">
    <span class="font14 color"><i class="btv_ico wave_big"></i> 摇一摇</span>
    <span><b class="app_direction_arrow">></b>奖品兑换</span>
    <span><b class="app_direction_arrow">></b><?php echo  $productName;?></span>
</article>
<article class="right_middle mar_left_right">
    <header style="border-bottom:1px solid #e7e7e7">
                <div class="right_middle_detail" style="height: 30px;padding: 10px;">
                    <!-- 下拉框 -->
                    <label class="win_field_title"   id="gaibain" >状态: </label>
                    <div class="select ver_top sp_sort_select" style="width:120px;float: left;margin-right:10px" >
                        <ul style="display: none;" class="sp-sort" id="change" >
                            <li value="2">已兑换</li>
                            <li value="1">未兑换</li>
                        </ul>
                        <span style="display: none;">
                        <input class="search-item" type="hidden" name="type" value="<?php echo $type;?>">
                        </span>
                        <a href="javascript:void(0);" name="<?php echo $type;?>"><?php if($type==2){echo "已兑换";}elseif($type==1){echo "未兑换";}?></a>
                    </div>
                    <!-- 下拉框 -->
                    <label class="win_field_title">搜索值: </label>
                    <div class="select ver_top sp_sort_select" style="width:120px;float: left;margin-right:10px" >
                        <ul style="display: none;" class="sp-sort" id="sreachtype">
                            <li value="1">用户名</li>
                            <li value="2">手机号码</li>

                        </ul>
                        <span style="display: none;">
                        <input class="search-item" type="hidden" name="sreachType" value="<?php echo $sreachType;?>">
                        </span>
                        <a href="javascript:void(0);" name="<?php echo $type;?>"><?php if($sreachType==1){echo "用户名";}elseif($sreachType==2){echo "手机";}?></a>
                    </div>
                    <!-- 搜索 -->
                    <div class="sp_search" style="float:left;margin-left:10px">
                        <input type="text" name="sreach" class="c-search search-item" placeholder="请输入关键字" value="<?php echo $sreach ;?>">
                        <button class="c-search-btn btv_ico" title="搜索" onclick="sreach()"></button>
                    </div>
                    <div class="win_export_excel" style="float: right">
                        <button class="form_btnblue b_btn" title="导出Excel表格"id="daochu">导出Excel表格</button>

                    </div>
            </div>

        </header>
    <!--以下section为内容区域-->
    <section>
        <section class="set_child scrollparent">
            <table class="table_list overscroll">
                <thead>
                <tr>
                    <th>兑换码</th>
                    <td>用户名</td>
                    <th>手机号码</th>
                    <th>活动名称</th>
                    <th>活动状态</th>
                    <th>商品</th>
                    <th>商品状态</th>
                    <th>是否过期</th>
                    <th>操作</th>

                </tr>
                </thead>
                <tbody>
                 <?php foreach($ProductLogs as $k=>$ProductLog){?>
                <tr>
                    <td>
                    <?php echo $ProductLog['ExchangeLog']['code'] ;?>
                    </td>
                    <td>
                    <?php echo $ProductLog['ExchangeLog']['user_name'] ;?>
                    </td>
                    <td>
                    <?php echo $ProductLog['ExchangeLog']['mobile_phone'] ;?>
                    </td>
                    <td>
                     <?php echo $ProductLog['ExchangeLog']['Aname'] ;?>
                    </td>
                    <td>
                        <?php echo $ProductLog['ExchangeLog']['Astatus'] ;?>
                    </td>
                    <td>
                    <?php echo $ProductLog['ExchangeLog']['prizename'] ;?>
                    </td>
                    <td>
                         <?php if($ProductLog['ExchangeLog']['status']==2){ echo "已兑换";}else{ echo "未兑换";} ?>
                    </td>
                    <td>
                         <?php echo $ProductLog['ExchangeLog']['is_overtime'];?>
                    </td>
                    <td>
                        <a href="javascript:void(0);" class="btv_ico ico_look" title="查看"  data="<?php echo $ProductLog['ExchangeLog']['id'] ;?>" onclick="showDetails($(this))"></a>
                         <?php if($ProductLog['ExchangeLog']['status']==1  &&  $ProductLog['ExchangeLog']['is_overtime']==""){?>
                       <a  href="javascript:void(0);" class="app_members_del wave_integral del btv_ico app_btn" title="兑换" onclick="exchange(<?php echo $ProductLog['ExchangeLog']['id'];?>)"></a>
                          <?php }?>

                    </td>
                </tr>
                  <?php } ?>
                </tbody>
            </table>
            <div class="app_page">
               <div class="t_right padding10" id="myform"></div>
            </div>
        </section>
    </section>
</article>
<footer class="footer_btn sub_ContainerHeight">
    <!-- app尾部btn -->
    <!-- 如果需要固定在窗口底部请加上.fixedBottom-->
    <button class="form_btnblue b_btn" title="返回" onclick="return1()" >返回</button>
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
      //改变搜索状态
       $("#change>li").on("click",function(){
        $("input[name='type']").val($(this).attr("value"));
         sreach();
       })
       //改变搜索类型
         $("#sreachtype>li").on("click",function(){
               $("input[name='sreachType']").val($(this).attr("value"));
         })

       //导出
             $("#daochu").on("click",function(){
             var data1={};
              $(".search-item").each(function(){
                 if($(this).attr('value') != ''){
                    data1[$(this).attr('name')]=$(this).val();
                 }
              });
              data1['id']="<?php echo $id;?>";
              //定义是那种活动类型（摇一摇，积分，现场）
                 data1['form']=2;
               $.ajax({
                  url:'<?php echo $this->html->url("/ExchangeLogs/JudgeNum");?>',// 跳转到 action
                  data:data1,
                  type:'post',
                  cache:false,
                  dataType:'json',
                  success:function(data ,textStatus) {
                  //小于1000
                      if(data.message==1){
                        window.location.href="<?php echo $this->html->url('/ExchangeLogs/ShowFromShake');?>?export=1&"+data.data;
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
                                      var url="<?php echo $this->html->url('/ExchangeLogs/ShowFromShake');?>?export=1&"
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
      });
    var return1=function(){
     window.location.href="<?php echo $this->Html->url("/ExchangeLogs/SearchByShark/1/").$productName?>";
    }
    //查看商品兑换详情
        var showDetails=function($this){
             var id=$this.attr("data");
             var param="type1=<?php echo $type;?>&type=<?php echo $type1 ;?>&sreachType=<?php echo $sreachType ;?>&sreach=<?php echo $sreach;?>&id="+id;
             var url="<?php echo $this->Html->url("/ExchangeLogs/GetShakeDetails")?>?";
             window.location.href=url+param;
        };

        //条件查询
        var sreach=function(){
             var param = "";
             var url = "<?php echo $this->html->url('/ExchangeLogs/ShowFromShake');?>?id=<?php echo $id;?>&name=<?php echo $productName;?>";
             $(".search-item").each(function(){
                       if($(this).attr('value') != ''){
                           param += '&'+$(this).attr('name')+'='+$(this).val();
                       }
             })
             window.location.href = url+param;
        }
   //兑换商品
       var exchange = function(acid){
           var dChange = dialog({
               id : 'errorIntegral4',//避免重复打开
               title: '确定兑换吗?',
               content: '点击确定后将<b style="color:red">兑换</b>此商品',
               okValue: '确定',
               cancelValue:'取消',
               ok : function(){
                   var id = acid;
                   var url = '<?php echo $this->html->url("/ExchangeLogs/UserGetShake");?>';
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

  </script>




