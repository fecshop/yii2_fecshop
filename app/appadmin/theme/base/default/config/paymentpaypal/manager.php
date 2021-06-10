<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
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

<div class="pageContent systemConfig"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
			
				<input type="hidden"  value="<?=  $_id; ?>" size="30" name="editFormData[_id]" class="textInput ">
				
				<fieldset id="fieldset_table_qbe">
					<legend style="color:#cc0000"><?= Yii::$service->page->translate->__('Payment Paypal Config') ?></legend>
					<div>
						<?= $editBar; ?>
					</div>
                    
				</fieldset>
                <div style="padding-left:100px;padding-top:40px;">
                    <b>注意1</b>：对于个人paypal账户收款，只需要填写<b>Paypal账户</b>即可，个人账户不支持paypal快捷支付（从购物车页面点击paypal按钮发起支付）
                    <br/><br/>
                    <b>注意2</b>：对于商户paypal账户收款，支持api授权，请您填写<b>Api账号信息</b>(就是尾部的三个api选项)，支付功能更为完善。
                </div>
				<?= $lang_attr ?>
				<?= $textareas ?>
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

<style>
.pageForm  .pageFormContent .edit_p{
    width:100%;
    line-height:35px;
}
.pageForm  .pageFormContent .edit_p .remark-text{
    font-size: 11px;
    color: #777;
    margin-left: 20px;
}
.pageForm   .pageFormContent p.edit_p label{
        width: 240px;
    line-height: 30px;
    font-size: 13px;
    font-weight: 500;
}

.pageContent .combox {
        margin-left:5px;
}
</style>





