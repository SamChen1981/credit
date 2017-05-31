<?php $this->assign('title', __('Order Management'));?>
<!-- <article class="right_top">
    <i class="btv_ico classfy_big"></i>
</article> -->
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
		</article>
		<article class="content">
			<section class="table_top sub_ContainerHeight">
				<form >
					<div class="fl_right">
						<a href="<?php echo $this->Html->url('/orders/choose_step');?>" class="hbtn"><i class="btv_ico new"></i>新建工单</a>
					</div>
					<div>
						 <div class="search_box ver_top">
                            <input type="text" name="searchName"  value="">
                            <input class="btv_ico" type="submit" value=""/>
                        </div>
					</div>
				</form>
			</section>
			<div id="showmsg"></div>
			<section class="scrollparent tableparent">
				<table class="table_list overscroll">
					<thead>
						<tr>
							<th>优先度</th>
							<th>任务名</th>
							<th>
								<div class="select sort_select"> 
									<ul style="display:none;">
										<li value="youku">优酷</li>
										<li value="tudou">土豆</li>
										<li value="iqiyi">爱奇艺</li>
										<li value="chinaCache">城管嘎嘎嘎</li>
									</ul>
									<a href="javascript:void(0);" name="1" >状态</a>
								</div>
							</th>
							<th>
								<div class="select sort_select"> 
									<ul style="display:none;">
										<li value="youku">优酷</li>
										<li value="tudou">土豆</li>
										<li value="iqiyi">爱奇艺</li>
										<li value="chinaCache">城管嘎嘎嘎</li>
									</ul>
									<a href="javascript:void(0);" name="1" >发布者</a>
								</div>	
							</th>
							<th>
								截止日期
								<img class="sorttime" src="../images/jtbottom.png"/>
							</th>
							<th>编辑</th>
						</tr>
					</thead>
					<tbody>
					  <?php foreach ( $Orders as $Order ) {?>
						<tr>
							<td>
								<span class="workorder_level level<?php echo $Order['Order']['priority']; ?>"><?php echo $Order['Order']['priority']; ?></span>
							</td>
							<td>
								<div class="workorder_table_img"><img src="../images/1.jpg"/><?php echo $Order['Order']['title']; ?></div>
							</td>
							<td><?php echo $Order['Order']['status']; ?></td>
							<td><?php echo $Order['Order']['author']; ?></td>
							<td><?php echo date( 'Y-m-d H:i:s', $Order['Order']['lasttime'] ); ?></td>
							<td>
								<!--<a class="edit btv_ico" href="javascript:void(0)"></a>-->
								<a class="look btv_ico table_op" href="javascript:void(0)"></a>
								<a class="del btv_ico table_op" href="javascript:void(0)" onclick='btv.confirm("这是标题",function(){alert("dd");})'></a>
							</td>
						</tr>
						 <?php }?>
					</tbody>
				</table>
			</section>
		</article>
		<article class="rigth_bottom sub_ContainerHeight" >
			<div class="fl_right" id="myform"></div>
		</article>
	 </section>
	 <!--右侧滑动具体信息-->
	 <section id="moreinfo"  class="rightSlide">
		<div class="slidetop sub_ContainerHeight">
			<i class="btv_ico deljs fl_right"></i>
			视频<<是是是是是是>>的拆条
		</div>
		<!--具体信息-->
		<div class="scrollparent slidecontent">
			<section class="overscroll slideInfo">
				<div class="slidevideo">视频位置</div>
				<div class="text-box">
					<label class="infor">加工类型：</label>
					视频拆条
				</div>
				<div class="text-box">
					<label class="infor">截止日期：</label>
					2015年8月12日 20:55:20
				</div>
				<div class="text-box">
					<label class="infor">最近工作：</label>
					2015年8月12日 20:55:20
				</div>
				<div class="text-box">
					<label class="infor fl_left">详细说明：</label>
					<div>这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明</div>
				</div>
				<p class="session"><<历史记录</p>
				<div class="finishedtitle">
                    <a href="javascript:void(0);" class="btv_btn form_btnblue">删除任务</a>
				</div>
				<ul class="clearfix finishimg">
					<li><img src="../images/1.jpg"/></li>
					<li><img src="../images/1.jpg"/></li>
					<li><img src="../images/1.jpg"/></li>
				</ul>
                <p class="finishedtitle">距接受者还有<span class="getday">2天22:23:14</span>来完成工作</span></p>
                <p class="finishedtitle"><span class="doner">大熊</span></span>还有<span class="getday">2天22:23:14</span>来完成工作</span></p>
				<div class="materpro">
					<div class="materline">
                        <div class="nowline"></div>
						<ul class="clearfix">
							<li>
								<span class="now"></span>
								<p>编目</p>
							</li>
							<li>
								<span class="now"></span>
								<p>编目</p>
                                <p>已拆17条</p>
							</li>
							<li>
								<span></span>
								<p>编目</p>
                                <p>2/7</p>
							</li>
							<li>
								<span></span>
								<p>编目</p>
							</li>
						</ul>
					</div>
				</div>
			</section>
		</div>
        <!--点击图片弹出相应的信息-->
        <div class="showCompleteDetail">
            <div class="scrollparent">
                <section class="overscroll slideInfo">
                    <div class="slidevideo">视频位置</div>
                    <div class="text-box">
                        <label class="infor">视频名称：</label>
                        视频拆条
                    </div>
                    <div class="text-box">
                        <label class="infor">视频长度：</label>
                         20:55:20
                    </div>
                    <div class="text-box">
                        <label class="infor fl_left">视频描述：</label>
                        <div>这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明这是说明说明</div>
                    </div>
                    <div class="text-box">
                        <label class="infor">作者：</label>
                        丽丽
                    </div>
                    <div class="text-box">
                        <label class="infor">编辑：</label>
                        丽丽
                    </div>
                    <div class="text-box">
                        <label class="infor"></label>
                        <a href="javascript:void();" class=" bigbutton  form_btnblue">在视频库中查看</a>
                    </div>
                </section>
            </div>
            <span></span>
        </div>
	 </section>
	 <!--历史记录-->

	 <section id="morehistory" class="rightSlide">
		<div class="slidetop sub_ContainerHeight">
			<i class="btv_ico deljs fl_right"></i>
			历史记录
		</div>
		<div class="scrollparent slidecontent">
			<section class="overscroll history">
				<article class="beginhis">
					2015-01-10
				</article>
				<p>
					<span>09:35:25</span>
					<span class="operater">Sam</span>
					<span>创建工单</span>
				</p>
				<p>
					<span>09:35:25</span>
					<span class="operater">Sam</span>
					<span>修改工单</span>
				</p>
				<p>
					<span>09:35:25</span>
					<span class="operater">Sam</span>
					<span>未通过审核</span>
				</p>
				<div class="back_message">
					<span><img src="../images/leftyh.png"/></span>
					<p>未通过审核未通过审核未通过审核未通过审核未通过审核未通过审核未通过审核未通过审核未通过审核未通过审核</p>
					<span><img src="../images/rightyh.png"/></span>
				</div>
				<p>
					<span>09:35:25</span>
					<span class="operater">Sam</span>
					<span>未通过审核</span>
				</p>
				<div class="back_message">
					<span><img src="../images/leftyh.png"/></span>
					<p>未通过审核未通过审核未通过审核未通过审核未通过审核未通过审核未通过审核未通过审核未通过审核未通过审核</p>
					<span><img src="../images/rightyh.png"/></span>
				</div>
			</section>
		</div>
	 </section>
<?php $this->start('script');?>
<script type="text/javascript">
$(function(){
            laypage({
                cont: $('#myform'), //容器。值支持id名、原生dom对象，jquery对象,
                pages: 100, //总页数
                skip: true, //是否开启跳页
                skin: 'xcontent',
                curr:5,
                groups: 5, //连续显示分页数,
                jump: function(obj, first){
                    //得到了当前页，用于向服务端请求对应数据
                    var curr = obj.curr;
                }
            });
        })  
</script>
<?php echo $this->Page->pages($this->Paginator->param('pageCount'), $this->request->query);?>
<?php $this->end();?>