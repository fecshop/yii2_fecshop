<?php  $payments = $parentThis['payments'];   ?>
<?php  $current_payment_mothod = $parentThis['current_payment_mothod'];   ?>
<div class="onestepcheckout-payment-method">
	<p class="onestepcheckout-numbers onestepcheckout-numbers-3">Payment method</p>
	<div class="payment_info">
		<div class="payment-methods">
			<dl id="checkout-payment-method-load">
				<?php  if(is_array($payments) && !empty($payments)){  ?>
					<?php foreach($payments as $payment => $info){ ?>	
					<?= $info['style'];  ?>	
					<dt>
						<input style="display:inline" id="p_method_<?= $payment ?>" value="<?= $payment ?>" name="payment_method" title="<?= $info['label']; ?>" class="radio validate-one-required-by-name" <?=  ($current_payment_mothod == $payment) ? 'checked="checked"' : '' ; ?> type="radio">
						<label for="p_method_<?= $payment ?>"><?= $info['label'] ?></label>
					</dt>
					<dd id="container_payment_method_<?= $payment ?>" class="payment-method" style="">
						<ul class="form-list" id="payment_form_<?= $payment ?>" style="">
							<li>
								<img style="margin:10px 0 8px 0" src="<?= $info['imageUrl'] ?>">
							</li>
							<li class="form-alt">
								<?= $info['supplement'] ?>	
							</li>
						</ul>
					</dd>
					<?php } ?>
				<?php } ?>
			</dl>
		</div>
	</div>
</div>