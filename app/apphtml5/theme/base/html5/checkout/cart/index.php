<?php
use fecshop\app\apphtml5\helper\Format;
?>
<div class="main container one-column">
	<div class="col-main">
	<?php if(is_array($cart_info) && !empty($cart_info)){   ?>
			    
		<div class="product_page">
			
			<div class="cart">
				<div class="page-title title-buttons">
					<div class="shopping-cart-img">
						Shopping Cart
					</div>
				</div>
				<div class="cart_info">
					<?php if(is_array($cart_info['products']) && (!empty($cart_info['products']))){ ?>
						<?php foreach($cart_info['products'] as $product_one){ ?>
							<div class="row">
								<div class="col-20">
									<a external href="<?= $product_one['url'] ?>" title="<?= $product_one['name'] ?>" class="product-image">
										<img src="<?= Yii::$service->product->image->getResize($product_one['image'],[150,150],false) ?>" alt="<?= $product_one['name'] ?>" width="75" height="75">
									</a>
								</div>
								<div class="col-80">
									<h2 class="product-name">
										<a external href="<?= $product_one['url'] ?>"><?= $product_one['name'] ?></a>
									</h2>
									<?php  if(is_array($product_one['custom_option_info'])){  ?>
									<ul class="options">
										<?php foreach($product_one['custom_option_info'] as $label => $val){  ?>
											
											<li><?= Yii::$service->page->translate->__(ucwords($label).':') ?><?= Yii::$service->page->translate->__($val) ?> </li>
											
										<?php }  ?>
									</ul>
									<div class="clear"></div>
									<?php }  ?>
									<span class="cart-price">
										<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($product_one['product_price']); ?></span>                
									</span>
									<div class="cart_qty">
										<a  externalhref="javascript:void(0)" class="cartqtydown changeitemqty" rel="<?= $product_one['item_id']; ?>" num="<?= $product_one['qty']; ?>">-</a>
										<input name="cart[qty]" size="4" title="Qty" class="input-text qty" rel="<?= $product_one['item_id']; ?>" maxlength="12" value="<?= $product_one['qty']; ?>">
										<a externalhref="javascript:void(0)" class="cartqtyup changeitemqty" rel="<?= $product_one['item_id']; ?>" num="<?= $product_one['qty']; ?>">+</a>
										<div class="clear"></div>
									</div>
									<a  externalhref="javascript:void(0)"  rel="<?= $product_one['item_id']; ?>" title="Remove item" class="btn-remove btn-remove2"><span class="icon icon-remove"></span></a>
									
								</div>
							</div>
						<?php } ?>
					<?php } ?>

				</div>
				
				<div class="cart-collaterals">
					<div class="col2-set">
						<div class="col-1">
						</div>
						<div class="col-2">
							<form id="discount-coupon-form" >
								<div class="discount">
									<h2><?= Yii::$service->page->translate->__('Discount Codes');?></h2>
									<div class="discount-form">
										<div class="input-box">
											<div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset"><input style="color:#777;" class="input-text" id="coupon_code" name="coupon_code" value=""></div>
										</div>
										<div class="buttons-coupon">
											<a external data-role="button" href="javascript:void(0)" onclick="cartcouponsubmit()" class="submitbutton ui-link ui-btn ui-shadow ui-corner-all" role="button"><span><span>Add Coupon</span></span> </a>
											
										</div>
									</div>
								</div>
							</form>
							<div class="clear"></div>
							
							
							
						</div>
					</div>
					<div class="cart_cost">
						<div class="row no-gutter">
							<div class="col-80"><?= Yii::$service->page->translate->__('Subtotal');?> :  </div>
							<div class="col-20"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['product_total']); ?></div>
						</div>
						
						<div class="row no-gutter">
							<div class="col-80"><?= Yii::$service->page->translate->__('Shipping Cost');?>  : </div>
							<div class="col-20"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['shipping_cost']); ?></div>
						</div>
						
						
						<div class="row no-gutter">
							<div class="col-80"><?= Yii::$service->page->translate->__('Discount');?>  :</div>
							<div class="col-20">-<?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['coupon_cost']); ?>%</div>
						</div>
						
						<div class="row no-gutter">
							<div class="col-80"><?= Yii::$service->page->translate->__('Grand Total');?>  :</div>
							<div class="col-20"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['grand_total']) ?></div>
						</div>
					</div>
					<div class="totals cart-totals">
						
						<div class="proceed_to_checkout">
							
							<div class="row no-gutter">
								<div class="col-50">
									<button onclick="location.href='<?= Yii::$service->url->getUrl('checkout/onepage');  ?>'" type="button" title="Proceed to Checkout" class="button btn-proceed-checkout btn-checkout"><span><span><?= Yii::$service->page->translate->__('Proceed to Pay');?></span></span></button>
							
								</div>
								<div class="col-50">
									<a  external class="express_paypal" href="<?= Yii::$service->url->getUrl('payment/paypal/express/start');    ?>">
										<img src="<?= Yii::$service->image->getImgUrl('/images/pay.png') ?>"  />
									</a>
									
								</div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				
			</div>
		</div>
	<?php }else{ ?>
		<div class="empty_cart">
		<?php
			$param = ['urlB' => '<a  external rel="nofollow" href="'.Yii::$service->url->getUrl('customer/account/login').'">','urlE' =>'</a>'];
		?>	
		
		<div id="empty_cart_info">
			<?= Yii::$service->page->translate->__('Your Shopping Cart is empty');?>
			<a external href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Start shopping now!');?></a>
			<br>
			<?= Yii::$service->page->translate->__('Please {urlB}log in{urlE} to view the products you have previously added to your Shopping Cart.',$param);?>
		</div>
  
  
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
			$.ajax({
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
		$.ajax({
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
		$.ajax({
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
	
	$(".add_coupon_submit").click(function(){
		coupon_code = $("#coupon_code").val();
		coupon_type = $(".couponType").val();
		coupon_url = "";
		if(coupon_type == 2){
			coupon_url = "<?=  Yii::$service->url->getUrl('checkout/cart/addcoupon'); ?>";
		}else if(coupon_type == 1){
			coupon_url = "<?=  Yii::$service->url->getUrl('checkout/cart/cancelcoupon'); ?>";
		}
		if(!coupon_code){
			alert("coupon can not empty!");
		}
		//coupon_url = $("#discount-coupon-form").attr("action");
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type:'post',
			data: {"coupon_code":coupon_code},
			url:coupon_url,
			success:function(data, textStatus){ 
				if(data.status == 'success'){
					window.location.href=currentUrl;
				}else if(data.content == 'nologin'){
					window.location.href="<?=  Yii::$service->url->getUrl('customer/account/login'); ?>";
				}else{
					$(".coupon_add_log").html(data.content);
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
			
		
	});
	
	
	
});

<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['changeCartInfo'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script>