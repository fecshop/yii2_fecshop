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
    //$reviw_rate_star_info = $parentThis['reviw_rate_star_info'];
    //$review_count = $parentThis['review_count'];
    //$reviw_rate_star_average = $parentThis['reviw_rate_star_average'];
    //var_dump($reviw_rate_star_info);
?>
<div class="product-Reviews">
	<div id="pic_list_2" class="scroll_horizontal">
		<div class="scroll_left">
			<a href=""><?= Yii::$service->page->translate->__('Product Review'); ?></a>
		</div>
		<div class="clear"></div>
		<div class="box pro_commit">
            <div class="averageWarp">
				<span class="lineBlock fon14"><?= Yii::$service->page->translate->__('Average Rating'); ?>: </span>
				<a  lehref="#" class="review_star review_star_<?= round($reviw_rate_star_average) ?>" onclick="javascript:return false;"></a>
				<b class="lineBlock fon18"><?= $reviw_rate_star_average ?></b>
				<span class="lineBlock"><?= Yii::$service->page->translate->__('based on {review_count} Customer Reviews',['review_count' => $review_count]) ?></span>
			</div>
            <div class="clear"></div>
            <div class="lbBox writeRiviewTitle">
				<ul class="lineBlock proportionStars">
					<li class="lbBox">
						<span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('5 stars'); ?></span>
						<div class="lineBlock proportionBox">
							<div style="width: <?=  $reviw_rate_star_info['star_5'] ?>%"> </div>
						</div>
						<span class="lineBlock"><?=  $reviw_rate_star_info['star_5'] ?>%</span>
					</li>
					<li class="lbBox">
						<span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('4 stars'); ?></span>
						<div class="lineBlock proportionBox">
							<div style="width: <?=  $reviw_rate_star_info['star_4'] ?>%"> </div>
						</div>
						<span class="lineBlock"><?=  $reviw_rate_star_info['star_4'] ?>%</span>
					</li>
					<li class="lbBox">
						<span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('3 stars'); ?></span>
						<div class="lineBlock proportionBox">
							<div style="width: <?=  $reviw_rate_star_info['star_3'] ?>%"> </div>
						</div>
                        <span class="lineBlock"><?=  $reviw_rate_star_info['star_3'] ?>%</span>				
					</li>
					<li class="lbBox">
						<span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('2 stars'); ?></span>
						<div class="lineBlock proportionBox">
							<div style="width: <?=  $reviw_rate_star_info['star_2'] ?>%"> </div>
						</div>
						<span class="lineBlock"><?=  $reviw_rate_star_info['star_2'] ?>%</span>
					</li>
					<li class="lbBox">
						<span class="lineBlock fz_blue"><?= Yii::$service->page->translate->__('1 stars'); ?></span>
						<div class="lineBlock proportionBox">
							<div style="width: <?=  $reviw_rate_star_info['star_1'] ?>%"> </div>
						</div>
                        <span class="lineBlock"><?=  $reviw_rate_star_info['star_1'] ?>%</span>
					</li>
				</ul>
				<div class="lineBlock writeRiviewBtn">
					<button type="submit" title="Save" class="button btn btn-primary addreview" onclick="javascrtpt:window.location.href='<?= Yii::$service->url->getUrl('catalog/reviewproduct/add',['spu'=>$spu,'_id'=>$_id]); ?>'"><span><span><?= Yii::$service->page->translate->__('Write a Customer Review'); ?></span></span></button>
				</div>
			</div>
            
			<div class="product-Reviews_top">
				<?php  if(is_array($coll) && !empty($coll)):  ?>
				<ul id="review_description">
					<?php foreach($coll as $one):  ?>
					<li>
						<div class="review_description_left">
							<a href="#" class="review_star review_star_<?= $one['rate_star'] ?>" onclick="javascript:return false;"></a>
							<p><?= Yii::$service->page->translate->__('By'); ?> <?= $one['name'] ?></p>
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
								<?php if($one['status'] == $noActiveStatus): ?>  
								<div class="moderation">
									<?= Yii::$service->page->translate->__('Your comment is awaiting moderation'); ?>...
								</div>
								<?php endif; ?>
								
							</div>
						</div>
						<div class="clear"></div>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
			<div class="clear"></div>
			
			<div class="clear"></div>
			<div class="view_all_review">
				<a href="<?= Yii::$service->url->getUrl('catalog/reviewproduct/lists',['spu'=>$spu,'_id'=>$_id]); ?>" >
					<?= Yii::$service->page->translate->__('View  All Review'); ?>(<?= $review_count; ?>) 
				</a>
			</div>
		</div>
	</div>
</div>