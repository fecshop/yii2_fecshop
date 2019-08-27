<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
/** 
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>

<form id="pagerForm" method="post" action="<?= \fec\helpers\CUrl::getCurrentUrl();  ?>">
	<?=  CRequest::getCsrfInputHtml();  ?>
</form>

<div class="pageContent">
    <div style="margin:100px 100px 10px 100px;">
	<?php if (is_array($addon_list)) : ?>
        <ul>
        <?php foreach ($addon_list as $addon_one): ?>
            <li class="addon_li">
                <div class="addon_d">
                    <img style="" src="<?= $addon_one['addon_info']['image'] ?>" />
                    <h2 style="margin10px auto"><?= $addon_one['addon_info']['name'] ?></h2>
                </div>
                <div class="clear"></div>
                <div style="margin-top:60px;">
                <?php    
                    $namespace = $addon_one['addon_info']['namespace'];
                    if (in_array($namespace, $installed_extensions_namespace)):
                        if ( version_compare($versionArr[$namespace], $addon_one['addon_info']['version'] ,'<') ):
                        
                ?>
                            <a class="abutton-update" href="javascript:void(0)"  addonName="<?= $addon_one['addon_info']['name'] ?>" rel="<?= $namespace ?>" folderName="<?= $addon_one['addon_info']['folder'] ?>"  packageName="<?= $addon_one['addon_info']['package'] ?>">需要升级</a>
                        <?php else: ?>
                            <a class="abutton-normal" href="javascript:void(0)">最新版本</a>
                        <?php endif; ?>
                 
                    
                <?php else: ?>
                    <a class="abutton" href="javascript:void(0)" addonName="<?= $addon_one['addon_info']['name'] ?>" rel="<?= $namespace ?>"  folderName="<?= $addon_one['addon_info']['folder'] ?>"  packageName="<?= $addon_one['addon_info']['package'] ?>">点击安装</a>
                
                <?php endif; ?>
                    
                    <div class="version_info">
                        <div class="">最高版本: <?= $addon_one['addon_info']['version'] ?></div>
                        <?php if($versionArr[$namespace]): ?>
                        <div style="margin-top:5px;">当前版本: <?= $versionArr[$namespace] ?></div>
                        <?php endif; ?>
                    </div>
                    
                </div>
                <a href="javascript:void(0)" class="removeAddon" title="卸载应用"  addonName="<?= $addon_one['addon_info']['name'] ?>" rel="<?= $namespace ?>" folderName="<?= $addon_one['addon_info']['folder'] ?>"  packageName="<?= $addon_one['addon_info']['package'] ?>">
                    <i class="fa fa-trash-o"></i>
                </a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    </div>
   
</div>

<style>
.removeAddon{
    display: block;
    bottom: 10px;
    position: absolute;
    right: 10px;
    font-size:18px;
    color:#999;
}
.removeAddon:hover{
    color:#333;
}

.version_info{
    float: right;
    margin-right: 10px;
}
.addon_li{
    width: 280px;
    height:390px;
    display: inline-block;
    margin: 10px;
    border: 1px solid #ccc;
    position: relative;
}
.addon_d{
    width:230px;
    margin:auto;
}
.addon_d img {
    width:230px;
}

.addon_d h2 {
    width:230px;
    display:block;
    margin:20px auto;
    text-align:center;
}

.abutton{
    background:#009688  !important;
    color:#fff;
    padding:5px 10px;
}

.abutton-update{
    background:#cc0000  !important;
    color:#fff;
    padding:5px 10px;
}

.abutton-normal{
    background:#337ab7 !important;
    color:#fff;
    padding:5px 10px;
}


.abutton:hover{
    opacity:0.8
}

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
        $(document).on("click",".removeAddon",function(){
            var self = this;
            alertMsg.confirm("您确定删除该应用吗？", {
                okCall: function(){
                    namespace = $(self).attr('rel');
                    var packageName = $(self).attr('packageName');
                    var addonName = $(self).attr('addonName');
                    var folderName = $(this).attr('folderName');
                    
                    var url = "<?= Yii::$service->url->getUrl("system/extensionmarket/uninstall"); ?>";
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
                    
                    
                    
                    
                },
                cancelCall : function() {
                    
                    
                }
            });
            
        });
        
        $(document).on("click",".abutton-update",function(){
            namespace = $(this).attr('rel');
            var packageName = $(this).attr('packageName');
            var addonName = $(this).attr('addonName');
            var folderName = $(this).attr('folderName');
            
            var url = "<?= Yii::$service->url->getUrl("system/extensionmarket/upgrade"); ?>";
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
