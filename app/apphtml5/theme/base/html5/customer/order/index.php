<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="account-ds">
	<div class="bar bar-nav account-top-m">
		<a external class="button button-link button-nav pull-left" href="<?= Yii::$service->url->getUrl('customer/account/index'); ?>">
			<span class="icon icon-left"></span>
		</a>
		<h1 class='title'><?= Yii::$service->page->translate->__('Customer Order'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>

<div class="order_list">

	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				
				<table id="my-orders-table" class="edit_order">
					<thead>
						<tr class="first last">
							<th><?= Yii::$service->page->translate->__('Order #');?> </th>
							<th><?= Yii::$service->page->translate->__('Date');?></th>
							<th><?= Yii::$service->page->translate->__('Operation');?></th>
						</tr>
					</thead>
					<tbody>
					<?php  if(is_array($order_list) && !empty($order_list)):  ?>
						<?php foreach($order_list as $order): 
							$currencyCode = $order['order_currency_code'];
							$symbol = Yii::$service->page->currency->getSymbol($currencyCode);
							
						?>
							<tr class="first odd">
								<td>
									<b><?= $order['increment_id'] ?></b><br/>
									<span class="order-status <?= Yii::$service->page->translate->__($order['order_status']); ?>"><?= Yii::$service->page->translate->__($order['order_status']); ?></span>
								</td>
								<td><span class="nobr"><?= date('Y-m-d H:i:s',$order['created_at']) ?></span></td>
								<td class="a-center last">
									<span class="nobr"><a external href="<?=  Yii::$service->url->getUrl('customer/order/view',['order_id' => $order['order_id']]);?>"><?= Yii::$service->page->translate->__('View Order');?></a>
									<span class="separator">|</span> <a external class="link-reorder" href="<?=  Yii::$service->url->getUrl('customer/order/reorder',['order_id' => $order['order_id']]);?>"><?= Yii::$service->page->translate->__('Reorder');?></a>
									</span>
								</td>
							</tr>
						
						<?php endforeach; ?>
					<?php endif; ?>
						
					</tbody>
				</table>
				<?php if($pageToolBar): ?>
					<div class="pageToolbar customer_order">
						<label class=""><?= Yii::$service->page->translate->__('Page:');?></label>
                        <?= $pageToolBar ?>
                        <div class="clear"></div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<div class="clear"></div>
</div>
	