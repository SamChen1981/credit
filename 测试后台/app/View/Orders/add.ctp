<?php $this->assign('title', __('Create Order'));?>
<!-- 	<form action="<?php echo $this->Html->url(array('controller'=>'Orders', 'action'=>'add'));?>" method="POST">
            <div>
                <div class="search_box ver_top">
                    工单标题<input type="text" name='data[Order][title]' value="" />
                    工单描述<input type="text" name='data[Order][desc]' value="" />
                    优先级<input type="text" name='data[Order][priority]' value="" />
                    截至日期<input type="text" name='data[Order][lasttime]' value="" />
                    <input class="btv_ico" type="submit" value="提交"/>
                </div>
            </div>
    </form> -->
    <!--左侧栏目列表-->
     <aside class="column_slider">
        <ul>
             <li class="user now"><a href="<?php echo $this->Html->url('/orders');?>"><i class="btv_ico"></i>工单</a></li>
            <li class="classfy"><a href="<?php echo $this->Html->url('/tasks');?>"><i class="btv_ico"></i>任务</a></li>
        </ul>
     </aside>
     <!--左侧栏目结束-->
     <form onsubmit="beforeSubmit();" method="POST">
     <section class="rightcontent">
        <article class="right_top sub_ContainerHeight">
            <i class="btv_ico user_big"></i>
            <span class="font14 color">工单</span>
            <span>>新建工单</span>
        </article>
        <div id="showmsg"></div>
        <article class="content">
            <section>
                <div class="text-box">
                    <label class="infor">工单标题：</label>
                    <input name='data[Order][title]' class="cominput" type="text" value=""/>
                </div>
                <!--工单类型-->
                <div class="text-box">
                    <label class="infor fl_left">工单类型：</label>
                    <div class="worker_form_inline">
                        <input type="text" value="流程权限" disabled/>
                        <!--工单类型下的选择-->
                        <div class="work_edit_user">
                            <!--左侧选择方式-->
                            <section class="work_method_list">
                                <article class="work_method_box">
                                    <ul class="method_ul" style="margin-top:0px;">
                                    <?php foreach( $workflowSteps as $k => $v ) {?>
                                        <li class="<?php if( $k == 0 ) { echo 'now';}?>" id="<?php echo $v; ?>"><?php echo $v;?></li>
                                    <?php }?>  
                                    </ul>
                                </article>
                                <!--隐藏的input框传单个选择或者批量选择-->
                                <article class="hiddeninput">
                                </article>
                                <article class="center select_more_show">
                                    <img class="pre" src="/images/top.png"/>
                                    <img class="next" src="/images/collapse.png"/>
                                </article>
                            </section>
                            <!--右侧选择人员-->
                            <section class="flow_power">
                                <!--默认所有-->
                                <div class="default_all center">
                                    <p>所有人</p>
                                    <a href="javascript:void();" class=" bigbutton  form_btnblue setsource">流程权限设置</a>
                                </div>
                                <!--选择用户-->
                                <div class="select_flow_user overscroll">
                                    <ul>
                                      
                                    </ul>
                                </div>
                                <!--添加选择按钮-->
                                <div class="add_flow_user btv_ico">
                                    <!--弹出显示选择编辑用户的框框-->
                                    <section class="user_role_box">
                                        <span class="small_index"></span>
                                        <!--用户角色tab选项-->
                                        <ul class="usertab">
                                            <li class="now">用户</li>
                                            <li>角色</li>
                                        </ul>
                                        <div class="user_list_tabbox">
                                            <div class="scrollparent">
                                                <ul class="work_userlist">
                                                    <?php foreach ($users as $value) {
                                                    ?>
                                                    <li id="<?php echo $value['id'];?>"><?php echo $value['name'];?></li>
                                                    <?php }?>
                                                </ul>
                                            </div>
                                            <div class="scrollparent" style="display:none;">
                                                <ul class="work_rolelist">
                                                    <?php foreach( $rolesArray as $v ) {?>
                                                    <li><?php echo $v['role_name'];?></li>
                                                    <?php }?>
                                                </ul>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <!--工单描述-->
                <div class="text-box">
                    <label class="infor fl_left">工单描述：</label>
                    <div class="worker_form_inline">
                        <textarea name="data[Order][desc]"></textarea>
                    </div>
                </div>
                <!--优先级-->
                <div class="text-box">
                    <label class="infor">优先级：</label>
                    <div class="displayInline upload_platform clearfix">
                        <div class="up_sel">
                           1
                            <i></i>
                        </div>
                        <div class="">
                            2
                            <i></i>
                        </div>
                        <div>
                            3
                            <i></i>
                        </div>
                        <div>
                            4
                            <i></i>
                        </div>
                        <div>
                            5
                            <i></i>
                        </div>
                    </div>
                    <input type="hidden" id="priority" name="data[Order][priority]">
                </div>
                <!--时间控件-->
                <div class="text-box">
                    <label class="infor">截止日期：</label>
                   <input type="text" name="data[Order][lasttime]" class="cominput" readonly onClick="WdatePicker()"/>
                </div>
            </section>
        </article>
        <article class="rigth_bottom sub_ContainerHeight" >
            <div class="fl_right" id="myform">
                <input type="submit" class="btv_btn form_btnblue" value="保存"/>
                <input type="button" class="btv_btn form_btnh" value="取消"/>
            </div>
        </article>
     </section>
     </form>
<?php $this->start('script');?>
 <script>

 function beforeSubmit() {
 	var priority = $('.up_sel').html();
 	$('#priority').val($.trim(priority.replace('<i></i>','')));
 }
     //每个用户对应的角色列表
      roles = eval((<?php echo $roles; ?>));

 </script>
<?php $this->end();?>
<?php $this->start('css');?>
<?php $this->end();?>