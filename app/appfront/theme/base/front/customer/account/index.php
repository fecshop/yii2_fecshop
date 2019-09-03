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
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				<div class="page-title">
					<h2><?= Yii::$service->page->translate->__('My Dashboard'); ?></h2>
				</div>
				<div class="welcome-msg">
					<p class="hello"><strong><?= Yii::$service->page->translate->__('Hello'); ?>,  !</strong></p>
					<p><?= Yii::$service->page->translate->__('From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.'); ?></p>
				</div>
				<div class="box-account box-info">
					<div class="col2-set">
						<div class="col-1">
							<div class="box">
								<div class="box-title">
									<h3><?= Yii::$service->page->translate->__('Contact Information'); ?></h3>
									<a href="<?= $accountEditUrl ?>"><?= Yii::$service->page->translate->__('Edit'); ?></a>
								</div>
								<div class="box-content">
									<div>							
										<span style="margin:0 10px;"><?= $email ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col2-set addressbook">
						<div class="col2-set">
							<div class="col-1">
								<div class="box">
									<div class="box-title">
										<h3><?= Yii::$service->page->translate->__('My Address Book'); ?></h3>	
									</div>
									<div class="box-content">
										<p><?= Yii::$service->page->translate->__('You Can Manager Your Address'); ?>. </p>
										<a href="<?= $accountAddressUrl ?>"><?= Yii::$service->page->translate->__('Manager Addresses'); ?></a>
									</div>
								</div>
							</div>
							<div class="col-2">
								<div class="box">
									<div class="box-title">
										<h3><?= Yii::$service->page->translate->__('My Order'); ?></h3>
									</div>
									<div class="box-content">
										<p><?= Yii::$service->page->translate->__('You Can View Your Order'); ?>. </p>
										<a href="<?= $accountOrderUrl ?>"><?= Yii::$service->page->translate->__('View'); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-left ">
		<?php
			$leftMenu = [
				'class' => 'fecshop\app\appfront\modules\Customer\block\LeftMenu',
				'view'	=> 'customer/leftmenu.php'
			];
		?>
		<?= Yii::$service->page->widget->render($leftMenu,$this); ?>
	</div>
	<div class="clear"></div>
</div>
	