<article class="right_top mar_left_right sub_ContainerHeight app_top_tree">
    <span class="font14 color"><i class="btv_ico wave_big"></i> 摇一摇</span>
    <span><b class="app_direction_arrow">></b>奖品兑换</span>
    <span><b class="app_direction_arrow">></b>兑换详情</span>
</article>
<article class="right_middle mar_left_right">

    <!-- 滚动条插件 -->
    <div class="scrollparent demoScroll" style="height:100%;width:100%;">
        <div class="overscroll">
            <p class="avtive_deta_par"><em>兑换码：</em><?php echo $ProductLog['ExchangeLog']["code"];?></p>
            <p class="avtive_deta_par"><em>用户名：</em><?php echo $ProductLog['ExchangeLog']["user_name"];?></p>
            <p class="avtive_deta_par"><em>活动名称：</em><?php echo $ProductLog['ExchangeLog']["aname"];?></p>
            <p class="avtive_deta_par"><em>活动类型：</em><?php echo $Atype[$ProductLog['ExchangeLog']["atype"]];?></p>
            <p class="avtive_deta_par"><em>所属单位：</em><?php echo $ProductLog['ExchangeLog']["acompany"];?></p>
            <p class="avtive_deta_par"> <em>活动时间：</em><?php echo $ProductLog['ExchangeLog']["start_time"];?>~<?php echo $ProductLog['ExchangeLog']["end_time"];?></p>
            <p class="avtive_deta_par"> <em>中奖时间：</em><?php echo date("Y-m-d H:i:s",$ProductLog['ExchangeLog']["create_time"]);?></p>
            <p class="avtive_deta_par"> <em>奖品：</em><?php echo $ProductLog['ExchangeLog']["pname"];?></p>
            <p class="avtive_deta_par"> <em>奖品数量：</em><?php echo $ProductLog['ExchangeLog']["quantity"];?></p>

            <?php if($ProductLog['ExchangeLog']["status"]==2){?>
            <p class="avtive_deta_par"> <em>兑换状态：</em>已兑换</p>
            <p class="avtive_deta_par"> <em>兑换时间：</em><?php echo date("Y-m-d H:i:s",$ProductLog['ExchangeLog']["modify_time"]);?></p>
            <p class="avtive_deta_par"> <em>操作人：<?php echo $ProductLog['ExchangeLog']["runner_id"];?></em></p>
            <?php }elseif($ProductLog['ExchangeLog']["status"]==1 && $ProductLog['ExchangeLog']["is_overtime"]=='未过期' ){?>
            <p class="avtive_deta_par"> <em>兑换状态：</em>未兑换</p>
            <?php }?>
        </div>
    </div>
       <!-- 滚动条插件 End -->
</article>
<footer class="footer_btn sub_ContainerHeight">
    <!-- app尾部btn -->
    <!-- 如果需要固定在窗口底部请加上.fixedBottom-->
    <?php if($ProductLog['ExchangeLog']["status"]==1 && $ProductLog['ExchangeLog']["is_overtime"]=="未过期"){?>
    <a href="#" class="form_btnblue btv_btn"  onclick="exchange(<?php echo $ProductLog['ExchangeLog']['id'] ;?>)" >兑换</a>
    <?php }?>
    <?php  if($type==1){?>
    <button class="form_btnblue b_btn" title="返回" onclick="return2()" >返回</button>
    <?php }elseif(in_array($type,array(2,3,4))){ ?>
    <button class="form_btnblue b_btn" title="返回"  onclick="return1()" >返回</button>
    <?php }?>

</footer>
</body>
<script>
    $(document).ready(function(){
    });
    //返回
    var return1=function(){
        window.location.href="<?php echo $this->Html->url('/ExchangeLogs/SearchFromAlogs'.$param)?>";
    }
    //返回(活动名称搜索)
    var return2=function(){
        var param="?<?php echo $param;?>";
        window.location.href="<?php echo $this->Html->url('/ExchangeLogs/ShowFromShake')?>"+param+"&id=<?php echo $ProductLog['ExchangeLog']["activity_id"]?>";
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
</html>