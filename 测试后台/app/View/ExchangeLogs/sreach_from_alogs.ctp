
<article class="right_top mar_left_right sub_ContainerHeight app_top_tree">
    <span class="font14 color"><i class="btv_ico wave_big"></i> 摇一摇</span>
    <span><b class="app_direction_arrow">></b>奖品兑换</span>
    <span><b class="app_direction_arrow">></b>搜索结果</span>
</article>
<article class="right_middle mar_left_right">
    <header style="border-bottom:1px solid #e7e7e7">
        <p class="search_results_par" style="margin-top: 0">共搜索到会员数<?php echo $count;?>条<a href="<?php echo $this->Html->url("ExchangeLogs/index");?>" class="Search_again">重新搜索</a></p>

        <div class="right_middle_detail" style="height: 30px;padding: 10px;">
            <!-- 下拉框 -->
            <label class="win_field_title">状态: </label>
            <div class="select ver_top sp_sort_select" style="width:120px;float: left;margin-right:10px" >
                <ul style="display: none;" class="sp-sort">
                    <li value="2">已兑换</li>
                    <li value="1">未兑换</li>
                </ul>
                <span style="display: none;">
                   <input class="search-item" type="hidden" name="status" value="<?php echo $status;?>">
                </span>
                <a href="javascript:void(0);" name="2"><?php if($status==2){ echo "已兑换";}else{echo "未兑换";}?></a>
            </div>
            <div class="win_export_excel" style="float: right">
                <button class="form_btnblue b_btn" title="导出Excel表格">导出Excel表格</button>

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
                    <th>操作</th>
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
                    <td> <?php echo  $statusName[$productLog['ExchangeLog']['Pstatus']];?></td>
                    <td> <?php echo  $productLog['ExchangeLog']['product_name'];?></td>
                    <td></td>
                    <td>未兑换</td>
                    <td>
                        <a href="javascript:void(0);" class="app_members_edit edit view btv_ico app_btn"></a>
                        <a  href="javascript:void(0);" class="app_members_del wave_integral del btv_ico app_btn"></a>
                    </td>

                </tr>
                <tr>
                    <td>NS65114</td>
                    <td>bob</td>
                    <td>1346579856</td>
                    <td>50分兑换ipadmin</td>
                    <td>进行中 </td>
                    <td>iPhone</td>
                    <td>1</td>
                    <td>已兑换</td>
                    <td>
                        <a href="javascript:void(0);" class="app_members_edit edit view btv_ico app_btn"></a>
                    </td>

                </tr>
                <tr>
                    <td>NS65114</td>
                    <td>bob</td>
                    <td>1346579856</td>
                    <td>50分兑换ipadmin</td>
                    <td>进行中 </td>
                    <td>iPhone</td>
                    <td>1</td>
                    <td>已兑换</td>
                    <td>
                        <a href="javascript:void(0);" class="app_members_edit edit view btv_ico app_btn"></a>
                    </td>

                </tr>
                <tr>
                    <td>NS65114</td>
                    <td>bob</td>
                    <td>1346579856</td>
                    <td>50分兑换ipadmin</td>
                    <td>进行中 </td>
                    <td>iPhone</td>
                    <td>1</td>
                    <td>已兑换</td>
                    <td>
                        <a href="javascript:void(0);" class="app_members_edit edit view btv_ico app_btn"></a>
                    </td>

                </tr>
                </tbody>
            </table>

            <div class="app_page">
            </div>

        </section>
    </section>
</article>
</body>
</html>