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
				<?php foreach($parentThis['products'] as $product): ?>
					<div class="item">
						<p class="tc pro_img">
							<a style="" class="i_proImg" href="<?= $product['url'] ?>">
								<img style="width:100%;" class="lazyOwl" data-src="<?= Yii::$service->product->image->getResize($product['image'],[180,200],false) ?>"  src="<?= Yii::$service->image->getImgUrl('appfront/images/lazyload1.gif') ; ?>">
							</a>
						</p>
						<!--
						<p class="proName">
							<a href="<?= $product['url'] ?>">
								<?= $product['name'] ?>
							</a>
						</p>
						-->
						<?php
							$config = [
								'class' 		=> 'fecshop\app\appfront\modules\Catalog\block\category\Price',
								'view'  		=> 'cms/home/index/price.php',
								'price' 		=> $product['price'],
								'special_price' => $product['special_price'],
							];
							echo Yii::$service->page->widget->renderContent('category_product_price',$config);
						?>
					</div>
				<?php  endforeach;  ?>
			</div>
		</div>
	</div>
</div>
 
<script>
<?php $this->beginBlock('owl_fecshop_slider') ?>  
$(document).ready(function(){
	$("#owl-buy-also-buy").owlCarousel({
		items : 6,
		lazyLoad : true,
		navigation : true,
		scrollPerPage : true,
		pagination:false,
		itemsCustom : false,
        slideSpeed : 900
	});
	
});
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['owl_fecshop_slider'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
<?php  endif;  ?>

