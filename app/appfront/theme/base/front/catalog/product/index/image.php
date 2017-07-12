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
$media_size = $parentThis['media_size'];
$image = $parentThis['image'];
$productImgMagnifier = $parentThis['productImgMagnifier'];
$small_img_width = $media_size['small_img_width'];
$small_img_height = $media_size['small_img_height'];
$middle_img_width = $media_size['middle_img_width'];
?>
<?php  $main_img = isset($image['main']['image']) ? $image['main']['image'] : '' ?>
<div class="product-main-img">
	<img id="zoom_03" src="<?= Yii::$service->product->image->getResize($main_img,$middle_img_width,false) ?>" data-zoom-image="<?= Yii::$service->product->image->getUrl($main_img);  ?>"/>
</div>
<?php
	if(isset($image['gallery']) && is_array($image['gallery']) && !empty($image['gallery'])):
		$gallerys = $image['gallery'];
		$gallerys = \fec\helpers\CFunc::array_sort($gallerys,'sort_order',$dir='asc');
		if(is_array($image['main']) && !empty($image['main'])):
			$main_arr[] = $image['main'];
			$gallerys = array_merge($main_arr,$gallerys);
		endif;	
	elseif(is_array($image['main']) && !empty($image['main'])):
		$main_arr[] = $image['main'];
		$gallerys = $main_arr;
	endif;
	if(is_array($gallerys) && !empty($gallerys)):
?>
		<div class="product-img-box">
			<div class="gallery-img">
				<a href="javascript:;" class="pre_images"></a>
				<div class="box-img" id="gal1">
					<div class="list-img" >
		<?php	
			foreach($gallerys as $gallery):
				$image		= $gallery['image'];
				$sort_order = $gallery['sort_order'];
				$label 		= $gallery['label'];
		?>
						<a href="#" data-image="<?= Yii::$service->product->image->getResize($image,$middle_img_width,false) ?>" data-zoom-image="<?= Yii::$service->product->image->getUrl($image);  ?>">
							<img class="elevateZoom lazyOwl" id="img_01" src="<?= Yii::$service->product->image->getResize($image,[$small_img_width,$small_img_height],false) ?>" />
						</a>
		<?php
			endforeach;
		?>
					</div>
				</div>
				<a href="javascript:;" class="next_images"></a>
				<div class="clear"></div>
			</div>
		</div>
	<div class="clear"></div>
<?php endif; ?>

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
		<?php if(!$productImgMagnifier):  ?>
			zoomType:"inner",
			cursor: "crosshair"
		<?php endif;  ?>
	}); 

	//pass the images to Fancybox
	$("#zoom_03").bind("click", function(e) {  
		var ez =   $('#zoom_03').data('elevateZoom');	
		$.fancybox(ez.getGalleryList());
		return false;
	});
	$(document).ready(function(){
		$(".next_images").click(function(){
			//83
			i = 0;
			$(".product-img-box .box-img .list-img img").each(function(){
				i++;
			});
			maxright = 83*(i-4);
			nowright = $(".product-img-box .list-img").css("top");
			nowright = parseFloat(nowright.replace("px",""));
			abs_nowright = Math.abs(nowright);
			//alert(nowright);
			if(abs_nowright >= maxright && (nowright < 0)){
				$(".product-img-box .list-img").animate({top: '0px'}, "slow");  
			}else{
				$(".product-img-box .list-img").animate({top: '-=125px'}, "fast"); 
			}		
		});
		
		$(".pre_images").click(function(){
			
			//83
			i = 0;
			$(".product-img-box .box-img .list-img img").each(function(){
				i++;
			});
			maxright = 83*(i-4);
			nowright = $(".product-img-box .list-img").css("top");
			nowright = parseFloat(nowright.replace("px",""));
			abs_nowright = Math.abs(nowright);
			
			if(nowright<0){
				
				$(".product-img-box .list-img").animate({top: '+=125px'}, "fast"); 
			}else{
				$(".product-img-box .list-img").animate({top: '0px'}, "slow"); 
			}	
			 
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