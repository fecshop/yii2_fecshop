<div class="account-ds">
	<div class="bar bar-nav account-top-m">
		<a external class="button button-link button-nav pull-left" href="<?= Yii::$service->url->getUrl('customer/account/index'); ?>">
			<span class="icon icon-left"></span>
		</a>
		<h1 class='title'><?= Yii::$service->page->translate->__('Customer Address'); ?></h1>
	</div>
</div>
<?= Yii::$service->page->widget->render('flashmessage'); ?>


<div class="list-block customer-login  customer-register">



<div class="main container two-columns-left">
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:4px 0 0">
				<div class="page-title">
					<h2><?= Yii::$service->page->translate->__('Edit Account Information');?></h2>
				</div>
				<table class="addressbook" width="100%" cellspacing="0" cellpadding="0" border="0">
					<thead>
						<tr class="ress_tit">
							<th width="76" valign="middle" align="center" height="31"><?= Yii::$service->page->translate->__('Name');?></th>                                                                                        
							<th width="67" valign="middle" align="center"><?= Yii::$service->page->translate->__('Address');?></th>
							<th class="th3" width="71" valign="middle" align="center"><?= Yii::$service->page->translate->__('Operation');?></th>
						</tr>
					</thead>
					<tbody>
					<?php   if(is_array($coll) && !empty($coll)){   ?>
					<?php 		foreach($coll as $one){ ?>
						<tr class="">
							<td valign="top" align="center"><?= $one['first_name'].' '.$one['last_name'] ?></td>
							
							<td valign="top" align="center">
								<?= $one['street1'].' '.$one['street2'] ?><br>
								<?= $one['city'] ?> 
								<?= Yii::$service->helper->country->getStateByContryCode($one['country'],$one['state']); ?>
								<?= Yii::$service->helper->country->getCountryNameByKey($one['country']); ?>
							</td>
							<td class="ltp" valign="top ltp" align="center">
								<input onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/address/edit',['address_id' => $one['address_id']]); ?>'" class="cpointer" value="<?= Yii::$service->page->translate->__('Modify');?>" name="" type="button">
								<a href="javascript:deleteAddress(<?= $one['address_id'] ?>)"><?= Yii::$service->page->translate->__('Delete');?></a>
								<?php  if($one['is_default'] == 1){ ?>
								<span style=" color:#cc0000"><?= Yii::$service->page->translate->__('Default');?></span> 
								<?php  } ?>								
							</td>
						</tr>	
					<?php 		} ?>
					<?php 	} ?>
					</tbody>
				</table>
				<div class="product-Reviews">
					<input onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/address/edit') ?>'" class="submitbutton addnew cpointer" value="<?= Yii::$service->page->translate->__('Add New Address');?>" name="" type="button">
					
				</div>
			</div>
		</div>

		<script>
		 function deleteAddress(address_id){
			var r=confirm('do you readly want delete this address?'); 
			if (r==true){ 
				url = "<?= Yii::$service->url->getUrl('customer/address') ?>?method=remove&address_id="+address_id;
				
				window.location.href=url;
			}
		 }

		</script>
	</div>
	
	<div class="col-left ">
		<?php
			$leftMenu = [
				'class' => 'fecshop\app\apphtml5\modules\Customer\block\LeftMenu',
				'view'	=> 'customer/leftmenu.php'
			];
		?>
		<?= Yii::$service->page->widget->render($leftMenu,$this); ?>
	</div>
	<div class="clear"></div>
</div>
	