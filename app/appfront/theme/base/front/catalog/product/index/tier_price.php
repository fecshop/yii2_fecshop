<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php  $tier_price = $parentThis['tier_price'];   ?>
<?php  if(is_array($tier_price) && !empty($tier_price) ): ?>
<div class="label"> <?= Yii::$service->page->translate->__('Wholesale Prices :'); ?></div>
	<table >
		<tr>
			<td><?= Yii::$service->page->translate->__('Qty:'); ?></td>
		<?php $i = 1;  ?>
		<?php  foreach($tier_price as $one):  ?>
			<?php if($i != 1):  ?>
				<td>
                    <?php $end_qty = $one['qty'] - 1; ?>
                    <?php if ($end_qty > $pre_qty):  ?>
                        <?php echo $pre_qty.'-'.$end_qty; ?>
                    <?php else: ?>
                        <?php echo $pre_qty ?>
                    <?php endif; ?>
				</td>
			<?php endif; ?>
			<?php
				$i++;
				$pre_qty = $one['qty'];
			?>
		<?php   endforeach;  ?>
			<td>
			<?= '>='.$pre_qty;  ?>
			</td>
		</tr>
		<tr>
			<td><?= Yii::$service->page->translate->__('Price:'); ?></td>
		<?php  foreach($tier_price as $one):  ?>
			<td><?= Yii::$service->product->price->formatSamplePrice($one['price']); ?></td>
		<?php  endforeach;  ?>
		</tr>
	</table>
<?php  endif;  ?>