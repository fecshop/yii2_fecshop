<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\product;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
/**
 * Product Service is the component that you can get product info from it.
 * @property Image|\fecshop\services\Product\Image $image ,This property is read-only.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Price extends Service
{
	protected $_currencyInfo;
	
	protected function actionFormatPrice($price){
		$currencyInfo = $this->getCurrentInfo();
		$price = $price * $currencyInfo['rate'];
		$price = ceil($price*100)/100;
		return [
			'code' 		=> $currencyInfo['code'],
			'symbol' 	=> $currencyInfo['symbol'],
			'value' 	=> $price,
		];
	}
	
	protected function getCurrentInfo(){
		if(!$this->_currencyInfo){
			$this->_currencyInfo = Yii::$service->page->currency->getCurrencyInfo();
		}
		return $this->_currencyInfo;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}


