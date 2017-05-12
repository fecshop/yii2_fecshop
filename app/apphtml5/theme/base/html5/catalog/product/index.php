
<div class="product_page">
	<div class="product_view">
		<input type="hidden" class="product_view_id" value="<?=  $_id ?>">
		<input type="hidden" class="sku" value="<?= $sku; ?>" />
		<input type="hidden" class="product_csrf" name="" value="" />
		
		<div class="media_img">
			<div class="media_img_content">
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
		
		<div class="product_info">
			<h1><?= $name; ?></h1>
			<div>
				<div class="rbc_cold">
					<span>
						<span class="average_rating"><?= Yii::$service->page->translate->__('Average rating'); ?> :</span>
						<span class="review_star review_star_<?= $reviw_rate_star_average ?>" style="font-weight:bold;" itemprop="average"></span>  
						
						<a external rel="nofollow" href="<?= Yii::$service->url->getUrl('catalog/reviewproduct/lists',['spu'=>$spu,'_id'=>$_id]); ?>">
							(<span itemprop="count"><?= $review_count ?> <?= Yii::$service->page->translate->__('reviews'); ?></span>)
						</a>
					</span>
				</div>
				<div class="clear"></div>
				<div class="item_code">
					<?= Yii::$service->page->translate->__('Item Code:'); ?>
					<span class="item_sku"><?= $sku; ?></span>
				</div>
				<div class="clear"></div>
			</div>
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
			<div class="product_info_section">
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
							'class' =>  'fecshop\app\apphtml5\modules\Catalog\block\product\CustomOption',
							'view'	=> 'catalog/product/index/custom_option.php',
							'custom_option' 	=> $custom_option,
							'attr_group'		=> $attr_group,
							'product_id'		=> $_id ,
							'middle_img_width' 	=> $media_size['middle_img_width'],
						];
						$optionsParam = [
							
						];
						
						
					?>
					<?= Yii::$service->page->widget->render($optionsView,$optionsParam); ?>
				
				</div>
				
				<div class="product_qty pg">
					<div class="label"><?= Yii::$service->page->translate->__('Qty:'); ?></div>
					<div class="rg">
						<select name="qty" class="qty">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							
						  </select>
					</div>
					<div class="clear"></div>
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
				
				<div class="addtocart">
					<a external href="javascript:void(0)" id="js_registBtn" class="button button-fill button-success redBtn addProductToCart">
						<em><span><i></i><?= Yii::$service->page->translate->__('Add To Cart'); ?></span></em>
					</a>
					
					<a external href="<?= Yii::$service->url->getUrl('catalog/favoriteproduct/add',['product_id'=>$_id]); ?>" id="js_registBtn" class="button button-fill button-success redBtn addProductToFavo">
						<em><span><i></i><?= Yii::$service->page->translate->__('Add to Favorites'); ?></span></em>
					</a>
					
					<div class="clear"></div>
				</div>
			</div>
			
		</div>
		
		
	</div>
	
	
	
	
	<div class="clear"></div>
	<div class="product_description_info">
		
		
		 <div class="buttons-tab">
			<a href="#tab1" class="tab-link active button"><?= Yii::$service->page->translate->__('Description'); ?></a>
			<a href="#tab2" class="tab-link button"><?= Yii::$service->page->translate->__('Reviews'); ?></a>
			<a href="#tab3" class="tab-link button"><?= Yii::$service->page->translate->__('Shipping & Payment'); ?></a>
		  </div>
		
		<div class="content-block">
			<div class="tabs">
			  <div id="tab1" class="tab active">
				<div class="content-block">
					<div class="text-description" style="">
						<?= $description; ?>
					</div>  
				</div>
			  </div>
			  <div id="tab2" class="tab">
				<div class="content-block">
					<div class="text-reviews" id="text-reviews" style="">
						<?php # review部分。
							$reviewView = [
								'class' 		=> 'fecshop\app\apphtml5\modules\Catalog\block\product\Review',
								'view'			=> 'catalog/product/index/review.php',
								'product_id' 	=> $_id,
								'spu'			=> $spu,
							];
							
						?>
						<?= Yii::$service->page->widget->render($reviewView,$reviewParam); ?>
					</div> 
				</div>
			  </div>
			  <div id="tab3" class="tab">
				<div class="content-block">
					<div class="text-questions" style="">
						<?php # payment部分。
							$paymentView = [
								'view'			=> 'catalog/product/index/payment.php',
							];
							
						?>
						<?= Yii::$service->page->widget->render($paymentView); ?>
					
					
						
					</div>  
				</div>
			  </div>
			</div>
		</div>
		
		
		
	</div>
	
	<div class="buy_also_buy_cer">
		<?php # buy also buy 部分。
			$buyAlsoBuyView = [
				'view'	=> 'catalog/product/index/buy_also_buy.php'
			];
			$buyAlsoBuyParam = [
				'products' => $buy_also_buy,
			];
		?>
		<?= Yii::$service->page->widget->render($buyAlsoBuyView,$buyAlsoBuyParam); ?>
	
	</div>
