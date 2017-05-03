<?php  $price_info = $parentThis['price_info'];   ?>
<?php if(isset($price_info['special_price']['value'])){  ?>			
	<div class="special_price special_active">
		<?= $price_info['special_price']['symbol']  ?><?= $price_info['special_price']['value'] ?>
	</div>
	<div class="price special_active">
		<?= $price_info['price']['symbol']  ?>
		<?= $price_info['price']['value'] ?>
	</div>
	<div class="clear"></div>
<?php }else{  ?>
	<div class="price no-special">
		<?= $price_info['price']['symbol']  ?><?= $price_info['price']['value'] ?>
	</div>
<?php } ?>