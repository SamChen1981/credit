
    <article class="right_top sub_ContainerHeight mar_left_right app_top_tree">
        <span class="font14 color"><i class="btv_ico pointMall_big"></i>积分商城</span>
        <span><b class="app_direction_arrow">></b>商品兑换</span>
        <span><b class="app_direction_arrow">></b>兑换详情</span>
    </article>
    <article class="right_middle pad_left_right">
    <!--以下section为内容区域-->
        <section>
            <div class="text-box">
                兑换码:<?php echo $ProductLog['ProductLog']["code"];?>
            </div>
            <div class="text-box">
                用户名:<?php echo $ProductLog['ProductLog']["user_name"];?>
            </div>
            <div class="text-box">
                用户手机号:<?php echo $ProductLog['ProductLog']["mobile_phone"];?>
            </div>
            <div class="text-box">
                活动名称:<?php echo $ProductLog['ProductLog']["activity_name"];?>
            </div>
            <div class="text-box">
                活动时间:<?php echo date("Y-m-d H:i:s",$ProductLog['ProductLog']["start_time"]);?>
            </div>
            <div class="text-box">
                参与时间:<?php echo date("Y-m-d H:i:s",$ProductLog['ProductLog']["create_time"]);?>
            </div>
            <div class="text-box">
                商品:<?php echo $ProductLog['ProductLog']["product_name"];?>
            </div>
            <div class="text-box">
                商品数量:<?php echo $ProductLog['ProductLog']["quantity"];?>
            </div>
            <?php if($ProductLog['ProductLog']["status"]==2){?>
             <div class="text-box">
                  兑换状态:已兑换
             </div>
             <div class="text-box">
                 兑换时间:<?php echo date("Y-m-d H:i:s",$ProductLog['ProductLog']["modify_time"]);?>
             </div>
             <div class="text-box">
                 操作人:还没定
             </div>
            <?php }else{?>
            <div class="text-box">
                  兑换状态:未兑换
             </div>
             <?php if($ProductLog['ProductLog']["is_overtime"]=="已过期"){?>
              <div class="text-box">
                  是否过期:已过期
              </div>
              <?php }?>
             <?php }?>
        </section>
    </article>
    <footer class="rigth_bottom pad_left_right sub_ContainerHeight t_right">
        <?php if($ProductLog['ProductLog']["status"]==1 && $ProductLog['ProductLog']["is_overtime"]=="未过期"){?>
         <a href="#" class="form_btnblue btv_btn"  onclick="exchange(<?php echo $ProductLog['ProductLog']['id'] ;?>)" >兑换</a>
       <?php }?>
       <?php  if($type==1){?>
        <a href="javascript:void(0)" class="form_btnblue btv_btn" onclick="return2()">返回</a>
       <?php }elseif(in_array($type,array(2,3,4))){ ?>
       <a href="javascript:void(0)" class="form_btnblue btv_btn" onclick="return1()">返回</a>
       <?php }?>
    </footer>
 </body>
 <script>
        $(document).ready(function(){

        });
        //返回()
        var return1=function(){
        window.location.href="<?php echo $this->Html->url('/ProductLogs/SearchFromLogs'.$param)?>";
        }
        //返回(活动名称搜索)
        var return2=function(){
        var param="?<?php echo $param;?>";
        //  param=param.replace("/&amp;/gm","&")
        //alert(param);
        //exit;
        window.location.href="<?php echo $this->Html->url('/ProductLogs/ShowFromProduct')?>"+param+"&id=<?php echo $ProductLog['ProductLog']["product_id"]?>";
        }
   // 条件查询。
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
                          var url = '<?php echo $this->html->url("/ProductLogs/UserGetProduct");?>';
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
