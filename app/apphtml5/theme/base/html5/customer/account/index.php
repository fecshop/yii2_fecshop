<div class="account-ds">
	<div class="bar bar-nav account-top-m">
		
		<h1 class='title'><?= Yii::$service->page->translate->__('My Account'); ?></h1>
	</div>
	 <?php
		$leftMenu = [
			'class' => 'fecshop\app\appfront\modules\Customer\block\LeftMenu',
			'view'	=> 'customer/leftmenu.php'
		];
	?>
	<?= Yii::$service->page->widget->render($leftMenu,$this); ?>
</div>