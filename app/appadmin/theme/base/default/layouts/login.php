<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use fecadmin\myassets\LoginAsset;
use common\widgets\Alert;
use fec\helpers\CUrl;
# css config
$cssOptions = [
    # css config 1.
    [
        'css'	=>[
            'css/login.css',
        ],
    ],
];
\Yii::$service->page->asset->jsOptions 	= [];
\Yii::$service->page->asset->cssOptions = $cssOptions;
\Yii::$service->page->asset->register($this);
$logoPath = $this->assetManager->publish('@fecshop/app/appadmin/theme/base/default/assets/images/blue_logo.png');
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>"  xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<style>
    .navList{
        padding-left:24px;
    }
    .login_title {
        display: block;
        padding: 25px 0 0 33px;
    }
    input:-webkit-autofill, textarea:-webkit-autofill, select:-webkit-autofill{
        background-color:#fff !important;
    }
</style>
 <div id="login">
		<div id="login_header">
            <div class="login_headerContent">
                <div class="navList">
                    <ul>
                        <li><a target="_blank" href="http://www.fecshop.com/first"><?= Yii::$service->page->translate->__('Abouts'); ?></a></li>
                        <li><a target="_blank"  href="http://www.fecshop.com/topic"><?= Yii::$service->page->translate->__('Feedback'); ?></a></li>
                        <li><a target="_blank"  href="http://www.fecshop.com/topic" target="_blank"><?= Yii::$service->page->translate->__('Help'); ?></a></li>
                        <li>
                            <?php $currentLangCode = Yii::$service->admin->getCurrentLangCode() ?>
                            <?php $langArr = Yii::$service->admin->getLangArr() ?>
                            <select class="store_langs" style="font-size:10px;">
                                <?php foreach ($langArr as $code => $name): ?>
                                    <option  value="<?= $code ?>" <?= ($code == $currentLangCode) ? 'selected="selected"' : ''  ?>>
                                        <?= $name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

         <div id="login_content">
             <div class="loginForm">
                 <h1 class="login_logo">
                     <a href="<?= Yii::$app->getHomeUrl()  ?>" style="font-size:35px;text-decoration:none;
                    color:#6f8992;">
                         <img  style="height:40px" alt="Fecshop" src="<?= Yii::getAlias($logoPath[1]); ?>">
                     </a>
                 </h1>
                 <?= $content; ?>
             </div>
             <div class="login_main">
                 <ul class="helpList">
                     <li><a target="_blank" href="http://www.fecmall.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-appadmin-about.html"><?= Yii::$service->page->translate->__('Introduction'); ?></a></li>
                     <li><a target="_blank" href="http://www.fecmall.com/wendang"><?= Yii::$service->page->translate->__('Fecmall Detailed'); ?></a></li>
                     <li><a target="_blank" href="http://www.fecmall.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-appadmin-about.html"><?= Yii::$service->page->translate->__('How to get started quickly?'); ?></a></li>
                 </ul>

             </div>
         </div>
         <div id="login_footer">
             Copyright &copy; 2016 <a style="text-decoration:none" target="_blank" href="http://www.fecmall.com">www.fecmall.com</a> <?= Yii::$service->page->translate->__('Login Fecmall Admin'); ?>Inc. All Rights Reserved.
         </div>

	</div>
<?php $this->endBody() ?>
<script> 
　$(document).ready(function(){
        $(".store_langs").change(function(){
            $langCode = $(this).val();
            $.ajax({
                url:'<?= Yii::$service->url->getUrl('fecadmin/login/changelang')  ?>',
                async:true,
                timeout: 80000,
                dataType: 'json', 
                type:'get',
                data:{
                    'lang':$langCode,
                },
                success:function(data, textStatus){
                    if (data.status == "success"){
                        url = window.location.href;
                        arr = url.split("?");
                        window.location.href = arr[0];
                    } else {
                        
                    }
                },
                error:function(){
                    alert('error');
                }
            });
        });    
    
    });  
</script> 
                            
</body>
</html>
<?php $this->endPage() ?>
