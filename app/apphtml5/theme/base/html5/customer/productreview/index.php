<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php
use fecshop\app\apphtml5\helper\Format;
?>
<div class="account-ds">
	<div class="bar bar-nav account-top-m">
		<a external class="button button-link button-nav pull-left" href="<?= Yii::$service->url->getUrl('customer/account/index'); ?>">
			<span class="icon icon-left"></span>
		</a>
		<h1 class='title'><?= Yii::$service->page->translate->__('Product Review'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>
<div class="account-container">
	<div class="col-main account_center">
		<div class="account_review_product">
			<div style="margin:4px 0 0">
				<div style="width:100%;min-height:500px;">
					<div style="width:100%;">
						<div>
							<?php  if(is_array($coll) && !empty($coll)):  ?>
							<table class="product-Reviews"> 
								<?php foreach($coll as $one):  ?>
									<tr>
										<td>
											<?php $main_image = isset($one['image']['main']['image']) ? $one['image']['main']['image'] : '' ?>
											<div class="review_description_left">
												<a external class="product_img" href="<?= Yii::$service->url->getUrl($one['url_key']);  ?>">
													<img src="<?= Yii::$service->product->image->getResize($main_image,[80,80],false) ?>" />
												</a>
												<a external href="#" class="review_star review_star_<?= $one['rate_star'] ?>" onclick="javascript:return false;"></a>
												
											</div>
										</td>
										<td>
											<?= $one['summary'] ?><br/><br/>
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
										</td>
										<td>
											<span class="review_date_time"><?= $one['review_date'] ? date('Y-m-d H:i:s',$one['review_date']) : '' ?></span>
											
										</td>
									</tr>
									
								<?php endforeach; ?>
							</table>	
							<?php if($pageToolBar): ?>
							<div class="pageToolbar">
								<label class=""><?= Yii::$service->page->translate->__('Page:');?></label>
                                <?= $pageToolBar ?>
                                <div class="clear"></div>
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
	