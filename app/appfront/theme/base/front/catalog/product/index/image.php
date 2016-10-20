<?php 

$media_size = $parentThis['media_size'];
$image = $parentThis['image'];
$productImgMagnifier = $parentThis['productImgMagnifier'];


 
	$small_img_width = $media_size['small_img_width'];
	$small_img_height = $media_size['small_img_height'];
	$middle_img_width = $media_size['middle_img_width'];
?>
<?php  $main_img = isset($image['main']['image']) ? $image['main']['image'] : '' ?>
<img id="zoom_03" src="<?= Yii::$service->product->image->getResize($main_img,$middle_img_width,false) ?>" data-zoom-image="<?= Yii::$service->product->image->getUrl($main_img);  ?>"/>
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
	if(is_array($gallerys) && !empty($gallerys)){
?>
		<div id="gal1" class="owl-carousel">
		<?php	
			foreach($gallerys as $gallery){
				$image		= $gallery['image'];
				$sort_order = $gallery['sort_order'];
				$label 		= $gallery['label'];
		?>
			<a href="#" data-image="<?= Yii::$service->product->image->getResize($image,$middle_img_width,false) ?>" data-zoom-image="<?= Yii::$service->product->image->getUrl($image);  ?>">
				<img class="elevateZoom lazyOwl" id="img_01" data-src="<?= Yii::$service->product->image->getResize($image,[$small_img_width,$small_img_height],false) ?>" />
			</a>
		<?php
			}
		?>
		</div>
<?php } ?>


<script>
<?php $this->beginBlock('product_view_zoom') ?>  
$(document).ready(function(){
   //initiate the plugin and pass the id of the div containing gallery images
	$("#zoom_03").elevateZoom({
			gallery:'gal1', 
			cursor: 'pointer',
			galleryActiveClass: 'active',
			imageCrossfade: true,
			//preloading: 1,
			loadingIcon: '<?= Yii::$service->image->getImgUrl('images/lazyload.gif'); ?>',  
		<?php if(!$productImgMagnifier){  ?>
			zoomType:"inner",
			cursor: "crosshair"
		<?php }  ?>
	}); 

	//pass the images to Fancybox
	$("#zoom_03").bind("click", function(e) {  
		var ez =   $('#zoom_03').data('elevateZoom');	
		$.fancybox(ez.getGalleryList());
		return false;
	});
	$(document).ready(function(){
		$("#gal1").owlCarousel({
			items : 4,
			lazyLoad : true,
			navigation : true,
			scrollPerPage : true,
			pagination:false,
			itemsCustom : false,
			slideSpeed : 900
		});
	});
});
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['product_view_zoom'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

<style>
.owl-theme .owl-controls{display:none}
.owl-theme:hover .owl-controls .owl-buttons div.owl-next{right:-5px}
.owl-theme:hover .owl-controls .owl-buttons div.owl-prev{left:-5px}
</style>