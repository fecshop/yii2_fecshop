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
			'js/js.js',
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
\Yii::$service->page->asset->jsOptions 	= $jsOptions;
\Yii::$service->page->asset->cssOptions = $cssOptions;				
\Yii::$service->page->asset->register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
<?= Yii::$service->page->widget->render('head',$this); ?>
</head>
<body>
<?php $this->beginBody() ?>
	<div class="page-group">
		<div class="page">
			<?= Yii::$service->page->widget->render('header',$this); ?>
			
			<div class="content" >
				<?= $content; ?>
			</div>
		</div>
		<?= Yii::$service->page->widget->render('menu',$this); ?>
	</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
