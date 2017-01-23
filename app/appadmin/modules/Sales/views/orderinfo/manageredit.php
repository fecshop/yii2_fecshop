<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use yii\helpers\Html;
use fec\helpers\CRequest;
use fecadmin\models\AdminRole;
use fecshop\app\appfront\helper\Format;
/** 
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>
<style>
.checker{float:left;}
.dialog .pageContent {background:none;}
.dialog .pageContent .pageFormContent{background:none;}
</style>

<div class="pageContent" style="background:#fff;"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
			
				<input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
				
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000">订单信息</legend>
					<div>
						<p class="edit_p">
							<label>订单号：</label>
							<span><?= $order['increment_id'] ?></span>
						</p>
						
						<p class="edit_p">
							<label>订单状态：</label>
							<span><?= $order['order_status'] ?></span>
						</p>
						
						<p class="edit_p">
							<label>Store：</label>
							<span><?= $order['store'] ?></span>
						</p>
						
						<p class="edit_p">
							<label>订单产品总数：</label>
							<span><?= $order['items_count'] ?></span>
						</p>
						
						<p class="edit_p">
							<label>订单总重量(KG)：</label>
							<span><?= $order['total_weight'] ?></span>
						</p>
						
						
						
						<p class="edit_p">
							<label>订单货币简码：</label>
							<span><?= $order['order_currency_code'] ?></span>
						</p>
						<?php $symbol = Yii::$service->page->currency->getSymbol($order['order_currency_code']);  ?>
						<p class="edit_p">
							<label>与基础货币汇率：</label>
							<span><?= $order['order_to_base_rate'] ?></span>
						</p>
						
						
						<p class="edit_p">
							<label>支付类型：</label>
							<span><?= $order['checkout_method'] ?></span>
						</p>
						<p class="edit_p">
							<label>支付方式：</label>
							<span><?= $order['payment_method'] ?></span>
						</p>
						
						<?php  if($order['remote_ip']){  ?>
						<p class="edit_p">
							<label>IP：</label>
							<span><?= $order['remote_ip'] ?></span>
						</p>
						<?php  }  ?>
						
						<p class="edit_p">
							<label>订单总金额：</label>
							<span><?= $symbol.$order['grand_total'] ?></span>
						</p>
						<p class="edit_p">
							<label>订单产品总额：</label>
							<span><?= $symbol.$order['subtotal'] ?></span>
						</p>
						
						<?php  if($order['subtotal_with_discount']){  ?>
						<p class="edit_p">
							<label>优惠券：</label>
							<span><?= $symbol.$order['coupon_code'] ?></span>
						</p>
						<?php }  ?>
						
						<?php  if($order['subtotal_with_discount']){  ?>
						<p class="edit_p">
							<label>订单折扣：</label>
							<span><?= $symbol.$order['subtotal_with_discount'] ?></span>
						</p>
						<?php  }  ?>
						
						<p class="edit_p">
							<label>订单运费：</label>
							<span><?= $symbol.$order['shipping_total'] ?></span>
						</p>
						
						<?php  if($order['payment_fee'] == 1){  ?>
						<p class="edit_p">
							<label>payment_fee：</label>
							<span><?= $order['payment_fee'] ?></span>
						</p>
						<?php  }  ?>
						
					</div>
				</fieldset>
				
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000">订单用户信息</legend>
					<div>
				
						<p class="edit_p">
							<label>FirstName：</label>
							<span><?= $symbol.$order['customer_firstname'] ?></span>
						</p>
						<p class="edit_p">
							<label>LastName：</label>
							<span><?= $order['customer_lastname'] ?></span>
						</p>
						<?php  if($order['customer_is_guest'] == 1){  ?>
						<p class="edit_p">
							<label>游客下单？</label>
							<span><?= '是' ?></span>
						</p>
						<?php } ?>
						<p class="edit_p">
							<label>Email：</label>
							<span><?= $order['customer_email'] ?></span>
						</p>
						<?php  if($order['customer_id']){  ?>
						<p class="edit_p">
							<label>customer_id：</label>
							<span><?= $order['customer_id'] ?></span>
						</p>
						<?php } ?>
					</div>
				</fieldset>
				
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000">订单货运信息</legend>
					<div>
				
						<p class="edit_p">
							<label>货运方式：</label>
							<span><?= $order['shipping_method'] ?></span>
						</p>
						<p class="edit_p">
							<label>订单运费：</label>
							<span><?= $symbol.$order['shipping_total'] ?></span>
						</p>
						
						<p class="edit_p">
							<label>电话：</label>
							<span><?= $order['customer_telephone'] ?></span>
						</p>
						
						<p class="edit_p">
							<label>国家：</label>
							<span><?= $order['customer_address_country'] ?></span>
						</p>
						
						<p class="edit_p">
							<label>省/市：</label>
							<span><?= $order['customer_address_state'] ?></span>
						</p>
						<p class="edit_p">
							<label>城市：</label>
							<span><?= $order['customer_address_city'] ?></span>
						</p>
						<p class="edit_p">
							<label>邮编：</label>
							<span><?= $order['customer_address_zip'] ?></span>
						</p>
						<p class="edit_p">
							<label>街道1：</label>
							<span><?= $order['customer_address_street1'] ?></span>
						</p>
						<p class="edit_p">
							<label>街道2：</label>
							<span><?= $order['customer_address_street2'] ?></span>
						</p>
						
					</div>
				</fieldset>
				
				
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000">订单产品信息</legend>
					<div>
						<table summary="Items Ordered" id="my-orders-table" class="data-table list" style="">
							<colgroup><col>
							<col width="1">
							<col width="1">
							<col width="1">
							<col width="1">
							</colgroup>
							<thead>
								<tr class="first last">
									<th>产品名称</th>
									<th>图片</th>
									<th>Sku</th>
									<th class="a-right">价格</th>
									<th class="a-center">个数</th>
									<th class="a-right">总金额</th>
								</tr>
							</thead>
							
							<tbody class="odd">
								<?php if(is_array($order['products']) && !empty($order['products'])){  ?>
									<?php foreach($order['products'] as $product){ ?>
									<tr id="order-item-row" class="border first">	
										<td>
											<a href="<?= '#' //Yii::$service->url->getUrl($product['redirect_url']) ; ?>">
												<h3 class="product-name">
													<?= $product['name'] ?>
												</h3>
											</a>
											<?php  if(is_array($product['custom_option_info'])){  ?>
											<ul>
												<?php foreach($product['custom_option_info'] as $label => $val){  ?>
													
													<li><?= $label ?>:<?= $val ?> </li>
													
												<?php }  ?>
											</ul>
											<?php }  ?>
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
													<span class="price"><?= $symbol ?><?= Format::price($product['price']); ?></span>                    
												</span>
											</span>
											<br>
										</td>
										<td class="a-right">
											<span class="nobr" style="text-align:center;width:30px;display:block" ><strong><?= $product['qty'] ?></strong><br>
											</span>
										</td>
										<td class="a-right last">
											<span class="price-excl-tax">
												<span class="cart-price">
													<span class="price"><?= $symbol ?><?= Format::price($product['row_total']); ?></span>                    
												</span>
											</span>
											<br>
										</td>
									</tr>
									<?php } ?>
								<?php } ?>
							</tbody>	

							<tfoot>
								<tr class="subtotal first">
									<td class="a-right" colspan="5">Subtotal</td>
									<td class="last a-right"><span class="price"><?= $symbol ?><?=  Format::price($order['subtotal']); ?></span></td>
								</tr>
								<tr class="shipping">
									<td class="a-right" colspan="5">Shipping &amp; Handling</td>
									<td class="last a-right">
										<span class="price"><?= $symbol ?><?=  Format::price($order['shipping_total']); ?></span>    
									</td>
								</tr>
								<tr class="discount">
									<td class="a-right" colspan="5">Discount</td>
									<td class="last a-right">
										<span class="price"><?= $symbol ?><?=  Format::price($order['subtotal_with_discount']); ?></span>    
									</td>
								</tr>
								<tr class="grand_total last">
									<td class="a-right" colspan="5">
										<strong>Grand Total</strong>
									</td>
									<td class="last a-right">
										<strong><span class="price"><?= $symbol ?><?=  Format::price($order['grand_total']); ?></span></strong>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</fieldset>
				
		</div>
	
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit">保存</button></div></div></li>
			
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	

