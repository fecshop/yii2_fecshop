<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CUrl;
use fec\helpers\CRequest;
?>
<style>
.login_bar {
    padding-left: 80px;
}
</style>
<form action="<?= CUrl::getUrl('fecadmin/login/index'); ?>" method="post">
	<?php echo CRequest::getCsrfInputHtml();  ?>	
	<p>
		<label>用户名：</label>
		<input type="text" name="login[username]" size="20" class="login_input" />
	</p>
	<p>
		<label>密码：</label>
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
		<input class="sub" type="submit" value="" />
	</div>
</form>

<script> <!-- 编写script标签是为了编辑器识别js代码，可以省略 -->  
<?php $this->beginBlock('js_end') ?>  
　$(document).ready(function(){$("#login-captcha-image").click();});  
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['js_end'],\yii\web\View::POS_LOAD); ?>