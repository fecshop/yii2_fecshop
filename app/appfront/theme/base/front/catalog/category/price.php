<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="c_price">
	<?php if(isset($special_price) && !empty($special_price)): ?>
    <?php $sp = 'sp'; ?>
    <?php endif; ?>
	<div class="price <?= $sp ?>">
		<?= $price['symbol'].Yii::$service->helper->format->numberFormat($price['value']) ?>
	</div>
	<?php if(isset($special_price) && !empty($special_price)):  ?>
	<div class="special_price">
		<?= $special_price['symbol'].Yii::$service->helper->format->numberFormat($special_price['value']) ?>
	</div>
	<div class="clear"></div>
	<?php endif;  ?>
</div>