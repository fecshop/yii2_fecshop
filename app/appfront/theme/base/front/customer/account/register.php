<div class="main container one-column">
	<div class="account-create">
		<div class="page-title">
			<h1>Create an Account</h1>
		</div>
        <form action="<?= Yii::$service->url->getUrl('customer/account/register'); ?>" method="post" id="form-validate">
			<div class="fieldset">
				<h2 class="legend">Personal Information</h2>
				<ul class="form-list">
					<li class="fields">
						<div class="customer-name">
							<div class="field name-firstname">
								<label for="firstname" class="required"><em>*</em>First Name</label>
								<div class="input-box">
									<input id="firstname" name="firstname" value="" title="First Name" maxlength="255" class="input-text required-entry" type="text">
								</div>
							</div>
							<div class="field name-lastname">
								<label for="lastname" class="required"><em>*</em>Last Name</label>
								<div class="input-box">
									<input id="lastname" name="lastname" value="" title="Last Name" maxlength="255" class="input-text required-entry" type="text">
								</div>
							</div>
						</div>
					</li>
					<li>
						<label for="email_address" class="required"><em>*</em>Email Address</label>
						<div class="input-box">
							<input name="email" id="email_address" value="" title="Email Address" class="input-text validate-email required-entry" type="text">
						</div>
					</li>
                    <li class="control">
						<div class="input-box">
							<input name="is_subscribed" title="Sign Up for Newsletter" value="1" id="is_subscribed" class="checkbox" type="checkbox">
						</div>
						<label for="is_subscribed">Sign Up for Newsletter</label>
					</li>
				</ul>
			</div>
			<div class="fieldset">
				<h2 class="legend">Login Information</h2>
				<ul class="form-list">
					<li class="fields">
						<div class="field">
							<label for="password" class="required"><em>*</em>Password</label>
							<div class="input-box">
								<input name="password" id="password" title="Password" class="input-text required-entry validate-password" type="password">
							</div>
						</div>
						<div class="field">
							<label for="confirmation" class="required"><em>*</em>Confirm Password</label>
							<div class="input-box">
								<input name="confirmation" title="Confirm Password" id="confirmation" class="input-text required-entry validate-cpassword" type="password">
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="buttons-set">
				<p class="required">* Required Fields</p>
				<p class="back-link"><a href="<?= Yii::$service->url->getUrl('customer/account/login'); ?>" class="back-link"><small>« </small>Back</a></p>
				<button type="button" id="js_registBtn" class="redBtn"><em><span><i></i>Submit</span></em></button>
			</div>
			<div class="clear"></div>
		</form>
	</div>
</div>
<?php 
$requiredValidate 			= 'This is a required field.';
$emailFormatValidate 		= 'Please enter a valid email address. For example johndoe@domain.com.';
$firstNameLenghtValidate 	= 'first name length must between';
$lastNameLenghtValidate 	= 'last name length must between';
$passwordLenghtValidate 	= 'Please enter 6 or more characters. Leading or trailing spaces will be ignored.';
$passwordMatchValidate 		= 'Please make sure your passwords match. ';
$minNameLength = 2;
$maxNameLength = 20;
$minPassLength = 6;  
$maxPassLength = 30;

?>
<script>
<?php $this->beginBlock('customer_account_register') ?>  
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
		
		//first name lenght check;
		firstname 	= $("#firstname").val();
		lastname 	= $("#lastname").val();
		password  	= $("#password").val();
		confirmation= $("#confirmation").val();
		minNameLength = <?= $minNameLength ?>;  
		maxNameLength = <?= $maxNameLength ?>;
		minPassLength = <?= $minPassLength ?>;  
		maxPassLength = <?= $maxPassLength ?>; 
		firstNameLength = firstname.length;
		lastNameLength  = lastname.length;
		passwordLength  = password.length;
		//firstname length validate
		if(firstNameLength < minNameLength || firstNameLength > maxNameLength){
			if(!$("#firstname").hasClass("validation-failed")){
				//alert(111);
				$("#firstname").parent().append('<div class="validation-advice" id="min_lenght" style=""><?= $firstNameLenghtValidate; ?> '+minNameLength+' , '+maxNameLength+'</div>');
				$("#firstname").addClass("validation-failed");
				validate = 0;		
			}
		}
		//lastname length validate
		if(lastNameLength < minNameLength || lastNameLength > maxNameLength){
			if(!$("#lastname").hasClass("validation-failed")){
				//alert(111);
				$("#lastname").parent().append('<div class="validation-advice" id="min_lenght" style=""><?= $lastNameLenghtValidate; ?> '+minNameLength+' , '+maxNameLength+'</div>');
				$("#lastname").addClass("validation-failed");
				validate = 0;		
			}
		}
		//password length validate
		if(passwordLength < minPassLength || passwordLength > maxPassLength){
			if(!$("#password").hasClass("validation-failed")){
				//alert(111);
				$("#password").parent().append('<div class="validation-advice" id="min_lenght" style=""><?= $passwordLenghtValidate; ?> </div>');
				$("#password").addClass("validation-failed");
				validate = 0;		
			}
		}
		//password validate
		if(confirmation != password){
			if(!$("#confirmation").hasClass("validation-failed")){
				//alert(111);
				$("#confirmation").parent().append('<div class="validation-advice" id="min_lenght" style=""><?= $passwordMatchValidate; ?></div>');
				$("#confirmation").addClass("validation-failed");
				validate = 0;		
			}
		}
		
		
		if(validate){
		//	alert("validate success");
			$(this).addClass("dataUp");
		}
	});
});
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['customer_account_register'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>




