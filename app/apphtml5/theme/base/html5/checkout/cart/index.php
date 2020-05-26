<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php
use fecshop\app\apphtml5\helper\Format;
use fec\helpers\CRequest;
?>
<div class="main container one-column">
	<div class="col-main">
     <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
	<?php if(is_array($cart_info) && !empty($cart_info)):   ?>
			    
		<div class="product_page">
			
			<div class="cart">
				<div class="page-title title-buttons">
					<div class="shopping-cart-img">
						<?= Yii::$service->page->translate->__('Shopping Cart'); ?>
					</div>
				</div>
				<div class="cart_info">
                    <div class="cart_select_div">
                        <input id="cart_select_all" type="checkbox" name="cart_select_all" class="cart_select cart_select_all">
                        &nbsp;
                        <label for="cart_select_all">Select All Product</label>
                    </div>
					<?php if(is_array($cart_info['products']) && (!empty($cart_info['products']))): ?>
						<?php foreach($cart_info['products'] as $product_one): ?>
							<div class="row">
								<div class="col-33">
                                    <input rel="<?= $product_one['item_id']; ?>" <?=  ($product_one['active'] == Yii::$service->cart->quoteItem->activeStatus ) ?  'checked="checked"' : '' ?> type="checkbox" name="cart_select_item" class="cart_select cart_select_item">
									<a external href="<?= $product_one['url'] ?>" title="<?= $product_one['name'] ?>" class="product-image">
										<img src="<?= Yii::$service->product->image->getResize($product_one['image'],[150,150],false) ?>" alt="<?= $product_one['name'] ?>" width="75" height="75">
									</a>
								</div>
								<div class="col-66">
									<h2 class="product-name">
										<a external href="<?= $product_one['url'] ?>"><?= $product_one['name'] ?></a>
									</h2>
									<?php  if(is_array($product_one['custom_option_info'])):  ?>
									<ul class="options">
										<?php foreach($product_one['custom_option_info'] as $label => $val):  ?>
											
											<li><?= Yii::$service->page->translate->__(ucwords($label).':') ?><?= Yii::$service->page->translate->__($val) ?> </li>
											
										<?php endforeach;  ?>
									</ul>
									<div class="clear"></div>
									<?php endif;  ?>
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
						<?php endforeach; ?>
					<?php endif; ?>
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
											<div class="ui-input-text ui-body-inherit ui-corner-all ui-shadow-inset">
												<input type="hidden" class="couponType"  value="<?= $cart_info['coupon_code'] ? 1 : 2 ; ?>"  />
												<input style="color:#777;" class="input-text" id="coupon_code" name="coupon_code" value="<?= $cart_info['coupon_code']; ?>">
											</div>
										</div>
										<div class="buttons-coupon">
											<a external data-role="button" href="javascript:void(0)"  class="add_coupon_submit submitbutton ui-link ui-btn ui-shadow ui-corner-all" role="button">
                                                <span>
                                                    <span><?= Yii::$service->page->translate->__($cart_info['coupon_code'] ? 'Cancel Coupon' : 'Add Coupon') ; ?></span>
                                                </span>
                                            </a>
										</div>
										<div class="clear"></div>
										<div class="coupon_add_log"></div>
									</div>
								</div>
							</form>
							<div class="clear"></div>
						</div>
					</div>
					<div class="cart_cost">
						<div class="row no-gutter">
							<div class="col-66"><?= Yii::$service->page->translate->__('Sub Total');?> :  </div>
							<div class="col-33"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['product_total']); ?></div>
						</div>
                        <div class="row no-gutter">
							<div class="col-66"><?= Yii::$service->page->translate->__('Sub Weight');?> :  </div>
							<div class="col-33"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['product_weight']); ?> Kg</div>
						</div>
                        <div class="row no-gutter">
							<div class="col-66"><?= Yii::$service->page->translate->__('Sub Volume');?> :  </div>
							<div class="col-33"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['product_volume']); ?> c㎡</div>
						</div>
						<div class="row no-gutter">
							<div class="col-66"><?= Yii::$service->page->translate->__('Shipping Cost');?>  : </div>
							<div class="col-33"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['shipping_cost']); ?></div>
						</div>
						<div class="row no-gutter">
							<div class="col-66"><?= Yii::$service->page->translate->__('Discount');?>  :</div>
							<div class="col-33">-<?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['coupon_cost']); ?>%</div>
						</div>
						<div class="row no-gutter">
							<div class="col-66"><?= Yii::$service->page->translate->__('Grand Total');?>  :</div>
							<div class="col-33"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['grand_total']) ?></div>
						</div>
					</div>
					<div class="totals cart-totals">
						<div class="proceed_to_checkout">
							<div class="row no-gutter">
								<div class="col-50">
									<button onclick="location.href='<?= Yii::$service->url->getUrl('checkout/onepage');  ?>'" type="button" title="Proceed to Checkout" class="button btn-proceed-checkout btn-checkout"><span><span><?= Yii::$service->page->translate->__('Proceed to Pay');?></span></span></button>
                                </div>
                                <?php if ($enablePaypalExpress): ?>
								<div class="col-50">
									<a  external class="express_paypal" href="<?= Yii::$service->url->getUrl('payment/paypal/express/start');    ?>">
										<img src="<?= Yii::$service->image->getImgUrl('/images/pay.png') ?>"  />
									</a>
								</div>
                                <?php endif;  ?>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	<?php else: ?>
		<div class="empty_cart ">
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
        <div class="empty_cart_img">
            
        </div>
	<?php  endif; ?>
	</div>
