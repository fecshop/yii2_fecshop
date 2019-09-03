<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="shopping-cart-img">
	<?= Yii::$service->page->translate->__('Contacts'); ?>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
<div class="list-block customer-login ">
	<div class="col-main account_center">
		<form method="post" id="form-validate" class="account-form" >
			<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
			<ul>
				<li>
					<div class="item-content">
						<div class="item-media">
							<i class="icon icon-form-name"></i>
						</div>
						<div class="item-inner">
							<div class="item-input">
								<input placeholder="Your Name" id="contacts_name" name="editForm[name]" value="<?= $name ?>" title="First Name" maxlength="255" class="input-text required-entry" type="text">
								
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="item-content">
						<div class="item-media">
							<i class="icon icon-form-name"></i>
						</div>
						<div class="item-inner">
							<div class="item-input">
								<input placeholder="Your Email Address" id="contacts_email" name="editForm[email]" value="<?= $email ?>" title="Last Name" maxlength="255" class="input-text required-entry" type="text">
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="item-content">
						<div class="item-media">
							<i class="icon icon-form-name"></i>
						</div>
						<div class="item-inner">
							<div class="item-input">
								<input placeholder="Your telephone" name="editForm[telephone]" id="contacts_telephone" value="<?= $telephone ?>" title="Email Address" class="input-text required-entry validate-email" type="text">
							
							</div>
						</div>
					</div>
				</li>
				<li>
					<div class="item-content">
						<div class="item-media">
							<i class="icon icon-form-name"></i>
						</div>
						<div class="item-inner">
							<div class="item-input">
								<textarea placeholder="Your Comment" name="editForm[comment]" id="contacts_comment"><?= $comment ?></textarea>
							
							</div>
						</div>
					</div>
				</li>
				<?php if($contactsCaptcha):  ?>
					<li>
						<div class="item-content">
							<div class="item-media"><i class="icon icon-form-password"></i></div>
							<div class="item-inner">
								<div class="item-input">
									<input placeholder="captcha" type="text" name="sercrity_code"  value="" size=10 class="login-captcha-input verification_code_input">
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
			<div class="clear"></div>
			<div class="buttons-set">
				<p>
					<a   external href="#"  id="js_contactBtn" class="button button-fill">
						<?= Yii::$service->page->translate->__('Contact Us'); ?>
					</a>
				</p>
			</div>	
		</form>
	</div>
	
	<div class="clear"></div>
	<div class="mailtous">
		<span><?= Yii::$service->page->translate->__('Our Email Address'); ?></span>: 
		<a href="mailto:<?= $contactsEmail ?>"><?= $contactsEmail ?>
		
	</div>
</div>

<script>
	<?php $this->beginBlock('contact_us') ?>  
	$(document).ready(function(){
		$("#js_contactBtn").click(function(){
			$("#form-validate").submit();
		});
	});
	<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['contact_us'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

	
	