<div class="main container two-columns-left">
	<div class="col-main account_center">
		<div class="std">
			<div style="margin:19px 0 0">
				<div class="page-title">
					<h2>Customer Address</h2>
				</div>
				<table class="addressbook" width="100%" cellspacing="0" cellpadding="0" border="0">
					<thead>
						<tr class="ress_tit">
							<th width="76" valign="middle" align="center" height="31">First Name</th>  
							<th width="72" valign="middle" align="center" height="31">Last Name</th>                                                                                       
							<th width="149" valign="middle" align="center">Region</th>
							<th width="207" valign="middle" align="center">Street</th>
							<th width="81" valign="middle" align="center"> Zip Code </th>
							<th width="101" valign="middle" align="center">Telephone </th>
							<th class="th3" width="71" valign="middle" align="center">Operation</th>
						</tr>
					</thead>
					<tbody>
						<tr class="">
							<td valign="top" align="center">firstname</td>
							<td valign="top" align="center">lastname</td>
							<td valign="top" align="center">city state Turks and Caicos Islands</td>
							<td valign="top" align="center">street2 street1</td>
							<td valign="top" align="center">233444</td>
							<td valign="top" align="center">32423432432</td>
							<td class="ltp" valign="top ltp" align="center">
								<input onclick="javascript:window.location.href='http://www.intosmile.com/customer/address/edit?address_id=1608'" class="cpointer" value="Modify " name="" type="button">
								<a href="javascript:deleteAddress(1608)">Delete</a>
								<span style=" color:#cc0000">Default</span>                                                                                                                                                                 
							</td>
						</tr>		
					</tbody>
				</table>
				<div class="product-Reviews">
					<input onclick="javascript:window.location.href='<?= Yii::$service->url->getUrl('customer/address/edit') ?>'" class="submitbutton addnew cpointer" value="Add New Address" name="" type="button">
					
				</div>
			</div>
		</div>

		<script>
		 function deleteAddress(address_id){
			var r=confirm(do you readly want delete this address?); 
			if (r==true){ 
				url = "<? Yii::$service->url->getUrl('customer/address') ?>?method=remove&address_id="+address_id;
				window.location.href=url;
			}
		 }

		</script>
	</div>
	
	<div class="col-left ">
		<?php
			$leftMenu = [
				'class' => 'fecshop\app\appfront\modules\Customer\block\LeftMenu',
				'view'	=> 'customer/leftmenu.php'
			];
		?>
		<?= Yii::$service->page->widget->render($leftMenu,$this); ?>
	</div>
	<div class="clear"></div>
</div>
	