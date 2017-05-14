
<div class="product-Reviews">
	<div id="pic_list_2" class="scroll_horizontal">
		
		<div class="clear"></div>
		<div class="box">
			<div class="product-Reviews_top">
				<?php  if(is_array($coll) && !empty($coll)){  ?>
						
					<?php foreach($coll as $one){  ?>
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
									<?php if($one['status'] == $noActiveStatus){ ?>  
										<?= Yii::$service->page->translate->__('Your Review is awaiting moderation...');?>
									<?php }else if($one['status'] == $refuseStatus){ ?>
										<?= Yii::$service->page->translate->__('Your Review is refused.');?>
									<?php } ?>
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
					<?php  } ?>
				
				<?php } ?>
			</div>
			<div class="clear"></div>
			
			<p class="buttons-row">
				<a external href="<?= Yii::$service->url->getUrl('catalog/reviewproduct/add',['spu'=>$spu,'_id'=>$_id]); ?>" class="button button-round">
					<?= Yii::$service->page->translate->__('Add Review'); ?>
				</a>
				<a external href="<?= Yii::$service->url->getUrl('catalog/reviewproduct/lists',['spu'=>$spu,'_id'=>$_id]); ?>" class="button button-round">
					<?= Yii::$service->page->translate->__('View  All Review'); ?>(<?= $review_count; ?>) 
				</a>
			</p>
		</div>
	</div>
</div>