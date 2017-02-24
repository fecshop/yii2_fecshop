
<style>
.paypal_success{line-height:24px;}
</style>
<div class="main container one-column">
	<div class="col-main">
		<div class="paypal_success">
			<div class="page-title">
				
			</div>
			<h2 class="sub-title">Your order has been received,Thank you for your purchase!</h2>
			
			<p>Your order # is: <?= $increment_id ?>.</p>
			<p>You will receive an order confirmation email with details of your order and a link to track its progress.</p>

			<div class="buttons-set">
				<button type="button" class="button" title="Continue Shopping" onclick="window.location='<?= Yii::$service->url->homeUrl();  ?>'"><span><span>Continue Shopping</span></span></button>
			</div>
			<?php // var_dump($order); ?>
		</div>
	</div>
</div>