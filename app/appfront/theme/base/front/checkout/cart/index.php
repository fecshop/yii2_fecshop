<?php
use fecshop\app\appfront\helper\Format;
?>
<div class="main container one-column">
	<div class="col-main">
	<?php if(is_array($cart_info) && !empty($cart_info)){   ?>
			    
		<div class="product_page">
			
			<div class="cart">
				<div class="page-title title-buttons">
					<div class="shopping-cart-img">
					
					</div>
				</div>
				<div>
					<?php if(is_array($cart_info['products']) && (!empty($cart_info['products']))){ ?>
								
					<div class="shopping-cart-div">
						<div class="shopping-cart-ab">
						</div>
						<table id="shopping-cart-table" class="data-table cart-table">
							<colgroup>
								<col width="1">
								<col width="">
								<col width="1">
								<col width="10">
								<col width="1">
								<col width="1">
								<col width="1">

							</colgroup>
							<thead>
								<tr class="first last">
									<th rowspan="1">&nbsp;</th>
									<th rowspan="1"><span class="nobr">Product Name</span></th>
									<th class="a-center" colspan="1"><span class="nobr">Unit Price</span></th>
									<th rowspan="1" class="a-center">Qty</th>
									<th class="a-center" colspan="1">Subtotal</th>
									<th rowspan="1" class="a-center">&nbsp;</th>
								</tr>
												</thead>
							<tfoot>
								
							</tfoot>
							<tbody>
								<?php foreach($cart_info['products'] as $product_one){ ?>
								
								<tr class="first last odd">
									<td>
										<a href="<?= $product_one['url'] ?>" title="<?= $product_one['name'] ?>" class="product-image">
										<img src="<?= Yii::$service->product->image->getResize($product_one['image'],[100,100],false) ?>" alt="2121" width="75" height="75">
										</a>
									</td>
									
									<td>
										<h2 class="product-name">
											<a href="<?= $product_one['url'] ?>"><?= $product_one['name'] ?></a>
										</h2>
										<?php  if(is_array($product_one['custom_option_info'])){  ?>
										<ul>
											<?php foreach($product_one['custom_option_info'] as $label => $val){  ?>
												
												<li><?= $label ?>:<?= $val ?> </li>
												
											<?php }  ?>
										</ul>
										<?php }  ?>
									</td>
									
									
									<td class="a-right">
										<span class="cart-price">
											<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($product_one['product_price']); ?></span>                
										</span>

									</td>
				   
									<td class="a-center">
										<div style="width:80px;">
											<a href="javascript:void(0)" class="cartqtydown changeitemqty" rel="<?= $product_one['item_id']; ?>" num="<?= $product_one['qty']; ?>"></a>
											<input name="cart[qty]" size="4" title="Qty" class="input-text qty" rel="<?= $product_one['item_id']; ?>" maxlength="12" value="<?= $product_one['qty']; ?>">
											<a href="javascript:void(0)" class="cartqtyup changeitemqty" rel="<?= $product_one['item_id']; ?>" num="<?= $product_one['qty']; ?>"></a>
											<div class="clear"></div>
										</div>
									</td>

				
									<td class="a-right">
										<span class="cart-price">
											<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($product_one['product_row_price']); ?></span>                            
										</span>
									</td>
									<td class="a-center last">
										<a href="javascript:void(0)"  rel="<?= $product_one['item_id']; ?>" title="Remove item" class="btn-remove btn-remove2">Remove item</a>
									</td>
								</tr>
								<?php  }  ?>
								
							</tbody>
						</table>
					</div>
					<?php  }  ?>
				</div>
				
				<div class="cart-collaterals">
					<div class="col2-set">
						<div class="col-1">
						</div>
						<div class="col-2">
							<form id="discount-coupon-form" action="http://www.intosmile.com/checkout/cart/couponpost" method="post">
								<div class="discount">
									<h2>Discount Codes</h2>
									<div class="discount-form">
										<label for="coupon_code">Enter your coupon code if you have one.</label>
										<input name="_csrf" id="csrf_coupone" value="" type="hidden">
										<div class="input-box">
											<input style="color:#777;" class="input-text" id="coupon_code" name="coupon_code" value="">
										</div>
										<div class="buttons-cou">
											<a href="javascript:void(0)" onclick="cartcouponsubmit()" class="submitbutton"><span><span>Add Coupon</span></span> </a>
											
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</form>
							
						</div>
					</div>
					<div class="totals cart-totals">
						<div class="process_total">
							<table id="shopping-cart-totals-table">
								<colgroup>
									<col>
									<col width="1">
								</colgroup>
								
								<tbody>
									<tr>
										<td style="" class="a-left" colspan="1">
											Item Subtotal:    </td>
										<td style="" class="a-right">
											<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['product_total']); ?></span>    </td>
									</tr><tr>
										<td style="" class="a-left" colspan="1">
											Shipping    </td>
										<td style="" class="a-right">
											<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['shipping_cost']); ?></span>    </td>
									</tr><tr>
										<td style="" class="a-left" colspan="1">
											Coupon:    </td>
										<td style="" class="a-right">
											<span class="price">-<?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['coupon_cost']); ?></span>    </td>
									</tr>
								</tbody>
							</table>
							<table id="shopping-cart-totals-table2">
								<colgroup>
									<col>
									<col width="90">
								</colgroup>
								<tbody>
									<tr>
										<td style="" class="a-left" colspan="1">
											<strong>Grand Total</strong>
										</td>
										<td style="" class="a-right">
											<strong><span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['grand_total']) ?></span></strong>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="proceed_to_checkout">
							
							<button onclick="location.href='http://www.intosmile.com/checkout/onepage'" type="button" title="Proceed to Checkout" class="button btn-proceed-checkout btn-checkout"><span><span>Proceed to Pay</span></span></button>
							
							<span class="or">- OR - </span>
							<a class="express_paypal" href="<?= Yii::$service->url->getUrl('paypal/express/start');    ?>">
							
							</a>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				
			</div>
		</div>
	<?php }else{ ?>
		<div class="empty_cart">
			Your Cart is empty, You Can <a rel="nofollow" href="<?= Yii::$service->url->homeUrl() ?>">Click Here to Home Page</a>
		
		
		
		</div>
	<?php  } ?>
	</div>
