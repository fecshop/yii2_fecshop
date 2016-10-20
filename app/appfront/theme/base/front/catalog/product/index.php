<div class="main container one-column">
	<div class="col-main">
		<div class="product_view">
			<div class="media_img">
				<div class="col-left ">
					<?php # 图片部分。
						$imageView = [
							'view'	=> 'catalog/product/index/image.php'
						];
						$imageParam = [
							'media_size' => $media_size,
							'image' => $image,
							'productImgMagnifier' => $productImgMagnifier,
						];
					?>
					<?= Yii::$service->page->widget->render($imageView,$imageParam); ?>
				</div>
			</div>
			<div class="product_info">
				<h1><?= $name; ?></h1>
				<div><?= $sku; ?></div>
				<div class="price_info">
					<?php # 价格部分
						$priceView = [
							'view'	=> 'catalog/product/index/price.php'
						];
						$priceParam = [
							'price_info' => $price_info,
						];
					?>
					<?= Yii::$service->page->widget->render($priceView,$priceParam); ?>
				
				</div>
				<div style="height:300px;">
					<div class="product_options">
						<?= $options; ?>
					</div>
					
					<script>
					<?php $this->beginBlock('product_options') ?>  
					$(document).ready(function(){
						$(".product_options a").click(function(){
							$url = $(this).attr("rel");
							if($url){
								window.location.href=$url;
							}else{
								
								
							}
							
						});
					});
					<?php $this->endBlock(); ?>  
					</script>  
					<?php $this->registerJs($this->blocks['product_options'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

					
				</div>
				<div class="tier_price_info">
					<?php # tier price 部分。
						$priceView = [
							'view'	=> 'catalog/product/index/tier_price.php'
						];
						$priceParam = [
							'tier_price' => $tier_price,
						];
					?>
					<?= Yii::$service->page->widget->render($priceView,$priceParam); ?>
				
				</div>
				
			</div>
			<div class="clear"></div>
		</div>
		<div class="proList">
		</div>
	</div>
</div>
