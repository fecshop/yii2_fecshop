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
					<legend style="color:#cc0000">编辑信息</legend>
					<div>
						
						<p class="edit_p">
							<label>SPU</label>
							<input disabled value="<?= $review['product_spu'] ?>" size="30" name="" class="textInput" type="text">
						</p>
						
						<p class="edit_p">
							<label>产品ID</label>
							<input disabled value="<?= $review['product_id'] ?>" size="30" name="" class="textInput" type="text">
						</p>
						
						<?= $editBar; ?>
						
						<p class="edit_p">
							<label>store</label>
							<input disabled value="<?= $review['store'] ?>" size="30" name="" class="textInput" type="text">
						</p>
						
						<p class="edit_p">
							<label>语言</label>
							<input disabled value="<?= $review['lang_code'] ?>" size="30" name="" class="textInput" type="text">
						</p>
						
						
						<p class="edit_p">
							<label>评论日期</label>
							<input disabled value="<?= date('Y-m-d H:i:s',$review['review_date']); ?>" size="30" name="" class="textInput" type="text">
						</p>
						
					</div>
				</fieldset>
				
				
				<?= $lang_attr ?>
				
				<?= $textareas ?>
				
				
		</div>
	
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit">保存</button></div></div></li>
			
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	

