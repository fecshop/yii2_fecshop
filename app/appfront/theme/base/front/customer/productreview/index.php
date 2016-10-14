<div class="main container two-columns-left">
	
	<div class="col-main account_center">
		
		<div class="std">
			<div style="margin:19px 0 0">
				<div class="page-title">
					<h2>My Product Reviews</h2>
				</div>
				<div style="width:100%;min-height:500px;">
					<div style="width:100%;">
						<ul id="review_description" style="padding:0px;">
							<li style="width:100%;min-height:160px;">
								<div class="review_description_left">
									<a target="_blank" href="http://www.intosmile.com/prise-crane-de-cristal-creative-glass-coupe-novetly.html">
										<p style="text-align:center;">
											<img src="http://img.intosmile.com/media/catalog/product/cache/110/110/710aa4d924f51b2be23e7fd5eda0d13f/f/i/file.jpg" style="width:110px;height:110px;">
										</p>
										<span>
											Creative Crystal Skull Shot Glass Cup Novetly
										</span>
									</a>
								</div>
								<div class="review_description_right" style="width:600px;">
									<span class="review_description_right_span">
										<b>summary your review</b>
									</span>
									<div class="review_description_centen">
										review content<br>
									</div>
								</div>
							</li>
							<li style="width:100%;min-height:160px;">
								<div class="review_description_left">
									
									<a target="_blank" href="http://www.intosmile.com/prise-crane-de-cristal-creative-glass-coupe-novetly.html">
									<p style="text-align:center;"><img src="http://img.intosmile.com/media/catalog/product/cache/110/110/710aa4d924f51b2be23e7fd5eda0d13f/f/i/file.jpg" style="width:110px;height:110px;"></p>
									<span>
										Creative Crystal Skull Shot Glass Cup Novetly 								</span>
									</a>
								</div>
								<div class="review_description_right" style="width:600px;">
									<span class="review_description_right_span"><b>yyy</b></span>
									<div class="review_description_centen">dddd<br></div>
								</div>
							</li>
						</ul>
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
	