</div>

<script>
	// add to cart js	
<?php $this->beginBlock('changeCartInfo') ?>
csrfName = "<?= CRequest::getCsrfName() ?>";
csrfVal = "<?= CRequest::getCsrfValue() ?>";
$(document).ready(function(){
	// set select all checkbox
    // selectall = "<?= Yii::$app->request->get('selectall') ?>";
    selectall = "<?=  \Yii::$service->helper->htmlEncode(Yii::$app->request->get('selectall')) ?>";
    selectAllChecked = false;
    if (selectall == 1) {
        selectAllChecked = true;
    } else {
        item_select_all = 1;
        $(".cart_select_item").each(function(){
            checked = $(this).is(':checked');
            if (checked == false) {
                item_select_all = 0;
            }
        });
        if (item_select_all == 1) {
            selectAllChecked = true;
        }
    }
    if (selectAllChecked) {
        $(".cart_select_all").attr("checked",selectAllChecked);
    } else {
        $(".cart_select_all").removeAttr("checked");
    }
	currentUrl = "<?= Yii::$service->url->getUrl('checkout/cart') ?>";
	updateCartInfoUrl = "<?= Yii::$service->url->getUrl('checkout/cart/updateinfo') ?>";
    selectOneProductUrl = "<?= Yii::$service->url->getUrl('checkout/cart/selectone') ?>";
    selectAllProductUrl = "<?= Yii::$service->url->getUrl('checkout/cart/selectall') ?>";
	$(".cartqtydown").click(function(){
		$item_id = $(this).attr("rel");
		num = $(this).attr("num");
		if(num > 1){
			$data = {
				item_id:$item_id,
				up_type:"less_one"
			};
			$data[csrfName] = csrfVal;
			$.ajax({
				async:true,
				timeout: 6000,
				dataType: 'json', 
				type:'post',
				data: $data,
				url:updateCartInfoUrl,
				success:function(data, textStatus){ 
					if(data.status == 'success'){
						window.location.href=currentUrl;
					} else {
                        alert(data.content);
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
		$data[csrfName] = csrfVal;
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type:'post',
			data: $data,
			url:updateCartInfoUrl,
			success:function(data, textStatus){ 
				if(data.status == 'success'){
					window.location.href=currentUrl;
				} else {
                    alert(data.content);
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
		$data[csrfName] = csrfVal;
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type:'post',
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
	
    $(".cart_select_item").click(function(){
		$item_id = $(this).attr("rel");
		checked = $(this).is(':checked');
        checked = checked ? 1 : 0;
		$data = {
			item_id:$item_id,
			checked:checked
		};
		$data[csrfName] = csrfVal;
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type:'post',
			data: $data,
			url:selectOneProductUrl,
			success:function(data, textStatus){ 
				if(data.status == 'success'){
					window.location.href = currentUrl;
				}
			},
			error:function (XMLHttpRequest, textStatus, errorThrown){}
		});
	});
    
    
    $(".cart_select_all").click(function(){
		checked = $(this).is(':checked');
        checked = checked ? 1 : 0;
		$data = {
			checked:checked
		};
		$data[csrfName] = csrfVal;
        selectCurrentUrl = currentUrl + '?selectall=' + checked;
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type:'post',
			data: $data,
			url:selectAllProductUrl,
			success:function(data, textStatus){ 
				if(data.status == 'success'){
					window.location.href = selectCurrentUrl;
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
			//alert("coupon can not empty!");
		}
		$data = {"coupon_code":coupon_code};
		$data[csrfName] = csrfVal;
		$.ajax({
			async:true,
			timeout: 6000,
			dataType: 'json', 
			type: 'post',
			data: $data,
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
<?php // Yii::$service->page->trace->getTraceCartJsCode($trace_cart_info) // 这个改成服务端发送加入购物车数据，而不是js传递的方式  ?>