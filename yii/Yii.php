<?php
$dir = __DIR__ . '/../../../yiisoft/yii2';
require($dir.'/BaseYii.php');

/**
 * Yii is a helper class serving common framework functionalities.
 *
 * It extends from [[\yii\BaseYii]] which provides the actual implementation.
 * By writing your own Yii class, you can customize some functionalities of [[\yii\BaseYii]].
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Yii extends \yii\BaseYii
{
	public static $service;
	
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require($dir.'/classes.php');
Yii::$container = new yii\di\Container();
