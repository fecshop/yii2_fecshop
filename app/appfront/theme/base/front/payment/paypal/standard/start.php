<html>
	<body>
		You will be redirected to the PayPal website in a few seconds
		<img src="<?= $loaderImg  ?>" />
		<?php // https://www.paypal.com/cgi-bin/webscr ?>
		<form action="<?= $submitAction ?>" id="paypal_standard_checkout" name="paypal_standard_checkout" method="POST">
			<input id="business" name="business" value="<?= $paypal_account ?>" type="hidden"/>
			
			<input id="invoice" name="invoice" value="<?= $order_increment_id  ?>" type="hidden"/>
			<input id="currency_code" name="currency_code" value="<?= $order_currency  ?>" type="hidden"/>
			<input id="paymentaction" name="paymentaction" value="<?= $paymentaction  ?>" type="hidden"/>
			<input id="return" name="return" value="<?= $return_url  ?>" type="hidden"/>
			<input id="cancel_return" name="cancel_return" value="<?= $cancel_url  ?>" type="hidden"/>
			<input id="notify_url" name="notify_url" value="<?= $notify_url  ?>" type="hidden"/>

			<input id="cpp_header_image" name="cpp_header_image" value="<?= $paypal_logo_img  ?>" type="hidden"/>
			<input id="item_name" name="item_name" value="<?= $store_name  ?>" type="hidden"/>
			<input id="charset" name="charset" value="utf-8" type="hidden"/>
			<input id="amount" name="amount" value="<?= $amount  ?>" type="hidden"/>
			<input id="tax" name="tax" value="<?= $tax  ?>" type="hidden"/>
			<input id="shipping" name="shipping" value="<?= $shipping  ?>" type="hidden"/>
			<input id="discount_amount" name="discount_amount" value="<?= $discount_amount  ?>" type="hidden"/>
			<?= $product_items ?>
			<input id="cmd" name="cmd" value="<?= $cmd  ?>" type="hidden"/>
			<input id="upload" name="upload" value="<?= $upload  ?>" type="hidden"/>
			<input id="tax_cart" name="tax_cart" value="<?= $tax_cart  ?>" type="hidden"/>
			<input id="discount_amount_cart" name="discount_amount_cart" value="<?= $discount_amount_cart  ?>" type="hidden"/>
			<?= $address_html ?>.'
			<span class="field-row">
			<input id="submit_to_paypal_button_ffe6e6319afa1dc2e9e4d822e58ca9ca" name="" value="'.Translate::__("Click here if you are not redirected within 10 seconds").'..." type="submit" class=" submit"/>
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