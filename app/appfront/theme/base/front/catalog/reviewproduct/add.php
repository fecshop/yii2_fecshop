<div class="main container one-column">
	<div class="col-main">
		<div class="std">
			<div class="product-Reviews_top">
				<div style="width:90px;float:left;">
					<a href="<?= $url  ?>">
						<img src="<?= Yii::$service->product->image->getResize($main_img,[80,80],false) ?>">
					</a>
				</div>
				
				<div style="width:700px;float:left;">
					<div style="">
						<a href="<?= $url  ?>"><?= $name ?></a> 
					</div>
					<div class="product_info review_add_price">
						<div class="price_info">
							<?php # 价格部分
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
				<div class="clear"></div>
			</div>
			
			<div class="product-Reviews_bottom">
				<form method="post" action="http://www.intosmile.com/review/product/add?id=112070">
					<input class="thiscsrf" value="YVpVUWRacG4uLg1nFRkdHTICDzMeORYeAD8ZEi8TFDYAIB4nKwIgPQ==" name="_csrf" type="hidden">			<input name="product_id" value="112070" type="hidden">
					<div class="h-30">
						<div class="lh30_f">
							<strong>Rate</strong><em class="product-description_em">*</em>&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
						<div class="lh30_f">
							<input name="reviews[rate]" value="5" id="review_price_field" type="hidden">
							<ul class="star_ul">
								<li><a class="star_1 full_star" title="1 stars" rel="1" alt="6" href="#" onclick="javascript:return false;"></a></li>
								<li><a class="star_2 full_star" title="2 stars" rel="2" alt="7" href="#" onclick="javascript:return false;"></a></li>
								<li><a class="star_3 full_star" title="3 stars" rel="3" alt="8" href="#" onclick="javascript:return false;"></a></li>
								<li><a class="star_4 full_star" title="4 stars" rel="4" alt="9" href="#" onclick="javascript:return false;"></a></li>
								<li><a class="star_5 full_star" title="5 stars" rel="5" alt="10" href="#" onclick="javascript:return false;"></a></li>
							</ul>          
							<span class="review_span_error" id="review_price_span" style="display:inline;"></span>
						</div>
					</div>
					
				
					<div id="review_create">
						<ul id="reviews_form_list">
							<li>
								<label for="nickname_field" class="required">
								<strong>Your Name</strong><em class="product-description_em">*</em></label>
								<div class="input-box">
									
									<input name="reviews[name]" id="review_email_field" class="input-text  review-input-text required-entry" value="" type="text">
								</div>
								<span class="review_span_error" id="review_email_span"></span>
							</li>
							<li>
								<label for="nickname_field" class="required">
								<strong>Summary of Your Review</strong><em class="product-description_em">*</em></label>
								<div class="input-box">
									<input name="reviews[title]" id="review_title_field" class="input-text  review-input-text required-entry" value="" type="text">
								</div>
								<span class="review_span_error" id="review_title_span"></span>
							</li>
							
								
								
							<li>
								<label for="height_field" class="">
								<strong>Your Height(Cm)</strong></label>
								<div class="input-box">
									<input name="reviews[height]" id="review_height_field" class="input-text review-input-text " value="" type="text">
								</div>
								<span class="review_span_error" id="review_height_span"></span>
							</li>
							
							<li>
								<label for="weight_field" class="">
								<strong>Your Weight(Kg)</strong></label>
								<div class="input-box">
									<input name="reviews[weight]" id="review_weight_field" class="input-text review-input-text " value="" type="text">
								</div>
								<span class="review_span_error" id="review_weight_span"></span>
							</li>
							
							<li>
								<label for="height_field" class="">
								<strong>The Size Your Buy</strong></label>
								<div class="input-box">
									<select class="product_size" name="size"><option value="L">L</option><option value="M">M</option><option value="S">S</option><option value="XL">XL</option><option value="XXL">XXL</option></select>						</div>
								<span class="review_span_error" id="review_height_span"></span>
							</li>
							
							<li>
								<label for="height_field" class="">
								<strong>Is It Fit For You ?</strong></label>
								<div class="input-box">
									<select name="is_it_fit">
										<option value="It Fit">It Fit</option>
										<option value="To Small For You">To Small For You</option>
										
										<option value="To Lager For You">To Lager For You</option>
									</select>
								</div>
								<span class="review_span_error" id="review_height_span"></span>
							</li>
							<li id="review_textarea" style="width: 722px;">
								<label for="nickname_field" class="required"><strong>Review</strong><em class="product-description_em">*</em></label>
								<div class="input-box">
									<textarea name="reviews[review]" id="review_review_field"></textarea>
								</div>
								<span class="review_span_error" id="review_review_span"></span>
							</li>
							<li>
								<label for="pass" class="required customertitle"><em>*</em>Verification code</label>
								<div class="input-box login_box">
								   <input class="verification_code_input" maxlength="4" name="sercrity_code" value="" type="text">
								   <img class="verification_code" src="http://www.intosmile.com/help/verificate/code" onclick="this.src=this.src+'?'">
								   <br>
								</div>
								<button type="submit" title="Submit Review" class="button" id="m_top_10" onclick="return check_review()"><span><span>Submit Review</span></span></button>
							</li>
						</ul>
						<div class="clear"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>



		

<script>
	// add to cart js	
	<?php $this->beginBlock('product_review_rate') ?>
	$(document).ready(function(){
	   $(".star_ul li a").click(function(){
                $(".star_ul li a").removeClass('full_star');
                $(this).addClass('full_star');
                $num = $(this).attr('rel');
                for($i=1;$i<=$num;$i++){
                   $('.star_'+$i).addClass('full_star');
                }
                $('#review_price_field').val($num);
        });
	});
	 
	<?php $this->endBlock(); ?>  
	<?php $this->registerJs($this->blocks['product_review_rate'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

</script> 