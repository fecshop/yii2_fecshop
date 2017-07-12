<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php  if(is_array($parentThis['products']) && !empty($parentThis['products'])): ?>
<div class="buy_also_buy" >
	<div class="scroll_left">
		<a href=""><?= Yii::$service->page->translate->__('Customers Who Bought This Item Also Bought'); ?></a>
	</div>
	<div class="scrollBox">	
		<div class="viewport" style="overflow: hidden; position: relative;">
			<div id="owl-buy-also-buy" class="owl-carousel">	
				<?php
					//$parentThis['products'] = $parentThis['products'];
					$parentThis['name'] = 'featured';
					$config = [
						'view'  		=> 'cms/home/index/product.php',
					];
					echo Yii::$service->page->widget->renderContent('category_product_price',$config,$parentThis);
				?>
			</div>
		</div>
	</div>
</div>
<?php  endif;  ?>

