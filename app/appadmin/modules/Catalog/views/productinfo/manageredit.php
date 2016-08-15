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
use fec\helpers\CUrl;
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
.edit_p{display:block;height:35px;}
.edit_p label{float:left;line-height: 20px;min-width:110px;}
.edit_p input{width:700px;}
.tabsContent .tabsContent .edit_p label{min-width:104px;}
</style>

<script>

$(document).ready(function(){
	$(document).off("change").on("change",".attr_group",function(){
		//alert(2222);
		options = {};
		val = $(this).val();
		pm = "?attr_group="+val;
		url = '<?= CUrl::getUrl("catalog/productinfo/manageredit"); ?>'+pm;
		$.pdialog.reload(url,options);
	});
	//$(".tabs").off("click").on("click",".tabsHeaderContent a",function(){
	//	
	//	initUI("#fieldset_table_qbe");
	//});
	
});

</script>

<div class="pageContent"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDoneCloseAndReflush);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		
		
		<div class="tabs" >
			<div class="tabsHeader">
				<div class="tabsHeaderContent">
					<ul>
						<li><a href="javascript:;"><span>基本信息</span></a></li>
						<li><a href="javascript:;"><span>Meta信息</span></a></li>
						<li><a href="javascript:;"><span>描述信息</span></a></li>
						<li><a href="javascript:;"><span>图片信息</span></a></li>
						<li><a href="javascript:;"><span>分类信息</span></a></li>
						<li><a href="javascript:;"><span>属性组信息</span></a></li>
						<li><a href="javascript:;"><span>自定义信息</span></a></li>
					</ul>
				</div>
			</div>
			<div class="tabsContent" style="height:500px;overflow:auto;">
				<div>
					<input type="hidden"  value="<?=  $product_id; ?>" size="30" name="product_id" class="textInput ">
				
					<fieldset id="fieldset_table_qbe">
						<legend style="color:#cc0000">产品属性组</legend>
						<div>
							<p class="edit_p">
								<?= $attrGroup ?>
							</p>
						</div>
					</fieldset>
					
					
					<?= $baseInfo ?>
				</div>
				
				<div><?= $metaInfo ?>
				</div>
				
				<div ><?= $descriptionInfo ?>
				</div>
				
				<div >
				</div>
				
				<div >
				</div>
				
				<div >
				</div>
				
				<div >
				</div>
				
			</div>
			<div class="tabsFooter">
				<div class="tabsFooterContent"></div>
			</div>
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

