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
<div class="main container two-columns-left">
<?= Yii::$service->page->widget->render('base/breadcrumbs',$this); ?>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>
	<div class="col-main account_center">
		<div class="std">
			<div>
				<form class="addressedit" action="<?= Yii::$service->url->getUrl('customer/address/edit'); ?>" id="form-validate" method="post">
					<?php echo CRequest::getCsrfInputHtml();  ?>
                    <input name="address[address_id]" value="<?= $address_id; ?>" type="hidden">
					<div class="">
						<ul class="">
							<li>
								<label class="required" for="email"><?= Yii::$service->page->translate->__('Email Address');?></label>
								<div class="input-box">
									<input class="input-text required-entry" maxlength="255" title="Email" value="<?= $email ?>" name="address[email]" id="customer_email"   type="text">
									
								</div>
							</li>
							<li class="">
								<div class="field name-firstname">
									<label class="required" for="firstname"><?= Yii::$service->page->translate->__('First Name');?></label>
									<div class="input-box">
										<input class="input-text required-entry" maxlength="255" title="First Name" value="<?= $first_name ?>" name="address[first_name]" id="firstname" type="text">
									</div>
								</div>
							</li>
							<li>
								<div class="field name-lastname">
									<label class="required" for="lastname"><?= Yii::$service->page->translate->__('Last Name');?></label>
									<div class="input-box">
										<input class="input-text required-entry" maxlength="255" title="Last Name" value="<?= $last_name ?>" name="address[last_name]" id="lastname" type="text">
									</div>
								</div>
							</li>
							
							
							<li>
								<div class="field name-lastname">
										<label class="required" for="lastname"><?= Yii::$service->page->translate->__('Telephone');?></label>
										<div class="input-box">
											<input class="input-text required-entry" maxlength="255" title="Last Name" value="<?= $telephone ?>" name="address[telephone]" id="lastname" type="text">
										</div>
									</div>
							</li>
							
							
							<li>
								<div class="field name-lastname">
										<label class="required" for="lastname"><?= Yii::$service->page->translate->__('Country');?></label>
										<div class="input-box">
											<select id="address:country" class="address_country validate-select" title="Country" name="address[country]">
												<?= $countrySelect;  ?>
											</select>
											
										</div>
									</div>
							</li>
							
							
							<li>
								<div class="field name-lastname">
										<label class="required" for="lastname"><?= Yii::$service->page->translate->__('State');?></label>
										<div class="input-box state_html">
											<?= $stateHtml;  ?>
											
										</div>
									</div>
							</li>
							
							
							<li>
								<div class="field name-lastname">
										<label class="required" for="lastname"><?= Yii::$service->page->translate->__('City');?></label>
										<div class="input-box">
											<input class="input-text required-entry" maxlength="255" title="Last Name" value="<?= $city ?>" name="address[city]" id="lastname" type="text">
										</div>
									</div>
							</li>
							
							
							<li>
								<div class="field name-lastname">
										<label class="required" for="lastname"><?= Yii::$service->page->translate->__('street1');?></label>
										<div class="input-box">
											<input class="input-text required-entry" maxlength="255" title="Last Name" value="<?= $street1 ?>" name="address[street1]" id="lastname" type="text">
										</div>
									</div>
							</li>
							
							
							<li>
								<div class="field name-lastname">
										<label  for="lastname"><?= Yii::$service->page->translate->__('street2');?></label>
										<div class="input-box">
											<input class="input-text optional" maxlength="255" title="street2" value="<?= $street2 ?>" name="address[street2]" id="lastname" type="text">
										</div>
									</div>
							</li>
							
							
							<li>
								<div class="field name-lastname">
										<label class="required" for="lastname"><?= Yii::$service->page->translate->__('Zip Code');?></label>
										<div class="input-box">
											<input class="input-text required-entry" maxlength="255" title="Last Name" value="<?= $zip ?>" name="address[zip]" id="lastname" type="text">
										</div>
									</div>
							</li>
							
							
							<li>
								<div class="field name-lastname">
									<div class="input-box">
										<input name="address[is_default]" value="1" title="Save in address book" id="address:is_default" class="address_is_default checkbox" <?= $is_default_str; ?> type="checkbox">
										<label for="address:is_default" style="display:inline;"><?= Yii::$service->page->translate->__('Is Default');?></label>
										
									</div>
								</div>
							</li>
							
						</ul>
						
					</div>
					
					<a href="javascript:void(0)" onclick="submit_address()" class="submitbutton"><span><span><?= Yii::$service->page->translate->__('Save');?></span></span> </a>
					
				</form>
			</div>
		</div>

	</div>
	
	<div class="col-left ">
		<?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
	</div>
	<div class="clear"></div>
</div>
	
	
<script>
<?php $this->beginBlock('editCustomerAddress') ?>
	$(document).ready(function(){
		$(".address_country").change(function(){
			//alert(111);
			ajaxurl = "<?= Yii::$service->url->getUrl('customer/address/changecountry') ?>";
			country = $(this).val();
			$.ajax({
				async:false,
				timeout: 8000,
				dataType: 'json', 
				type:'get',
				data: {
						'country':country,
				},
				url:ajaxurl,
				success:function(data, textStatus){ 
					$(".state_html").html(data.state);
				},
				error:function (XMLHttpRequest, textStatus, errorThrown){
						
				}
			});
			
		});

	});	
	function submit_address(){
		i = 1;
		jQuery(".addressedit input").each(function(){
			type = jQuery(this).attr("type");
			if(type != "hidden"){
				value = jQuery(this).val();
				if(!value){
					//alert($(this).hasClass('optional'));
					if(!$(this).hasClass('optional')){
						i = 0;
					}
				}
			}
		});
		
		jQuery(".addressedit select").each(function(){
			value = jQuery(this).val();
			if(!value){
				i = 0;
			}
		});
		if(i){
			jQuery(".addressedit").submit();
		}else{
			alert("You Must Fill All Field");
		}
	}
	
<?php $this->endBlock(); ?> 
<?php $this->registerJs($this->blocks['editCustomerAddress'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>

</script>