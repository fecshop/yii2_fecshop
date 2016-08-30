<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container two-columns-left">
<?php // echo count($products); ?>
<?php  $count = 4; $end = $count-1; ?>
	<div class="col-main">
		<div class="menu_category">
			<div class="category_img">
				<a href="#"><?=  $image ? '<img style="width:980px;" src="'.$image.'"/>' : '';?><a>
			</div>
			<div class="category_description">
				<h1><?=  $name ?></h1>
				<?=  $description ?>
			</div>
			<div class="panelBar">
				<div class="toolbar">
					<div class="tb_le">
						<b>Sort By:</b>
						<select>
							<option value="new">New</option>
							<option value="name">Name</option>
							<option value="price">Price</option>
						</select>
						<select>
							<option value="30">30</option>
							<option value="60">60</option>
							<option value="90">90</option>
						</select>
					</div>
					<div class="tb_rg">
						<a href="#"><</a>
						
						<a href="#">1</a>
						<a href="#">2</a>
						<a href="#">3</a>
						<a href="#">4</a>
						<a href="#">5</a>
						<a href="#">></a>
					</div>
					<div class="clear"></div>
				</div>
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
										<img src="<?= Yii::$service->product->image->getResize($product['image'],[230,230],false) ?>"  />
									</a>
								</div>
								<div class="c_name">
									<a href="<?= $product['url'] ?>">
										<?= $product['name'] ?>
									</a>
								</div>
								<?php
									$config = [
										'class' 		=> 'fecshop\app\appfront\modules\Catalog\block\category\Price',
										'view'  		=> 'catalog/category/price.php',
										'price' 		=> $product['price'],
										'special_price' => $product['special_price'],
									];
									echo Yii::$service->page->widget->renderContent('category_product_price',$config);
								?>
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
	</div>

	<div class="col-left ">
	
	</div>
	<div class="clear"></div>
</div>