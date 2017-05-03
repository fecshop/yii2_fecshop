<html>
	<body>
		<?= Yii::$service->page->translate->__('You will be redirected to the PayPal website in a few seconds...');  ?>
		<img src="<?= $loader_img  ?>" />
		<?php // https://www.paypal.com/cgi-bin/webscr ?>
		<form action="<?= $payment_url ?>" id="paypal_standard_checkout" name="paypal_standard_checkout" method="POST">
			<input id="business" name="business" value="<?= $account ?>" type="hidden"/>
			
			<input id="invoice" name="invoice" value="<?= $order['increment_id']  ?>" type="hidden"/>
			<input id="currency_code" name="currency_code" value="<?= $order['order_currency_code']  ?>" type="hidden"/>
			<input id="paymentaction" name="paymentaction" value="<?= $payment_action  ?>" type="hidden"/>
			<input id="return" name="return" value="<?= $success_redirect_url  ?>" type="hidden"/>
			<input id="cancel_return" name="cancel_return" value="<?= $cancel_url  ?>" type="hidden"/>
			<input id="notify_url" name="notify_url" value="<?= $ipn_url  ?>" type="hidden"/>

			<input id="cpp_header_image" name="cpp_header_image" value="<?= $paypal_logo_img  ?>" type="hidden"/>
			<input id="item_name" name="item_name" value="<?= $store_name  ?>" type="hidden"/>
			<input id="charset" name="charset" value="utf-8" type="hidden"/>
			
			<input id="amount" name="amount" value="<?= $order['grand_total'] ? (str_replace(',','',number_format($order['grand_total'],2))) : number_format(0,2)  ?>" type="hidden"/>
			<input id="tax" name="tax" value="<?= $tax  ?>" type="hidden"/>
			<input id="shipping" name="shipping" value="<?= $order['shipping_total'] ? (str_replace(',','',number_format($order['shipping_total'],2))) : number_format(0,2)  ?>" type="hidden"/>
			<input id="discount_amount" name="discount_amount" value="<?= $order['subtotal_with_discount'] ? (str_replace(',','',number_format($order['subtotal_with_discount'],2))) : number_format(0,2)  ?>" type="hidden"/>
			<?= $product_items_and_shipping ?>
			<input id="cmd" name="cmd" value="<?= $cmd  ?>" type="hidden"/>
			<input id="upload" name="upload" value="<?= $upload  ?>" type="hidden"/>
			<input id="tax_cart" name="tax_cart" value="<?= $tax_cart  ?>" type="hidden"/>
			<input id="discount_amount_cart" name="discount_amount_cart" value="<?= $order['subtotal_with_discount'] ? (str_replace(',','',number_format($order['subtotal_with_discount'],2))) : number_format(0,2)  ?>" type="hidden"/>
			<?= $address_html ?>
			<span class="field-row">
			<input id="submit_to_paypal_button" name="" value="<?= Yii::$service->page->translate->__('Click here if you are not redirected within 10 seconds ...');  ?>" type="submit" class=" submit"/>
			</span>
		</form>
		<script type="text/javascript">
			function func(){
				document.getElementById("paypal_standard_checkout").submit();
			}
			window.onload=func;
		</script>
	</body>
</html>