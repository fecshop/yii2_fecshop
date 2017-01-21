<div class="main container two-columns-left">
<?= Yii::$service->page->widget->render('flashmessage'); ?>

	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				<div class="page-title">
					<h2>Customer Order</h2>
				</div>
				<table id="my-orders-table" class="edit_order">
					<thead>
						<tr class="first last">
							<th>Order #</th>
							<th>Date</th>
							<th>Ship To</th>
							<th><span class="nobr">Order Total</span></th>
							<th><span class="nobr">Order Status</span></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
					<?php  if(is_array($order_list) && !empty($order_list)){  ?>
						<?php foreach($order_list as $order){ 
							$currencyCode = $order['order_currency_code'];
							$symbol = Yii::$service->page->currency->getSymbol($currencyCode);
							
						?>
							<tr class="first odd">
								<td><?= $order['increment_id'] ?></td>
								<td><span class="nobr"><?= date('Y-m-d H:i:s',$order['created_at']) ?></span></td>
								<td><?= $order['customer_firstname'] ?> <?= $order['customer_lastname'] ?></td>
								<td><span class="price"><?= $symbol ?><?= $order['grand_total'] ?></span></td>
								<td><em><?= $order['order_status']; ?></em></td>
								<td class="a-center last">
									<span class="nobr"><a href="<?=  Yii::$service->url->getUrl('customer/order/view',['order_id' => $order['order_id']]);?>">View Order</a>
									<span class="separator">|</span> <a class="link-reorder" href="<?=  Yii::$service->url->getUrl('customer/order/reorder',['order_id' => $order['order_id']]);?>">Reorder</a>
									</span>
								</td>
							</tr>
						
						<?php } ?>
					<?php } ?>
						
					</tbody>
				</table>
				<?php if($pageToolBar){ ?>
					<div class="pageToolbar">
						<label class="title">Page:</label><?= $pageToolBar ?>
					</div>
				<?php } ?>
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
	