</div>

<script>
	// add to cart js	
	<?php $this->beginBlock('add_to_cart') ?>
	$(document).ready(function(){
		$(".addProductToCart").click(function(){
			i = 1;
			$(".product_custom_options .pg .rg ul.required").each(function(){
				val = $(this).find("li.current a.current").attr("value");
			    if(!val){
				    $(this).parent().parent().css("border","1px dashed #cc0000").css('padding-left','10px').css("margin-left","-10px");
					i = 0;
				}else{
					$(this).parent().parent().css("border","none").css('padding-left','0px').css("margin-left","0px");
			    
			    }
			});
			if(i){
				custom_option = new Object();
				$(".product_custom_options .pg .rg ul").each(function(){
					$m = $(this).find("li.current a.current");
					attr = $m.attr("attr");
					value = $m.attr("value");
					custom_option[attr] = value;
				});
				custom_option_json = JSON.stringify(custom_option);
				//alert(custom_option_json);
				sku = $(".sku").val();
				qty = $(".qty").val();
				qty = qty ? qty : 1;
				csrfName = $(".product_csrf").attr("name");
				csrfVal  = $(".product_csrf").val();
				
				$(".product_custom_options").val(custom_option_json);
				$(this).addClass("dataUp");
				// ajax 提交数据
				
				addToCartUrl = "<?= Yii::$service->url->getUrl('checkout/cart/add'); ?>";
				$data = {};
				$data['custom_option'] 	= custom_option_json;
				$data['product_id'] 	= "<?= $_id ?>";
				$data['qty'] 			= qty;
				$data[csrfName] 		= csrfVal;
				$.ajax({
					async:true,
					timeout: 6000,
					dataType: 'json', 
					type:'post',
					data: $data,
					url:addToCartUrl,
					success:function(data, textStatus){ 
						if(data.status == 'success'){
							items_count = data.items_count;
							$("#js_cart_items").html(items_count);
							window.location.href="<?= Yii::$service->url->getUrl("checkout/cart") ?>";
						}else{
							content = data.content;
							$(".addProductToCart").removeClass("dataUp");
							alert(content);
						}
						
					},
					error:function (XMLHttpRequest, textStatus, errorThrown){}
				});
				
			}
		});
	   
	   // product favorite
	   $("#divMyFavorite").click(function(){
			if($(this).hasClass('act')){
				alert("<?= Yii::$service->page->translate->__('You already favorite this product'); ?>");
			}else{
				url = $(this).attr('url');
				$(this).addClass('act');
				window.location.href = url;
			}
	   });
	   // 改变个数的时候，价格随之变动
	   $(".qty").blur(function(){
			// 如果全部选择完成，需要到ajax请求，得到最后的价格
			i = 1;
			$(".product_custom_options .pg .rg ul.required").each(function(){
				val = $(this).find("li.current a.current").attr("value");
				attr  = $(this).find("li.current a.current").attr("attr");
				if(!val){
				   i = 0;
				}
			});
			if(i){
				getCOUrl = "<?= Yii::$service->url->getUrl('catalog/product/getcoprice'); ?>";
				product_id = "<?=  $_id ?>";		
				qty = $(".qty").val();
				custom_option_sku = '';
				for(x in custom_option_arr){
					one = custom_option_arr[x];	
					j = 1;
					$(".product_custom_options .pg .rg ul.required").each(function(){
						val = $(this).find("li.current a.current").attr("value");
						attr  = $(this).find("li.current a.current").attr("attr");
						if(one[attr] != val){
							j = 0;
							//break;
						}
					});
					if(j){
						custom_option_sku = one['sku'];
						break;
					}
				}
				$data = {
					custom_option_sku:custom_option_sku,
					qty:qty,
					product_id:product_id
				};
				$.ajax({
					async:true,
					timeout: 6000,
					dataType: 'json', 
					type:'get',
					data: $data,
					url:getCOUrl,
					success:function(data, textStatus){ 
						$(".price_info").html(data.price);
					},
					error:function (XMLHttpRequest, textStatus, errorThrown){}
				});
			}
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
	$.init(); 
	<?php $this->endBlock(); ?>  
	<?php $this->registerJs($this->blocks['product_info_tab'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 
  
 