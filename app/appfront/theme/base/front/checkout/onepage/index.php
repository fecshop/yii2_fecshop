<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
?>
<div class="main container one-column">
	<div class="col-main">
        <?= Yii::$service->page->widget->render('base/breadcrumbs',$this); ?>
		<?= Yii::$service->page->widget->render('base/flashmessage'); ?>
		<form action="<?= Yii::$service->url->getUrl('checkout/onepage'); ?>" method="post" id="onestepcheckout-form">
			<?= CRequest::getCsrfInputHtml(); ?>
			<fieldset style="margin: 0;" class="group-select">
				<p class="onestepcheckout-description"><?= Yii::$service->page->translate->__('Welcome to the checkout,Fill in the fields below to complete your purchase');?> !</p>
				<?php if (\Yii::$app->user->isGuest): ?>
                    <p class="onestepcheckout-login-link">
                        <a href="<?= Yii::$service->url->getUrl('customer/account/login'); ?>" id="onestepcheckout-login-link"><?= Yii::$service->page->translate->__('Already registered? Click here to login');?>.</a>
                    </p>
                <?php endif; ?>
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
								'cart_address'		=> $cart_address,
								//'payments' => $payments,
								//'current_payment_mothod' => $current_payment_mothod,
							];
						?>
						<?= Yii::$service->page->widget->render($addressView,$addressParam); ?>
					
					</div>

					<div class="onestepcheckout-column-middle">
						<div class="shipping_method_html">
							<?= Yii::$service->page->widget->render('order/shipping', ['shippings' => $shippings]); ?>
						</div>
						<?php # payment部分
							$paymentParam = [
								'payments' => $payments,
								'current_payment_mothod' => $current_payment_mothod,
							];
						?>
						<?= Yii::$service->page->widget->render('order/payment', $paymentParam); ?>
						<div class="onestepcheckout-coupons">
							<div style="display: none;" id="coupon-notice"></div>
							<div class="op_block_title"><?= Yii::$service->page->translate->__('Coupon codes (optional)');?></div>
							<label for="id_couponcode"><?= Yii::$service->page->translate->__('Enter your coupon code if you have one.');?></label>
							
							<input type="hidden" class="couponType"  value="<?= $cart_info['coupon_code'] ? 1 : 2 ; ?>"  />
							<input style="color:#777;" class="input-text" id="id_couponcode" name="coupon_code" value="<?= $cart_info['coupon_code']; ?>">
							<br>
							<button style="" type="button" class="submitbutton add_coupon_submit" id="onestepcheckout-coupon-add"><?= Yii::$service->page->translate->__($cart_info['coupon_code'] ? 'Cancel Coupon' : 'Add Coupon') ; ?></button>
							<div class="clear"></div>
							<div class="coupon_add_log"></div>
						</div>
                        
                        
						<div class="onestepcheckout-coupons">
							<div class="op_block_title"><?= Yii::$service->page->translate->__('Order Remark (optional)');?></div>
							<label for="id_couponcode"><?= Yii::$service->page->translate->__('You can fill in the order remark information below');?></label>
							<textarea class="order_remark" name="order_remark" style="width:94%;height:100px;padding:10px;"></textarea>
						</div>
                        
						
					</div>

					<div class="onestepcheckout-column-right">
						<div class="review_order_view">
							<?php # review order部分
								$reviewOrderParam = [
									'cart_info' => $cart_info,
									'currency_info' => $currency_info,
								];
							?>
							<?= Yii::$service->page->widget->render('order/view', $reviewOrderParam); ?>
							
						</div>
						<div class="onestepcheckout-place-order">
							<a class="large orange onestepcheckout-button" href="javascript:void(0)" id="onestepcheckout-place-order"><?= Yii::$service->page->translate->__('Place order now');?></a>
							<div class="onestepcheckout-place-order-loading"><img src="<?= Yii::$service->image->getImgUrl('images/opc-ajax-loader.gif'); ?>">&nbsp;&nbsp;<?= Yii::$service->page->translate->__('Please wait, processing your order...');?></div>
						</div>
					</div>
					<div style="clear: both;">&nbsp;</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<script>
