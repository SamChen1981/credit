
    <article class="right_top sub_ContainerHeight mar_left_right app_top_tree">
        <span class="font14 color"><i class="btv_ico pointMall_big"></i>积分商城</span>
        <span><b class="app_direction_arrow">></b>商品兑换</span>
        <span><b class="app_direction_arrow">></b>搜索结果</span>
    </article>
    <article class="table_top mar_left_right sub_ContainerHeight">
       <span class="m_right">共搜索到会员数<?php echo $count;?>条</span>
        <a href="<?php echo $this->Html->url("/ExchangeLogs/index")?>" class="a_link">重新搜索</a>
    </article>
    <article class="table_top mar_left_right sub_ContainerHeight">
        <section class="fl_right">
            <span class="form_btnblue btv_btn" id="daochu">导出EXCEL表格</span>
        </section>
        <div class="select ver_top m_right">
                    <ul style="display:none;" class="choiceStatus">
                        <li value="2">已兑换</li>
                        <li value="1">未兑换</li>
                    </ul>
                    <input type="hidden" name="sort" value="">
                    <span style="display:none;">
                       <input class="search-item" type="hidden" name="status" value="<?php echo $status;?>">
                    </span>
                    <a href="javascript:void(0);" name="2"><?php if($status==2){ echo "已兑换";}else{echo "未兑换";}?></a>
                </div>
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
                 <?php  foreach($productLogs as $productLog){ ?>
                 <tr>
                         <td>
                           <?php echo  $productLog['ExchangeLog']['code'];?>
                         </td>
                         <td>
                            <?php echo  $productLog['ExchangeLog']['user_name'];?>
                         </td>
                         <td>
                               <?php echo  $productLog['ExchangeLog']['mobile_phone'];?>
                         </td>
                         <td>
                               <?php echo  $productLog['ExchangeLog']['activity_name'];?>
                         </td>
                         <td>
                              <?php echo  $statusName[$productLog['ExchangeLog']['Pstatus']];?>
                         </td>
                         <td>
                              <?php echo  $productLog['ExchangeLog']['product_name'];?>
                         </td>
                         <td>
                              <?php if($productLog['ExchangeLog']['status']==2)
                              {
                               echo "已兑换";
                               }elseif($productLog['ExchangeLog']['status']==1){
                               echo "未兑换";
                               };
                              ?>
                         </td>
                         <td>
                             <?php if($productLog['ExchangeLog']['status']==1){echo $productLog['ExchangeLog']['is_overtime'];}?>
                         </td>
                         <td>
                             <a href="javascript:void(0)" class="btv_ico ico_look" title="查看"  data="<?php echo  $productLog['ExchangeLog']['id'] ;?>"  onclick="showDetails($(this))"></a>
                             <?php if($productLog['ExchangeLog']['status']==1 && $productLog['ExchangeLog']['is_overtime']=="" ){?>
                                <a href="javascript:void(0)" class="btv_ico ico_duihuan"   data="<?php echo $productLog['ExchangeLog']['id'] ;?>"  title="兑换" onclick="exchange(<?php echo $productLog['ExchangeLog']['id'] ;?>)" ></a>
                             <?php }?>
                         </td>
                 </tr>
                 <?php } ?>

                </tbody>
            </table>
        </section>
    </article>
    <footer class="rigth_bottom pad_left_right sub_ContainerHeight">
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
        //改变查询条件
         $(".choiceStatus >li").on('click',function(){
                var status=$(this).attr('value');
                 var param = "";
                 param="/<?php echo $type;?>/"+status+"/<?php  echo $sreach;?>";
                 var url = "<?php echo $this->html->url('/ExchangeLogs/SearchFromLogs');?>";
                 window.location.href = url+param;
         })

             //导出
             $("#daochu").on("click",function(){
             var data1={};
              $(".search-item").each(function(){
                 if($(this).attr('value') != ''){
                    data1[$(this).attr('name')]=$(this).val();
                 }
              });
               data1['search']="<?php echo $search; ?>";
               data1['type']="<?php echo $type; ?>";
               data1['form']=1;
               $.ajax({
                  url:'<?php echo $this->html->url("/ExchangeLogs/JudgeNumByLog");?>',// 跳转到 action
                  data:data1,
                  type:'post',
                  cache:false,
                  dataType:'json',
                  success:function(data ,textStatus) {
                  //小于1000
                      if(data.message==1){
                        window.location.href="<?php echo $this->html->url('/ExchangeLogs/SearchFromLogs');?>"+data.data;
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
                                      var url="<?php echo $this->html->url('/ExchangeLogs/SearchFromLogs');?>"
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
       //查看商品兑换详情
        var showDetails=function($this){
             var id=$this.attr("data");
             var param="type=<?php echo $type ;?>&status=<?php echo $status ;?>&sreach=<?php echo $sreach;?>&id="+id;
             var url="<?php echo $this->Html->url("/ExchangeLogs/GetProductDetails")?>?";
             window.location.href=url+param;
        };
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
                        var url = '<?php echo $this->html->url("/ExchangeLogs/UserGetProduct");?>';
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

