
     <article class="right_top sub_ContainerHeight mar_left_right app_top_tree">
         <span class="font14 color"><i class="btv_ico pointMall_big"></i>积分商城</span>
     </article>
     <ul class="edit_title mar_left_right clearfix sub_ContainerHeight">
         <li><a href="<?php echo $this->Html->url('/Products/index');?>"> 商品管理</a></li>
         <li><a href="<?php echo $this->Html->url('/ResultStatistics/index');?>">效果统计</a></li>
         <li class="now"><a href="<?php echo $this->Html->url('/ProductLogs/index');?>">商品兑换</a></li>
     </ul>
     <article class="table_top small_description sub_ContainerHeight mar_left_right">
         <p>操作提示：<br/>
             可按照活动名称、用户名、用户手机号码、兑换码四个类型进行搜索
         </p>
     </article>
     <form class="right_middle registerform" >
        <article class="right_main pad_left_right">
		<!--以下section为内容区域-->
            <section style="margin-left: 15px;">

            <h1 class="paddingtop10">搜索类型</h1>
            <div class="text-box" style="padding-top:10px;">
                <div class="select">
                    <ul style="display:none;" class="choiceForm">
                        <li value="1">积分商城</li>
                        <li value="2">摇一摇</li>
                        <li value="3">活动游戏</li>
                    </ul>
                    <input type="hidden" >
                    <span style="display:none;">
                        <input class="search-item" type="hidden" name="activityForm" value="<?php echo $type;?>">
                    </span>
                    <a href="javascript:void(0);" name="1">积分商城</a>
                </div>
            </div>
                <div class="text-box" style="padding-top:10px;">
                    <div class="select">
                        <ul style="display:none;" class="choiceStatus">
                            <li value="1">活动名称</li>
                            <li value="2">用户名</li>
                            <li value="3">手机号码</li>
                            <li value="4">兑换码</li>
                        </ul>
                        <input type="hidden" >
                        <span style="display:none;">
                            <input class="search-item" type="hidden" name="type" value="<?php echo $type;?>">
                        </span>
                        <a href="javascript:void(0);" name="1">活动名称</a>
                    </div>
                </div>
                <div class="text-box">
                    <input type="text" class="cominput" name="sreach" placeholder="请输入内容"/>
                </div>
            </section>
        </article>
        <footer class="rigth_bottom sub_ContainerHeight pad_left_right t_right">
            <input type="submit" class="form_btnblue btv_btn" value="搜索" onclick="sreach()"/>
        </footer>
    </form>
 </body>
    <script>
        $(document).ready(function(){
            //滚动条
            $(".right_main").getContainerHeight({
                parentContainer:".right_middle"
            });

            //搜索条件改变时，改变隐藏。

         $(".choiceStatus >li").on('click',function(){
            $('input[name="type"]').val($(this).attr('value'));
         });
            //搜索活动类型时，改变隐藏。

                  $(".choiceForm >li").on('click',function(){
                     $('input[name="activityForm"]').val($(this).attr('value'));
                  });

        });
        var sreach=function(){
            var param="";
            var type=$("input[name='type']").val();
            var sreach=$("input[name='sreach']").val();
            param="type="+type;
            if(sreach!=''){
                param="&sreach"+sreach;
            }
            var url="<?php echo $this->Html->url("/ProductLogs/index?")?>";
            window.location.href=url+param;
        }
    </script>

