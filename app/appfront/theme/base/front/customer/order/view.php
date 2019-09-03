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
use fecshop\app\appfront\helper\Format;
?>
<div class="main container two-columns-left">
    <?= Yii::$service->page->widget->render('base/breadcrumbs',$this); ?>
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:19px 0 0">
				<div class="my_account_order">
					<div class="page-title title-buttons">
						<h1><?= Yii::$service->page->translate->__('Order #');?><?=  $increment_id ?>				<?= Yii::$service->page->translate->__($order_status);?>				</h1>
					</div>
					<p class="order-date"><?=  date('Y-m-d H:i:s',$created_at); ?></p>
					<div class="col2-set order-info-box">
						<div class="col-1">
							<div class="box">
                                <div class="box-title">
                                    <h2><?= Yii::$service->page->translate->__('Shipping Address');?></h2>
                                </div>
                                <div class="box-content">
                                    <address><?=  $customer_firstname ?> <?=  $customer_lastname ?><br>
                                    <?=  $customer_address_street1 ?><br><?=  $customer_address_street2 ?><br><?=  $customer_address_city ?>,<?=  $customer_address_state_name ?>,<?=  $customer_address_country_name ?><br>
                                    <?= Yii::$service->page->translate->__('T:');?><?=  $customer_telephone ?>
                                    <br>
                                    <?= Yii::$service->page->translate->__('Tracking Number');?>: 
                                        <span style="color:#777;">
                                            <?=  $tracking_number ? $tracking_number : Yii::$service->page->translate->__('null') ?>
                                        </span>
                                    </address>
                                </div>
                            </div>				
                        </div>
						<div class="col-2">
							<div class="box">
								<div class="box-title">
									<h2><?= Yii::$service->page->translate->__('Shipping Method');?></h2>
								</div>
								<div class="box-content">
								<?=  $shipping_method ?>             
								</div>
							</div>				</div>
						<div class="col-2">
							<div class="box box-payment">
								<div class="box-title">
									<h2><?= Yii::$service->page->translate->__('Payment Method');?></h2>
								</div>
								<div class="box-content">
									<p><strong><?=  $payment_method ?></strong></p>
								</div>
							</div>				
                        </div>
					</div>
					
					<div class="order-items order-details">
						<h2 class="table-caption"><?= Yii::$service->page->translate->__('Items Ordered');?></h2>

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
									<th><?= Yii::$service->page->translate->__('Product Name');?></th>
									<th><?= Yii::$service->page->translate->__('Product Image');?></th>
									<th><?= Yii::$service->page->translate->__('Sku');?></th>
									<th class="a-right"><?= Yii::$service->page->translate->__('Price');?></th>
									<th class="a-center"><?= Yii::$service->page->translate->__('Qty');?></th>
                                    <th class="a-center"><?= Yii::$service->page->translate->__('Review');?></th>
									<th class="a-center"><?= Yii::$service->page->translate->__('Subtotal');?></th>
								</tr>
							</thead>
							<tfoot>
								<tr class="subtotal first">
									<td class="a-right" colspan="6"><?= Yii::$service->page->translate->__('Subtotal');?></td>
									<td class="last a-center"><span class="price"><?= $currency_symbol ?><?=  Format::price($subtotal); ?></span></td>
								</tr>
								<tr class="shipping">
									<td class="a-right" colspan="6"><?= Yii::$service->page->translate->__('Shipping Cost');?></td>
									<td class="last a-center">
										<span class="price"><?= $currency_symbol ?><?=  Format::price($shipping_total); ?></span>    
									</td>
								</tr>
								<tr class="discount">
									<td class="a-right" colspan="6"><?= Yii::$service->page->translate->__('Discount');?></td>
									<td class="last a-center">
										<span class="price"><?= $currency_symbol ?><?=  Format::price($subtotal_with_discount); ?></span>    
									</td>
								</tr>
								<tr class="grand_total last">
									<td class="a-right" colspan="6">
										<strong><?= Yii::$service->page->translate->__('Grand Total');?></strong>
									</td>
									<td class="last a-center">
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
												<h3 class="product-name">
													<?= $product['name'] ?>
												</h3>
											</a>
											<?php  if(is_array($product['custom_option_info'])):  ?>
											<ul>
												<?php foreach($product['custom_option_info'] as $label => $val):  ?>
													
													<li><?= Yii::$service->page->translate->__($label.':') ?><?= Yii::$service->page->translate->__($val) ?> </li>
													
												<?php endforeach;  ?>
											</ul>
											<?php endif;  ?>
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
										<td class="a-center">
											<span class="nobr" >
                                                <strong><?= $product['qty'] ?></strong>
                                                <br>
											</span>
										</td>
                                        <td class="a-center">
											<a href="<?= Yii::$service->url->getUrl('/catalog/reviewproduct/add',['_id' => $product['product_id']])  ?>">
                                                <span class="" >
                                                    Review 
                                                    <br>
                                                </span>
                                            </a>
										</td>
										<td class="a-center last">
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
						<br/>
						<div class="buttons-set">
							<p class="back-link"><a href="<?= Yii::$service->url->getUrl('customer/order/index'); ?>"><small> « </small><?= Yii::$service->page->translate->__('Back to My Orders');?></a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-left ">
		<?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
	</div>
	<div class="clear"></div>
</div>