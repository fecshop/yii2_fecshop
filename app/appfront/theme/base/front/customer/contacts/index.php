<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container two-columns-left">
	<div class="col-main account_center">
		<?= Yii::$service->page->widget->render('base/flashmessage'); ?>			
		<div class="std">
			<div class="page-title">
				<h2><?= Yii::$service->page->translate->__('Contact Information'); ?></h2>
			</div>
			<form method="post" id="form-validate" autocomplete="off">
				<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
				<div class="fieldset">
					<h2 class="legend"><?= Yii::$service->page->translate->__('Contact Information'); ?></h2>
					<ul class="form-list">
						<li class="fields">
							<div class="customer-name">
								<div class="field name-firstname">
									<label for="firstname" class="required"><em>*</em><?= Yii::$service->page->translate->__('Name'); ?></label>
									<div class="input-box">
										<input id="contacts_name" name="editForm[name]" value="<?= $name ?>" title="First Name" maxlength="255" class="input-text required-entry" type="text">
									</div>
									<div style="height:10px;" id="contacts_name_span"></div>
								</div>
								<div class="field name-lastname">
									<label for="lastname" class="required"><em>*</em><?= Yii::$service->page->translate->__('Email Address'); ?></label>
									<div class="input-box">
										<input id="contacts_email" name="editForm[email]" value="<?= $email ?>" title="Last Name" maxlength="255" class="input-text required-entry" type="text">
									</div>
									<div style="height:10px;" id="contacts_email_span"></div>
										
								</div>
								<div class="clear"></div>
							</div>
						</li>
						<li>
							<label for="email" class="required"><?= Yii::$service->page->translate->__('Telephone'); ?></label>
							<div class="input-box">
								<input name="editForm[telephone]" id="contacts_telephone" value="<?= $telephone ?>" title="Email Address" class="input-text required-entry validate-email" type="text">
								<span id="email_edit_span"></span>
							</div>
						</li>
						<li>
							<label for="email" class="required"><em>*</em><?= Yii::$service->page->translate->__('Comment'); ?></label>
							<div class="input-box">
								<textarea name="editForm[comment]" id="contacts_comment"><?= $comment ?></textarea>
								<span id="contacts_comment_span"></span>
							</div>
						</li>
						<?php  if($contactsCaptcha):  ?>
						<li>
							<label for="pass" class="required customertitle"><em>*</em><?= Yii::$service->page->translate->__('Captcha'); ?></label>
							<div class="input-box login_box">
								<input class="verification_code_input" maxlength="4" name="sercrity_code" value="" type="text">
									<img class="login-captcha-img"  title="click refresh" src="<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?<?php echo md5(time() . mt_rand(1,10000));?>" align="absbottom" onclick="this.src='<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?'+Math.random();"></img>
									<i class="refresh-icon"></i>
								<br>
								<script>
								<?php $this->beginBlock('login_captcha_onclick_refulsh') ?>  
								$(document).ready(function(){
									$(".refresh-icon").click(function(){
										$(this).parent().find("img").click();
									});
								});
								<?php $this->endBlock(); ?>  
								</script>  
								<?php $this->registerJs($this->blocks['login_captcha_onclick_refulsh'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
							</div>
						</li>
						<?php  endif; ?>
					</ul>
				</div>
			<div class="buttons-set">
			   <button type="submit" title="Save" class="button " ><span><span><?= Yii::$service->page->translate->__('Contact Us'); ?></span></span></button>
			</div>
		</form>
		</div>
	</div>
	
	<div class="col-left ">
		<address class="block contact-us-address-block">
			<div class="block-title">
				<h2><?= Yii::$service->page->translate->__('Contacts'); ?></h2>
			</div>
			<div class="block-content">
				<strong><?= Yii::$service->page->translate->__('Email Address'); ?>:</strong> 
					<a href="mailto:<?= $contactsEmail ?>"><?= $contactsEmail ?></a>
				<br>
			</div>
		</address>
	</div>
	<div class="clear"></div>
</div>
	