<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php
use fecshop\app\apphtml5\helper\Format;
?>
<?php  $shippings = $parentThis['shippings'];   ?>
<div class="onestepcheckout-shipping-method">
	<p class="onestepcheckout-numbers onestepcheckout-numbers-2"><?= Yii::$service->page->translate->__('Shipping Method') ?></p>
	<div class="onestepcheckout-shipping-method-block">    
		<dl class="shipment-methods">
			<?php if(!empty($shippings) &&  is_array($shippings)): ?>
			<?php 	foreach($shippings as $shipping): ?>
			
			<div class="shippingmethods">
				<div class="flatrate"><?= Yii::$service->page->translate->__($shipping['label']) ?></div>
				<div>
					<input data-role="none" <?= $shipping['checked'] ? 'checked="checked"' : '' ?> type="radio" id="s_method_flatrate_flatrate<?= $shipping['shipping_i'] ?>" value="<?= $shipping['method'] ?>" class="validate-one-required-by-name" name="shipping_method">
					<label for="s_method_flatrate_flatrate<?= $shipping['shipping_i'] ?>"><?= $shipping['name'] ?>
						<strong>                 
							<span class="price"><?= $shipping['symbol'] ?><?= Format::price($shipping['cost']); ?></span>
						</strong>
					</label>
				</div>
			</div>
			<?php 	endforeach; ?>
			<?php endif; ?>
		</dl>
	</div>
</div>