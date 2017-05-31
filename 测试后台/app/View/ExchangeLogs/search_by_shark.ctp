<?php
  echo $this->Html->css('appWave');
  echo $this->Html->script("appWave");
 ?>
    <script>
        //分页插件
        $(function(){
        })

    </script>

<article class="right_top mar_left_right sub_ContainerHeight app_top_tree">
    <span class="font14 color"><i class="btv_ico wave_big"></i> 摇一摇</span>
    <span><b class="app_direction_arrow">></b>奖品兑换</span>
    <span><b class="app_direction_arrow">></b>搜索结果</span>
</article>
<article class="right_middle mar_left_right">
    <header>
        <div class="search_prompt">
            <p>共搜到会员数<em><?php echo  $count;?></em>条 <a href="<?php echo $this->Html->url("/ExchangeLogs/index");?>" class="Search_again">重新搜索</a> </p>
        </div>
    </header>
    <section>
        <section class="set_child scrollparent">
            <table class="table_list overscroll">
                <thead>
                <tr>
                    <th>活动名称</th>
                    <th>活动时间</th>
                    <th>状态</th>
                    <th>中奖数</th>
                    <th>操作</th>

                </tr>
                </thead>
                <tbody>
                <?php foreach($sreach as $k=>$v){ ?>
                <tr>
                    <td><?php  echo $v['name'] ;?></td>
                    <td><?php  echo $v['startTime'];?>至<?php  echo $v['endTime'];?></td>
                    <td><?php if($v['status']==2){ echo "已结束";}else{ echo "进行中";}?></td>
                    <td><?php  echo $v['nums'];?> </td>
                    <td>
                        <a href="javascript:void(0);" class="app_members_edit edit view btv_ico app_btn" title="查看"  onclick="lookOver(<?php echo $v['id'];?>)"></a>
                    </td>

                </tr>
                <?php }?>
                </tbody>

            </table>
            <div class="app_page">
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
     });
     var lookOver=function(id){
      window.location.href="<?php echo $this->Html->url('/ExchangeLogs/ShowFromShake')?>?id="+id+"&name=<?php echo $name;?>";
     }

 </script>
</html>