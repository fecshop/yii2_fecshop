<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="swiper-container" data-space-between='10'>
	<div class="swiper-wrapper">
		<div class="swiper-slide"><img src="<?= Yii::$service->image->getImgUrl('custom/home_img_1.jpg','apphtml5'); ?>" alt="" style='width: 100%'></div>
		<div class="swiper-slide"><img src="<?= Yii::$service->image->getImgUrl('custom/home_img_2.jpg','apphtml5'); ?>" alt="" style='width: 100%'></div>
		<div class="swiper-slide"><img src="<?= Yii::$service->image->getImgUrl('custom/home_img_3.jpg','apphtml5'); ?>" alt="" style='width: 100%'></div>
	</div>
	<div class="swiper-pagination"></div>
</div>
<div style="padding:10px;">
	<div class="row">
		<div class="col-50">
			<img src="<?= Yii::$service->image->getImgUrl('custom/home_small_1.jpg','apphtml5'); ?>" alt="" style='width: 100%'>
		</div>
		<div class="col-50">
			<img src="<?= Yii::$service->image->getImgUrl('custom/home_small_2.jpg','apphtml5'); ?>" alt="" style='width: 100%'>
		</div>
	</div>
</div>


<style type="text/css">
	.infinite-scroll-preloader {
		margin-top:-20px;
	}
</style>
<div style="clear:both;"></div>
<div style="padding:10px;">      
    <!-- 添加 class infinite-scroll 和 data-distance  向下无限滚动可不加infinite-scroll-bottom类，这里加上是为了和下面的向上无限滚动区分-->
    <div class=" infinite-scroll infinite-scroll-bottom" data-distance="100">
        <div class="list-block">
            <div class="list-container">
				<?php
					$parentThis['products'] = $bestFeaturedProducts;
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
	  
	  


<script>
<?php $this->beginBlock('owl_fecshop_slider') ?>  
$.init();  
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['owl_fecshop_slider'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
