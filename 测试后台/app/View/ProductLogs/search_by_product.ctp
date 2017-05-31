 <article class="right_top sub_ContainerHeight mar_left_right app_top_tree">
        <span class="font14 color"><i class="btv_ico pointMall_big"></i>积分商城</span>
        <span><b class="app_direction_arrow">></b>商品兑换</span>
        <span><b class="app_direction_arrow">></b>搜索结果</span>
    </article>
    <article class="table_top mar_left_right sub_ContainerHeight">
       <span class="m_right">共搜索到会员数<?php echo $count;?>条</span>
        <a href="<?php echo $this->Html->url("/ProductLogs/index")?>" class="a_link">重新搜索</a>
    </article>
    <article class="right_middle pad_left_right scrollparent">
    <!--以下section为内容区域-->
        <section class="overscroll">
            <table class="table_list">
                <thead>
                    <tr>
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
                <?php  foreach($products as $product){?>
                     <tr>
                            <td>
                                <?php echo  $product['Product']['activity_name'];?>
                            </td>
                            <td>
                                <?php echo  $product['Product']['product_name'];?>
                            </td>
                            <td>
                               <?php echo  $product['Product']['quantity'];?>
                            </td>
                            <td>
                            <?php echo  $statusName[$product['Product']['status']];?>
                            </td>
                            <td>
                               <?php echo  $product['Product']['member_nums'];?>
                            </td>
                            <td>
                            <?php if(in_array($product['Product']['status'],array(1,2,3,4))){?>
                                <a href="javascript:void(0)" class="btv_ico ico_look" title="查看" onclick="lookOver(<?php echo  $product['Product']['id'];?>)"></a>
                            <?php } ?>
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

     });
     var lookOver=function(id){
     window.location.href="<?php echo $this->Html->url("/ProductLogs/ShowFromProduct")?>?id="+id;
     }

 </script>

