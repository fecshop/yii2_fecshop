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
				<label>账号：</label>
				<input    value="<?php echo $current_account; ?>" name="" id="username"  type="text" size="30" readonly="readonly"/>
			</p>
			<p>
				<label>旧密码：</label>
				<input class=" "  title="old Password" value="" name="updatepass[old_password]" id="old_password" type="password" size="30" />
			</p>
			<p>
			 
				<label>新密码：</label>
				<input    class=" "   title="New Password" value="" name="updatepass[new_password]" id="new_password" type="password" size="30" />		
			</p>
			<p>
				<label>密码确认：</label>
				
				<input class="" equalto="#new_password" value="" name="updatepass[password_repeat]" id="password_repeat" type="password"  size="30"  >
			</p>
			
		</div>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>


