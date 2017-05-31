
     <article class="right_top sub_ContainerHeight mar_left_right app_top_tree">
         <span class="font14 color"><i class="btv_ico pointMall_big"></i>积分商城</span>
         <span><b class="app_direction_arrow">></b>商品管理</span>
		 <span><b class="app_direction_arrow">></b>查看详情</span>
     </article>
        <article class="right_middle pad_left_right scrollparent ">
		<!--以下section为内容区域-->
            <section class="overscroll">
                <section class="text-box">
					活动名称：
                    <span><?php echo $product['Product']['activity_name'] ;?></span>
				</section>
				<section class="text-box">
					商品名称：
                    <span><?php echo $product['Product']['product_name'] ;?></span>
				</section>
				<section class="text-box">
					商品数量：
                    <span><?php echo $product['Product']['quantity'] ;?></span>
				</section>
                <!--上传商品缩略图-->
                <section class="text-box">
                    商品图片：
                    <div class="displayInline ver_top">
                        <img src="<?php echo PIC_URL.$product['Product']['thumb_img'] ;?>" width="90" height="90"/>
                    </div>
                </section>
                <section class="text-box">
                    兑换积分：
                    <?php if(!empty($product['Product']['credits1'])){ ?>
                    <span>dretsec1 (<?php echo $product['Product']['credits1'];?>)</span>
                    <?php }?>
                    <?php if(!empty($product['Product']['credits2'])){?>
                        <span>dretsec2 (<?php echo $product['Product']['credits2'];?>)</span>
                    <?php }?>
                    <?php if(!empty($product['Product']['credits3'])){?>
                        <span>dretsec3 (<?php echo $product['Product']['credits3'];?>)</span>
                    <?php }?>
                </section>
                <section class="text-box">
                    每个用户可兑换次数：
                    <span><?php echo $product['Product']['exchange_times'];?></span>
                </section>
                <section class="text-box">
                    商品领取方式：
                    <span><?php echo $product['Product']['accept_way']==1?"自取":"快递";?></span>
                </section>
                <div id="way" style="display:none">
                <section class="text-box">
                    兑换到期时间：
                    <span><?php echo date("Y-m-d",$product['Product']['end_time']);?></span>
                </section>    
                <!--选择自取后要显示的内容-->
                <section class="self_get_mall" >
                    <article class="text-box">
                        商品领取地址：
                        <span><?php echo $product['Product']['accept_addr'];?></span>
                    </article>
                    <article class="text-box">
                        联系人：
                        <span><?php echo $product['Product']['link_man'];?></span>
                    </article>
                    <article class="text-box">
                        联系方式：
                        <span><?php echo $product['Product']['link_phone'];?></span>
                    </article>
                    <article class="text-box">
                        领取时间：
                        <span><?php echo $product['Product']['accept_time']==1?'工作日':'不限';?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $product['Product']['accept_time_desc'];?></span>
                    </article>
                </section>
            </div>
            </section>
        </article>
        <footer class="rigth_bottom sub_ContainerHeight pad_left_right t_right">
           <?php if(isset($show) && $show==1){ ?>
                <input type="button" class="form_btnblue btv_btn" onclick="window.location.href='history.back(-1);return false;'" value="返回"/>
           <?php }else{ ?>
           <input type="button" class="form_btnblue btv_btn" id='back'  value="返回"/>
           <?php } ?>

        </footer>

 </body>
    <script>
        $(document).ready(function(){
            $(".right_middle").showScroll({
                change_tag:".change_scroll",
                objright:"20px",
                background:"#eeeeee"
            });
            var way=<?php echo $product['Product']['accept_way'];?>;
            if(way==1){
                $("#way").show();
            }
              $("#back").click(function(){
                 window.location.href ="<?php echo $this->Html->url('/products/index').'?'.$query; ?>";
            })
           // var return1=function(){
           //   window.location.href ="<?php echo $this->Html->url('/products/index').'?'.$query; ?>";
           // }
        });
    </script>

