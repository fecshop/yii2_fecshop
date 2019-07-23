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
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDoneCloseAndReflush);">
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
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000"><?= Yii::$service->page->translate->__('Example') ?></legend>
					<div>
							<b><?= Yii::$service->page->translate->__('Store Key') ?></b>: 
                            Example：fecshop.appfront.fancyecommerce.com,fecshop.appfront.fancyecommerce.com/fr
                            <br/> 
							<b><?= Yii::$service->page->translate->__('Language Name') ?></b>: Example: English, Français,中文
                            <br/> <br/> <b><?= Yii::$service->page->translate->__('Local Theme Dir') ?></b>: Example: @appfront/theme/terry/theme01
                            <br/> <br/> <b><?= Yii::$service->page->translate->__('Third Theme Dir') ?></b>: Example: @mmm/theme/terry/theme01,@mmm/theme/terry/theme02。 多个用逗号隔开
                            <br/> <br/> <b><?= Yii::$service->page->translate->__('Https Enable') ?></b>:  当前的store，是否是https
                            <br/> <br/> <b><?= Yii::$service->page->translate->__('Sitemap Dir') ?></b>: sitemap文件地址，例子：@appfront/web/sitemap.xml
                            <br/> <br/> 
                            
                            
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    </div>
				</fieldset>
		</div>
	
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li>
                    <div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit"><?= Yii::$service->page->translate->__('Save') ?></button></div></div>
                </li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?= Yii::$service->page->translate->__('Cancel') ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	

