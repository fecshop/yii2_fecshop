<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="main container one-column">
<?= Yii::$service->page->widget->render('flashmessage'); ?>
	<div class="col-main">
		<div class="std">
			<div class="review_lists">
				<div class="review_list_product" style="width:100%">
					<div class="row">
						<div class="col-20">
							<a external href="<?= $url ?>">
								<img src="<?= Yii::$service->product->image->getResize($main_img,[150,150],false) ?>">
							</a>
						</div>
						<div class="col-80">
							<a external class="product_name" href="<?= $url ?>">
								<?= $name ?>
							</a>
							
							<div class="product_info review_add_price">
								<div class="price_info">
									<?php 
										$priceView = [
											'view'	=> 'catalog/product/index/price.php'
										];
										$priceParam = [
											'price_info' => $price_info,
										];
									?>
									<?= Yii::$service->page->widget->render($priceView,$priceParam); ?>
								</div>
							</div>
						</div>
					</div>
					
					
					
					<div class="review_cart">
						
						
						<div style="margin:20px 0 0">
							<div class="rbc_cold">
								<span>
									<span class="average_rating"><?= Yii::$service->page->translate->__('Average rating :');?></span>
									<span class="review_star review_star_<?= $reviw_rate_star_average ?>" style="font-weight:bold;" itemprop="average"></span>  
									
									<a external rel="nofollow" href="<?= Yii::$service->url->getUrl('catalog/reviewproduct/lists',['spu'=>$spu,'_id'=>$_id]); ?>">
										(<span itemprop="count"><?= $review_count ?> <?= Yii::$service->page->translate->__('reviews');?></span>)
									</a>
								</span>
							</div>					
							
							<div class="content-block">
								<div class="row">
									<div class="col-50">
										<a external href="<?= $url ?>"  class="submitbutton button  button-fill button-success">
											<span><span> <?= Yii::$service->page->translate->__('Add To Cart');?></span></span> 
										</a>
									</div>
									<div class="col-50">
										<a external style="margin-left:10px" href="<?= $addReviewUrl ?>" onclick="" class="submitbutton button  button-fill button-danger">
											<span><span> <?= Yii::$service->page->translate->__('Add Review');?></span></span> 
										</a>
									</div>
								</div>
							</div>
							
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="product-reviews"> 
					<div class="clear"></div>
					<div class="review_title">
						<a external href="#"><?= Yii::$service->page->translate->__('Product Review');?></a>
					</div>
						<?php  if(is_array($coll) && !empty($coll)):  ?>
						
							<?php foreach($coll as $one):  ?>
								<div class="card">
									<div class="fec-card-header">
										<?= $one['summary'] ?>
									</div>
									<div class="fec-card-content">
										<div class="fec-card-content-inner">
											<div class="review-content">
												<?= $one['review_content'] ?>
											</div>
												
											<div class="moderation">
											<?php if($one['status'] == $noActiveStatus): ?>  
												<?= Yii::$service->page->translate->__('Your Review is awaiting moderation...');?>
											<?php elseif($one['status'] == $refuseStatus): ?>
												<?= Yii::$service->page->translate->__('Your Review is refused.');?>
											<?php endif; ?>
											</div>
											<div class="review_list_remark">
												<p><?= Yii::$service->page->translate->__('By');?> <?= $one['name'] ?></p>
												<span><?= $one['review_date'] ? date('Y-m-d H:i:s',$one['review_date']) : '' ?></span>
											</div>
										</div>
									</div>
									<div class="fec-card-footer">
										<a href="#" class="review_star review_star_<?= $one['rate_star'] ?>" onclick="javascript:return false;"></a>
									</div>
								</div>
							<?php  endforeach; ?>
						
						<?php endif; ?>
					
					<?php if($pageToolBar): ?>
					<div class="pageToolbar">
						<label class=""><?= Yii::$service->page->translate->__('Page:');?></label>
                        <?= $pageToolBar ?>
                         <div class="clear"></div>
                    </div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>