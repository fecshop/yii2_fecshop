<?php
use fecshop\app\appfront\helper\Format;

?>
<body style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
	<div style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td align="center" valign="top" style="padding:20px 0 20px 0">
					<table bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0;">
						<!-- [ header starts here] -->
						<tr>
							<td valign="top">
								<a href="<?= $homeUrl; ?>">
									<img src="<?=  $logoImg; ?>" alt="" style="margin:10px 0 ;" border="0"/>
								</a>
							</td>
						</tr>
						<!-- [ middle starts here] -->
						<tr>
							<td valign="top">
								<h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;"">Hello, <?= $name; ?></h1>
								<p style="font-size:12px; line-height:16px; margin:0;">
									Thank you for your order from <?= $storeName; ?>.
									Once your package ships we will send an email with a link to track your order.
									You can check the status of your order by <a href="<?= Yii::$service->url->getUrl("customer/account"); ?>" style="color:#1E7EC8;">logging into your account</a>.
									If you have any questions about your order please contact us at <a href="mailto:<?= $contactsEmailAddress ?>" style="color:#1E7EC8;"><?= $contactsEmailAddress ?></a> or call us at <span class="nobr"><?= $contactsPhone; ?></span> Monday - Friday, 8am - 5pm PST.
								</p>
								<p style="font-size:12px; line-height:16px; margin:0;">Your order confirmation is below. Thank you again for your business.</p>
							</td>
					   </tr>
						<tr>
							<td>
								<h2 style="font-size:18px; font-weight:normal; margin:0;">Your Order #<?= $order['increment_id'] ?> <small>(placed on <?= $order['created_at'] ?>)</small></h2>
							</td>
						</tr>
						<tr>
							<td>
								<table cellspacing="0" cellpadding="0" border="0" width="650">
									<thead>
									<tr>
										<th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">Billing Information:</th>
										<th width="10"></th>
										<th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">Payment Method:</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td valign="top" style="padding:7px 9px 9px;font-size:12px;border-right-color:rgb(234,234,234);border-bottom-color:rgb(234,234,234);border-left-color:rgb(234,234,234);border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-right-style:solid;border-bottom-style:solid;border-left-style:solid">
												<?= $name ?><br> 
												<?= $order['customer_address_street1']." ".$order['customer_address_street2']?><br>
												<?= $order['customer_address_city']; ?>, <?= $stateName; ?>, <span data="36137" onclick="return false;" t="7" style="border-bottom:1px dashed #ccc;z-index:1"><?= $order['customer_address_zip']; ?></span><br>
												<?= $countryName; ?><br>
												T: <span data="<?= $order['customer_telephone']; ?>" onclick="return false;" t="7" style="border-bottom:1px dashed #ccc;z-index:1"><?= $order['customer_telephone']; ?></span>
										</td>
												
										<td>&nbsp;</td>
										<td valign="top" style="padding:7px 9px 9px;font-size:12px;border-right-color:rgb(234,234,234);border-bottom-color:rgb(234,234,234);border-left-color:rgb(234,234,234);border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-right-style:solid;border-bottom-style:solid;border-left-style:solid">
											<p><b><?= ucfirst(str_replace('_', ' ', $order['payment_method'])); ?></b></p>

											</td>
									</tr>
									</tbody>
								</table>
								<br/>
								
								<table cellspacing="0" cellpadding="0" border="0" width="650">
									<thead>
									<tr>
										<th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">Shipping Information:</th>
										<th width="10"></th>
										<th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">Shipping Method:</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td valign="top" style="font-size:12px; padding:7px 9px 9px 9px; border-left:1px solid #EAEAEA; border-bottom:1px solid #EAEAEA; border-right:1px solid #EAEAEA;">
											<?= $name ?><br> 
												<?= $order['customer_address_street1']." ".$order['customer_address_street2']?><br>
												<?= $order['customer_address_city']; ?>, <?= $stateName; ?>, <span data="36137" onclick="return false;" t="7" style="border-bottom:1px dashed #ccc;z-index:1"><?= $order['customer_address_zip']; ?></span><br>
												<?= $countryName; ?><br>
												T: <span data="<?= $order['customer_telephone']; ?>" onclick="return false;" t="7" style="border-bottom:1px dashed #ccc;z-index:1"><?= $order['customer_telephone']; ?></span>
										
										</td>
										<td>&nbsp;</td>
										<td valign="top" style="font-size:12px; padding:7px 9px 9px 9px; border-left:1px solid #EAEAEA; border-bottom:1px solid #EAEAEA; border-right:1px solid #EAEAEA;">
											<?= $order['shipping_method']; ?>
											&nbsp;
										</td>
									</tr>
									</tbody>
								</table>
								<br/>
								<table width="650" cellspacing="0" cellpadding="0" border="0" style="border:1px solid rgb(234,234,234)">
										<thead>
											<tr>
												<th bgcolor="#eaeaea" align="left" style="padding:3px 9px;font-size:13px">Image</th>
												
												<th bgcolor="#eaeaea" align="left" style="padding:3px 9px;font-size:13px">Name</th>
												<th bgcolor="#eaeaea" align="left" style="padding:3px 9px;font-size:13px">Sku</th>
												<th bgcolor="#eaeaea" align="center" style="padding:3px 9px;font-size:13px">Qty</th>
												<th bgcolor="#eaeaea" align="right" style="padding:3px 9px;font-size:13px">SubTotal</th>
											</tr>
										</thead>
										<?php if (is_array($order['products']) && !empty($order['products'])) {
    ?>
										<?php foreach ($order['products'] as $product) {
        ?>
											
											<tbody>
												<tr>
													<td valign="top" align="left" style="padding:3px 9px;font-size:11px;border-bottom-color:rgb(204,204,204);border-bottom-width:1px;border-bottom-style:dotted">
														<a href="<?=  Yii::$service->url->getUrl($product['redirect_url']) ; ?>">
															<img src="<?= Yii::$service->product->image->getResize($product['image'], [100,100], false) ?>" alt="<?= $product['name'] ?>" width="75" height="75">
														</a>
													</td>
													<td valign="top" align="left" style="padding:3px 9px;font-size:11px;border-bottom-color:rgb(204,204,204);border-bottom-width:1px;border-bottom-style:dotted">
													<b style="font-size:11px">
													<?= $product['name'] ?></b>
													<?php  if (is_array($product['custom_option_info'])) {
            ?>
														<ul>
															<?php foreach ($product['custom_option_info'] as $label => $val) {
                ?>
																
																<li><?= Yii::$service->page->translate->__($label.':') ?><?= Yii::$service->page->translate->__($val) ?> </li>
																
															<?php
            } ?>
														</ul>
													<?php
        } ?>
													</td>
													<td valign="top" align="left" style="padding:3px 9px;font-size:11px;border-bottom-color:rgb(204,204,204);border-bottom-width:1px;border-bottom-style:dotted"><?= $product['sku'] ?></td>
													<td valign="top" align="center" style="padding:3px 9px;font-size:11px;border-bottom-color:rgb(204,204,204);border-bottom-width:1px;border-bottom-style:dotted"><?= $product['qty'] ?></td>
													<td valign="top" align="right" style="padding:3px 9px;font-size:11px;border-bottom-color:rgb(204,204,204);border-bottom-width:1px;border-bottom-style:dotted"><span><?= $order['currency_symbol'] ?><?= Format::price($product['row_total']); ?></span></td>
												</tr>
											</tbody>
										<?php
    }
} ?>
										<tbody>
											<tr>
												<td align="right" style="padding:3px 9px" colspan="4">Subtotal</td>
												<td align="right" style="padding:3px 9px"><span><?= $order['currency_symbol'] ?><?=  Format::price($order['subtotal']); ?></span></td>
											</tr>
											<tr>
												<td align="right" style="padding:3px 9px" colspan="4">Shipping &amp; handling </td>
												<td align="right" style="padding:3px 9px"><span><?= $order['currency_symbol'] ?><?=  Format::price($order['shipping_total']); ?></span></td>
											</tr>
											<tr>
												<td align="right" style="padding:3px 9px" colspan="4">Discount </td>
												<td align="right" style="padding:3px 9px"><span><?= $order['currency_symbol'] ?><?=  Format::price($order['subtotal_with_discount']); ?></span></td>
											</tr>
											<tr>
												<td align="right" style="padding:3px 9px" colspan="4"><b>Total</b></td>
												<td align="right" style="padding:3px 9px"><b><span><?= $order['currency_symbol'] ?><?=  Format::price($order['grand_total']); ?></span></b></td>
											</tr>
										</tbody>
									</table>
								<p style="font-size:12px; margin:0 0 10px 0"></p>
							</td>
						</tr>
						<tr>
							<td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA; text-align:center;"><center><p style="font-size:12px; margin:0;">Thank you, <strong><?= $storeName; ?></strong></p></center></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</body>


