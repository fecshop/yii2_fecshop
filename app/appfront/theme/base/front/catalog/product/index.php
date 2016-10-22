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
						<?php # options部分
							$optionsView = [
								'view'	=> 'catalog/product/index/options.php'
							];
							$optionsParam = [
								'options' => $options,
							];
						?>
						<?= Yii::$service->page->widget->render($optionsView,$optionsParam); ?>
					
					</div>
					<div class="product_custom_options">
						<?php # options部分
							$optionsView = [
								'view'	=> 'catalog/product/index/custom_option.php'
							];
							$optionsParam = [
								'custom_option' => $custom_option,
							];
						?>
						<?= Yii::$service->page->widget->render($optionsView,$optionsParam); ?>
					
					</div>
					
					<div class="product_qty">
						<div>Qty:</div>
						<div>
							<input type="text" name="qty" class="qty" value="1" />
						</div>
						<div class="clear"></div>
					</div>
					
					
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
