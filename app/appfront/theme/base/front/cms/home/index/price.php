<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<p class="proPrice">
    <?php $price['value'] = Yii::$service->helper->format->numberFormat($price['value'])  ?>
	<?php if(isset($special_price) && !empty($special_price)):  ?>
        <?php $special_price['value'] = Yii::$service->helper->format->numberFormat($special_price['value'])  ?>
		<span class="bizhong"><?= $special_price['code'] ?></span><span orgp="<?= $special_price['value'] ?>" class="my_shop_price f14"><span class="icon"><?= $special_price['symbol'] ?></span><?= $special_price['value'] ?></span>
		<span class="bizhong"><?= $price['code'] ?></span><del orgp="<?= $price['value'] ?>" class="my_shop_price"><span class="icon"><?= $price['symbol'] ?></span><?= $price['value'] ?></del>
	
	<?php else: ?>
		<span class="bizhong"><?= $price['code'] ?></span><span orgp="<?= $price['value'] ?>" class="my_shop_price f14"><span class="icon"><?= $price['symbol'] ?></span><?= $price['value'] ?></span>
		
	<?php endif; ?>
</p>