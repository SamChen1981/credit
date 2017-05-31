
    <article class="right_top sub_ContainerHeight mar_left_right app_top_tree">
        <span class="font14 color"><i class="btv_ico pointMall_big"></i>积分商城</span>
        <span><b class="app_direction_arrow">></b>商品兑换</span>
        <span><b class="app_direction_arrow">></b><?php echo $ProductLogs[0]['ProductLog']['product_name'];?></span>
    </article>
    <article class="table_top mar_left_right sub_ContainerHeight">
        <section class="fl_right">
            <span class="form_btnblue btv_btn" id="daochu" >导出EXCEL表格</span>
        </section>
        <section>
            <div class="displayInline ver_top">
                <span class="displayInline ver_middle">状态:</span>
                <div class="select ver_middle m_right">
                    <ul style="display:none;" id="change">
                        <li value="2">已兑换</li>
                        <li value="1">未兑换</li>
                    </ul>
                    <input type="hidden" name="sort" value="">
                    <span style="display:none;">
                     <input class="search-item" type="hidden" name="type" value="<?php echo $type;?>">
                    </span>
                    <a href="javascript:void(0);" name="<?php echo $type;?>"><?php if($type==2){echo "已兑换";}elseif($type==1){echo "未兑换";}?></a>
                </div>
            </div>
            <div class="displayInline ver_top">
                <span class="displayInline ver_middle">搜索值:</span>
                <div class="select ver_middle m_right">
                    <ul style="display:none;">
                        <li value="1">用户名</li>
                        <li value="2">手机</li>
                    </ul>
                    <input type="hidden" name="sort" value="">
                    <span style="display:none;">
                         <input class="search-item" type="hidden" name="sreachType" value="<?php echo $sreachType;?>">
                    </span>
                    <a href="javascript:void(0);" name="<?php echo $type;?>"><?php if($sreachType==1){echo "用户名";}elseif($sreachType==2){echo "手机";}?></a>
                </div>
            </div>
            <div class="displayInline ver_top">
                <div class="search_box">
                    <input  type="text" class="search-item"  name="sreach" value="<?php echo $sreach ;?>">
                    <input class="btv_ico" type="submit" placeholder="输入关键字" value="" onclick="sreach()">
                </div>
            </div>
        </section>
    </article>
    <article class="right_middle pad_left_right scrollparent">
    <!--以下section为内容区域-->
        <section class="overscroll">
            <table class="table_list">
                <thead>
                <tr>
                    <th>
                        兑换码
                    </th>
                    <th>
                       用户名
                    </th>
                    <th>
                        手机号码
                    </th>
                    <th>
                        活动名称
                    </th>
                    <th>
                        活动状态
                    </th>
                    <th>
                       商品
                    </th>
                    <th>
                        商品状态
                    </th>
                    <th>
                           是否过期
                     </th>
                    <th>
                        操作
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($ProductLogs as $k=>$ProductLog){?>
                 <tr>
                        <td>
                           <?php echo $ProductLog['ProductLog']['code'] ;?>
                        </td>
                        <td>
                             <?php echo $ProductLog['ProductLog']['user_name'] ;?>
                        </td>
                        <td>
                            <?php echo $ProductLog['ProductLog']['mobile_phone'] ;?>
                        </td>
                        <td>
                           <?php echo $ProductLog['ProductLog']['activity_name'] ;?>
                        </td>
                        <td>
                            <?php echo $ProductLog['ProductLog']['ProductStatus'] ;?>
                        </td>
                        <td>
                            <?php echo $ProductLog['ProductLog']['product_name'] ;?>
                        </td>
                        <td>
                             <?php echo $ProductLog['ProductLog']['status'] ;?>
                        </td>
                         <td>
                            <?php echo $ProductLog['ProductLog']['is_overtime'] ;?>
                         </td>
                        <td>
                            <a href="javascript:void(0)" class="btv_ico ico_look" title="查看" data="<?php echo $ProductLog['ProductLog']['id'] ;?>"  onclick="showDetails($(this))"></a>
                            <?php if($ProductLog['ProductLog']['status']=="未兑换" &&  $ProductLog['ProductLog']['is_overtime']==""){?>
                                <a href="javascript:void(0)" class="btv_ico ico_duihuan" title="兑换"  onclick="exchange(<?php echo $ProductLog['ProductLog']['id'] ;?>)"></a>
                            <?php }?>
                        </td>
                 </tr>
                <?php } ?>

                </tbody>
            </table>
            <div class="t_right padding10" id="myform"></div>
        </section>
    </article>
    <footer class="rigth_bottom pad_left_right sub_ContainerHeight t_right">
            <a href="#" class="form_btnblue btv_btn" onclick="return1()">返回</a>
    </footer>
    <?php echo $this->Page->pages($this->Paginator->param('pageCount'), $this->request->query);?>
 </body>
 <script>
     $(document).ready(function(){
     //改变搜索状态
      $("#change>li").on("click",function(){
       $("input[name='type']").val($(this).attr("value"));
        sreach();
      })
      //导出
            $("#daochu").on("click",function(){
            var data1={};
             $(".search-item").each(function(){
                if($(this).attr('value') != ''){
                   data1[$(this).attr('name')]=$(this).val();
                }
             });
             data1['id']="<?php echo $ProductLog['ProductLog']['product_id'];?>";
              $.ajax({
                 url:'<?php echo $this->html->url("/ProductLogs/JudgeNum");?>',// 跳转到 action
                 data:data1,
                 type:'post',
                 cache:false,
                 dataType:'json',
                 success:function(data ,textStatus) {
                 //小于1000
                     if(data.message==1){
                       window.location.href="<?php echo $this->html->url('/ProductLogs/ShowFromProduct');?>?export=1&"+data.data;
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
                                     var url="<?php echo $this->html->url('/ProductLogs/ShowFromProduct');?>?export=1&"
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
    window.location.href="<?php echo $this->Html->url("/ProductLogs/SearchByProduct/1/").$productName?>";
   }
   //查看商品兑换详情
       var showDetails=function($this){
            var id=$this.attr("data");
            var param="type1=<?php echo $type;?>&type=<?php echo $type1 ;?>&sreachType=<?php echo $sreachType ;?>&sreach=<?php echo $sreach;?>&id="+id;
            var url="<?php echo $this->Html->url("/ProductLogs/GetProductDetails")?>?";
            window.location.href=url+param;
       };

       //条件查询
       var sreach=function(){
            var param = "";
            var url = "<?php echo $this->html->url('/ProductLogs/ShowFromProduct');?>?id=<?php echo $ProductLog['ProductLog']['product_id'];?>";
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
                  var url = '<?php echo $this->html->url("/ProductLogs/UserGetProduct");?>';
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
