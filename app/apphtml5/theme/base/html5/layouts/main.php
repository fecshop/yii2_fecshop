<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php
$jsOptions = [
	# js config 1
	[
		'options' => [
			'position' =>  'POS_END',
		//	'condition'=> 'lt IE 9',
		],
		'js'	=>[
			'js/zepto.min.js',
			'js/sm.min.js',
			'js/sm-extend.min.js',
            'js/fec.js',
		],
	],
];


# css config
$cssOptions = [
	# css config 1.
	[
		'css'	=>[
			'css/sm.min.css',
			'css/sm-extend.min.css',
			'css/fec.css',
		],
	],
];
\Yii::$service->page->asset->jsOptions 	= \yii\helpers\ArrayHelper::merge($jsOptions, \Yii::$service->page->asset->jsOptions);
\Yii::$service->page->asset->cssOptions = \yii\helpers\ArrayHelper::merge($cssOptions, \Yii::$service->page->asset->cssOptions);				
\Yii::$service->page->asset->register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
<?= Yii::$service->page->widget->render('base/head',$this); ?>
</head>
<body>
<?= Yii::$service->page->widget->render('base/beforeContent',$this); ?>
<?php $this->beginBody() ?>
	<div class="page-group">
		<div class="page">
			<?= Yii::$service->page->widget->render('base/header',$this); ?>
			<div class="content" id=''>
				<?= $content; ?>
			</div>
		</div>
		<?= Yii::$service->page->widget->render('base/menu',$this); ?>
        <?= Yii::$service->page->widget->render('base/trace',$this); ?>
	</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
