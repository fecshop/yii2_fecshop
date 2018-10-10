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
								<h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;">
								您好, <?= $name; ?></h1>
								<p style="font-size:12px; line-height:16px; margin:0 0 10px 0;">
									感谢您在 <?= $storeName; ?> 下订单.
									一旦您的包裹运送，我们将发送一个电子邮件链接跟踪您的订单。
									如果您对订单有任何疑问，请与我们联系 <a href="mailto:<?= $contactsEmailAddress ?>" style="color:#1E7EC8;"><?= $contactsEmailAddress ?></a> ，或者打电话： <span class="nobr"><?= $contactsPhone; ?></span> Monday - Friday, 8am - 5pm PST.
								</p>
								<p style="font-size:12px; line-height:16px; margin:0;">您的订单确认如下。 再次感谢您对我们的支持</p>
							</td>
					   </tr>
						<tr>
							<td>
								<h2 style="font-size:18px; font-weight:normal; margin:0;">您的订单编号 #<?= $order['increment_id'] ?> <small>(下单时间： <?= $order['created_at'] ?>)</small></h2>
							</td>
						</tr>
						<tr>
							<td>
								<table cellspacing="0" cellpadding="0" border="0" width="650">
									<thead>
									<tr>
										<th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">账单信息:</th>
										<th width="10"></th>
										<th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">支付信息:</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td valign="top" style="padding:7px 9px 9px;font-size:12px;border-right-color:rgb(234,234,234);border-bottom-color:rgb(234,234,234);border-left-color:rgb(234,234,234);border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-right-style:solid;border-bottom-style:solid;border-left-style:solid">
												<?= $name ?><br> 
												<?= $order['customer_address_street1']." ".$order['customer_address_street2']?><br>
												<?= $order['customer_address_city']; ?>, <?= $stateName; ?>, <span data="36137" onclick="return false;" t="7" style="border-bottom:1px dashed #ccc;z-index:1"><?= $order['customer_address_zip']; ?></span><br>
												<?= $countryName; ?><br>
												电话: <span data="<?= $order['customer_telephone']; ?>" onclick="return false;" t="7" style="border-bottom:1px dashed #ccc;z-index:1"><?= $order['customer_telephone']; ?></span>
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
										<th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">货运信息:</th>
										<th width="10"></th>
										<th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">货运方式:</th>
									</tr>
									</thead>
									<tbody>
									<tr>
										<td valign="top" style="font-size:12px; padding:7px 9px 9px 9px; border-left:1px solid #EAEAEA; border-bottom:1px solid #EAEAEA; border-right:1px solid #EAEAEA;">
											<?= $name ?><br> 
												<?= $order['customer_address_street1']." ".$order['customer_address_street2']?><br>
												<?= $order['customer_address_city']; ?>, <?= $stateName; ?>, <span data="36137" onclick="return false;" t="7" style="border-bottom:1px dashed #ccc;z-index:1"><?= $order['customer_address_zip']; ?></span><br>
												<?= $countryName; ?><br>
												电话: <span data="<?= $order['customer_telephone']; ?>" onclick="return false;" t="7" style="border-bottom:1px dashed #ccc;z-index:1"><?= $order['customer_telephone']; ?></span>
										
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
												<th bgcolor="#eaeaea" align="left" style="padding:3px 9px;font-size:13px">产品图片</th>
												<th bgcolor="#eaeaea" align="left" style="padding:3px 9px;font-size:13px">产品名字</th>
												<th bgcolor="#eaeaea" align="left" style="padding:3px 9px;font-size:13px">Sku</th>
												<th bgcolor="#eaeaea" align="center" style="padding:3px 9px;font-size:13px">产品个数</th>
												<th bgcolor="#eaeaea" align="right" style="padding:3px 9px;font-size:13px">小计</th>
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
												<td align="right" style="padding:3px 9px" colspan="4">小计</td>
												<td align="right" style="padding:3px 9px"><span><?= $order['currency_symbol'] ?><?=  Format::price($order['subtotal']); ?></span></td>
											</tr>
											<tr>
												<td align="right" style="padding:3px 9px" colspan="4">运费 </td>
												<td align="right" style="padding:3px 9px"><span><?= $order['currency_symbol'] ?><?=  Format::price($order['shipping_total']); ?></span></td>
											</tr>
											<tr>
												<td align="right" style="padding:3px 9px" colspan="4">折扣 </td>
												<td align="right" style="padding:3px 9px"><span><?= $order['currency_symbol'] ?><?=  Format::price($order['subtotal_with_discount']); ?></span></td>
											</tr>
											<tr>
												<td align="right" style="padding:3px 9px" colspan="4"><b>总计</b></td>
												<td align="right" style="padding:3px 9px"><b><span><?= $order['currency_symbol'] ?><?=  Format::price($order['grand_total']); ?></span></b></td>
											</tr>
										</tbody>
									</table>
								<p style="font-size:12px; margin:0 0 10px 0"></p>
							</td>
						</tr>
						<tr>
							<td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA; text-align:center;"><center><p style="font-size:12px; margin:0;">感谢您, <strong><?= $storeName; ?></strong></p></center></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</body>


