
<?php $i = 0; ?>
<?php  if(is_array($parentThis['products']) && !empty($parentThis['products'])){ ?>
	<?php foreach($parentThis['products'] as $product){ ?>
		
		<?php if($i%2 == 0){  ?>
			<div class="row">
		<?php } ?>
			<div class="col-50 product_list">
				<a href="<?= $product['url'] ?>" external>
					<img width="100%"  src="<?= Yii::$service->product->image->getResize($product['image'],296,false) ?>"  />
				</a>
				<p class="product_name" style="">
					<a href="<?= $product['url'] ?>" external>
						<?= $product['name'] ?>
					</a>
				</p>
				<p style="color: #333;">
					<?php
						$config = [
							'class' 		=> 'fecshop\app\apphtml5\modules\Catalog\block\category\Price',
							'view'  		=> 'cms/home/index/price.php',
							'price' 		=> $product['price'],
							'special_price' => $product['special_price'],
						];
						echo Yii::$service->page->widget->renderContent('category_product_price',$config);
					?>
				</p>
			</div>
			
		<?php $i++; ?>
		<?php if($i%2 == 0){  ?>
			</div>
		<?php } ?>
		
	<?php  }  ?>
	<?php if($i%2 != 0){  ?>
		</div>
	<?php } ?>
<?php  }  ?>
