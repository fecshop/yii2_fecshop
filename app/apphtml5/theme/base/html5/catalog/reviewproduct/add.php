<div class="main container one-column">
<?= Yii::$service->page->widget->render('flashmessage'); ?>
	<div class="col-main">
		<div class="std">
			<div class="review_add">
				<div class="row">
					<div class="col-20">
						<a external href="<?= $url ?>">
							<img src="<?= Yii::$service->product->image->getResize($main_img,[150,150],false) ?>">
						</a>
					</div>
					<div class="col-80">
						<a external class="product_name" href="<?= $url ?>">
							<?= $product_name ?>
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
				
			</div>
			<div class="product-Reviews_bottom">
				<form method="post" action="">
					<?= \fec\helpers\CRequest::getCsrfInputHtml();  ?>
					<input name="editForm[product_spu]" value="<?= $spu ?>" id="product_spu" type="hidden">
					<input name="editForm[product_id]" value="<?= $product_id ?>" id="product_id" type="hidden">
						
					<div class="list-block" >
						<ul>			
							<li>
								<div class="item-content">
									<div class="item-media">
										<i class="icon icon-form-name"></i>
									</div>
									<div class="item-inner">
										<div class="item-title label">
											<?= Yii::$service->page->translate->__('Name');?><em class="product-description_em">*</em>
										</div>
										<input name="editForm[name]" id="review_email_field" type="text" placeholder="Your name"  class="input-text  review-input-text required-entry" value="<?=  $editForm['name'] ? $editForm['name'] : $customer_name ?>">
									</div>
								</div>
							</li>
											
							<li>
								<div class="item-content">
									<div class="item-media">
										<i class="icon icon-form-name"></i>
									</div>
									<div class="item-inner">
										<div class="item-title label">
											<?= Yii::$service->page->translate->__('Summary');?><em class="product-description_em">*</em>
										</div>
										<input placeholder=" Summary of Your Review*" name="editForm[summary]" id="review_title_field" class="input-text  review-input-text required-entry" value="<?=  $editForm['summary'] ?>" type="text">
									
									</div>
								</div>
							</li>
							
							<li>
								<div class="item-content">
								  <div class="item-media"><i class="icon icon-form-name"></i></div>
								  <div class="item-inner">
									<div class="item-title label">
										<?= Yii::$service->page->translate->__('Rate');?></strong><em class="product-description_em">*</em>
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
								</div>
							  </li> 
							
							<li class="align-top">
								<div class="item-content">
									<div class="item-media">
										<i class="icon icon-form-comment"></i>
									</div>
									<div class="item-inner">
										<div class="item-title label"><?= Yii::$service->page->translate->__('Review');?></div>
											<textarea placeholder="Your review content" name="editForm[review_content]" id="review_review_field"><?=  $editForm['review_content'] ?></textarea>
									</div>
								</div>
							</li>
							<?php if($add_captcha){  ?>
							<li>
								<div class="item-content">
									<div class="item-media">
										<i class="icon icon-form-name"></i>
									</div>
									<div class="item-inner">
										<div class="item-title label">
											<?= Yii::$service->page->translate->__('Captcha');?><em class="product-description_em">*</em>
										</div>
										<div class="input-box login-captcha">
											<input type="text" name="editForm[captcha]" value="" size=10 class="login-captcha-input"> 
											<img class="login-captcha-img"  title="点击刷新" src="<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>" align="absbottom" onclick="this.src='<?= Yii::$service->url->getUrl('site/helper/captcha'); ?>?'+Math.random();"></img>
											<span class="icon icon-refresh"></span>
											
										</div>
										<script>
										<?php $this->beginBlock('login_captcha_onclick_refulsh') ?>  
										$(document).ready(function(){
											$(".icon-refresh").click(function(){
												$(this).parent().find("img").click();
											});
										});
										<?php $this->endBlock(); ?>  
										</script>  
										<?php $this->registerJs($this->blocks['login_captcha_onclick_refulsh'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

									</div>
								</div>
							</li>
							<?php }  ?>
						</ul>
						<div class="review_submit">
							<button type="submit" title="Submit Review" class="button" id="m_top_10" onclick="return check_review()"><span><span><?= Yii::$service->page->translate->__('Submit');?></span></span></button>
						</div>
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