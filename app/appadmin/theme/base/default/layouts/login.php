<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use fecadmin\myassets\LoginAsset;
use common\widgets\Alert;
use fec\helpers\CUrl;
use fecadmin\views\layouts\Head;
use fecadmin\views\layouts\Footer;
use fecadmin\views\layouts\Header;
use fecadmin\views\layouts\Menu;
LoginAsset::register($this);
?>

<?php
$login_logoPath = $this->assetManager->publish('@fecadmin/myassets/dwz_jui-master/themes/default/images/login_logo.gif');
$login_titlePath = $this->assetManager->publish('@fecadmin/myassets/dwz_jui-master/themes/default/images/login_title.png');
$header_bgPath = $this->assetManager->publish('@fecadmin/myassets/dwz_jui-master/themes/default/images/header_bg.png');
$login_bannerPath = $this->assetManager->publish('@fecadmin/myassets/dwz_jui-master/themes/default/images/login_banner.jpg');
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
 <div id="login">
		<div id="login_header">
			<h1 class="login_logo">
				<!--  <img src="<?= $login_logoPath[1] ?>" /> -->
				<a href="<?= Yii::$app->getHomeUrl()  ?>" style="font-size:35px;text-decoration:none;
				color:#6f8992;"><img src="<?= CUrl::getHomeUrl(); ?>/skin/default/images/blue_logo.png"></a>
			</h1>
			<div class="login_headerContent">
				<div class="navList">
					<ul>
						<li><a target="_blank" href="http://www.fecshop.com/first">设为首页</a></li>
						<li><a target="_blank"  href="http://www.fecshop.com/topic">反馈</a></li>
						<li><a target="_blank"  href="http://www.fecshop.com/topic" target="_blank">帮助</a></li>
					</ul>
				</div>
				<h2 class="login_title">
				<!-- <img src="<?= $login_titlePath[1] ?>" /> -->
				<a target="_blank" href="http://www.fecshop.com" style="font-size:15px;text-decoration:none;
				color:#6f8992;">登录FecShop后台</a>
				</h2>
			</div>
		</div>
		<div id="login_content">
			<div class="loginForm">
				<?= $content; ?>
			</div>
			<div class="login_banner"><img src="<?= $login_bannerPath[1] ?>" /></div>
			<div class="login_main">
				<ul class="helpList">
					<li><a target="_blank" href="http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-appadmin-about.html">功能简介</a></li>
					<li><a target="_blank" href="http://www.fecshop.com/first">Fecshop 详细说明</a></li>
					<li><a target="_blank" href="http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-appadmin-about.html">如何快速开始？</a></li>
				</ul>
				<div class="login_inner">
					<p> <a  style="text-decoration:none;color:#333" target="_blank" href="http://www.fecshop.com">Fecshop</a>的后台管理</p>
					
					<p></p>
				</div>
			</div>
		</div>
		<div id="login_footer">
			Copyright &copy; 2016 <a style="text-decoration:none" target="_blank" href="http://www.fecshop.com">www.fecshop.com</a> Inc. All Rights Reserved.
		</div>
	</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
