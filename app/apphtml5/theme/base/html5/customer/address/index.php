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
<div class="account-ds">
	<div class="bar bar-nav account-top-m">
		<a external class="button button-link button-nav pull-left" href="<?= Yii::$service->url->getUrl('customer/account/index'); ?>">
			<span class="icon icon-left"></span>
		</a>
		<h1 class='title'><?= Yii::$service->page->translate->__('Customer Address'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('base/flashmessage'); ?>


<div class="main container two-columns-left">
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				
				<table class="addressbook" width="100%" cellspacing="0" cellpadding="0" border="0">
					<thead>
						<tr class="ress_tit">
							<th width="76" valign="middle" align="center" height="31"><?= Yii::$service->page->translate->__('Name');?></th>                                                                                        
							<th width="67" valign="middle" align="center"><?= Yii::$service->page->translate->__('Address');?></th>
							<th class="th3" width="71" valign="middle" align="center"><?= Yii::$service->page->translate->__('Operation');?></th>
						</tr>
					</thead>
					<tbody>
					<?php   if(is_array($coll) && !empty($coll)):   ?>
					<?php 		foreach($coll as $one): ?>
						<tr class="">
							<td valign="top" align="center"><?= $one['first_name'].' '.$one['last_name'] ?></td>
							
							<td valign="top" align="center">
								<?= $one['street1'].' '.$one['street2'] ?><br>
								<?= $one['city'] ?> 
								<?= Yii::$service->helper->country->getStateByContryCode($one['country'],$one['state']); ?>
								<?= Yii::$service->helper->country->getCountryNameByKey($one['country']); ?>
								<?php  if($one['is_default'] == 1): ?>
								<br/><span style=" color:#cc0000"><?= Yii::$service->page->translate->__('Default Address');?></span> 
								<?php  endif; ?>	
							</td>
							<td class="ltp" valign="top ltp" align="center">
								<a external href="<?= Yii::$service->url->getUrl('customer/address/edit',['address_id' => $one['address_id']]); ?>">
									<span class="icon icon-edit"></span>
								</a>
								<a external href="javascript:deleteAddress(<?= $one['address_id'] ?>)">
									<span class="icon icon-remove"></span>
								</a>
															
							</td>
						</tr>	
					<?php 		endforeach; ?>
					<?php 	endif; ?>
					</tbody>
				</table>
				<div class="add_new_address">
					<a external href="<?= Yii::$service->url->getUrl('customer/address/edit') ?>" class="button  button-light"><?= Yii::$service->page->translate->__('Add New Address');?></a>
				</div>
			</div>
		</div>
		<script>
            function deleteAddress(address_id){
                var r=confirm("<?= Yii::$service->page->translate->__('do you readly want delete this address?') ?>");
                if (r==true){
                    url = "<?= Yii::$service->url->getUrl('customer/address') ?>";
                    doPost(url, {"method": "remove", "address_id": address_id, "<?= CRequest::getCsrfName() ?>": "<?= CRequest::getCsrfValue() ?>" });
                }
            }
		</script>
	</div>
	
	<div class="clear"></div>
</div>
	