<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license/
 */
use fec\helpers\CUrl;
use fec\helpers\CRequest;
?>
<style>

</style>
<form action="<?= CUrl::getUrl('fecadmin/login/index'); ?>" method="post">
	<?php echo CRequest::getCsrfInputHtml();  ?>
	<p class="lg">
		<label><?= Yii::$service->page->translate->__('User'); ?> : </label>
		<input type="text" name="login[username]" size="20" class="login_input" />
	</p>
	<p class="lg">
		<label><?= Yii::$service->page->translate->__('Password'); ?> : </label>
		<input type="password" name="login[password]" size="20" class="login_input" />
	</p>
	<!--
	<p>
		<label>验证码：</label>
		<input name="login[captcha]" class="code" type="text" size="5" />
		<?php
			//echo \fec\helpers\CCaptcha::widget([
			//	'name' => 'login[captcha]',
			//	'class' => \fec\helpers\CCaptcha::className(),
			//	'id'	=> 'login-captcha',
			//	'template' =>  '{image}',
				//'action'	=> '/fecadmin/captcha/index'
			//]);
		?>
	</p>
	-->
	<p>
		<span style="color:#cc0000"><?= $error; ?> </span>
	</p>
	<div class="login_bar">
		<input class="sub" type="submit" value="<?= Yii::$service->page->translate->__('Login'); ?>" />
	</div>
</form>

<script> <!-- 编写script标签是为了编辑器识别js代码，可以省略 -->  
<?php $this->beginBlock('js_end') ?>  
　$(document).ready(function(){$("#login-captcha-image").click();});  
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['js_end'],\yii\web\View::POS_LOAD); ?>