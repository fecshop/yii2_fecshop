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
    <?= Yii::$service->page->widget->render('base/breadcrumbs',$this); ?>
	<div class="col-main account_center">
		<div class="account_review_product">
			<div style="margin:4px 0 0">
				<div class="page-title">
					<h2><?= Yii::$service->page->translate->__('Product Review');?></h2>
				</div>
				<div style="width:100%;min-height:500px;">
					<div style="width:100%;">
						<div>
							<?php  if(is_array($coll) && !empty($coll)):  ?>
							<div class="product-Reviews"> 
								<div class="clear"></div>
								<div class="product-Reviews_top">
									<ul id="review_description">
										<?php foreach($coll as $one):  ?>
										<li>
											<?php $main_image = isset($one['image']['main']['image']) ? $one['image']['main']['image'] : '' ?>
											<div class="review_description_left">
												<a class="product_img" href="<?= Yii::$service->url->getUrl($one['url_key']);  ?>">
													<img src="<?= Yii::$service->product->image->getResize($main_image,[120,120],false) ?>" />
												</a>
												<a  href="#" class="review_star review_star_<?= $one['rate_star'] ?>" onclick="javascript:return false;"></a>
												
											</div>
											<div class="review_description_right">
												<span class="review_description_right_span"><b><?= $one['summary'] ?></b></span>
												<span class="review_date_time"><?= $one['review_date'] ? date('Y-m-d H:i:s',$one['review_date']) : '' ?></span>
												<div class="clear"></div>
												<div class="review_description_centen">
													<div class="addsize"></div>
													<div class="review-content">
														<?= $one['review_content'] ?>
													</div>
													
													<?php if($one['status'] == $noActiveStatus): ?>  
													<div class="review_moderation">
														<?= Yii::$service->page->translate->__('Your Review is awaiting moderation...');?>
													</div>
													<?php elseif($one['status'] == $refuseStatus): ?>
													<div class="review_refuse">
														<?= Yii::$service->page->translate->__('Your Review is refused.');?>
													</div>
													<?php elseif($one['status'] == $activeStatus): ?>
													<div class="review_accept">
														<?= Yii::$service->page->translate->__('Your Review is accept.');?>
													</div>
													<?php endif; ?>
												</div>
											</div>
											<div class="clear"></div>
										</li>
										<?php endforeach; ?>
									</ul>
								</div>
								<?php if($pageToolBar): ?>
								<div class="pageToolbar">
									<label class="title"><?= Yii::$service->page->translate->__('Page:');?></label><?= $pageToolBar ?>
								</div>
								<?php endif; ?>
							</div>
							<?php else: ?>
								<?= Yii::$service->page->translate->__('You have submitted no reviews');?>.
							<?php endif; ?>
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-left ">
		<?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
	</div>
	<div class="clear"></div>
</div>
	