<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use yii\helpers\Html;
use fec\helpers\CRequest;
use fecadmin\models\AdminRole;
?>
<style>
.checker{float:left;}
.dialog .pageContent {background:none;}
.dialog .pageContent .pageFormContent{background:none;}
</style>

<div class="pageContent"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
			
				<input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
				
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000"><?= Yii::$service->page->translate->__('Edit Info'); ?></legend>
					<div>
						<?= $editBar; ?>
					</div>
				</fieldset>
				
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000"><?= Yii::$service->page->translate->__('Role Info'); ?></legend>
					
					<div>
							
						<div class="checkbox-list">
							<?php 
								$role_arr = AdminRole::getAdminRoleArr();
								foreach($role_arr as $id => $label){
									$checked = '';
									if(in_array($id,$role_ids)){
										$checked = 'checked="true"';
									}
							?>
							<label class="checkbox-inline">
								<div id="uniform-inlineCheckbox21" class="checker">
									<span><input <?= $checked; ?>  type="checkbox" value="<?= $id; ?>" id="inlineCheckbox" name="role[<?= $id; ?>]"></span>
								</div>
								<?= $label ?>
							</label>
							<?php
								}
							?>	
						</div>
					</div>
				</fieldset>
		</div>
	
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit"><?= Yii::$service->page->translate->__('Save'); ?></button></div></div></li>
			
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?= Yii::$service->page->translate->__('Cancel'); ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	

