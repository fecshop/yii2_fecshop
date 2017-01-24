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
 * Helper services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Helper extends Service
{
	protected $_app_name;
	
	/**
	 * 得到当前的app入口的名字，譬如 appfront apphtml5  appserver等
	 */
	public function getAppName(){
		return   Yii::$app->params['appName'];
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}