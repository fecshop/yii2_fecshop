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
use fecshop\app\appfront\helper\Format;
use fec\helpers\CRequest;
?>
<div class="main container one-column">
	<div class="col-main">
    <?= Yii::$service->page->widget->render('base/breadcrumbs',$this); ?>
    <?= Yii::$service->page->widget->render('base/flashmessage'); ?>
	<?php if(is_array($cart_info) && !empty($cart_info)):   ?>
			    
		<div class="product_page">
			
			<div class="cart">
				<div class="page-title title-buttons">
					<div class="shopping-cart-img">
					
					</div>
				</div>
				<div>
					<?php if(is_array($cart_info['products']) && (!empty($cart_info['products']))): ?>
								
					<div class="shopping-cart-div">
						<div class="shopping-cart-ab">
						</div>
						<table id="shopping-cart-table" class="data-table cart-table">
							<colgroup>
                                <col width="1">
								<col width="1">
								<col width="">
								<col width="6">
								<col width="76">
								<col width="76">
								<col width="91">
                                <col width="76">
								<col width="106">
								<col width="106">
                                <col width="26">
								<col width="1">
							</colgroup>
							<thead>
								<tr class="first last">
                                    <th rowspan="1"><input type="checkbox" name="cart_select_all" class="cart_select cart_select_all" id="cart_select_all">&nbsp;<label for="cart_select_all">All</label></th>
									<th rowspan="1">&nbsp;</th>
									<th rowspan="5"><span class="nobr"><?= Yii::$service->page->translate->__('Product Name');?></span></th>
									<th class="a-center" colspan="1"><span class="nobr"><?= Yii::$service->page->translate->__('Unit Price');?></span></th>
                                    <th class="a-center" colspan="1"><span class="nobr"><?= Yii::$service->page->translate->__('Weight');?></span></th>
                                    <th class="a-center" colspan="1"><span class="nobr"><?= Yii::$service->page->translate->__('Volume');?></span></th>
									<th rowspan="1" class="a-center"><?= Yii::$service->page->translate->__('Qty');?></th>
									<th class="a-center" colspan="1"><?= Yii::$service->page->translate->__('Sub Price');?></th>
                                    <th class="a-center" colspan="1"><?= Yii::$service->page->translate->__('Sub Weight');?></th>
                                    <th class="a-center" colspan="1"><?= Yii::$service->page->translate->__('Sub Volume');?></th>
									<th rowspan="1" class="a-right">&nbsp;</th>
								</tr>
												</thead>
							<tfoot>
								
							</tfoot>
							<tbody>
								<?php foreach($cart_info['products'] as $product_one): ?>
								
								<tr class="first last odd">
                                
                                    <td>
                                        <input rel="<?= $product_one['item_id']; ?>" <?=  ($product_one['active'] == Yii::$service->cart->quoteItem->activeStatus ) ?  'checked="checked"' : '' ?> type="checkbox" name="cart_select_item" class="cart_select cart_select_item">
                                    </td>
									
                                    
									<td>
										<a href="<?= $product_one['url'] ?>" title="<?= $product_one['name'] ?>" class="product-image">
										<img src="<?= Yii::$service->product->image->getResize($product_one['image'],[100,100],false) ?>" alt="<?= $product_one['name'] ?>" width="75" height="75">
										</a>
									</td>
									
									<td>
										<h2 class="product-name">
											<a href="<?= $product_one['url'] ?>"><?= $product_one['name'] ?></a>
										</h2>
										<?php  if(is_array($product_one['custom_option_info'])):  ?>
										<ul>
											<?php foreach($product_one['custom_option_info'] as $label => $val):  ?>
												
												<li><?= Yii::$service->page->translate->__(ucwords($label)).':' ?><?= Yii::$service->page->translate->__($val) ?> </li>
												
											<?php endforeach;  ?>
										</ul>
										<?php endif;  ?>
									</td>
									
									
									<td class="a-right">
										<span class="cart-price">
											<span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($product_one['product_price']); ?></span>                
										</span>

									</td>
                                    
                                    <td class="a-right">
										<span class="cart-price">
											<span class="price"><?= Format::price($product_one['product_weight']); ?>g</span>                
										</span>
									</td>
                                    
                                    <td class="a-right">
										<span class="cart-price">
											<span class="price"><?= Format::price($product_one['product_volume']); ?>c㎡</span>                
										</span>
									</td>
                                
				   
									<td class="a-center">
										<div style="width:60px;margin:auto">
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
                                    
                                    <td class="a-right">
										<span class="cart-price">
											<span class="price"><?= Format::price($product_one['product_row_weight']); ?>g</span>                            
										</span>
									</td>
                                    
                                    
                                    <td class="a-right">
										<span class="cart-price">
											<span class="price"><?= Format::price($product_one['product_row_volume']); ?>c㎡</span>                            
										</span>
									</td>
                                    
                                    
									<td class="a-right last">
										<a style="margin-right: 15px;float: right;" href="javascript:void(0)"  rel="<?= $product_one['item_id']; ?>" title="Remove item" class="btn-remove btn-remove2"><?= Yii::$service->page->translate->__('Remove item');?></a>
									</td>
								</tr>
								<?php  endforeach;  ?>
								
							</tbody>
						</table>
					</div>
					<?php  endif;  ?>
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
										<label for="coupon_code"><?= Yii::$service->page->translate->__('Enter your coupon code if you have one.');?></label>
										<div class="input-box">
											<input type="hidden" class="couponType"  value="<?= $cart_info['coupon_code'] ? 1 : 2 ; ?>"  />
											<input style="color:#777;" class="input-text" id="coupon_code" name="coupon_code" value="<?= $cart_info['coupon_code']; ?>">
										</div>
										<div class="buttons-cou">
											<a href="javascript:void(0)" class="add_coupon_submit submitbutton"><span><span><?= Yii::$service->page->translate->__($cart_info['coupon_code'] ? 'Cancel Coupon' : 'Add Coupon') ; ?></span></span> </a>
											
										</div>
										<div class="clear"></div>
										<div class="coupon_add_log"></div>
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
											<?= Yii::$service->page->translate->__('Sub Totla');?> : 
                                        </td>
										<td style="" class="a-right">
											<span class="price">
                                                <?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['product_total']); ?>
                                            </span>    
                                        </td>
									</tr>
                                    <tr>
										<td style="" class="a-left" colspan="1">
											<?= Yii::$service->page->translate->__('Sub Weight');?> : 
                                        </td>
										<td style="" class="a-right">
											<span class="price">
                                                <?= Format::price($cart_info['product_weight']); ?> g
                                            </span>    
                                        </td>
									</tr>
                                    <tr>
										<td style="" class="a-left" colspan="1">
											<?= Yii::$service->page->translate->__('Sub Volume');?> : 
                                        </td>
										<td style="" class="a-right">
											<span class="price">
                                                <?= Format::price($cart_info['product_volume']); ?> c㎡
                                            </span>    
                                        </td>
									</tr>
                                    <tr>
										<td style="" class="a-left" colspan="1">
											<?= Yii::$service->page->translate->__('Discount');?> :    </td>
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
											<strong><?= Yii::$service->page->translate->__('Grand Total');?></strong>
										</td>
										<td style="" class="a-right">
											<strong><span class="price"><?=  $currency_info['symbol'];  ?><?= Format::price($cart_info['grand_total']) ?></span></strong>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="proceed_to_checkout">
							
							<button onclick="location.href='<?= Yii::$service->url->getUrl('checkout/onepage');  ?>'" type="button" title="Proceed to Checkout" class="button btn-proceed-checkout btn-checkout"><span><span><?= Yii::$service->page->translate->__('Proceed to Pay');?></span></span></button>
							<?php if ($enablePaypalExpress): ?>
                                <span class="or">- <?= Yii::$service->page->translate->__('OR');?> - </span>
                                <a class="express_paypal" href="<?= Yii::$service->url->getUrl('payment/paypal/express/start');    ?>">
                                
                                </a>
                            <?php endif;  ?>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				
			</div>
		</div>
	<?php else: ?>
		<div class="empty_cart">
		<?php
			$param = ['urlB' => '<a rel="nofollow" href="'.Yii::$service->url->getUrl('customer/account/login').'">','urlE' =>'</a>'];
		?>	
		
		<div id="empty_cart_info">
			<?= Yii::$service->page->translate->__('Your Shopping Cart is empty');?>
			<a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Start shopping now!');?></a>
			<br>
			<?= Yii::$service->page->translate->__('Please {urlB}log in{urlE} to view the products you have previously added to your Shopping Cart.',$param);?>
		</div>
  
  
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
    $(".cart_select_all").attr("checked",selectAllChecked);
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
            
			jQuery.ajax({
				async:true,
				timeout: 6000,
				dataType: 'json', 
				type:'post',
				data: $data,
				url:updateCartInfoUrl,
				success:function(data, textStatus){ 
					if (data.status == 'success') {
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
				if (data.status == 'success') {
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
		//coupon_url = $("#discount-coupon-form").attr("action");
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
<?php //  Yii::$service->page->trace->getTraceCartJsCode($trace_cart_info) // 这个改成服务端发送加入购物车数据，而不是js传递的方式 ?>

