<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php 
$media_size = isset($parentThis['media_size']) ? $parentThis['media_size'] : null;
$image = $parentThis['image'];
$middle_img_width = isset($media_size['middle_img_width']) ? $media_size['middle_img_width'] : 400;
$is_in_stock = $parentThis['is_in_stock'];
?>
<div class="swiper-container" data-space-between='10'>
	<div class="swiper-wrapper">
<?php
	if(isset($image['gallery']) && is_array($image['gallery']) && !empty($image['gallery'])){
		$gallerys = $image['gallery'];
		$gallerys = \fec\helpers\CFunc::array_sort($gallerys,'sort_order',$dir='asc');
		if(is_array($image['main']) && !empty($image['main'])){
			$main_arr[] = $image['main'];
			$gallerys = array_merge($main_arr,$gallerys);
		}	
	}else if(is_array($image['main']) && !empty($image['main'])){
		$main_arr[] = $image['main'];
		$gallerys = $main_arr;
	}
?>
	<?php if(is_array($gallerys) && !empty($gallerys)): ?>
		<?php foreach($gallerys as $gallery): ?>
			<?php $image = $gallery['image']; ?>
			<div class="swiper-slide product-main-img">
                <span class="second_tag">
                    <?= Yii::$service->page->translate->__('OUT OF STOCK'); ?>
                </span>
                <img class="lazy" data-src="<?= Yii::$service->product->image->getResize($image,$middle_img_width,false)  ?>" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif'); ?>" alt="" style='width: 100%'>
            </div>	
		<?php endforeach ?>
	<?php endif; ?>
	</div>
	<div class="swiper-pagination"></div>
</div>
<script>
<?php $this->beginBlock('owl_fecshop_slider') ?>  
$.init();  
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['owl_fecshop_slider'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

<style>
.product-main-img{
    position:relative;
}
.second_tag {
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,.5);
    color: #fff;
    font-size: 16px;
    text-align: center;
    padding-top: 0;
    top: 0;
    left: 0;
    margin-left: 0;
    margin-top: 0;
    line-height: 400px;
</style>
