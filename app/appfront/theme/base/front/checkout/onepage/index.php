<div class="main container one-column">
	<div class="col-main">
	
		<form class="coupon_code_form" action="http://www.intosmile.com/checkout/onepage/coupon" method="post">
			<input class="thiscsrf" value="dTJOYlNrbGktV3s0PgxYBSBELw5hCRoYO1EdGzwuQSA8ShEyKwInXQ==" name="_csrf" type="hidden">	<input name="coupon_code" class="coupon_code" type="hidden">
		</form>
			
		<form action="<?= Yii::$service->url->getUrl('checkout/onepage'); ?>" method="post" id="onestepcheckout-form">
			<input class="thiscsrf" value="dTJOYlNrbGktV3s0PgxYBSBELw5hCRoYO1EdGzwuQSA8ShEyKwInXQ==" name="_csrf" type="hidden">	
			<fieldset style="margin: 0;" class="group-select">
				<h1 class="onestepcheckout-title">Checkout</h1>
				<p class="onestepcheckout-description">Welcome to the checkout. Fill in the fields below to complete your purchase!</p>
				<p class="onestepcheckout-login-link">
					<a href="<?= Yii::$service->url->getUrl('customer/account/login'); ?>" id="onestepcheckout-login-link">Already registered? Click here to login.</a>
				</p>
				<div class="onestepcheckout-threecolumns checkoutcontainer onestepcheckout-skin-generic onestepcheckout-enterprise">
					<div class="onestepcheckout-column-left">
						<?php # address 部门
							//echo $address_view_file;
							$addressView = [
								'view'	=> $address_view_file,
							];
							//var_dump($address_list);
							$addressParam = [
								'cart_address_id' 	=> $cart_address_id,
								'address_list'	  	=> $address_list,
								'customer_info'	  	=> $customer_info,
								'country_select'  	=> $country_select,
								'state_html'  	  	=> $state_html,
								//'payments' => $payments,
								//'current_payment_mothod' => $current_payment_mothod,
							];
						?>
						<?= Yii::$service->page->widget->render($addressView,$addressParam); ?>
					
					</div>

					<div class="onestepcheckout-column-middle">
						<?php # shipping部分
							$shippingView = [
								'view'	=> 'checkout/onepage/index/shipping.php'
							];
							$shippingParam = [
								'shippings' => $shippings,
							];
						?>
						<?= Yii::$service->page->widget->render($shippingView,$shippingParam); ?>
					
				
				
						<?php # payment部分
							$paymentView = [
								'view'	=> 'checkout/onepage/index/payment.php'
							];
							$paymentParam = [
								'payments' => $payments,
								'current_payment_mothod' => $current_payment_mothod,
							];
						?>
						<?= Yii::$service->page->widget->render($paymentView,$paymentParam); ?>
					
							
						<div class="onestepcheckout-coupons">
							<div style="display: none;" id="coupon-notice"></div>
							<div class="op_block_title">Coupon codes (optional)</div>
							<label for="id_couponcode">Enter your coupon code if you have one.</label>
							
							<input type="hidden" class="couponType"  value="<?= $cart_info['coupon_code'] ? 1 : 2 ; ?>"  />
							<input style="color:#777;" class="input-text" id="id_couponcode" name="coupon_code" value="<?= $cart_info['coupon_code']; ?>">
							<br>
							<button style="" type="button" class="submitbutton add_coupon_submit" id="onestepcheckout-coupon-add"><?= $cart_info['coupon_code'] ? 'Cancel Coupon' : 'Add Coupon' ; ?></button>
							<div class="clear"></div>
							<div class="coupon_add_log"></div>
						</div>
						
						
					</div>

					<div class="onestepcheckout-column-right">
						<?php # review order部分
							$reviewOrderView = [
								'view'	=> 'checkout/onepage/index/review_order.php'
							];
							$reviewOrderParam = [
								'cart_info' => $cart_info,
								'currency_info' => $currency_info,
							];
						?>
						<?= Yii::$service->page->widget->render($reviewOrderView,$reviewOrderParam); ?>
					
					</div>
					<div style="clear: both;">&nbsp;</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<script>
