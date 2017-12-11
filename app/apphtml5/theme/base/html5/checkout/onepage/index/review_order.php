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
<?php  $cart_info = $parentThis['cart_info'];   ?>
<?php  $currency_info = $parentThis['currency_info'];   ?>
<?php  if(is_array( $cart_info) && !empty( $cart_info)): ?>
<?php  	$products = $cart_info['products']  ?>
<p class="onestepcheckout-numbers onestepcheckout-numbers-4"><?= Yii::$service->page->translate->__('Review your order') ?></p>
<div class="onestepcheckout-summary">
	<table class="onestepcheckout-summary">
		<thead>
			<tr>
				<th class="image"></th>
				<th class="name"><?= Yii::$service->page->translate->__('Name') ?></th>
				<th class="qty"><?= Yii::$service->page->translate->__('Qty') ?></th>
				<th class="total"><?= Yii::$service->page->translate->__('Subtotal') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($products as $product):  ?>
			<tr>
				<td class='image'>
					<a href="<?= $product['url'] ?>" title="<?= $product['name'] ?>" class="product-image">
						<img src="<?= Yii::$service->product->image->getResize($product['image'],[100,100],false) ?>" alt="2121" >
					</a>
					
				</td>
				
				<td class="name">
					<h2 class="product-name">
						<a href="<?= $product['url'] ?>" title="<?= $product['name'] ?>" class="product-image">
							<?= $product['name'] ?>
						</a>
					</h2>
					<?php  if(is_array($product['custom_option_info'])):  ?>
					<ul>
						<?php foreach($product['custom_option_info'] as $label => $val):  ?>
							
							<li><?= Yii::$service->page->translate->__(ucwords($label).':') ?><?= Yii::$service->page->translate->__($val) ?> </li>
							
						<?php endforeach;  ?>
					</ul>
					<?php endif;  ?>
				</td>
				<td class="qty"><?= $product['qty']; ?></td>
				<td class="total"><span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($product['product_row_price']); ?></span></td>
			</tr>
			<?php  endforeach; ?>			
		</tbody>
	</table>
	<table class="onestepcheckout-totals">
		<tbody>
			<tr>
				<td class="totals"><?= Yii::$service->page->translate->__('Subtotal') ?></td>
				<td class="value">
					<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['product_total']); ?></span>       
				</td>
			</tr>
			<tr>
				<td class="totals"><?= Yii::$service->page->translate->__('Shipping Cost') ?></td>
				<td class="value">
					<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['shipping_cost']); ?></span> 
				</td>
			</tr>
			<tr>
				<td class="totals"><?= Yii::$service->page->translate->__('Discount') ?></td>
				<td class="value">
					<span class="price">-<?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['coupon_cost']); ?></span> 
				</td>
			</tr>
			<tr class="grand-total">
				<td class="totals"><?= Yii::$service->page->translate->__('Grand Total') ?></td>
				<td class="value">
					<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['grand_total']) ?></span>   
				</td>
			</tr>						
        </tbody>
	</table>
</div>

<?php  endif; ?>