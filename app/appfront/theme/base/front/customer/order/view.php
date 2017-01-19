<?php
use fecshop\app\appfront\helper\Format;
?>
<div class="main container two-columns-left">
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:19px 0 0">
				<div class="my_account_order">
					<div class="page-title title-buttons">
						<h1>Order #<?=  $increment_id ?>				<?=  $order_status ?>				</h1>
					</div>
					<p class="order-date"><?=  date('Y-m-d H:i:s',$created_at); ?></p>
					<div class="col2-set order-info-box">
						<div class="col-1">
							<div class="box">
							<div class="box-title">
								<h2>Shipping Address</h2>
							</div>
							<div class="box-content">
								<address><?=  $customer_firstname ?> <?=  $customer_lastname ?><br>
								<?=  $customer_address_street1 ?><br><?=  $customer_address_street2 ?><br><?=  $customer_address_city ?>,<?=  $customer_address_state ?>,<?=  $customer_address_country ?><br>
								T:<?=  $customer_telephone ?>

								</address>
							</div>
						</div>				</div>
						<div class="col-2">
							<div class="box">
								<div class="box-title">
									<h2>Shipping Method</h2>
								</div>
								<div class="box-content">
								<?=  $shipping_method ?>             
								</div>
							</div>				</div>
						<div class="col-2">
							<div class="box box-payment">
								<div class="box-title">
									<h2>Payment Method</h2>
								</div>
								<div class="box-content">
									<p><strong><?=  $payment_method ?></strong></p>
								</div>
							</div>				</div>
					</div>
					
					<div class="order-items order-details">
						<h2 class="table-caption">Items Ordered</h2>

						<table summary="Items Ordered" id="my-orders-table" class="data-table">
							<colgroup><col>
							<col width="1">
							<col width="1">
							<col width="1">
							<col width="1">
							</colgroup>
							<thead>
								<tr class="first last">
									<th>Product Name</th>
									<th>Product Image</th>
									<th>Sku</th>
									<th class="a-right">Price</th>
									<th class="a-center">Qty</th>
									<th class="a-right">Subtotal</th>
								</tr>
							</thead>
							<tfoot>
								<tr class="subtotal first">
									<td class="a-right" colspan="5">Subtotal</td>
									<td class="last a-right"><span class="price"><?= $currency_symbol ?><?=  Format::price($subtotal); ?></span></td>
								</tr>
								<tr class="shipping">
									<td class="a-right" colspan="5">Shipping &amp; Handling</td>
									<td class="last a-right">
										<span class="price"><?= $currency_symbol ?><?=  Format::price($shipping_total); ?></span>    
									</td>
								</tr>
								<tr class="discount">
									<td class="a-right" colspan="5">Discount</td>
									<td class="last a-right">
										<span class="price"><?= $currency_symbol ?><?=  Format::price($subtotal_with_discount); ?></span>    
									</td>
								</tr>
								<tr class="grand_total last">
									<td class="a-right" colspan="5">
										<strong>Grand Total</strong>
									</td>
									<td class="last a-right">
										<strong><span class="price"><?= $currency_symbol ?><?=  Format::price($grand_total); ?></span></strong>
									</td>
								</tr>
							</tfoot>
							<tbody class="odd">
								<?php if(is_array($products) && !empty($products)){  ?>
									<?php foreach($products as $product){ ?>
									<tr id="order-item-row" class="border first">	
										<td>
											<a href="<?=  Yii::$service->url->getUrl($product['redirect_url']) ; ?>">
												<h3 class="product-name">
													<?= $product['name'] ?>
												</h3>
											</a>
											<dl class="item-options">
												
											</dl>
											
										</td>
										<td>
											<a href="<?=  Yii::$service->url->getUrl($product['redirect_url']) ; ?>">
												<img src="<?= Yii::$service->product->image->getResize($product['image'],[100,100],false) ?>" alt="<?= $product['name'] ?>" width="75" height="75">
											</a>
										</td>
										<td><?= $product['sku'] ?></td>
										<td class="a-right">
											<span class="price-excl-tax">
												<span class="cart-price">
													<span class="price"><?= $currency_symbol ?><?= Format::price($product['price']); ?></span>                    
												</span>
											</span>
											<br>
										</td>
										<td class="a-right">
											<span class="nobr" ><strong><?= $product['qty'] ?></strong><br>
											</span>
										</td>
										<td class="a-right last">
											<span class="price-excl-tax">
												<span class="cart-price">
													<span class="price"><?= $currency_symbol ?><?= Format::price($product['row_total']); ?></span>                    
												</span>
											</span>
											<br>
										</td>
									</tr>
									<?php } ?>
								<?php } ?>
								</tbody>								   
						</table>

						<div class="buttons-set">
							<p class="back-link"><a href="<?= Yii::$service->url->getUrl('customer/order/index'); ?>"><small>? </small>Back to My Orders</a></p>
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