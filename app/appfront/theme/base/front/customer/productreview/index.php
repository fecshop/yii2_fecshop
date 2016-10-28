<div class="main container two-columns-left">
	
	<div class="col-main account_center">
		
		<div class="account_review_product">
			<div style="margin:19px 0 0">
				
				<div style="width:100%;min-height:500px;">
					<div style="width:100%;">
						<div class="product-Reviews"> 
							<div class="clear"></div>
							<div class="scroll_left">
								<a href="">Product Review</a>
							</div>
							<div class="product-Reviews_top">
								<?php  if(is_array($coll) && !empty($coll)){  ?>
								<ul id="review_description">
									<?php foreach($coll as $one){  ?>
									
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
												
												<?php if($one['status'] == $noActiveStatus){ ?>  
												<div class="review_moderation">
													Your Review is awaiting moderation...
												</div>
												<?php }else if($one['status'] == $refuseStatus){ ?>
												<div class="review_refuse">
													Your Review is refused.
												</div>
												<?php }else if($one['status'] == $activeStatus){ ?>
												<div class="review_accept">
													Your Review is accept.
												</div>
												<?php } ?>
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
								<label class="title">Page:</label><?= $pageToolBar ?>
							</div>
							<?php } ?>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-left ">
		<?php
			$leftMenu = [
				'class' => 'fecshop\app\appfront\modules\Customer\block\LeftMenu',
				'view'	=> 'customer/leftmenu.php'
			];
		?>
		<?= Yii::$service->page->widget->render($leftMenu,$this); ?>
	</div>
	<div class="clear"></div>
</div>
	