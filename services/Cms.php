<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
/**
 * Cms services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Cms extends Service
{
	/**
	 * cms storage db, you can set value: mysqldb,mongodb.
	 */
	public $storage = 'mysqldb';
	
}