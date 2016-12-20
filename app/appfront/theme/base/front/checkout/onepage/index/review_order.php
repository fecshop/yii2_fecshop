<?php
use fecshop\app\appfront\helper\Format;
?>
<?php  $cart_info = $parentThis['cart_info'];   ?>
<?php  $currency_info = $parentThis['currency_info'];   ?>
<?php  if(is_array( $cart_info) && !empty( $cart_info)){ ?>
<?php  	$products = $cart_info['products']  ?>
<p class="onestepcheckout-numbers onestepcheckout-numbers-4">Review your order</p>
<div class="onestepcheckout-summary">
	<table class="onestepcheckout-summary">
		<thead>
			<tr>
				<th class="image"></th>
				<th class="name">name</th>
				<th class="qty">Qty</th>
				<th class="total">Subtotal</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($products as $product){  ?>
			<tr>
				<td class='image'>
					<a href="<?= $product['url'] ?>" title="<?= $product['name'] ?>" class="product-image">
						<img src="<?= Yii::$service->product->image->getResize($product['image'],[100,100],false) ?>" alt="2121" width="75" height="75">
					</a>
				</td>
				
				<td class="name">
					<a href="<?= $product['url'] ?>" title="<?= $product['name'] ?>" class="product-image">
						<?= $product['name'] ?>
					</a>
				</td>
				<td class="qty"><?= $product['qty']; ?></td>
				<td class="total"><span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($product['product_row_price']); ?></span></td>
			</tr>
			<?php  } ?>			
		</tbody>
	</table>

	<table class="onestepcheckout-totals">
		<tbody>
			<tr>
				<td class="title">Subtotal</td>
				<td class="value">
					<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['product_total']); ?></span>       
				</td>
			</tr>
			<tr>
				<td class="title">Shipping</td>
				<td class="value">
					<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['shipping_cost']); ?></span> 
				</td>
			</tr>
			<tr>
				<td class="title">Discount</td>
				<td class="value">
					<span class="price">-<?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['coupon_cost']); ?></span> 
				</td>
			</tr>
			<tr class="grand-total">
				<td class="title">Grand total</td>
				<td class="value">
					<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['grand_total']) ?></span>   
				</td>
			</tr>						</tbody>
	</table>
</div>
<div class="onestepcheckout-place-order">
	<span><img src="http://www.intosmile.com/skin/default/images/scroll/waitPage.gif"></span>
	<a class="large orange onestepcheckout-button" href="javascript:void(0)" id="onestepcheckout-place-order">Place order now</a>
</div>
<?php  } ?>