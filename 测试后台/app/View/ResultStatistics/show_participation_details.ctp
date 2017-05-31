
    <article class="right_top sub_ContainerHeight mar_left_right app_top_tree">
        <span class="font14 color"><i class="btv_ico pointMall_big"></i>积分商城</span>
        <span><b class="app_direction_arrow">></b>效果统计</span>
        <span><b class="app_direction_arrow">></b><?php  echo $activity_name;?>></span>
    </article>
    <article class="table_top mar_left_right sub_ContainerHeight">
        <h1 class="paddingbottom10">活动概况</h1>
        <span class="m_right50">参与用户数:<?php echo $member_nums;?>人</span>
        <span class="m_right50">总参与次数:<?php echo $participation_nums;?>次</span>
        <span class="m_right50">用户人均参与次数:<?php echo $num;?>人/次</span>
    </article>
    <article class="table_top mar_left_right sub_ContainerHeight">
        <h1 class="paddingbottom10">活动明细（统计截止日期：<?php echo date("Y-m-d",time());?>）</h1>
        <section>
            <div class="fl_right">
                <span class="form_btnblue btv_btn" id="daochu" >导出EXCEL表格</span>
            </div>
            <div class="search_box ver_top">
                <input   type="text" name="sreach" value="<?php echo $activityName;?>">
                <input class="btv_ico" type="submit" id="sreach" placeholder="输入关键字" value="">
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
                            ID
                        </th>
                        <th>
                            用户名
                        </th>
                        <th>
                            手机号码
                        </th>
                        <th>
                            参与时间
                        </th>
                        <th>
                            兑换商品
                        </th>
                        <th>
                            花费积分
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($users as $k=>$user){?>
                <tr>
                     <td>
                            <?php echo $user['ExchangeLog']['id'];?>
                     </td>
                     <td>
                        <?php echo $user['ExchangeLog']['user_name'];?>
                     </td>
                     <td>
                        <?php echo $user['ExchangeLog']['mobile_phone'];?>
                     </td>
                     <td>
                        <?php echo date("Y-m-d H:i:s",$user['ExchangeLog']['create_time']);?>
                     </td>
                     <td>
                       <?php echo $user['ExchangeLog']['product_name'];?>
                     </td>
                     <td>
                        <?php echo $user['ExchangeLog']['credits'];?>
                     </td>
                 </tr>
                <?php }?>
                </tbody>
            </table>
            <div class="t_right padding10" id="myform"></div>
        </section>
    <?php echo $this->Page->pages($this->Paginator->param('pageCount'), $this->request->query);?>
    </article>
    <footer class="rigth_bottom pad_left_right sub_ContainerHeight t_right">
            <a href="javascript:void(0)" onclick="return1()" class="form_btnblue btv_btn">返回</a>
    </footer>
 </body>
 <script>
     $(document).ready(function(){
      //滚动条
          $(".scrollparent").showScroll({
              change_tag:".change_scroll",
              objright:"5px",
              background:"#eeeeee"

          });

          
         $("#sreach").on("click",function(){
          var sreach=$("input[name='sreach']").val();
          var param="sreach="+sreach+"&id="+<?php  echo $id; ?>;
          url="<?php echo $this->Html->url("/ResultStatistics/ShowParticipationDetails?") ;?>";
          window.location.href=url+param;
         });
       //导出
      $("#daochu").on("click",function(){
      var data1={};
      var sreach=$("input[name='sreach']").val();
      data1["sreach"]=sreach;
      data1["id"]='<?php  echo $id; ?>';
        $.ajax({
           url:'<?php echo $this->html->url("/ResultStatistics/JudgeNum");?>',// 跳转到 action
           data:data1,
           type:'post',
           cache:false,
           dataType:'json',
           success:function(data ,textStatus) {
           //小于1000
               if(data.message==1){
                 window.location.href="<?php echo $this->html->url('/ResultStatistics/ShowParticipationDetails');?>?export=1&"+data.data;
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
                               var url="<?php echo $this->html->url('/ResultStatistics/ShowParticipationDetails');?>?export=1&"
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
     var query="<?php echo $query?>";
     var url="<?php echo $this->Html->url('/ResultStatistics/index?');?>";
     window.location.href=url+query;
     }
 </script>

