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
		<h1 class='title'><?= Yii::$service->page->translate->__('Edit Account'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>

<div class="list-block customer-login  customer-register">
	<form method="post" id="form-validate" autocomplete="off" action="<?=  $actionUrl ?>">
		<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
		<ul>
			<li>
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input type="text" placeholder="<?= Yii::$service->page->translate->__('Email Address');?>"  style="color:#ccc;" readonly="true" id="customer_email" name="editForm[email]" value="<?= $email ?>" title="Email"  class="input-text required-entry" />
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
							<input  placeholder="<?= Yii::$service->page->translate->__('First Name'); ?>" id="firstname" name="editForm[firstname]" value="<?= $firstname ?>" title="First Name"  class="input-text required-entry" type="text"  />
							<div class="validation-advice" id="required_current_firstname" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
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
							<input  type="text" placeholder="<?= Yii::$service->page->translate->__('Last Name'); ?>" id="lastname" name="editForm[lastname]" value="<?= $lastname ?>" title="Last Name" maxlength="255" class="input-text required-entry" />
							<div class="validation-advice" id="required_current_lastname" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
						</div>
					</div>
				</div>
			</li>
			
			<li class="control">
				<div class="change_password_label item-content">
					<input name="editForm[change_password]" id="change_password" value="1" onclick="setPasswordForm(this.checked)" title="Change Password" class="checkbox" type="checkbox">
					<label for="change_password"><?= Yii::$service->page->translate->__('Change Password');?></label>
				</div>
			</li>
			
			<li class="fieldset_pass" style="display:none">
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input placeholder="<?= Yii::$service->page->translate->__('Current Password'); ?>" title="Current Password" class="input-text required-entry" name="editForm[current_password]" id="current_password" type="password" />
							<div class="validation-advice" id="required_current_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
								
						</div>
					</div>
				</div>
			</li>
			
			<li class="fieldset_pass" style="display:none">
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input placeholder="<?= Yii::$service->page->translate->__('New Password'); ?>" title="New Password" class="input-text validate-password required-entry" name="editForm[password]" id="password" type="password" />
							<div class="validation-advice" id="required_new_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>	
						</div>
					</div>
				</div>
			</li>
			
			<li class="fieldset_pass" style="display:none">
				<div class="item-content">
					<div class="item-media">
						<i class="icon icon-form-name"></i>
					</div>
					<div class="item-inner">
						<div class="item-input">
							<input placeholder="<?= Yii::$service->page->translate->__('Confirm New Password'); ?>"  title="Confirm New Password" class="input-text validate-cpassword required-entry" name="editForm[confirmation]" id="confirmation" type="password"  />
							<div class="validation-advice" id="required_confirm_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
						</div>
					</div>
				</div>
			</li>
		</ul>
		<div class="clear"></div>
		<div class="buttons-set">
			<p>
				<a external href="#"  id="js_editBtn" class="button button-fill">
					<?= Yii::$service->page->translate->__('Edit Account'); ?>
				</a>
			</p>
		</div>
		
	</form>
</div>

<script>
<?php $this->beginBlock('customer_account_info_update') ?> 
	
	function setPasswordForm(arg){
		if(arg){
            $('.fieldset_pass').show();
        }else{
            $('.fieldset_pass').hide();
        }
    }
    function check_edit(){
        $check_current_password = true;
        $check_new_password = true;
        $check_confir_password = true;
		$check_current_firstname = true;
		$check_current_lastname = true;
		
		$firstname = $('#firstname').val();
		$lastname = $('#lastname').val();
		$check_confir_password_with_pass = true;
		
		if($firstname == ''){
		   $('#firstname').addClass('validation-failed');
		   $('#required_current_firstname').show();
		   $check_current_firstname = false;
		}else{
		   $('#firstname').removeClass('validation-failed');
		   $('#required_current_firstname').hide();
		   $check_current_firstname = true;
		}
		
		if($lastname == ''){
		   $('#lastname').addClass('validation-failed');
		   $('#required_current_lastname').show();
		   $check_current_lastname = false;
		}else{
		   $('#lastname').removeClass('validation-failed');
		   $('#required_current_lastname').hide();
		   $check_current_lastname = true;
		}
		
        if($('#change_password').is(':checked')){
            $current_password = $('#current_password').val();
            $password = $('#password').val();
            $confirmation = $('#confirmation').val();
            if($current_password == ''){
               $('#current_password').addClass('validation-failed');
               $('.required_current_password').show();
               $check_current_password = false;
            }else{
               $('#current_password').removeClass('validation-failed');
               $('#required_current_password').hide();
               $check_current_password = true;
            }
            if($password == ''){
               $('#password').addClass('validation-failed');
               $('#required_new_password').show().html('This is a required field.');;
               $check_new_password = false;
            }else{
                if(!checkPass($password)){
                    $('#password').addClass('validation-failed');
                    $('#required_new_password').show();
                    $('#required_new_password').html('Must have 6 to 30 characters and no spaces.');
                    $check_new_password = false;
                }else{
                    $('#password').removeClass('validation-failed');
                    $('#required_new_password').hide();
                    $check_new_password = true;
                }
            }
			
            if($confirmation == ''){
               $('#confirmation').addClass('validation-failed');
               $('#required_confirm_password').show().html('This is a required field.');
               $check_confir_password = false;
            }else{
                if(!checkPass($confirmation)){
                    $('#confirmation').addClass('validation-failed');
                    $('#required_confirm_password').show();
                    $('#required_confirm_password').html('Must have 6 to 30 characters and no spaces.');
                    $check_confir_password = false;
                 }else{
					if($password != $confirmation){
						$('#confirmation').addClass('validation-failed');
						$('#required_confirm_password').show();
						$('#required_confirm_password').html('Two password is not the same！');
						$check_confir_password_with_pass = false;
					}else{
						$('#confirmation').removeClass('validation-failed');
						$('#required_confirm_password').hide();
						$check_confir_password = true;
					}
                    
                }
            }
		}
	 
		if( $check_confir_password_with_pass && $check_current_firstname && $check_current_lastname && $check_confir_password && $check_new_password && $check_current_password){
			return true;
		}else{
			return false;
		}
	}
	
	function checkPass(str){
        var re = /^\w{6,30}$/;
         if(re.test(str)){
           return true;
        }else{
           return false;
        }
    }
    function checkEmail(str){  
        var myReg = /^[-_A-Za-z0-9]+@([_A-Za-z0-9]+\.)+[A-Za-z0-9]{2,3}$/; 
        if(myReg.test(str)) return true; 
        return false; 
    } 
	$(document).ready(function(){
		$("#js_editBtn").click(function(){
			if(check_edit()){
				$("#form-validate").submit();
			}
		});
	});
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['customer_account_info_update'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

	