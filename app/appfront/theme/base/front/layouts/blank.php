<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?><?= Yii::$service->page->widget->render('base/beforeContent',$this); ?>
<?= $content; ?>
<?= Yii::$service->page->widget->render('base/trace',$this); ?>
	