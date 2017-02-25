<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\helper;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\services\Service;
use fecshop\models\mongodb\FecshopServiceLog;
/**
 * AR services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
# use \fecshop\services\helper\Format;
class Format extends Service
{
	/**
	 * @property $number | Float
	 * @property $bits | Int
	 * @return $number | Float 
	 * 返回格式化形式的
	 */
	public function number_format($number,$bits=2){
		return number_format($number,$bits,'.','');
		//$n = pow(10,$bits);
		//$number = ceil ($number * $n ) / $n ;
		//$number = number_format();
		//return $number;
	}
	
	
	
}