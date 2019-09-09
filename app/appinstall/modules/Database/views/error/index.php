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
			<h1><b>OPPS! 404</b> We Couldn’t Find this Page</h1>
			<p>Please contact us if you think this is a server error, Thank you.</p>
			<h2><a href="<?= Yii::$app->homeUrl; ?>">Bring me back Home</a></h2>
		</div>
	</div>	
</div>
