<div class="main container two-columns-left">
	
	<div class="col-main account_center">
								

		<div class="std">
			<div style="margin:19px 0 0">
				<div class="page-title">
					<h2>My Dashboard</h2>
				</div>
				<div class="welcome-msg">
					<p class="hello"><strong>Hello,  !</strong></p>
					<p>From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.</p>
				</div>
				<div class="box-account box-info">
				  
					<div class="col2-set">
						<div class="col-1">
							<div class="box">
								<div class="box-title">
									<h3>Contact Information</h3>
									<a href="http://www.intosmile.com/customer/account/edit">Edit</a>
								</div>
								<div class="box-content">
									<div>
																	
										<span style="margin:0 10px;">xxxx@dsfasdfdssdf.com </span>
									</div>
									
								</div>
							</div>
						</div>
						
					</div>
					<div class="col2-set addressbook">
						
						
						<div class="col2-set">
							<div class="col-1">
								<div class="box">
									<div class="box-title">
										<h3>Address Book</h3>
										
									</div>
									<div class="box-content">
										<p>You Can Manager Your Address. </p>
										<a href="http://www.intosmile.com/customer/address">Manager Addresses</a>
									</div>
									
								</div>
							</div>
							<div class="col-2">
								<div class="box">
									<div class="box-title">
										<h3>Order</h3>
										
									</div>
									<div class="box-content">
										<p>You Can View Your Order. </p>
										<a href="http://www.intosmile.com/customer/order/index">View</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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
	