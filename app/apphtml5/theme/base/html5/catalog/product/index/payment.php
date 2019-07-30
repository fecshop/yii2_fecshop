<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="scroll_left">
	<a href=""><?= Yii::$service->page->translate->__('Payment Methods:'); ?></a>
</div>
<p><?= Yii::$service->page->translate->__('FECSHOP.com accepts PayPal, Credit Card, Western Union and Wire Transfer as secure payment methods:'); ?></p>

<p><?= Yii::$service->page->translate->__('Global:'); ?></p>

<p><?= Yii::$service->page->translate->__('1. PayPal'); ?></p>

<p><img alt="" height="96" src="<?= Yii::$service->image->getImgUrl('appfront/images/paypal48.jpg') ?>" width="300"></p>

<p><?= Yii::$service->page->translate->__('1) Login To Your Account or use Credit Card Express.'); ?><br>
<?= Yii::$service->page->translate->__('2) Enter your Card Details, the order will be shipped to your PayPal address. And click "Submit".'); ?><br>
<?= Yii::$service->page->translate->__('3) Your Payment will be processed and a receipt will be sent to your email inbox.'); ?></p>

<p><?= Yii::$service->page->translate->__('2. Credit Card'); ?></p>

<p><img alt="" height="40" src="<?= Yii::$service->image->getImgUrl('appfront/images/creditcard48.jpg') ?>" width="554"></p>

<p>	<?= Yii::$service->page->translate->__('1) Choose your shipping address OR create a new one.'); ?><br>
	<?= Yii::$service->page->translate->__('2) Enter your Card Details and click "Submit".'); ?><br>
	<?= Yii::$service->page->translate->__('3) Your Payment will be processed and a receipt will be sent to your email inbox.'); ?></p>
