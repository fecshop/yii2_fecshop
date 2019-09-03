<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<div class="product_page">
	<div class="product_view">
		<input type="hidden" class="product_view_id" value="<?=  $_id ?>">
		<input type="hidden" class="sku" value="<?= $sku; ?>" />
		<input type="hidden" class="product_csrf" name="" value="" />
		<div class="media_img">
			<div class="media_img_content">
				<?php # 图片部分。
					$imageParam = [
                        'media_size' => $media_size,
                        'image' => $image_thumbnails,
                    ];
				?>
				<?= Yii::$service->page->widget->render('product/image',$imageParam); ?>
			</div>
		</div>
		<div class="product_info">
			<h1><?= $name; ?></h1>
			<div>
				<div class="rbc_cold">
					<span>
						<span class="average_rating"><?= Yii::$service->page->translate->__('Average rating'); ?> :</span>
						<span class="review_star review_star_<?= round($reviw_rate_star_average) ?>" style="font-weight:bold;" itemprop="average"></span>
                        
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
                <?= Yii::$service->page->widget->render('product/price', ['price_info' => $price_info]); ?>
			</div>
			<div class="product_info_section" id="product_info_section">
				<div class="product_options">
					<?= Yii::$service->page->widget->render('product/options', ['options' => $options]); ?>
				</div>
				<div class="product_custom_options">
					
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
                        <?php if ($package_number >= 2) { ?>
                            X <?= $package_number ?> items
                        <?php } ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="tier_price_info">
					<?= Yii::$service->page->widget->render('product/tier_price', ['tier_price' => $tier_price]); ?>
				</div>
				<div class="addtocart">
					<a external href="javascript:void(0)" id="js_registBtn" class="button button-fill button-success redBtn addProductToCart">
						<em><span><i></i><?= Yii::$service->page->translate->__('Add To Cart'); ?></span></em>
					</a>

					<a href="javascript:void(0)" url="<?= Yii::$service->url->getUrl('catalog/favoriteproduct/add'); ?>"  product_id="<?= $_id?>" id="divMyFavorite" rel="nofollow"  external class="button button-fill button-success redBtn addProductToFavo">
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
                        <?php if(is_array($groupAttrArr)): ?>
                            <table>
                            <?php foreach($groupAttrArr as $k => $v): ?>
                                <tr>
                                    <td><?= Yii::$service->page->translate->__($k); ?></td>
                                    <td><?= Yii::$service->page->translate->__($v); ?></td></tr>
                            <?php endforeach; ?>
                            </table>
                            <br/>
                        <?php endif; ?>
						<?= $description; ?>
                        <div class="img-section">
                            <?php   if(is_array($image_detail)):  ?>
                                <?php foreach($image_detail as $image_detail_one): ?>
                                    <br/>
                                    <img class="lazy" src="<?= Yii::$service->image->getImgUrl('images/lazyload.gif');   ?>" data-src="<?= Yii::$service->product->image->getUrl($image_detail_one['image']); //->getResize($image_detail_one['image'],550,false) ?>"  />
                                    
                                <?php  endforeach;  ?>
                            <?php  endif;  ?>
                        </div>
					</div>  
				</div>
			  </div>
			  <div id="tab2" class="tab">
				<div class="content-block">
					<div class="text-reviews" id="text-reviews" style="">
						<?php # review部分。
							$reviewParam = [
								'product_id' 	=> $_id,
								'spu'			=> $spu,
                            ];
							$reviewParam['reviw_rate_star_info'] = $reviw_rate_star_info;
                           $reviewParam['review_count'] = $review_count;
                           $reviewParam['reviw_rate_star_average'] = $reviw_rate_star_average;
						?>
						<?= Yii::$service->page->widget->DiRender('product/review', $reviewParam); ?>
					</div> 
				</div>
			  </div>
			  <div id="tab3" class="tab">
				<div class="content-block">
					<div class="text-questions" style="">
						<?= Yii::$service->page->widget->render('product/payment'); ?>
					
					</div>  
				</div>
			  </div>
			</div>
		</div>
	</div>
	<div class="buy_also_buy_cer">
        <?= Yii::$service->page->widget->render('product/buy_also_buy', ['products' => $buy_also_buy]); ?>
	</div>
</div>
<script>
	// add to cart js	
	<?php $this->beginBlock('add_to_cart') ?>
	$(document).ready(function(){
		productAjaxUrl = "<?= Yii::$service->url->getUrl('customer/ajax/product');  ?>";
		product_id   = "<?=  $_id ?>";
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json',
			type:'get',
			data: {
				// 'currentUrl':window.location.href,
				'product_id':product_id
			},
			url:productAjaxUrl,
			success:function(data, textStatus){
				if(data.favorite){
					$("#divMyFavorite").addClass("act");
				}
				if(data.csrfName && data.csrfVal && data.product_id){
					$(".product_csrf").attr("name",data.csrfName);
					$(".product_csrf").val(data.csrfVal);
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});

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
				if (csrfName && csrfVal) {
					$data[csrfName] 		= csrfVal;
				}
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
				$(this).addClass('act');
				url = $(this).attr('url');
				product_id = $(this).attr('product_id');
				csrfName = $(".product_csrf").attr("name");
				csrfVal  = $(".product_csrf").val();
				param = {};
				param["product_id"] = product_id;
				param[csrfName] = csrfVal;
				doPost(url, param);
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
    $.init(); 
	<?php $this->endBlock(); ?> 
	<?php $this->registerJs($this->blocks['add_to_cart'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 
<?= Yii::$service->page->trace->getTraceProductJsCode($sku)  ?>
  
 