<div class="scrollBox">	
	<div class="viewport" style="overflow: hidden; position: relative;">
				<?php  if(is_array($parentThis['products']) && !empty($parentThis['products'])){ ?>
					<?php foreach($parentThis['products'] as $product){ ?>
						<ul class="slides" style="width: 1200%; transition-duration: 0s; transform: translate3d(0px, 0px, 0px);">                        
							<li class="proview" style="width: 285px; float: left; display: block;">
								<p class="tc pro_img">
									<a class="i_proImg" href="<?= $product['url'] ?>">
										<img class="js_lazy" data-original="<?= Yii::$service->product->image->getResize($product['image'],[285,434],false) ?>"  src="<?= Yii::$service->image->getImgUrl('images/lazyload1.gif','appfront') ; ?>">
									</a>
								</p>
								<p class="proName">
									<a href="<?= $product['url'] ?>">
										<?= $product['name'] ?>
									</a>
								</p>
								<?php
									$config = [
										'class' 		=> 'fecshop\app\appfront\modules\Catalog\block\category\Price',
										'view'  		=> 'cms/home/index/price.php',
										'price' 		=> $product['price'],
										'special_price' => $product['special_price'],
									];
									echo Yii::$service->page->widget->renderContent('category_product_price',$config);
								?>
							</li>
						</ul>
					<?php  }  ?>
					
						</ul>
						
				<?php  }  ?>
			</div>