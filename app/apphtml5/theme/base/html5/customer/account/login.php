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
	<?= Yii::$service->page->translate->__('Login'); ?>
	<a external href="<?= Yii::$service->url->getUrl('customer/account/register');  ?>" class="f-right"><?= Yii::$service->page->translate->__('Register'); ?></a>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
<div class="list-block customer-login">
	<form action="<?= Yii::$service->url->getUrl("customer/account/login");  ?>" method="post" id="login-form" class="account-form">
		<ul>
			<li>
				<div class="item-content">
					<div class="item-media"><i class="icon icon-form-email"></i></div>
					<div class="item-inner">
						<div class="item-input">
							<input name="editForm[email]" value="<?= $email; ?>" id="email" type="email" placeholder="<?= Yii::$service->page->translate->__('E-mail'); ?>">
						</div>
					</div>
				</div>
			</li>
			<li>
				<div class="item-content">
					<div class="item-media"><i class="icon icon-form-password"></i></div>
					<div class="item-inner">
						<div class="item-input">
							<input type="password" placeholder="<?= Yii::$service->page->translate->__('Password'); ?>"  name="editForm[password]" class="input-text required-entry validate-password" id="pass" title="Password" >
						</div>
					</div>
				</div>
			</li>
			<?php if($loginPageCaptcha):  ?>
			<li>
				<div class="item-content">
					<div class="item-media"><i class="icon icon-form-password"></i></div>
					<div class="item-inner">
						<div class="item-input">
							<input placeholder="captcha" type="text" name="editForm[captcha]" value="" size=10 class="login-captcha-input"><img class="login-captcha-img"  title="<?= Yii::$service->page->translate->__('click refresh'); ?>" src="<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?<?php echo md5(time() . mt_rand(1,10000));?>" align="absbottom" onclick="this.src='<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?'+Math.random();"></img>
							 <span class="icon icon-refresh"></span>
						</div>
					</div>
				</div>
				<script>
				<?php $this->beginBlock('login_captcha_onclick_refulsh') ?>  
				$(document).ready(function(){
					$(".icon-refresh").click(function(){
						$(this).parent().find("img").click();
					});
				});
				<?php $this->endBlock(); ?>  
				</script>  
				<?php $this->registerJs($this->blocks['login_captcha_onclick_refulsh'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
			</li>
			<?php endif; ?>
		</ul>
		
		<div class="clear"></div>
		<div class="buttons-set">
			<p><a external href="#"  id="js_registBtn" class="button button-fill"><?= Yii::$service->page->translate->__('Sign In'); ?></a></p>
			<a external href="<?= Yii::$service->url->getUrl('customer/account/forgotpassword');  ?>" class="f-left"><?= Yii::$service->page->translate->__('Forgot Your Password?'); ?></a>
			
		</div>
		<div class="clear"></div>
		<div class="third_login">
			<div class="fago_login">
				<img onclick="facebooklogin()" src="<?= Yii::$service->image->getImgUrl("images/facebook.jpg") ?>" /><br/>
				<img onclick="googlelogin()"src="<?=Yii::$service->image->getImgUrl("images/google.jpg") ?>" /><br/>
			</div>
			<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
			<div class="col2-set">
				<div class="col-1 new-users">
					<div class="buttons-set">
						
					</div>
				</div>
				<div class="col-2 registered-users">
					
				</div>
			</div>
		</div>
	</form>
</div>	
  
<script type="text/javascript">	
<?php $this->beginBlock('customer_account_login') ?> 

	$(document).ready(function(){
		$("#js_registBtn").click(function(){
			$("#login-form").submit();
		});
        $(".email_register_resend").click(function(){
            emailRegisterResendUrl = "<?= Yii::$service->url->getUrl('customer/account/resendregisteremail') ?>";
            $.ajax({
                async:true,
                timeout: 6000,
                dataType: 'json', 
                type:'get',
                data: {
                    "email": "<?= $email ?>"
                },
                url:emailRegisterResendUrl,
                success:function(data, textStatus){ 
                    // 
                    if (data.resendStatus == 'success') {
                        //$(".resend_text").html('resend register email success');
                        alert("<?= Yii::$service->page->translate->__('resend register email success') ?>")
                    } else {
                        //$(".resend_text").html('resend register email fail');
                        alert("<?= Yii::$service->page->translate->__('resend register email fail') ?>")
                    }
                },
                error:function (XMLHttpRequest, textStatus, errorThrown){}
            });
            
            
        });
	});
	var newwindow;
	var intId;
	function facebooklogin(){
		var  screenX    = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
			 screenY    = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
			 outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
			 outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
			 width    = 800,
			 height   = 450,
			 left     = parseInt(screenX + ((outerWidth - width) / 2), 10),
			 top      = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
			 features = (
				'width=' + width +
				',height=' + height +
				',left=' + left +
				',top=' + top
			  );

		newwindow=window.open('<?php echo $facebookLoginUrl; ?>','Login_by_facebook',features);

	   if (window.focus) {newwindow.focus()}
	  return false;
	}
	
	function googlelogin(){
		var  screenX    = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
			 screenY    = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
			 outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
			 outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
			 width    = 800,
			 height   = 450,
			 left     = parseInt(screenX + ((outerWidth - width) / 2), 10),
			 top      = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
			 features = (
				'width=' + width +
				',height=' + height +
				',left=' + left +
				',top=' + top
			  );

		newwindow=window.open('<?= $googleLoginUrl; ?>   ','Login_by_facebook',features);

	   if (window.focus) {newwindow.focus()}
	  return false;
	}
	
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['customer_account_login'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
 
 
 
 