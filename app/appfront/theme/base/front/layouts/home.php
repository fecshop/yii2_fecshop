<?php
/* @var $this \yii\web\View */
/* @var $content string */
use fecshop\app\appfront\theme\base\front\assets\AppAsset;
AppAsset::register($this);
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
	<?php $this->head() ?>
<?php
//$publishedPath = $this->assetManager->publish('@fecadmin/myassets/dwz_jui-master/dwz.frag.xml');
?>
</head>
<body>
<?php $this->beginBody() ?>
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
