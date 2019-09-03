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
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				<div class="page-title">
					<h2><?= Yii::$service->page->translate->__('Customer Address');?></h2>
				</div>
				<table class="addressbook" width="100%" cellspacing="0" cellpadding="0" border="0">
					<thead>
						<tr class="ress_tit">
							<th width="76" valign="middle" align="center" height="31"><?= Yii::$service->page->translate->__('First Name');?></th>  
							<th width="72" valign="middle" align="center" height="31"><?= Yii::$service->page->translate->__('Last Name');?></th>                                                                                       
							<th width="167" valign="middle" align="center"><?= Yii::$service->page->translate->__('Email Address');?></th>
							<th width="67" valign="middle" align="center"><?= Yii::$service->page->translate->__('Country');?></th>
							<th width="79" valign="middle" align="center"><?= Yii::$service->page->translate->__('State');?></th>
							
							<th width="81" valign="middle" align="center"> <?= Yii::$service->page->translate->__('Zip Code');?> </th>
							<th width="101" valign="middle" align="center"><?= Yii::$service->page->translate->__('Telephone');?> </th>
							<th class="th3" width="71" valign="middle" align="center"><?= Yii::$service->page->translate->__('Operation');?></th>
						</tr>
					</thead>
					<tbody>
					<?php   if(is_array($coll) && !empty($coll)):   ?>
					<?php 		foreach($coll as $one): ?>
						<tr class="">
							<td valign="top" align="center"><?= $one['first_name'] ?></td>
							<td valign="top" align="center"><?= $one['last_name'] ?></td>
							<td valign="top" align="center"><?= $one['email'] ?></td>
							<td valign="top" align="center"><?= $one['country'] ?></td>
							<td valign="top" align="center"><?= $one['state'] ?></td>
							<td valign="top" align="center"><?= $one['zip'] ?></td>
							<td valign="top" align="center"><?= $one['telephone'] ?></td>
							<td class="ltp" valign="top ltp" align="center">
								<input onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/address/edit',['address_id' => $one['address_id']]); ?>'" class="cpointer" value="<?= Yii::$service->page->translate->__('Modify');?>" name="" type="button">
								<a href="javascript:deleteAddress(<?= $one['address_id'] ?>)"><?= Yii::$service->page->translate->__('Delete');?></a>
								<?php  if($one['is_default'] == 1): ?>
								<span style=" color:#cc0000"><?= Yii::$service->page->translate->__('Default');?></span> 
								<?php  endif; ?>								
							</td>
						</tr>	
					<?php 		endforeach; ?>
					<?php 	endif; ?>
					</tbody>
				</table>
				<div class="product-Reviews">
					<input onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/address/edit') ?>'" class="submitbutton addnew cpointer" value="<?= Yii::$service->page->translate->__('Add New Address');?>" name="" type="button">
					
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
	
	<div class="col-left ">
		<?= Yii::$service->page->widget->render('customer/left_menu', $this); ?>
	</div>
	<div class="clear"></div>
</div>
	