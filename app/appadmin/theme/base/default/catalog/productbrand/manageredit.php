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
use fec\helpers\CUrl;

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
               <?= $lang_attr ?>
               <?= $textareas ?>
                    <fieldset id="fieldset_table_qbe">
                    <legend style="color:#009688"><?= Yii::$service->page->translate->__('Edit Info') ?></legend>
                    <div>
                        <?= $editBar; ?>
                    </div>
                    </fieldset>
                    <p class="edit_p">
                        <label style="float:none;display:inline-block;">Logo图片:</label>
                        <input type="hidden" class="textInput image" value="<?=  $image ?>" name="image" style="width:550px;">
                            <span style="width:80px;display:inline-block;">
                                <img style="width:70px;height:70px;<?= $image ? 'display:inline-block;' : 'display:none;'   ?>" class="cat_image" src="<?=  $imageurl ?>" />
                        </span>
                            <button style="" onclick="getElementById('inputimage').click()" class="scalable" type="button" title="Duplicate" id=""><span><span><span><?= Yii::$service->page->translate->__('Browse Files') ?></span></span></span></button>
                        <input type="file"  id="inputimage" style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/>
                        <a onclick="removeCategoryImage()" href="javascript:void(0)" style="font-size: 20px; margin-left: 10px;margin-top: 10px;display: inline-block;color: #555;" >
                            <i class="fa fa-trash-o"></i>
                        </a>
                        <br><br><br>
                   </p>
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
<script>

function removeCategoryImage () {
    var file = document.getElementById('inputimage');
    file.value = '';
    $(".cat_image").attr("src", "");
    $(".cat_image").hide();
    $(".image").val('');
    $("#inputimage").val('');
}
$(document).ready(function(){
	
	$("#inputimage").change(function(){
		var data = new FormData();
		$.each($('#inputimage')[0].files, function(i, file) {
			data.append('upload_file', file);
		});
        data.append("<?= CRequest::getCsrfName() ?>", "<?= CRequest::getCsrfValue() ?>");
		$.ajax({
			url:'<?= CUrl::getUrl('catalog/productbrand/imageupload'); ?>',
			type:'POST',
			data:data,
			async:false,
			dataType: 'json', 
			timeout: 80000,
			cache: false,
			contentType: false,		//不可缺参数
			processData: false,		//不可缺参数
			success:function(data, textStatus){
				if(data.return_status == "success"){
					$(".image").val(data.relative_path);
					$(".cat_image").attr("src",data.img_url);
                    $(".cat_image").show();
				}
			},
			error:function(){
				alert('<?= Yii::$service->page->translate->__('Upload Error') ?>');
			}
		});
	});
});
</script> 
