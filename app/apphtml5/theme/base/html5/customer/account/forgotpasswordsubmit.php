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
		<h1 class='title'><?= Yii::$service->page->translate->__('Forget Password Send Success'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>	

<div class="customer-forgot-success ">
<?php if(!empty($identity)):  ?>
	<div>
		<?= Yii::$service->page->translate->__('We\'ve sent a message to the email address'); ?> <?=  $identity['email'] ?>
		<?= Yii::$service->page->translate->__('Please follow the instructions provided in the message to reset your password.'); ?>
	</div>
	<div>
		<p><?= Yii::$service->page->translate->__('Didn\'t receive the mail from us?'); ?> <a external href="<?= $forgotPasswordUrl ?>"><?= Yii::$service->page->translate->__('click here to retry'); ?></a></p>

		<p><?= Yii::$service->page->translate->__('Check your bulk or junk email folder.'); ?></p>
		<?php
			$param = ['logUrlB' => '<a external href="'. $contactUrl.' ">','logUrlE' => '</a> '];
		?>
		<p><?= Yii::$service->page->translate->__('Confirm your identity to reset password ,If you still can\'t find it, click {logUrlB} support center {logUrlE} for help',$param); ?></p>
	</div>
<?php else:  ?>
	<div>
		<?php
			$param = ['logUrlB' => '<a external href="'. $forgotPasswordUrl.' ">','logUrlE' => '</a> '];
		?>
		<?= Yii::$service->page->translate->__('Email address do not exist, please {logUrlB} click here {logUrlE} to re-enter!',$param); ?> 
	</div>
	<div>
<?php  endif; ?>
</div>