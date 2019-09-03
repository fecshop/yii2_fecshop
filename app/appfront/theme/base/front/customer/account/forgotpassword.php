<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container one-column">
    <?= Yii::$service->page->widget->render('base/breadcrumbs',$this); ?>
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
	<div class="account-create">
		<div class="page-title">
			<h1><?= Yii::$service->page->translate->__('Forgot Password'); ?></h1>
		</div>
        <form action="<?= Yii::$service->url->getUrl('customer/account/forgotpasswordsubmit'); ?>" method="post" id="form-validate">
			
			<div class="fieldset" style="width:auto">
				<h2 class="legend"><?= Yii::$service->page->translate->__('Confirm your identity to reset password'); ?></h2>
				<ul class="form-list">
					
					<li>
						<label for="email_address" class="required"><em>*</em><?= Yii::$service->page->translate->__('Email Address'); ?></label>
						<div class="input-box">
							<input name="editForm[email]" id="email_address" value="<?= $email ?>" title="Email Address" class="input-text validate-email required-entry" type="text">
						</div>
					</li>
					
					<?php  if($forgotCaptcha):   ?>
					<li>
						<div class="field">
                            <label for="captcha" class="required"><em>*</em><?= Yii::$service->page->translate->__('Captcha'); ?></label>
                            <div  class="input-box forgot-captha register-captcha ">
								<input type="text" name="editForm[captcha]" value="" size=10 class="login-captcha-input required-entry"> 
								<img class="login-captcha-img"  title="click refresh" src="<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?<?php echo md5(time() . mt_rand(1,10000));?>" align="absbottom" onclick="this.src='<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?'+Math.random();"></img>
								<i class="refresh-icon"></i>
								<div class="clear"></div>
                            </div>
							<script>
							<?php $this->beginBlock('forgot_password_captcha_onclick_refulsh') ?>  
							$(document).ready(function(){
								$(".refresh-icon").click(function(){
									$(this).parent().find("img").click();
								});
							});
							<?php $this->endBlock(); ?>  
							</script>  
							<?php $this->registerJs($this->blocks['forgot_password_captcha_onclick_refulsh'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

                        </div>
						
                    </li>
					<?php endif;  ?>
				</ul>
			</div>
			
			<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
			<div class="buttons-set">
				
				<button type="button" id="js_registBtn" class="redBtn"><em><span><i></i><?= Yii::$service->page->translate->__('Submit'); ?></span></em></button>
				<p class="back-link"><a href="<?= Yii::$service->url->getUrl('customer/account/login'); ?>" class="back-link"><small>« </small><?= Yii::$service->page->translate->__('Back'); ?></a></p>
				
			</div>
			<div class="clear"></div>
		</form>
	</div>
</div>
<?php 
$requiredValidate 			= 'This is a required field.';
$emailFormatValidate 		= 'Please enter a valid email address. For example johndoe@domain.com.';
?>
<script>
<?php $this->beginBlock('forgot_password') ?>  
$(document).ready(function(){
	$("#js_registBtn").click(function(){
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




