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
		echo $this->Html->css('common');
        echo $this->Html->css('cssrest');
        echo $this->Html->css('appAllModel');
        echo $this->Html->css('webuploader/webuploader.css');
        echo $this->Html->css('jquery.Jcrop.css');
        echo $this->Html->css('dialog/ui-dialog.css');
        echo $this->Html->css('appMembersGroup.css');
         $script = array('/lib/jquery-1.8.3.min',"/lib/Validform5.3.2","/lib/dialog-plus-min","common","appCommon","/lib/laypage","/lib/dialog-min","/lib/datapicter/WdatePicker","/lib/webuploader.min","/lib/jquery.Jcrop","/js/appMembersGroup");
         echo $this->Html->script($script );

		// echo $this->fetch('meta');
		// echo $this->fetch('css');
		// echo $this->fetch('script');
	?>
</head>
<body>
	<?php echo $this->fetch('content'); ?>
	<?php echo $this->element('sql_dump'); ?>
	<script>
    			var showMessage = function(message){
    				var showError = dialog({
    					id : 'showMessage',//避免重复打开
    				    content: message,
    				    title: '提示',
    				    okValue: '确认',
    				    padding: '20px 50px',
    				    ok : function(){

    				    }
    				});
    				showError.show();
    				setTimeout(function(){
    					showError.close().remove();
    				},5000);
    			}
    	</script>
</body>
</html>
