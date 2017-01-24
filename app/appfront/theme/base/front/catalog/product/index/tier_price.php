<?php  $tier_price = $parentThis['tier_price'];   ?>

<?php  if(is_array($tier_price) && !empty($tier_price) ) { ?>
<div class="label"> Wholesale Prices :</div>
	<table >
		<tr>
			<td>qty:</td>
		<?php $i = 1;  ?>
		<?php  foreach($tier_price as $one){  ?>
			<?php if($i != 1){  ?>
				<td>
					<?php echo $pre_qty.'-'.$one['qty']; ?>
				</td>
			<?php	} ?>
			<?php
				$i++;
				$pre_qty = $one['qty'];
			?>
		<?php  }  ?>
			<td>
			<?= '>='.$pre_qty;  ?>
			</td>
		</tr>
		<tr>
			<td>price:</td>
		<?php  foreach($tier_price as $one){  ?>
			<td><?= Yii::$service->product->price->formatSamplePrice($one['price']); ?></td>
		<?php  }  ?>
		</tr>
	</table>
<?php  }  ?>