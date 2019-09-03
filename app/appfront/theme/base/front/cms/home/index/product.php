<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="scrollBox">	
	<div class="viewport" style="overflow: hidden; position: relative;">
		<?php  if(is_array($parentThis['products']) && !empty($parentThis['products'])): ?>
		<div id="owl-<?= $parentThis['name']; ?>" class="owl-carousel">	
			<?php foreach($parentThis['products'] as $product): ?>
				<div class="item">
					<p class="tc pro_img">
						<a style="" class="i_proImg" href="<?= $product['url'] ?>">
							<img style="width:100%;" class="lazyOwl" data-src="<?= Yii::$service->product->image->getResize($product['image'],[285,434],false) ?>"  src="<?= Yii::$service->image->getImgUrl('appfront/images/lazyload1.gif') ; ?>">
						</a>
					</p>
					<p class="proName">
						<a href="<?= $product['url'] ?>">
							<?= $product['name'] ?>
						</a>
					</p>
					<?php
						$diConfig = [
							'price' 		=> $product['price'],
							'special_price' => $product['special_price'],
						];
						echo Yii::$service->page->widget->DiRender('home/product_price',$diConfig);
					?>
				</div>
			<?php  endforeach;  ?>
		</div>	
		<?php  endif;  ?>
	</div>
</div>