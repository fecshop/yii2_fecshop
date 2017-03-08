<div class="main container one-column">
<?= Yii::$service->page->widget->render('flashmessage'); ?>
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
						<a href="<?= $url  ?>"><?= $product_name ?></a> 
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
				<form method="post" action="">
					<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
					<input name="editForm[product_spu]" value="<?= $spu ?>" id="product_spu" type="hidden">
					<input name="editForm[product_id]" value="<?= $product_id ?>" id="product_id" type="hidden">
					
					<div class="h-30">
						<div class="lh30_f">
							<strong><?= Yii::$service->page->translate->__('Rate');?></strong><em class="product-description_em">*</em>&nbsp;&nbsp;&nbsp;&nbsp;
						</div>
						<div class="lh30_f">
							<input name="editForm[rate_star]" value="5" id="review_price_field" type="hidden">
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
								<strong><?= Yii::$service->page->translate->__('Your Name');?></strong><em class="product-description_em">*</em></label>
								<div class="input-box">
									
									<input name="editForm[name]" id="review_email_field" class="input-text  review-input-text required-entry" value="<?=  $editForm['name'] ? $editForm['name'] : $customer_name ?>" type="text">
								</div>
								<span class="review_span_error" id="review_email_span"></span>
							</li>
							<li>
								<label for="nickname_field" class="required">
								<strong><?= Yii::$service->page->translate->__('Summary of Your Review');?></strong><em class="product-description_em">*</em></label>
								<div class="input-box">
									<input name="editForm[summary]" id="review_title_field" class="input-text  review-input-text required-entry" value="<?=  $editForm['summary'] ?>" type="text">
								</div>
								<span class="review_span_error" id="review_title_span"></span>
							</li>
							
							
							<li id="review_textarea" style="width: 722px;">
								<label for="nickname_field" class="required"><strong><?= Yii::$service->page->translate->__('Review');?></strong><em class="product-description_em">*</em></label>
								<div class="input-box">
									<textarea name="editForm[review_content]" id="review_review_field"><?=  $editForm['review_content'] ?></textarea>
								</div>
								<span class="review_span_error" id="review_review_span"></span>
							</li>
							
							<?php if($add_captcha){  ?>
							<li style="width:700px;">
								<label for="captcha" class="required"><em>*</em><?= Yii::$service->page->translate->__('Captcha');?></label>
								<div class="input-box login-captcha">
									<input type="text" name="editForm[captcha]" value="" size=10 class="login-captcha-input"> 
									<img class="login-captcha-img"  title="点击刷新" src="<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>" align="absbottom" onclick="this.src='<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?'+Math.random();"></img>
									<i class="refresh-icon"></i>
								</div>
								<script>
								<?php $this->beginBlock('login_captcha_onclick_refulsh') ?>  
								$(document).ready(function(){
									$(".refresh-icon").click(function(){
										$(this).parent().find("img").click();
									});
								});
								<?php $this->endBlock(); ?>  
								</script>  
								<?php $this->registerJs($this->blocks['login_captcha_onclick_refulsh'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

							</li>
							<?php }  ?>
							<li>
							
								<button type="submit" title="Submit Review" class="button" id="m_top_10" onclick="return check_review()"><span><span><?= Yii::$service->page->translate->__('Submit');?></span></span></button>
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