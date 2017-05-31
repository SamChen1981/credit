
    <article class="right_top sub_ContainerHeight mar_left_right app_top_tree">
        <span class="font14 color"><i class="btv_ico pointMall_big"></i>积分商城</span>
    </article>
    <ul class="edit_title mar_left_right clearfix sub_ContainerHeight">
        <li><a href="<?php echo $this->Html->url('/Products/index');?>"> 商品管理</a></li>
        <li class="now"><a href="<?php echo $this->Html->url('/ResultStatistics/index');?>">效果统计</a></li>
        <li><a href="<?php echo $this->Html->url('/ExchangeLogs/index');?>">商品兑换</a></li>
    </ul>
    <article class="table_top mar_left_right sub_ContainerHeight">
        <div class="fl_right">
            <div class="search_box ver_top">
                <input   type="text" name="sreach" value="<?php echo $sreach;?>">
                <input class="btv_ico" type="submit" placeholder="输入关键字" id="sreach"  value="">
            </div>
        </div>
        <div>
            <div class="select ver_top m_right">
                <ul style="display:none;" class="choiceStatus">
                    <li value="1">进行中</li>
                    <li value="2">已结束</li>
                    <li value="3">已兑完</li>
                </ul>
                <input type="hidden" name="sort" value="">
                <span style="display:none;">
                <input class="search-item" type="hidden" name="status" value="<?php echo $status;?>">
                </span>
                <a href="javascript:void(0);" name="<?php echo $status;?>"><?php echo $statusName1;?></a>
            </div>
        </div>
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
                            活动名称
                        </th>
                        <th>
                            兑换商品
                        </th>
                        <th>
                            商品数量
                        </th>
                        <th>
                            发布时间
                        </th>
                        <th>
                            结束时间
                        </th>
                        <th>
                            状态
                        </th>
                        <th>
                            参与用户数
                        </th>
                        <th>
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($activties as $k =>$activty){?>
<tr>
                        <td>
                            <?php echo $activty["Product"]['id'];?>
                        </td>
                        <td>
                            <?php echo $activty["Product"]['activity_name'];?>
                        </td>
                        <td>
                           <?php echo $activty["Product"]['product_name'];?>
                        </td>
                        <td>
                           <?php echo $activty["Product"]['quantity'];?>
                        </td>
                        <td>
                           <?php echo date("Y-m-d H:i:s",$activty["Product"]['start_time']);?>
                        </td>
                        <?php if($activty["Product"]['status']==1){?>
                          <td>
                          &nbsp;
                          </td>
                        <?php }else{?>
                         <td>
                             <?php echo date("Y-m-d H:i:s",$activty["Product"]['modify_time']);?>
                         </td>
                        <?php }?>

                        <td>
                            <?php echo $statusName[$activty["Product"]['status']];?>
                        </td>
                        <td>
                            <?php echo $activty["Product"]['member_nums'];?>
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="btv_ico ico_look" title="查看" data="<?php echo $activty["Product"]['id'];?>" onclick="showDetails($(this))"></a>
                        </td>
                    </tr>
                <?php }?>
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
     //滚动条
     $(".scrollparent").showScroll({
         change_tag:".change_scroll",
         objright:"5px",
         background:"#eeeeee"

     });


     //条件搜索
     $(".choiceStatus >li").on('click',function(){
                $('input[name="status"]').val($(this).attr('value'));
                search();
     });
     $("#sreach").on("click",function(){
        search();
     })

     });
    // 条件查询
    var search = function(){
            var param = "";
            var sreach=$("input[name='sreach']").val();
            if(sreach !=""){
             param="sreach="+sreach;
            }
            var url = "<?php echo $this->html->url('/ResultStatistics/index?');?>";
            $(".search-item").each(function(){
                if($(this).attr('value') != ''){
                    param += '&'+$(this).attr('name')+'='+$(this).val();
                }
            });
            window.location.href = url+param;
    }
    var  showDetails=function($this){
     var id=$this.attr("data");
     var url= "<?php echo $this->html->url('/ResultStatistics/ShowParticipationDetails');?>?id="+id+"&status=<?php echo $status; ?>";
       window.location.href=url;
    }
 </script>

