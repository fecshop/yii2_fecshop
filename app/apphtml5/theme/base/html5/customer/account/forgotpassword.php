<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="account-ds">
	<div class="bar bar-nav account-top-m">
		<a external class="button button-link button-nav pull-left" href="<?= Yii::$service->url->getUrl('customer/account/index'); ?>">
			<span class="icon icon-left"></span>
		</a>
		<h1 class='title'><?= Yii::$service->page->translate->__('Forgot Password'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
<div class="list-block customer-login ">
	<form class="account-form" action="<?= Yii::$service->url->getUrl('customer/account/forgotpasswordsubmit'); ?>" method="post" id="form-validate">
		<ul>
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input placeholder="<?= Yii::$service->page->translate->__('Your Email Address');?>" name="editForm[email]" id="email_address" value="<?= $email ?>" title="Email Address" class="input-text validate-email required-entry" type="text">
						</div>
					</div>
				</div>
			</li>
			<?php if($forgotCaptcha):  ?>
				<li>
					<div class="item-content">
						<div class="item-media"><i class="icon icon-form-password"></i></div>
						<div class="item-inner">
							<div class="item-input">
								<input placeholder="<?= Yii::$service->page->translate->__('Captcha'); ?>" type="text" name="editForm[captcha]" value="" size=10 class="login-captcha-input">
                                <img class="login-captcha-img"  title="<?= Yii::$service->page->translate->__('click refresh'); ?>" src="<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?<?php echo md5(time() . mt_rand(1,10000));?>" align="absbottom" onclick="this.src='<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?'+Math.random();"></img>
								<span class="icon icon-refresh"></span>
							</div>
						</div>
					</div>
					<script>
						<?php $this->beginBlock('forgot_password_captcha_onclick_refulsh') ?>  
						$(document).ready(function(){
							$(".icon-refresh").click(function(){
								$(this).parent().find("img").click();
							});
						});
						<?php $this->endBlock(); ?>  
					</script>  
					<?php $this->registerJs($this->blocks['forgot_password_captcha_onclick_refulsh'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
				</li>
			<?php endif; ?>
		</ul>
		<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
		<div class="clear"></div>
		<div class="buttons-set">
			<p>
				<a external href="javascript:void(0)"   id="js_forgotBtn" class="button button-fill">
					<?= Yii::$service->page->translate->__('Send Authorization Code'); ?>
				</a>
			</p>
		</div>
		
		<div class="clear"></div>
	</form>
</div>
<?php 
$requiredValidate 			= 'This is a required field.';
$emailFormatValidate 		= 'Please enter a valid email address. For example johndoe@domain.com.';

?>
<script>
<?php $this->beginBlock('forgot_password') ?>  
$(document).ready(function(){
	$("#js_forgotBtn").click(function(){
		validate = 1;
		$(".validation-advice").remove();
		$(".validation-failed").removeClass("validation-failed");
		var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		// empty check
		$(".account-create .required-entry").each(function(){
			val = $(this).val();
			if(!val){
				$(this).addClass("validation-failed");
				$(this).parent().append('<div class="validation-advice" id="advice-required-entry-firstname" style=""><?= $requiredValidate; ?></div>');
				validate = 0;
			}
		});
		// email check
		$(".account-create .validate-email").each(function(){
			email = $(this).val();
			if(email){
				if(!$(this).hasClass("validation-failed")){
					if(!myreg.test(email)){
						$(this).parent().append('<div class="validation-advice" id="advice-validate-email-email_address" style=""><?= $emailFormatValidate; ?></div>');
						$(this).addClass("validation-failed");
						validate = 0;
					}
				}
			}else{
				validate = 0;
			}
		});
		if(validate){
			$(this).addClass("dataUp");
			$("#form-validate").submit();
		}
	});
});
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['forgot_password'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>




