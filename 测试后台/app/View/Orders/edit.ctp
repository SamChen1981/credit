<?php $this->assign('title', __('Edit Order'));?>
	<form action="<?php echo $this->Html->url(array('controller'=>'Orders', 'action'=>'edit'));?>" method="POST">
            <div>
                <div class="search_box ver_top">
                    <input type="hidden" name='data[Order][id]' value="<?php echo $this->request->data['Order']['id']?>" />
                    工单标题<input type="text" name='data[Order][title]' value="<?php echo $this->request->data['Order']['title']?>" />
                    工单描述<input type="text" name='data[Order][desc]' value="<?php echo $this->request->data['Order']['desc']?>" />
                    优先级<input type="text" name='data[Order][priority]' value="<?php echo $this->request->data['Order']['priority']?>" />
                    截至日期<input type="text" name='data[Order][lasttime]' value="<?php echo $this->request->data['Order']['lasttime']?>" />
                    <input type="hidden" name="data[Order][id]" value="<?php echo $this->request->data['Order']['id']?>" />
                    <input class="btv_ico" type="submit" value="提交"/>
                </div>
            </div>
    </form>
<?php $this->start('script');?>
<?php $this->end();?>
<?php $this->start('css');?>
<?php $this->end();?>