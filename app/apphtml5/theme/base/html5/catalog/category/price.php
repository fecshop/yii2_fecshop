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
	<?php if(isset($special_price) && !empty($special_price)){ $sp = 'sp'; }  ?>
	
	<div class="price <?= $sp ?>">
		<?= $price['symbol'].$price['value'] ?>
	</div>
	<?php if(isset($special_price) && !empty($special_price)):  ?>
	<div class="special_price">
		<?= $special_price['symbol'].$special_price['value'] ?>
	</div>
	<div class="clear"></div>
	<?php endif;  ?>
</div>