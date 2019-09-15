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
/** 
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>
<style>
.checker{float:left;}
.dialog .pageContent {background:none;}
.dialog .pageContent .pageFormContent{background:none;}
</style>

<div class="pageContent"> 
	<form  method="post"  class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
                <input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#009688"><?= Yii::$service->page->translate->__('Edit Info') ?></legend>
					<div>
						<?= $editBar; ?>
					</div>
				</fieldset>
				<?= $lang_attr ?>
				<?= $textareas ?>
				
		</div>
        
	</form>
</div>	

<style>
.pageFormContent p{
    width:100%;
}
.pageFormContent input.textInput{
    width:80%;
}
</style>
