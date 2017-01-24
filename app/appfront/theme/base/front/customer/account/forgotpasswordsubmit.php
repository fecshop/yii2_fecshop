<div class="main container one-column">
<?php if(!empty($identity)){  ?>
	<div>
		We've sent a message to the email address <?=  $identity['email'] ?>
		you have on file with us.
		Please follow the instructions provided in the message to reset your password.
	</div>
	<div>
		<p>Didn't receive the mail from us? <a href="<?= $forgotPasswordUrl ?>">click here to retry</a></p>

		<p>Check your bulk or junk email folder.</p>

		<p>If you still can't find it, click <a href="<?= $contactUrl ; ?>">support center</a> for help </p>
	</div>
<?php }else{  ?>
	<div>
		Email address do not exist, please <a href="<?= $forgotPasswordUrl ?>">click here</a> to re-enter!
	</div>
	<div>
		



<?php  } ?>
</div>