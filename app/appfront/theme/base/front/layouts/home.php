<?php
\Yii::$app->page->asset->register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en" id="sammyDress">
<head>
<?= Yii::$app->page->widget->render('head',$this); ?>
</head>
<body>
<?php $this->beginBody() ?>
	<header id="header">
		<?= Yii::$app->page->widget->render('header',$this); ?>
		<?= Yii::$app->page->widget->render('menu',$this); ?>
	</header>
	
	<div id="mainBox">
		<?= $content; ?>
	</div>
	<div class="footer-container">
		<?= Yii::$app->page->widget->render('footer',$this); ?>
	</div>
	<?= Yii::$app->page->widget->render('scroll',$this); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

