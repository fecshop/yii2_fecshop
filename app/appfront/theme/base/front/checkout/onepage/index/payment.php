<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php  $payments = $parentThis['payments'];   ?>
<?php  $current_payment_method = $parentThis['current_payment_method'];   ?>
<div class="onestepcheckout-payment-method">
	<p class="onestepcheckout-numbers onestepcheckout-numbers-3"><?= Yii::$service->page->translate->__('Payment Method');?></p>
	<div class="payment_info">
		<div class="payment-methods">
			<dl id="checkout-payment-method-load">
				<?php  if(is_array($payments) && !empty($payments)):  ?>
					<?php foreach($payments as $payment => $info): ?>	
					<?= $info['style'];  ?>	
					<?php 
						if($info['checked'] == true):
							$checked = 'checked="checked"';
						else:
							$checked = '';
						endif;
					?>	
					<dt>
						<input <?=  $checked; ?> style="display:inline" id="p_method_<?= $payment ?>" value="<?= $payment ?>" name="payment_method" title="<?= $info['label']; ?>" class="radio validate-one-required-by-name" <?=  ($current_payment_method == $payment) ? 'checked="checked"' : '' ; ?> type="radio">
						<label for="p_method_<?= $payment ?>"><?= Yii::$service->page->translate->__($info['label']) ?></label>
					</dt>
					<dd id="container_payment_method_<?= $payment ?>" class="payment-method" style="">
						<ul class="form-list" id="payment_form_<?= $payment ?>" style="">
							<li>
							<?php if(isset($info['imageUrl']) && !empty($info['imageUrl'])): ?>
								<img style="margin:10px 0 8px 0" src="<?= $info['imageUrl'] ?>">
							<?php endif; ?>
							</li>
							<li class="form-alt"><?= Yii::$service->page->translate->__($info['supplement']) ?></li>
						</ul>
					</dd>
					<?php endforeach; ?>
				<?php endif; ?>
			</dl>
		</div>
	</div>
</div>