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
		<div class="swiper-slide"><img  class="lazy" data-src="<?= Yii::$service->image->getImgUrl('apphtml5/custom/home_img_1.jpg'); ?>" alt="" ></div>
		<div class="swiper-slide"><img  class="lazy" data-src="<?= Yii::$service->image->getImgUrl('apphtml5/custom/home_img_2.jpg'); ?>" alt="" ></div>
		<div class="swiper-slide"><img  class="lazy" data-src="<?= Yii::$service->image->getImgUrl('apphtml5/custom/home_img_3.jpg'); ?>" alt="" ></div>
	</div>
	<div class="swiper-pagination"></div>
</div>
<div style="padding:10px;">
	<div class="row">
		<div class="col-50">
			<img class="lazy" data-src="<?= Yii::$service->image->getImgUrl('apphtml5/custom/home_small_1.jpg'); ?>" alt="" style='width: 100%'>
		</div>
		<div class="col-50">
			<img class="lazy" data-src="<?= Yii::$service->image->getImgUrl('apphtml5/custom/home_small_2.jpg'); ?>" alt="" style='width: 100%'>
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
                    echo Yii::$service->page->widget->render('cms/productlist', $parentThis);
				?>
				
            </div>
        </div>
       
    </div>
</div>	  

<div class="footer_bar">
	<div class="change-bar">
		<div class="c_left"><?= Yii::$service->page->translate->__('Language'); ?>: </div>
		<div class="c_right">
			<select class="lang" rel="">
				<?php foreach($stores as $store=> $langName):   ?>
					<?php  $selected = ""; ?>
					<?php if($store == $currentStore){ $selected = 'selected = "selected"';  } ?>
					<option <?= $selected ?> value="<?= '//'.$store ?>"><?= $langName ?></option>
				<?php endforeach; ?>	
			</select>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="change-bar">
		<div class="c_left"><?= Yii::$service->page->translate->__('Currency'); ?>: </div>
		<div class="c_right">
			<select class="currency">
				<?php foreach($currencys as $c):    ?>
					<?php  $selected = ""; ?>
					<?php if($c['code'] == $currency['code']){ $selected = 'selected = "selected"';  } ?>
					<option <?= $selected ?> value="<?= $c['code'] ?>"><label><?= $c['symbol'] ?></label><?= $c['code'] ?></option>
				<?php endforeach; ?>	
			</select>
		</div>
		<div class="clear"></div>
	</div>
</div>

<div class="footer-bottom">
	<?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('copy_right_apphtml5','appfront') ?>
</div>				
 
<script>
<?php $this->beginBlock('owl_fecshop_slider') ?>  
$.init();  
$(document).ready(function(){
	currentBaseUrl = "<?=  $currentBaseUrl; ?>";
	$(".footer_bar .change-bar .lang").change(function(){
		redirectUrl = $(this).val();
		location.href=redirectUrl;
		
	});
	
	$(".footer_bar .change-bar .currency").change(function(){
		currency = $(this).val();
		
		htmlobj=$.ajax({url:currentBaseUrl+"/cms/home/changecurrency?currency="+currency,async:false});
		//alert(htmlobj.responseText);
		location.reload() ;
	});
   
});
<?php $this->endBlock(); ?>  
</script>  
<?php $this->registerJs($this->blocks['owl_fecshop_slider'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
