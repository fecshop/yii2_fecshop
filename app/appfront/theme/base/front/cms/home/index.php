<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container">
	<?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('home-big-img','appfront') ?>
	<div class="mt10">
		<h4 class="sectionBox_h"><span>BEST SELLER</span><em><a href="">MORE</a></em></h4>
		<div class="pro-content">
			<?php
				$parentThis['products'] = $bestSellerProducts;
				$config = [
					'view'  		=> 'cms/home/index/product.php',
				];
				echo Yii::$service->page->widget->renderContent('category_product_price',$config,$parentThis);
			?>
		</div>

	</div>
	<div class="mt10" style="margin-top:34px;">
		<h4 class="sectionBox_h"><span>FEATURED PRODUCTS</span><em><a href="">MORE</a></em></h4>
		<div class="pro-content">
			<?php
				$parentThis['products'] = $bestFeaturedProducts;
				$config = [
					'view'  		=> 'cms/home/index/product.php',
				];
				echo Yii::$service->page->widget->renderContent('category_product_price',$config,$parentThis);
			?>
		</div>
	</div>
	
</div>