</div>

<script>
	// add to cart js	
<?php $this->beginBlock('changeCartInfo') ?>
$(document).ready(function(){
	currentUrl = "<?= Yii::$service->url->getUrl('checkout/cart') ?>"
	updateCartInfoUrl = "<?= Yii::$service->url->getUrl('checkout/cart/updateinfo') ?>"
	$(".cartqtydown").click(function(){
		$item_id = $(this).attr("rel");
		num = $(this).attr("num");
		if(num > 1){
			$data = {
				item_id:$item_id,
				up_type:"less_one"
			};
			jQuery.ajax({
				async:true,
				timeout: 6000,
				dataType: 'json', 
				type:'get',
				data: $data,
				url:updateCartInfoUrl,
				success:function(data, textStatus){ 
					if(data.status == 'success'){
						window.location.href=currentUrl;
					}
				},
				error:function (XMLHttpRequest, textStatus, errorThrown){}
			});
		}
	});
	
	$(".cartqtyup").click(function(){
		$item_id = $(this).attr("rel");
		$data = {
			item_id:$item_id,
			up_type:"add_one"
		};
		jQuery.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type:'get',
			data: $data,
			url:updateCartInfoUrl,
			success:function(data, textStatus){ 
				if(data.status == 'success'){
					window.location.href=currentUrl;
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
		
	});
	
	$(".btn-remove").click(function(){
		$item_id = $(this).attr("rel");
		
		$data = {
			item_id:$item_id,
			up_type:"remove"
		};
		jQuery.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type:'get',
			data: $data,
			url:updateCartInfoUrl,
			success:function(data, textStatus){ 
				if(data.status == 'success'){
					window.location.href=currentUrl;
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
		
	});
	
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['changeCartInfo'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>