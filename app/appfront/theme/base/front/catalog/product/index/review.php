<?php 
$_id 			= $parentThis['_id'];
$review_count	= $parentThis['review_count'];
$spu			= $parentThis['spu'];
$_id			= $parentThis['_id'];
?>
<div class="product-Reviews">
	<div id="pic_list_2" class="scroll_horizontal">
		<div class="scroll_left">
			<a href="">Product Review</a>
		</div>
		<div class="clear"></div>
		<div class="box">
			<div class="product-Reviews_top">
				<ul id="review_description">
					<li>
						<div class="review_description_left">
							<a href="#" class="review_star review_star_5" onclick="javascript:return false;"></a>
							<p>By fashion</p>
							<span>2016-10-25 10:06:43</span>
						</div>
						<div class="review_description_right">
							<input id="review_url_407" value="http://www.intosmile.com/review/product/addreply?id=407" type="hidden">
							<span class="review_description_right_span"><b>good</b></span>
							<div class="review_description_centen">
								<div class="addsize"></div>
								<br>
								<div class="review-content">
									i like  this sweater
								</div>
								<br>
							</div>
						</div>
						<div class="clear"></div>
					</li>
					<li>
						<div class="review_description_left">
							<a href="#" class="review_star review_star_5" onclick="javascript:return false;"></a>
							<p>By fashion</p>
							<span>2016-10-25 10:06:43</span>
						</div>
						<div class="review_description_right">
							<input id="review_url_407" value="http://www.intosmile.com/review/product/addreply?id=407" type="hidden">
							<span class="review_description_right_span"><b>good</b></span>
							<div class="review_description_centen">
								<div class="addsize"></div>
								<br>
								<div class="review-content">
									i like  this sweater
								</div>
								<br>
							</div>
						</div>
						<div class="clear"></div>
					</li>
					<li>
						<div class="review_description_left">
							<a href="#" class="review_star review_star_5" onclick="javascript:return false;"></a>
							<p>By fashion</p>
							<span>2016-10-25 10:06:43</span>
						</div>
						<div class="review_description_right">
							<input id="review_url_407" value="http://www.intosmile.com/review/product/addreply?id=407" type="hidden">
							<span class="review_description_right_span"><b>good</b></span>
							<div class="review_description_centen">
								<div class="addsize"></div>
								<br>
								<div class="review-content">
									i like  this sweater
								</div>
								<br>
							</div>
						</div>
						<div class="clear"></div>
					</li>
				</ul>
			</div>
			<div class="clear"></div>
			<a class="submitbutton" href="<?= Yii::$service->url->getUrl('catalog/reviewproduct/add',['spu'=>$spu,'_id'=>$_id]); ?>" >
				Add Review
			</a>
			<div class="clear"></div>
			
			<div class="view_all_review">
				<a href="<?= Yii::$service->url->getUrl('catalog/reviewproduct/lists',['spu'=>$spu,'_id'=>$_id]); ?>" >
					View  All Review(<?= $review_count; ?>) 
				</a>
			</div>
		</div>
	</div>
</div>