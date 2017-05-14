<div class="main container one-column">
<?= Yii::$service->page->widget->render('flashmessage'); ?>
	<div class="col-main">
		<div class="std">
			<div class="review_lists">
				<div class="review_list_product" style="width:100%">
					<div style="width:170px;float:left;">
						<a href="<?= $url ?>">
							<img src="<?= Yii::$service->product->image->getResize($main_img,[150,150],false) ?>">
						</a>
					</div>
					
					<div style="width:600px;float:left;">
						<div style="">
						<a class="product_name" href="<?= $url ?>">
							<?= $name ?>
						</a> </div>
						<div class="product_info review_add_price">
							<div class="price_info">
								<?php # 浠锋牸閮ㄥ垎
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
						<div style="margin:20px 0 0">
							<div class="rbc_cold">
								<span>
									<span class="average_rating"><?= Yii::$service->page->translate->__('Average rating :');?></span>
									<span class="review_star review_star_<?= $reviw_rate_star_average ?>" style="font-weight:bold;" itemprop="average"></span>  
									
									<a rel="nofollow" href="<?= Yii::$service->url->getUrl('catalog/reviewproduct/lists',['spu'=>$spu,'_id'=>$_id]); ?>">
										(<span itemprop="count"><?= $review_count ?> <?= Yii::$service->page->translate->__('reviews');?></span>)
									</a>
								</span>
							</div>					
							<a href="<?= $url ?>"  class="submitbutton">
								<span><span> <?= Yii::$service->page->translate->__('Add To Cart');?></span></span> 
							</a>
							
							<a style="margin-left:10px" href="<?= $addReviewUrl ?>" onclick="" class="submitbutton">
								<span><span> <?= Yii::$service->page->translate->__('Add Review');?></span></span> 
							</a>	 
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="product-Reviews"> 
					<div class="clear"></div>
					<div class="scroll_left">
						<a href=""><?= Yii::$service->page->translate->__('Product Review');?></a>
					</div>
					<div class="product-Reviews_top">
						<?php  if(is_array($coll) && !empty($coll)){  ?>
						<ul id="review_description">
							<?php foreach($coll as $one){  ?>
							
							<li>
								<div class="review_description_left">
									<a href="#" class="review_star review_star_<?= $one['rate_star'] ?>" onclick="javascript:return false;"></a>
									<p><?= Yii::$service->page->translate->__('By');?> <?= $one['name'] ?></p>
									<span><?= $one['review_date'] ? date('Y-m-d H:i:s',$one['review_date']) : '' ?></span>
								</div>
								<div class="review_description_right">
									<input id="review_url_407" value="" type="hidden">
									<span class="review_description_right_span"><b><?= $one['summary'] ?></b></span>
									<div class="review_description_centen">
										<div class="addsize"></div>
										<div class="review-content">
											<?= $one['review_content'] ?>
										</div>
										
										<div class="moderation">
										<?php if($one['status'] == $noActiveStatus){ ?>  
											<?= Yii::$service->page->translate->__('Your Review is awaiting moderation...');?>
										<?php }else if($one['status'] == $refuseStatus){ ?>
											<?= Yii::$service->page->translate->__('Your Review is refused.');?>
										<?php } ?>
										</div>
									</div>
								</div>
								<div class="clear"></div>
							</li>
							<?php } ?>
						</ul>
						<?php } ?>
					</div>
					<?php if($pageToolBar){ ?>
					<div class="pageToolbar">
						<label class="title"><?= Yii::$service->page->translate->__('Page:');?></label><?= $pageToolBar ?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>