<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container">
<?php  //var_dump($products); ?>
<?php  $count = 3; $end = $count-1; ?>
	<div class="col-main">
		<h1><?=  $name ?></h1>
		<div class="category_img">
		
		</div>
		<div class="panelBar">
		
		</div>
		<div class="category_product">
			<?php  if(is_array($products)){ ?>
				<?php $i = 0;  foreach($products as $product){ ?>
					<?php  if($i%$count == 0){ ?>
					<ul>
					<?php  } ?>
						<li>
							<div class="c_img">
								<a href="<?= $product['url'] ?>">
									<img src="<?= Yii::$service->product->image->getResize($product['image'],[280,380],false) ?>"  />
								</a>
							</div>
							<div class="c_name">
								<a href="<?= $product['url'] ?>">
									<?= $product['name'] ?>
								</a>
							</div>
							<div class="c_price">
								<div class="price">
									<?= $product['price'] ?>
								</div>
								<div class="special_price">
									<?= $product['special_price'] ?>
								</div>
							</div>
						</li>
					<?php  if($i%$count == $end){ ?>
					</ul>
					<?php  } ?>
					<?php  $i++; ?>
				<?php  }  ?>
				<?php  if($i%$count != $end){ ?>
					</ul>
					<?php  } ?>
			<?php  }  ?>
		</div>
	</div>

	<div class="col-left ">
	
	</div>
	<div class="clear"></div>
</div>