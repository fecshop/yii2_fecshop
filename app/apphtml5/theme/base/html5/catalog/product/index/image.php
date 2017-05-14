<?php 

$media_size = isset($parentThis['media_size']) ? $parentThis['media_size'] : null;
$image = $parentThis['image'];

$middle_img_width = isset($media_size['middle_img_width']) ? $media_size['middle_img_width'] : 400;
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
	<?php if(is_array($gallerys) && !empty($gallerys)){ ?>
		<?php foreach($gallerys as $gallery){ ?>
			<?php $image = $gallery['image']; ?>
			<div class="swiper-slide"><img src="<?= Yii::$service->product->image->getResize($image,$middle_img_width,false)  ?>" alt="" style='width: 100%'></div>	
		<?php } ?>
	<?php } ?>
	</div>
	<div class="swiper-pagination"></div>
</div>
<script>
<?php $this->beginBlock('owl_fecshop_slider') ?>  
$.init();  
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['owl_fecshop_slider'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>


