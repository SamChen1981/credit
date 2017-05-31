<?php $this->assign('title', __('Create Order'));?>
<!--左侧栏目列表-->
	 <aside class="column_slider">
		<ul>
			 <li class="user now"><a href="<?php echo $this->Html->url('/orders');?>"><i class="btv_ico"></i>工单</a></li>
            <li class="classfy"><a href="<?php echo $this->Html->url('/tasks');?>"><i class="btv_ico"></i>任务</a></li>
		</ul>
	 </aside>
	 <!--左侧栏目结束-->
	 <section class="rightcontent">
		<article class="right_top sub_ContainerHeight">
			<i class="btv_ico user_big"></i>
			<span class="font14 color">工单</span>
            <span>>新建工单</span>
		</article>
        <article class="content">
            <ul class="select_source_flow clearfix">
            <?php foreach($roleDatas as $v) {?>
                <li><a href="<?php echo $this->webroot . 'orders/add/' . $v;?>"><?php echo $v;?></a></li>
            <?php }?>
            </ul>
        </article>

	 </section>
