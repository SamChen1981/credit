<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		// echo $this->Html->css('cake.generic');
		echo $this->Html->css('cssrest');
		echo $this->Html->css('workorder');
        echo $this->Html->css('common');
		echo $this->fetch('meta');
		echo $this->fetch('css');
	?>
</head>
<body>
	<?php echo $this->fetch('content'); ?>
	
	<?php echo $this->element('sql_dump'); ?>

	<?php
	$script = array('jquery-1.8.3.min', 'moreMenu', 'common', 'workCommon', 'myworkplace', 'datapicter/WdatePicker.js' );
	echo $this->Html->script($script );
	?>
	<script type="text/javascript">
		var appHost = "<?php echo $this->webroot;?>";
	</script>
	<?php echo $this->fetch('script');?>
</body>
</html>
<script>
//打开应用
    function a(){ 
        var openUrl = 'http://www.baidu.com';
        var data = {'url':openUrl,'action':'1','width':960,'height':600,'title':'百度首页'};
        top.postMessage(data,'*');
    }
</script>