<?php $this->beginBlock('placeOrder') ?>
    csrfName = $(".thiscsrf").attr("name");
    csrfVal = $(".thiscsrf").val();
	function validateEmail(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	// ajax
	function ajaxreflush(){
		shipping_method = $("input[name=shipping_method]:checked").val();
		//alert(shipping_method);
		country = $(".billing_country").val();
		address_id = $(".address_list").val();
		state   = $(".address_state").val();
		//alert(state);
		if(country || address_id){
			$(".onestepcheckout-summary").html('<div style="text-align:center;min-height:40px;"><img src="<?= Yii::$service->image->getImgUrl('images/ajax-loader.gif'); ?>"  /></div>');
			$(".onestepcheckout-shipping-method-block").html('<div style="text-align:center;min-height:40px;"><img src="<?= Yii::$service->image->getImgUrl('images/ajax-loader.gif'); ?>"  /></div>');
				
			ajaxurl = "<?= Yii::$service->url->getUrl('checkout/onepage/ajaxupdateorder');  ?>";
			
			
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
					status = data.status;
					if(status == 'success'){
						$(".review_order_view").html(data.reviewOrderHtml)
						$(".shipping_method_html").html(data.shippingHtml);
					
					}
						
				},
				error:function (XMLHttpRequest, textStatus, errorThrown){
						
				}
			});
		}
	}	
	$(document).ready(function(){
		currentUrl = "<?= Yii::$service->url->getUrl('checkout/onepage') ?>"
		//优惠券
		$(".add_coupon_submit").click(function(){
			coupon_code = $("#id_couponcode").val();
			coupon_type = $(".couponType").val();
			coupon_url = "";
			$succ_coupon_type = 0;
			if(coupon_type == 2){
				coupon_url = "<?=  Yii::$service->url->getUrl('checkout/cart/addcoupon'); ?>";
				$succ_coupon_type = 1;
			}else if(coupon_type == 1){
				coupon_url = "<?=  Yii::$service->url->getUrl('checkout/cart/cancelcoupon'); ?>";
				$succ_coupon_type = 2;
			}
			//alert(coupon_type);
			if(!coupon_code){
				//alert("coupon can not empty!");
			}
			$data = {"coupon_code":coupon_code};
            $data[csrfName] = csrfVal;
			$.ajax({
				async:true,
				timeout: 6000,
				dataType: 'json', 
				type:'post',
				data: $data,
				url:coupon_url,
				success:function(data, textStatus){ 
					if(data.status == 'success'){
						$(".couponType").val($succ_coupon_type);
						hml = $('.add_coupon_submit').html();
						if(hml == '<?= Yii::$service->page->translate->__('Add Coupon');?>'){
							$('.add_coupon_submit').html('<?= Yii::$service->page->translate->__('Cancel Coupon');?>');
						}else{
							$('.add_coupon_submit').html('<?= Yii::$service->page->translate->__('Add Coupon');?>');
						}
						$(".coupon_add_log").html("");
						ajaxreflush();
					}else if(data.content == 'nologin'){
						$(".coupon_add_log").html("<?= Yii::$service->page->translate->__('you must login your account before you use coupon');?>");
					}else{
						$(".coupon_add_log").html(data.content);
					}
				},
				error:function (XMLHttpRequest, textStatus, errorThrown){}
			});
				
			
		});
		
		// 对于非登录用户，可以填写密码，进行注册账户，这里进行信息的检查。
		$("#id_create_account").click(function(){
			if($(this).is(':checked')){
				email = $("input[name='billing[email]']").val();
				if(!email){
					$(this).prop('checked', false);
					$(".label_create_account").html(" <?= Yii::$service->page->translate->__('email address is empty, you must Fill in email');?>");
				}else{
					thischeckbox = this;
					if(!validateEmail(email)){
						$(this).prop('checked', false);
						$(".label_create_account").html(" <?= Yii::$service->page->translate->__('email address format is incorrect');?>");
						
					}else{
						// ajax  get if  email is register
						$.ajax({
							async:true,
							timeout: 6000,
							dataType: 'json', 
							type:'get',
							data: {"email":email},
							url:"<?= Yii::$service->url->getUrl('customer/ajax/isregister'); ?>",
							success:function(data, textStatus){ 
								if(data.registered == 2){
									$(".label_create_account").html("");
									$("#onestepcheckout-li-password").show();
									$("#onestepcheckout-li-password input").addClass("required-entry");
					
								}else{
									$(thischeckbox).prop('checked', false);
									$(".label_create_account").html(" <?= Yii::$service->page->translate->__('This email is registered , you must fill in another email');?>");
								}
							},
							error:function (XMLHttpRequest, textStatus, errorThrown){}
						});
					}
				}
			}else{
				$(".label_create_account").html("");
				$("#onestepcheckout-li-password").hide();
				$("#onestepcheckout-li-password input").removeClass("required-entry");
			}
		});
		//###########################
		//下单(这个部分未完成。)
		$("#onestepcheckout-place-order").click(function(){
			$(".validation-advice").remove();
			i = 0;
			j = 0;
			address_list = $(".address_list").val();
			// shipping
			shipment_method = $(".onestepcheckout-shipping-method-block input[name='shipping_method']:checked").val();
			//alert(shipment_method);
			if(!shipment_method){
				$(".shipment-methods").after('<div style=""  class="validation-advice"><?= Yii::$service->page->translate->__('This is a required field.');?></div>');
				j = 1;
			}
			//alert(j);
			//payment  
			payment_method = $("#checkout-payment-method-load input[name='payment_method']:checked").val();
			//alert(shipment_method);
			if(!payment_method){
				$(".checkout-payment-method-load").after('<div style=""  class="validation-advice"><?= Yii::$service->page->translate->__('This is a required field.');?></div>');
				j = 1;
			}
			if(address_list){
				if(!j){
					$(".onestepcheckout-place-order").addClass('visit');
				
					$("#onestepcheckout-form").submit();
				}
			}else{
				$("#onestepcheckout-form .required-entry").each(function(){
					value = $(this).val();
					if(!value){
						i++;
						$(this).after('<div style=""  class="validation-advice"><?= Yii::$service->page->translate->__('This is a required field.');?></div>');
					}
				});
				//email  format validate
				user_email = $("#billing_address .validate-email").val();
				if(user_email && !validateEmail(user_email)){
					$("#billing_address .validate-email").after('<div style=""  class="validation-advice"><?= Yii::$service->page->translate->__('email address format is incorrect');?></div>');
					i++;
				}
				// password 是否长度大于6，并且两个密码一致
				if($("#id_create_account").is(':checked')){
					
					new_user_pass = $(".customer_password").val();
					new_user_pass_cm = $(".customer_confirm_password").val();
					<?php 
						$passwdMinLength = Yii::$service->customer->getRegisterPassMinLength();
						$passwdMaxLength = Yii::$service->customer->getRegisterPassMaxLength();
					?>
					passwdMinLength = "<?= $passwdMinLength ?>";
					passwdMaxLength = "<?= $passwdMaxLength ?>";
					if(new_user_pass.length < passwdMinLength){
						$(".customer_password").after('<div style=""  class="validation-advice"><?= Yii::$service->page->translate->__('Password length must be greater than or equal to {passwdMinLength}',['passwdMinLength' => $passwdMinLength]);?></div>');
						i++;
					}else if(new_user_pass.length > passwdMaxLength){
						$(".customer_password").after('<div style=""  class="validation-advice"><?= Yii::$service->page->translate->__('Password length must be less than or equal to {passwdMaxLength}',['passwdMaxLength' => $passwdMaxLength]);?></div>');
						i++;
					}else if(new_user_pass != new_user_pass_cm){
						$(".customer_confirm_password").after('<div style=""  class="validation-advice"><?= Yii::$service->page->translate->__('The passwords are inconsistent');?></div>');
						i++; 
					}  
				}
				
				if(!i && !j){
					$(".onestepcheckout-place-order").addClass('visit');
					$("#onestepcheckout-form").submit();
				}
			}
			
		});
		
		//登录用户切换地址列表
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
					ajaxreflush();
				}
			}
		});
		
		// 国家选择后，state需要清空，重新选择或者填写
		$(".billing_country").change(function(){
			country = $(this).val();
			//state   = $(".address_state").val();
			//shipping_method = $("input[name=shipping_method]:checked").val();
			//alert(shipping_method);
			
			//$(".onestepcheckout-shipping-method-block").html('<div style="text-align:center;min-height:40px;"><img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /></div>');
			//$(".onestepcheckout-summary").html('<div style="text-align:center;min-height:40px;"><img src="http://www.intosmile.com/skin/default/images/ajax-loader.gif"  /></div>');
			ajaxurl = "<?= Yii::$service->url->getUrl('checkout/onepage/changecountry'); ?>";
			
			$.ajax({
				async:true,
				timeout: 8000,
				dataType: 'json', 
				type:'get',
				data: {
						'country':country,
						//'shipping_method':shipping_method,
						//'state':state
						},
				url:ajaxurl,
				success:function(data, textStatus){ 
					$(".state_html").html(data.state);
					
				},
				error:function (XMLHttpRequest, textStatus, errorThrown){
						
				}
			});
			ajaxreflush();	
		});
		
		// state select 改变后的事件
		$(".input-state").off("change").on("change","select.address_state",function(){
			ajaxreflush();
		});
		// state input 改变后的事件
		$(".input-state").off("blur").on("blur","input.address_state",function(){
			ajaxreflush();
		});
		
		
		//改变shipping methos
		$(".onestepcheckout-column-middle").off("click").on("click","input[name=shipping_method]",function(){
			ajaxreflush();
		});
		
		
		
		//$("#billing_address_list").off("change").on("change",".selectstate",function(){
		//	value = $(".selectstate option:selected").text();
		//	if($(".selectstate").val()){
		//		$(".inputstate").val(value);
		//	}else{
		//		$(".inputstate").val('');
		//	}
		//});
		
	});	
	//ajaxreflush();
<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['placeOrder'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

</script>
    

	