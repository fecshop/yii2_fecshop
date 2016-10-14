<div class="main container two-columns-left">
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:19px 0 0">
				<div class="my_account_order">
					<div class="page-title title-buttons">
						<h1>Order #intosmileEn000015519				pending				</h1>
					</div>
					<p class="order-date">2016-10-13 17:24:42</p>
					<div class="col2-set order-info-box">
						<div class="col-1">
							<div class="box">
							<div class="box-title">
								<h2>Shipping Address</h2>
							</div>
							<div class="box-content">
								<address>firstname lastname<br>
								street2<br>street1<br>city,state,TC,<br>
								T:32423432432

								</address>
							</div>
						</div>				</div>
						<div class="col-2">
							<div class="box">
								<div class="box-title">
									<h2>Shipping Method</h2>
								</div>
								<div class="box-content">
								Free shipping( 7-20 work days) - HKBRAM             
								</div>
							</div>				</div>
						<div class="col-2">
							<div class="box box-payment">
								<div class="box-title">
									<h2>Payment Method</h2>
								</div>
								<div class="box-content">
									<p><strong>PayPal Website Payments Standard</strong></p>
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
									<th>Sku</th>
									<th class="a-right">Price</th>
									<th class="a-center">Qty</th>
									<th class="a-right">Subtotal</th>
								</tr>
							</thead>
							<tfoot>
								<tr class="subtotal first">
									<td class="a-right" colspan="4">Subtotal</td>
									<td class="last a-right"><span class="price">$7.99</span></td>
								</tr>
								<tr class="shipping">
									<td class="a-right" colspan="4">Shipping &amp; Handling</td>
									<td class="last a-right">
										<span class="price">$0.00</span>    
									</td>
								</tr>
								<tr class="discount">
									<td class="a-right" colspan="4">Discount</td>
									<td class="last a-right">
										<span class="price">$0.00</span>    
									</td>
								</tr>
								<tr class="grand_total last">
									<td class="a-right" colspan="4">
										<strong>Grand Total</strong>
									</td>
									<td class="last a-right">
										<strong><span class="price">$7.99</span></strong>
									</td>
								</tr>
							</tfoot>
							<tbody class="odd"><tr id="order-item-row-145548" class="border first">
									<td>
										<h3 class="product-name">Creative Crystal Skull Shot Glass Cup Novetly </h3>
										<dl class="item-options">
											
										</dl>
										
									</td>
									<td>grdx01001</td>
									<td class="a-right">
										<span class="price-excl-tax">
											<span class="cart-price">
												<span class="price">$7.99</span>                    
											</span>
										</span>
										<br>
									</td>
									<td class="a-right">
										<span class="nobr">Ordered:<strong>1</strong><br>
										</span>
									</td>
									<td class="a-right last">
										<span class="price-excl-tax">
											<span class="cart-price">
												<span class="price">$7.99</span>                    
											</span>
										</span>
										<br>
									</td>
								</tr></tbody>								   
						</table>

						<div class="buttons-set">
							<p class="back-link"><a href="http://www.intosmile.com/customer/order/index"><small>? </small>Back to My Orders</a></p>
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