<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
$this->title = 'Dashboard';

?>
<script>
	function  sbt(thiss, navTabAjaxDone){
		
		return validateCallback(thiss, navTabAjaxDone);
	}
</script>
<div class="pageContent">
	<form method="post" action="<?= $editUrl ?>" class="pageForm required-validate" onsubmit="return sbt(this, navTabAjaxDone);">
		<?php echo CRequest::getCsrfInputHtml();  ?>
		<div class="pageFormContent" layoutH="56" style="width:450px;">
			<p>
				<label><?= Yii::$service->page->translate->__('Account'); ?>：</label>
				<input    value="<?php echo $current_account; ?>" name="" id="username"  type="text" size="30" readonly="readonly"/>
			</p>
			<p>
				<label><?= Yii::$service->page->translate->__('Old Password'); ?>：</label>
				<input class=" "  title="old Password" value="" name="updatepass[old_password]" id="old_password" type="password" size="30" />
			</p>
			<p>
			 
				<label><?= Yii::$service->page->translate->__('New Password'); ?>：</label>
				<input    class=" "   title="New Password" value="" name="updatepass[new_password]" id="new_password" type="password" size="30" />		
			</p>
			<p>
				<label><?= Yii::$service->page->translate->__('Password Confirmation'); ?>：</label>
				
				<input class="" equalto="#new_password" value="" name="updatepass[password_repeat]" id="password_repeat" type="password"  size="30"  >
			</p>
			
		</div>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?= Yii::$service->page->translate->__('Save'); ?></button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?= Yii::$service->page->translate->__('Cancel'); ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>


