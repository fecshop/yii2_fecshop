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
    
	<div class="account-login">
		<div class="page-title">
			<h1><?= Yii::$service->page->translate->__('Login or Create an Account'); ?></h1>
		</div>
		<form action="<?= Yii::$service->url->getUrl("customer/account/login");  ?>" method="post" id="login-form">
			<div class="col2-set">
				<div class="col-1 new-users">
					<div class="content">
						<h2><?= Yii::$service->page->translate->__('New Customers'); ?></h2>
						<p><?= Yii::$service->page->translate->__('By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.'); ?></p>
					</div>
					<button onclick="window.location='<?= Yii::$service->url->getUrl('customer/account/register') ?>';" type="button" title="Create an Account" class="redBtn"><em><span><i></i><?= Yii::$service->page->translate->__('register'); ?></span></em></button>
				</div>   
				<div class="col-2 registered-users">
					<div class="content">
						<h2><?= Yii::$service->page->translate->__('Registered Customers'); ?></h2>
						<p><?= Yii::$service->page->translate->__('If you have an account with us, please log in.'); ?></p>
						<ul class="form-list">
							<li>
								<label for="email" class="required"><em>*</em><?= Yii::$service->page->translate->__('Email Address'); ?></label>
								<div class="input-box">
									<input name="editForm[email]" value="<?= $email; ?>" id="email" class="input-text required-entry validate-email" title="Email Address" type="text">
								</div>
							</li> 
							<li>
								<label for="pass" class="required"><em>*</em><?= Yii::$service->page->translate->__('Password'); ?></label>
								<div class="input-box">
									<input name="editForm[password]" class="input-text required-entry validate-password" id="pass" title="Password" type="password">
								</div>
							</li>
							<?php if($loginPageCaptcha):  ?>
							<li>
								<label for="captcha" class="required"><em>*</em><?= Yii::$service->page->translate->__('Captcha'); ?></label>
								<div class="input-box login-captcha">
									<input type="text" name="editForm[captcha]" value="" size=10 class="login-captcha-input"> 
									<img class="login-captcha-img"  title="<?= Yii::$service->page->translate->__('click refresh'); ?>" src="<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?<?php echo md5(time() . mt_rand(1,10000));?>" align="absbottom" onclick="this.src='<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?'+Math.random();"></img>
									<i class="refresh-icon"></i>
								</div>
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

							</li>
							<?php endif;  ?>
						
						<div class="clear"></div>
						<div class="buttons-set">
							<button type="submit" id="js_registBtn" class="redBtn"><em><span><i></i><?= Yii::$service->page->translate->__('Sign In'); ?></span></em></button>
							<a href="<?= Yii::$service->url->getUrl('customer/account/forgotpassword');  ?>" class="f-left"><?= Yii::$service->page->translate->__('Forgot Your Password?'); ?></a>
							
						</div>
						<div class="clear"></div>
						<div class="fago_login">
							<img onclick="facebooklogin()" src="<?= Yii::$service->image->getImgUrl("images/facebook.jpg") ?>" /><br/>
							<img onclick="googlelogin()"src="<?=Yii::$service->image->getImgUrl("images/google.jpg") ?>" /><br/>
						</div>
					</div>
				</div>
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
		</form>
	</div>
</div>

<script type="text/javascript">
<?php $this->beginBlock('customer_account_login') ?> 
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
             height   = 650,
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
    $(document).ready(function(){
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
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['customer_account_login'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

 
 