<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use fecadmin\myassets\AppAsset;
use common\widgets\Alert;
use fec\helpers\CUrl;
use fecadmin\views\layouts\Head;
use fecadmin\views\layouts\Footer;
use fecadmin\views\layouts\Header;
use fecadmin\views\layouts\Menu;


//AppAsset::register($this);

//$cssAndJs = Head::getJsAndCss();
//var_dump( $cssAndJs['js']);exit;
//$this->assetBundles["fecadmin\myassets\AppAsset"]->js 	= $cssAndJs['js'];
//$this->assetBundles["fecadmin\myassets\AppAsset"]->css = $cssAndJs['css'];

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>"  xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
<?php
//$publishedPath = $this->assetManager->publish('@fecadmin/myassets/dwz_jui-master/dwz.frag.xml');
 
 ?>

</head>
<body>
<?php $this->beginBody() ?>
 

343243245
	<div id="layout">
		<div id="header">
			
			
		</div>

		<?= $content ?>

	</div>
<footer class="footer">
    <div class="container">
       
    </div>
</footer>
	





<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
