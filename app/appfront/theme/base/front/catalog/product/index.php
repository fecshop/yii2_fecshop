<div class="main container one-column">
	<div class="col-main">
		<div class="product_page">
			<div class="product_view">
				
				<div class="product_info">
					<h1><?= $name; ?></h1>
					<div>
						<div class="rbc_cold">
							<span>
								<span class="average_rating">Average rating :</span>
								<span class="review_star review_star_4" style="font-weight:bold;" itemprop="average">0</span>  
								
								<a rel="nofollow" href="http://www.intosmile.com/fashion-solid-color-long-sleeve-round-neck-dress-1-1-1-1-1.html">
									(<span itemprop="count">0 reviews</span>)
								</a>
							</span>
						</div>
					</div>
					<div class="item_code">Item Code: <?= $sku; ?></div>
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
					<div style="height:300px;">
						<div class="product_options">
							<?php # options部分
								$optionsView = [
									'view'	=> 'catalog/product/index/options.php'
								];
								$optionsParam = [
									'options' => $options,
								];
							?>
							<?= Yii::$service->page->widget->render($optionsView,$optionsParam); ?>
						
						</div>
						<div class="product_custom_options">
							<?php # custom options部分
								$optionsView = [
									'view'	=> 'catalog/product/index/custom_option.php'
								];
								$optionsParam = [
									'custom_option' => $custom_option,
								];
							?>
							<?= Yii::$service->page->widget->render($optionsView,$optionsParam); ?>
						
						</div>
						
						<div class="product_qty pg">
							<div class="label">Qty:</div>
							<div class="rg">
								<input type="text" name="qty" class="qty" value="1" />
							</div>
							<div class="clear"></div>
						</div>
						
						<div class="addtocart">
							<button type="button" id="js_registBtn" class="redBtn"><em><span><i></i>Add To Cart</span></em></button>
							
							<div class="myFavorite_nohove" id="myFavorite">
								<a href="javascript:void(0);" class="addheart" id="divMyFavorite" rel="nofollow" onclick="addFavorite(this)" value="http://www.intosmile.fr/favorite/product/add?sku=gyxh0682" url="">
									Add to Favorites
								</a>				
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div class="tier_price_info">
						<?php # tier price 部分。
							$priceView = [
								'view'	=> 'catalog/product/index/tier_price.php'
							];
							$priceParam = [
								'tier_price' => $tier_price,
							];
						?>
						<?= Yii::$service->page->widget->render($priceView,$priceParam); ?>
					
					</div>
				</div>
				<div class="media_img">
					<div class="col-left ">
						<?php # 图片部分。
							$imageView = [
								'view'	=> 'catalog/product/index/image.php'
							];
							$imageParam = [
								'media_size' => $media_size,
								'image' => $image,
								'productImgMagnifier' => $productImgMagnifier,
							];
						?>
						<?= Yii::$service->page->widget->render($imageView,$imageParam); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="product_description_info">
				<div class="nav" id="nav-container">  
					<ul id="nav-box">
						<li  class="nav_tab cur" rel="description">DESCRIPTION</li>  
						<li  class="nav_tab" rel="reviews">REVIEWS</li>  
						<li  class="nav_tab" rel="questions">SHIPPING & PAYMENT</li>  
						<li   class="nav_tab" rel="wholesale">WHOLESALE</li>  
					</ul>    
				</div>  
				<div id="text">  
					<div class="text-description" style="">
						<?= $description; ?>
					</div>  
					<div class="text-reviews" style="">
						<?php # review部分。
							$reviewView = [
								'class' 		=> 'fecshop\app\appfront\modules\Catalog\block\product\Review',
								'view'			=> 'catalog/product/index/review.php',
								'product_id' 	=> $_id,
								'spu'			=> $spu,
							];
							
						?>
						<?= Yii::$service->page->widget->render($reviewView,$reviewParam); ?>
					</div>  
					<div class="text-questions" style="width:100%;height:500px;background:blue;text-align:center;">text3</div>   
					<div class="text-wholesale" style="width:100%;height:500px;background:yellow;text-align:center;">text2</div>  
					
				</div> 
			</div>
		</div>
		<div class="proList">
		</div>
	</div>
</div>

<script>
	// add to cart js	
	<?php $this->beginBlock('add_to_cart') ?>
	$(document).ready(function(){
	   $("#js_registBtn").click(function(){
		   $(this).addClass("dataUp");
	   });
	});
	<?php $this->endBlock(); ?> 
	<?php $this->registerJs($this->blocks['add_to_cart'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

	//tab 切换js
	<?php $this->beginBlock('product_info_tab') ?> 
	var navContainer = document.getElementById("nav-container");  
	var navBox = document.getElementById("nav-box");  
	var text = document.getElementById("text");  
	var navBoxChild = navBox.children;  
	var textChild = text.children;  
	var num = navContainer.offsetTop;  
	var a = navContainer.offsetHeight;  
	window.onscroll = function(){  
		var scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;  
		if(scrollTop >= num){  
			navContainer.className = "nav fixed";  
			text.style.paddingTop = a +"px";  
		}else{  
			navContainer.className = "nav";  
			text.style.paddingTop = "";  
		}  
		//当导航与相应文档接触的时候自动切换  
		//method1  
		for(var i=0;i<navBoxChild.length;i++){  
			if( scrollTop + a >= textChild[i].offsetTop){  
				for(var j=0;j<navBoxChild.length;j++){  
					navBoxChild[j].className = "";  
				}  
				navBoxChild[i].className = "cur";  
		   }  
		}  
	};  
	for(var i=0;i<navBoxChild.length;i++){  
		var interval;  
		navBoxChild[i].index = i;  
		navBoxChild[i].onclick = function(){  
			var self = this;  
			clearInterval(interval); 
			
			if(document.body.scrollTop){
				scroll = document.body;
			}else if(document.documentElement.scrollTop){
				scroll = document.documentElement;
			}

			interval = setInterval(function(){  
				if(scroll.scrollTop + a<=textChild[self.index].offsetTop){  
					scroll.scrollTop += 40;  
					if(scroll.scrollTop + a>=textChild[self.index].offsetTop){  
						scroll.scrollTop = textChild[self.index].offsetTop-a;  
						clearInterval(interval);  
					}  
				}else{  
					scroll.scrollTop /= 1.1;  
					if(scroll.scrollTop + a<=textChild[self.index].offsetTop){  
						scroll.scrollTop = textChild[self.index].offsetTop-a;  
						clearInterval(interval);  
					}  
				}  
			},40);  
		};  
	}  
	<?php $this->endBlock(); ?>  
	<?php $this->registerJs($this->blocks['product_info_tab'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 
  