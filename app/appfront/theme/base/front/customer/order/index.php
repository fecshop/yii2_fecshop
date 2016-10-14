<div class="main container two-columns-left">
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
						<tr class="first odd">
							<td>intosmileEn000015519</td>
							<td><span class="nobr">2016-10-13 17:24:42</span></td>
							<td>firstname lastname</td>
							<td><span class="price">$7.99</span></td>
							<td><em>pending</em></td>
							<td class="a-center last">
								<span class="nobr"><a href="http://www.intosmile.com/customer/order/view?order_id=15519">View Order</a>
								<span class="separator">|</span> <a class="link-reorder" href="http://www.intosmile.com/customer/order/reorder?order_id=15519">Reorder</a>
								</span>
							</td>
						</tr>
					</tbody>
				</table>
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
	