<?php $this->beginBlock('placeOrder') ?>
	
	function ajaxreflush(){
		shipping_method = $("input[name=shipping_method]:checked").val();
		//alert(shipping_method);
		country = $(".billing_country").val();
		address_id = $(".address_list").val();
		$(".onestepcheckout-summary").html('<div style="text-align:center;min-height:40px;"><img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /></div>');
		ajaxurl = "http://www.intosmile.com/checkout/onepage/getshipping";
		state   = $(".inputstate").val();
		$.ajax({
			async:false,
			timeout: 8000,
			dataType: 'json', 
			type:'get',
			data: {
					'country':country,
					'shipping_method':shipping_method,
					'address_id':address_id,
					'state':state,
					},
			url:ajaxurl,
			success:function(data, textStatus){ 
				
				$(".onestepcheckout-summary").html(data.total_html)
				$(".input-state").html(data.state);
					
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){
					
			}
		});
	}	
	$(document).ready(function(){
		currentUrl = "<?= Yii::$service->url->getUrl('checkout/onepage') ?>"
		$(".add_coupon_submit").click(function(){
			coupon_code = $("#id_couponcode").val();
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
		
		$("#id_create_account").click(function(){
			
			if($(this).is(':checked')){
				$("#onestepcheckout-li-password").show();
				$("#onestepcheckout-li-password input").addClass("required-entry");
			}else{
				$("#onestepcheckout-li-password").hide();
				$("#onestepcheckout-li-password input").removeClass("required-entry");
			}
		});
		
		
		$("#onestepcheckout-place-order").click(function(){
			$(".validation-advice").remove();
			i = 0;
			address_list = $(".address_list").val();
			if(address_list){
				$(".onestepcheckout-place-order span").show();
				$("#onestepcheckout-form").submit();
			}else{
				$("#onestepcheckout-form .required-entry").each(function(){
					value = $(this).val();
					if(!value){
						i++;
						$(this).after('<div style=""  class="validation-advice">This is a required field.</div>');
					}
				});
				if(!i){
					$(".onestepcheckout-place-order span").show();
					$("#onestepcheckout-form").submit();
				}
			}
		});
		
		$(".address_list").change(function(){
			val = $(this).val();
			if(!val){
				$(".billing_address_list_new").show();
				 
				$(".save_in_address_book").attr("checked","checked");
				ajaxreflush();
				
			}else{
				$(".billing_address_list_new").hide();
				$(".save_in_address_book").attr("checked",false);
				addressid = $(this).val();
				
				if(addressid){
					
					$(".onestepcheckout-shipping-method-block").html('<div style="text-align:center;min-height:40px;"><img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /></div>');
					$(".onestepcheckout-summary").html('<div style="text-align:center;min-height:40px;"><img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /></div>');
					shipping_method = $("input[name=shipping_method]:checked").val();
					ajaxurl = "http://www.intosmile.com/checkout/onepage/changeaddress";
					$.ajax({
						async:false,
						timeout: 8000,
						dataType: 'json', 
						type:'get',
						data: {
								'address_id':addressid,
								'shipping_method':shipping_method
								},
						url:ajaxurl,
						success:function(data, textStatus){ 
							
							$(".onestepcheckout-shipping-method").html(data.shippint_html);
							$(".onestepcheckout-summary").html(data.total_html)
							//$(".onestepcheckout-summary tbody").html(data.product_html);
							//$(".onestepcheckout-totals tbody").html(data.total_html);
								
						},
						error:function (XMLHttpRequest, textStatus, errorThrown){
								
						}
					});
				}
			}
		});
		
		//$(document).on(".billing_country","click",function(){
		$(".billing_country").change(function(){
			country = $(this).val();
			//state   = $(".address_state").val();
			shipping_method = $("input[name=shipping_method]:checked").val();
			//alert(shipping_method);
			
			$(".onestepcheckout-shipping-method-block").html('<div style="text-align:center;min-height:40px;"><img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /></div>');
			$(".onestepcheckout-summary").html('<div style="text-align:center;min-height:40px;"><img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /></div>');
			ajaxurl = "<?= Yii::$service->url->getUrl('checkout/onepage/changecountry'); ?>";
			$.ajax({
				async:false,
				timeout: 8000,
				dataType: 'json', 
				type:'get',
				data: {
						'country':country,
						'shipping_method':shipping_method,
						//'state':state
						},
				url:ajaxurl,
				success:function(data, textStatus){ 
					
					//$(".onestepcheckout-shipping-method").html(data.shippint_html);
					//$(".onestepcheckout-summary").html(data.total_html);
					$(".state_html").html(data.state);
					//$(".onestepcheckout-summary tbody").html(data.product_html);
					//$(".onestepcheckout-totals tbody").html(data.total_html);
						
				},
				error:function (XMLHttpRequest, textStatus, errorThrown){
						
				}
			});
				
		});
		
	
		$(".onestepcheckout-column-middle").off("click").on("click","input[name=shipping_method]",function(){
			ajaxreflush();
			
		});
		
		
		
		$("#billing_address_list").off("change").on("change",".selectstate",function(){
			value = $(".selectstate option:selected").text();
			if($(".selectstate").val()){
				$(".inputstate").val(value);
			}else{
				$(".inputstate").val('');
			}
		});
		
		
					
					
		

	});	
<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['placeOrder'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

</script>
    

	