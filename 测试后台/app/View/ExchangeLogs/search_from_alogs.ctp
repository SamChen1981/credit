 <script>
        //分页插件
        $(function(){
        })

    </script>
</head>
<body>
<article class="right_top mar_left_right sub_ContainerHeight app_top_tree">
    <span class="font14 color"><i class="btv_ico wave_big"></i> 摇一摇</span>
    <span><b class="app_direction_arrow">></b>奖品兑换</span>
    <span><b class="app_direction_arrow">></b>搜索结果</span>
</article>
<article class="right_middle mar_left_right">
    <header style="border-bottom:1px solid #e7e7e7">
        <p class="search_results_par" style="margin-top: 0">共搜索到会员数<?php echo $count;?>条<a href="<?php echo $this->Html->url("/ExchangeLogs/index")?>" class="Search_again" >重新搜索</a></p>

        <div class="right_middle_detail" style="height: 30px;padding: 10px;">
            <!-- 下拉框 -->
            <label class="win_field_title">状态: </label>
            <div class="select ver_top sp_sort_select" style="width:120px;float: left;margin-right:10px" >
                <ul style="display: none;" class="sp-sort choice" >
                    <li value="2">已兑换</li>
                    <li value="1">未兑换</li>
                </ul>
                <span style="display: none;">
                <input class="search-item" type="hidden" name="status" value="<?php echo $status;?>">
                </span>
               <a href="javascript:void(0);" name="2"><?php if($status==2){ echo "已兑换";}else{echo "未兑换";}?></a>
            </div>
            <div class="win_export_excel" style="float: right">
                <button class="form_btnblue b_btn" title="导出Excel表格" id="daochu">导出Excel表格</button>

            </div>
        </div>

    </header>
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
                    <th>奖品</th>
                    <th>数量</th>
                    <th>奖品状态</th>
                    <th>是否过期</th>
                    <th>操作</th>


                </tr>
                </thead>
                <tbody>

                <?php  foreach($productLogs as $productLog){ ?>
                <tr>

                    <td><?php echo  $productLog['ExchangeLog']['code'];?></td>
                    <td> <?php echo  $productLog['ExchangeLog']['user_name'];?></td>
                    <td> <?php echo  $productLog['ExchangeLog']['mobile_phone'];?></td>
                    <td> <?php echo  $productLog['ExchangeLog']['Aname'];?></td>
                    <td><?php echo  $productLog['ExchangeLog']['Astatus'];?></td>
                    <td><?php echo  $productLog['ExchangeLog']['prizename'];?></td>
                    <td><?php echo  $productLog['ExchangeLog']['prizenum'];?></td>
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
                           <a href="javascript:void(0);" class="app_members_edit edit view btv_ico app_btn" title="查看"  data="<?php echo  $productLog['ExchangeLog']['id'] ;?>"  onclick="showDetails($(this))"></a>
                        <?php if($productLog['ExchangeLog']['status']==1 && $productLog['ExchangeLog']['is_overtime']=="" ){?>
                           <a  href="javascript:void(0);" class="app_members_del wave_integral del btv_ico app_btn" data="<?php echo $productLog['ExchangeLog']['id'] ;?>"  title="兑换" onclick="exchange(<?php echo $productLog['ExchangeLog']['id'] ;?>)" ></a>
                        <?php }?>
                    </td>

                </tr>
                   <?php } ?>
                </tbody>
            </table>
            <div class="app_page">
              <footer class="rigth_bottom pad_left_right sub_ContainerHeight">
                    <div class="fl_right" id="myform"></div>
                </footer>
                   <?php echo $this->Page->pages($this->Paginator->param('pageCount'), $this->request->query);?>
            </div>
        </section>
    </section>
</article>
</body>
<script>
     $(document).ready(function(){
      $(".scrollparent").showScroll({
         change_tag:".change_scroll",
         objright:"5px",
         background:"#eeeeee"
      });
        //改变查询条件
         $(".choice >li").on('click',function(){
                var status=$(this).attr('value');
                 var param = "";
                 param="/<?php echo $type;?>/"+status+"/<?php  echo $sreach;?>";
                 var url = "<?php echo $this->html->url('/ExchangeLogs/SearchFromAlogs');?>";
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
            data1['form']=2;
            $.ajax({
               url:'<?php echo $this->html->url("/ExchangeLogs/JudgeNumByLog");?>',// 跳转到 action
               data:data1,
               type:'post',
               cache:false,
               dataType:'json',
               success:function(data ,textStatus) {
               //小于1000
                   if(data.message==1){
                     window.location.href="<?php echo $this->html->url('/ExchangeLogs/SearchFromAlogs');?>"+data.data;
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
                                   var url="<?php echo $this->html->url('/ExchangeLogs/SearchFromAlogs');?>"
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
             var url="<?php echo $this->Html->url("/ExchangeLogs/GetShakeDetails")?>?";
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
