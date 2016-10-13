<div class="main container two-columns-left">
	
	<div class="col-main account_center">
		
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
	