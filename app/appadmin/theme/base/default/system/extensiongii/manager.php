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

<form id="pagerForm" method="post" action="<?= \fec\helpers\CUrl::getCurrentUrl();  ?>">
	<?=  CRequest::getCsrfInputHtml();  ?>
</form>

<?php if (!$guest): ?>
<div class="pageContent systemConfig"> 
	<form  method="post" action="<?= $saveUrl ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
			
        
                <fieldset id="fieldset_table_qbe">
					<legend style="color:#888">Gii生成应用</legend>
					<div>
                        <p class="edit_p">
                            <label><?= Yii::$service->page->translate->__('Extension Package'); ?></label>
                            <input type="text" value="<?= $package ?>" size="30" name="editFormData[package]" class="textInput required">
                            <span class="remark-text">开发者的package包名，<b>必须使用英文命名</b>,  强烈建议您使用默认值，不需进行改动</span>
                        </p>
                        <!--
                        <p class="edit_p">
                            <label><?= Yii::$service->page->translate->__('Extension Folder'); ?></label>
                            <input type="text" value="" size="30" name="editFormData[addon_folder]" class="textInput required">
                            <span class="remark-text">应用文件名，也就是应用所在的文件夹的名字</span>
                        </p>
                        -->
						<p class="edit_p">
                            <label><?= Yii::$service->page->translate->__('Extension Namespace'); ?></label>
                            <input type="text" value="" size="30" name="editFormData[namespaces]" class="textInput required">
                            <span class="remark-text">应用的namespaces，<b>必须使用英文命名</b>, 为了保证唯一性，建议先去应用市场平台添加应用，以免和其他的应用发生冲突，导致无法发布应用</span>
                        </p>
                        <!--
                        <p class="edit_p">
                            <label><?= Yii::$service->page->translate->__('Extension Name'); ?></label>
                            <input type="text" value="" size="30" name="editFormData[addon_name]" class="textInput required">
                            <span class="remark-text">应用的名字，这个用于在分类中显示应用的名字，可以中文</span>
                        </p>
                        -->
                        <p class="edit_p">
                            <label><?= Yii::$service->page->translate->__('Extension Author'); ?></label>
                            <input type="text" value="<?= $addon_author ?>" size="30" name="editFormData[addon_author]" class="textInput required">
                            <span class="remark-text">应用的作者，建议您使用默认值即可</span>
                        </p>
                        
                    </div>
				</fieldset>
                
		</div>
	
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li>
                    <div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit"><?= Yii::$service->page->translate->__('Generate') ?></button></div></div>
                </li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close"><?= Yii::$service->page->translate->__('Cancel') ?></button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>	
<?php endif; ?>
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


<style>
.checker{float:left;}
.dialog .pageContent {background:none;}
.dialog .pageContent .pageFormContent{background:none;}
</style>


<script>
    $(document).ready(function(){
        var isGuest = <?=  $guest ? 'true' : 'false' ?> ;
        if (isGuest) {
            $(".accountLogin").click();
            var url = "<?= Yii::$service->url->getUrl('system/extensionmarket/login') ?>";
            var title = "用户登陆";
            var dlgId = '1';
            var options = {"width": "700","height":"480","mask":true,"drawable":true};
            $.pdialog.open(url, dlgId, title, options);　
        }
        $(document).off("click").on("click",".abutton",function(){
            namespace = $(this).attr('rel');
            var packageName = $(this).attr('packageName');
            var addonName = $(this).attr('addonName');
            var folderName = $(this).attr('folderName');
            
            var url = "<?= Yii::$service->url->getUrl("system/extensionmarket/install"); ?>";
            url += '?namespace=' + namespace;
            url += '&packageName=' + packageName;
            url += '&folderName=' + folderName;
            url += '&addonName=' + encodeURIComponent(addonName);
            
            $.ajax({
                url: url,
                async: true,
                timeout: 800000,
                dataType: 'json', 
                type: 'get',
                success:function(data, textStatus){
                    
                    if(data.statusCode == 200){
                        //alert(data.statusCode);
                        message = data.message;
                        alertMsg.correct(message);
                        navTab.reloadFlag('page1');
                    } else if (data.statusCode == 300){
                        message = data.message;
                        alertMsg.error(message)
                    } else {
                        alertMsg.error("错误");
                    }
                    //
                },
                error:function(){
                    
                }
            });
            
        });
        
    });


</script>
