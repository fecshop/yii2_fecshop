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
		<h1 class='title'><?= Yii::$service->page->translate->__('Reset Password Success'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>	
<div class="customer-forgot-success">
	<?php
		$param = ['logUrlB' => '<a external href="'.$loginUrl.'">','logUrlE' => '</a> '];
	?>
	<?= Yii::$service->page->translate->__('Reset you account success, you can {logUrlB} click here {logUrlE} to login .',$param); ?>

</div>