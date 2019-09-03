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
<div class="account-ds">
	<div class="bar bar-nav account-top-m">
		<a external class="button button-link button-nav pull-left" href="<?= Yii::$service->url->getUrl('customer/order/index'); ?>">
			<span class="icon icon-left"></span>
		</a>
		<h1 class='title'><?= Yii::$service->page->translate->__('View Order'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>


<div class="account-container">
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:2px 0 0">
				<div class="my_account_order">
					<table class="page-title title-buttons">
						<tbody>
							<tr><td><?= Yii::$service->page->translate->__('Order#');?> :</td><td><?=  $increment_id ?>	</td></tr>		
							<tr><td><?= Yii::$service->page->translate->__('Order Status');?>:</td><td><?= Yii::$service->page->translate->__($order_status);?></td></tr>		
							<tr><td><?= Yii::$service->page->translate->__('Order Date');?>:</td><td><?=  date('Y-m-d H:i:s',$created_at); ?></td></tr>								
						</tbody>
					</table>
					<div class="col2-set order-info-box">
						<div class="col-1">
							<div class="box">
							<div class="box-title">
								<h5><?= Yii::$service->page->translate->__('Shipping Address');?>:</h5>
							</div>
							<div class="box-content">
								<table>
									<tbody>
										<tr><td><?=  $customer_firstname ?> <?=  $customer_lastname ?></td></tr>	
										<tr><td><?=  $customer_address_street1 ?><br><?=  $customer_address_street2 ?></td></tr>	
										<tr><td><?=  $customer_address_city ?>,<?=  $customer_address_state_name ?>,<?=  $customer_address_country_name ?></td></tr>	
										<tr><td><?= Yii::$service->page->translate->__('T:');?><?=  $customer_telephone ?></td></tr>	

									</tbody>
								</table>
							</div>
						</div>				</div>
						<div class="col-2">
							<div class="box">
								<div class="box-title">
									<h5><?= Yii::$service->page->translate->__('Shipping Method');?>:</h5>
								</div>
								<div class="box-content">
									<table>
										<tbody>
											<tr><td><?=  $shipping_method ?></td></tr>  
										</tbody>
									</table>
								</div>
							</div>				
                        </div>
                        <div class="col-2">
							<div class="box">
								<div class="box-title">
									<h5><?= Yii::$service->page->translate->__('Tracking Number');?>:</h5>
								</div>
								<div class="box-content">
									<table>
										<tbody>
											<tr><td><?=  $tracking_number ? $tracking_number : Yii::$service->page->translate->__('null') ?></td></tr>  
										</tbody>
									</table>
								</div>
							</div>				
                        </div>
						<div class="col-2">
							<div class="box box-payment">
								<div class="box-title">
									<h5><?= Yii::$service->page->translate->__('Payment Method');?>:</h5>
								</div>
								<div class="box-content">
									<table>
										<tbody>
											<tr><td><?=  $payment_method ?></td></tr>  
										</tbody>
									</table>
								</div>
							</div>			
						</div>
					</div>
					
					<div class="order-items order-details box-title">
						<h5 class="table-caption"><?= Yii::$service->page->translate->__('Items Ordered');?>:</h5>

						<table summary="Items Ordered" id="my-orders-table" class="data-table">
							<colgroup>
                                <col>
                                <col width="1">
                                <col width="1">
                                <col width="1">
                                <col width="1">
 							</colgroup>
							<thead>
								<tr class="first last">
									<th><?= Yii::$service->page->translate->__('Product Image');?></th>
									<th><?= Yii::$service->page->translate->__('Product Info');?></th>
									<th class="a-center"><?= Yii::$service->page->translate->__('Qty');?></th>
                                    <th class="a-center"><?= Yii::$service->page->translate->__('Review');?></th>
									<th class="a-right"><?= Yii::$service->page->translate->__('Subtotal');?></th>
								</tr>
							</thead>
							<tfoot>
								<tr class="subtotal first">
									<td class="a-right" colspan="4"><?= Yii::$service->page->translate->__('Subtotal');?></td>
									<td class="last a-center"><span class="price"><?= $currency_symbol ?><?=  Format::price($subtotal); ?></span></td>
								</tr>
								<tr class="shipping">
									<td class="a-right" colspan="4"><?= Yii::$service->page->translate->__('Shipping Cost');?></td>
									<td class="last a-center">
										<span class="price"><?= $currency_symbol ?><?=  Format::price($shipping_total); ?></span>    
									</td>
								</tr>
								<tr class="discount">
									<td class="a-right" colspan="4"><?= Yii::$service->page->translate->__('Discount');?></td>
									<td class="last a-center">
										<span class="price"><?= $currency_symbol ?><?=  Format::price($subtotal_with_discount); ?></span>    
									</td>
								</tr>
								<tr class="grand_total last">
									<td class="a-center" colspan="4">
										<strong><?= Yii::$service->page->translate->__('Grand Total');?></strong>
									</td>
									<td class="last a-right">
										<strong><span class="price"><?= $currency_symbol ?><?=  Format::price($grand_total); ?></span></strong>
									</td>
								</tr>
							</tfoot>
							<tbody class="odd">
								<?php if(is_array($products) && !empty($products)):  ?>
									<?php foreach($products as $product): ?>
									<tr id="order-item-row" class="border first">	
										<td>
											<a href="<?=  Yii::$service->url->getUrl($product['redirect_url']) ; ?>">
												<img src="<?= Yii::$service->product->image->getResize($product['image'],[100,100],false) ?>" alt="<?= $product['name'] ?>" width="75" height="75">
											</a>
										</td>
										<td>
											<div><?= Yii::$service->page->translate->__('sku')?>:<?= $product['sku'] ?></div>
											<?php  if(is_array($product['custom_option_info'])):  ?>
											
												<?php foreach($product['custom_option_info'] as $label => $val):  ?>
													<div>
														<?= Yii::$service->page->translate->__($label.':') ?><?= Yii::$service->page->translate->__($val) ?>
													</div>
												<?php endforeach;  ?>
											
											<?php endif;  ?>
											
											<dl class="item-options">
											</dl>
										</td>
										
										<td class="a-center">
											<span class="nobr" ><strong><?= $product['qty'] ?></strong><br>
											</span>
										</td>
                                        <td class="a-center">
											<a style="font-size:1em" href="<?= Yii::$service->url->getUrl('/catalog/reviewproduct/add',['_id' => $product['product_id']])  ?>">
                                                <span class="" >
                                                    Review 
                                                    <br>
                                                </span>
                                            </a>
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
									<?php endforeach; ?>
								<?php endif; ?>
								</tbody>								   
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="clear"></div>
</div>