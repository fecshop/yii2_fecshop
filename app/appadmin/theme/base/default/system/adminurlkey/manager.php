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
	<form id="pagerForm"  method="post" action="<?= $saveUrl ?>"  onsubmit="return navTabReflush(this);" >
		<?php echo CRequest::getCsrfInputHtml();  ?>	
		<div layouth="56" class="pageFormContent" style="height: 240px; overflow: auto;">
            <div style="padding:10px;">
                <label style="float: none; display: block;width: 900px;">请将表 admin_url_key 导出的sql复制进来（只复制您添加的sql部分即可），更详细的介绍参看：
                    <a target="_blank" style="color:blue" href="http://www.fecmall.com/doc/fecshop-guide/addons/cn-2.0/guide-fecmall-addons-developer-admin-url-key-tools.html">Fecmall-后台菜单Admin Url Key Gii工具</a>
                </label><br/>
                <textarea style="padding:10px;display:block;width:700px;height:200px;" name="editFormData[sql]"><?= $sqlStr ?></textarea>
                <br>
                <p style="width:100%;margin:20px 0 0px ;">结果：<p>
                <textarea style="padding:10px;display:block;width:700px;height:200px;"><?= $generateStr ?></textarea>
            </div>
		</div>
	
		<div class="formBar">
			<ul>
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
    function navTabReflush(form, navTabId){
        var $form = $(form);
        navTab.reload($form.attr('action'), {data: $form.serializeArray(), navTabId:navTabId});
        
        return false;
    }
    
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
                        message = data.message;
                        alertMsg.correct(message);
                        navTab.reloadFlag('page1');
                    } else if (data.statusCode == 300){
                        message = data.message;
                        alertMsg.error(message)
                    } else {
                        alertMsg.error("错误");
                    }
                },
                error:function(){}
            });
        });
    });

</script>
