<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="main container one-column">
	<div class="col-main">
		<div class="content-404 text-center">
			<img class="image404" src="<?=  Yii::$service->image->getImgUrl('images/404.png','apphtml5') ?>" class="img-responsive" alt=""  />
			<h1><b><?= Yii::$service->page->translate->__('OPPS!'); ?></b> <?= Yii::$service->page->translate->__('We Couldn’t Find this Page'); ?></h1>
			<p><?= Yii::$service->page->translate->__('Please contact us if you think this is a server error, Thank you.'); ?></p>
			<h2><a href="<?= Yii::$service->url->homeUrl(); ?>"><?= Yii::$service->page->translate->__('Bring me back Home'); ?></a></h2>
		</div>
		<!--
		<div class="site-error">

			<h1><?= \Yii::$service->helper->htmlEncode($this->title) ?></h1>

			<div class="alert alert-danger">
				<?= nl2br(\Yii::$service->helper->htmlEncode($message)) ?>
			</div>

			<p>
				The above error occurred while the Web server was processing your request.
			</p>
			<p>
				Please contact us if you think this is a server error. Thank you.
			</p>

		</div>
		-->
</div>
