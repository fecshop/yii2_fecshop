<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
$dir = __DIR__ . '/../../../yiisoft/yii2';
require($dir.'/BaseYii.php');
 /**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Yii extends \yii\BaseYii
{
	public static $service;
	
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require($dir.'/classes.php');
Yii::$container = new yii\di\Container();
