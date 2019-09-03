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
    <?= Yii::$service->page->widget->render('base/breadcrumbs',$this); ?>
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				<div class="page-title">
					<h2><?= Yii::$service->page->translate->__('Edit Account Information');?></h2>
				</div>
				<form method="post" id="form-validate" autocomplete="off" action="<?=  $actionUrl ?>">
					<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
					<div class="">
						<ul class="">
							<li>
								<label for="email" class="required"><?= Yii::$service->page->translate->__('Email Address');?></label>
								<div class="input-box">
									<input style="color:#ccc;" readonly="true" id="customer_email" name="editForm[email]" value="<?= $email ?>" title="Email" maxlength="255" class="input-text required-entry" type="text">
								</div>
							</li>
							<li class="">
                                <div class="field name-firstname">
                                    <label for="firstname" class="required"><?= Yii::$service->page->translate->__('First Name');?></label>
                                    <div class="input-box">
                                        <input id="firstname" name="editForm[firstname]" value="<?= $firstname ?>" title="First Name" maxlength="255" class="input-text required-entry" type="text">
                                        <div class="validation-advice" id="required_current_firstname" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
                                    </div>
                                </div>
							</li>
							<li>
								<div class="field name-lastname">
                                    <label for="lastname" class="required"><?= Yii::$service->page->translate->__('Last Name');?></label>
                                    <div class="input-box">
                                        <input id="lastname" name="editForm[lastname]" value="<?= $lastname ?>" title="Last Name" maxlength="255" class="input-text required-entry" type="text">
                                        <div class="validation-advice" id="required_current_lastname" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
                                    </div>
                                </div>
							</li>
							<li class="control">
								<input name="editForm[change_password]" id="change_password" value="1" onclick="setPasswordForm(this.checked)" title="Change Password" class="checkbox" type="checkbox">
								<label style="display:inline;" for="change_password"><?= Yii::$service->page->translate->__('Change Password');?></label>
							</li>
						</ul>
					</div>
				
					<div class="" id="fieldset_pass" style="display:none;">
						
						<ul class="form-list">
							<li>
								<label style="font-weight:100;" for="current_password" class="required"><?= Yii::$service->page->translate->__('Current Password');?></label>
								<div class="input-box">
									<input title="Current Password" class="input-text required-entry" name="editForm[current_password]" id="current_password" type="password">
									<div class="validation-advice" id="required_current_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
								</div>
							</li>
							<li class="fields">
								<div class="field">
									<label style="font-weight:100;" for="password" class="required"><?= Yii::$service->page->translate->__('New Password');?></label>
									<div class="input-box">
										<input title="New Password" class="input-text validate-password required-entry" name="editForm[password]" id="password" type="password">
										<div class="validation-advice" id="required_new_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
									</div>
								</div>
								<div class="field">
									<label style="font-weight:100;" for="confirmation" class="required"><em>*</em><?= Yii::$service->page->translate->__('Confirm New Password');?></label>
									<div class="input-box">
										<input title="Confirm New Password" class="input-text validate-cpassword required-entry" name="editForm[confirmation]" id="confirmation" type="password">
										<div class="validation-advice" id="required_confirm_password" style="display:none;"><?= Yii::$service->page->translate->__('This is a required field.');?></div>
									</div>
								</div>
								<div class="clear"></div>
							</li>
						</ul>
					</div>
					<div class="buttons-set">
						<button type="submit" title="Save" class="button" onclick="return check_edit()"><span><span><?= Yii::$service->page->translate->__('Submit');?></span></span></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="col-left ">
		<?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
	</div>
	<div class="clear"></div>
</div>

<script>
<?php $this->beginBlock('customer_account_info_update') ?> 
	function setPasswordForm(arg){
        if(arg){
            $('#fieldset_pass').show();
        }else{
            $('#fieldset_pass').hide();
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
               $('#required_current_password').show();
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
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['customer_account_info_update'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

	