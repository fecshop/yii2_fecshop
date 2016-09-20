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
	/**
	 * 当产品的special_price 大于 price 的时候，是否以 price 为准。
	 */
	public $ifSpecialPriceGtPriceFinalPriceEqPrice;
	
	protected $_currencyInfo;
	
	/**
	 * @property  $price 		 | Float  产品的价格 
	 * 得到当前货币状态下的产品的价格信息。
	 */
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
	/**
	 * 得到单个产品的最终价格。
	 * @property  $price 		 | Float  产品的价格
	 * @property  $special_price | Float  产品的特价
	 * @property  $special_from  | Int    产品的特检开始时间
	 * @property  $special_to    | Int    产品的特检结束时间
	 * @return    Float
	 */
	protected function actionGetFinalPrice($price,$special_price,$special_from,$special_to){
		if($this->specialPriceisActive($price,$special_price,$special_from,$special_to)){
			return $special_price;
		}else{
			return $price;
		}
	}
	/**
	 * 判断产品的special_price是否有效，下面几种情况会无效
	 * 1. $special_price为空
	 * 2. 产品的$special_price 大于 $price，并且，ifSpecialPriceGtPriceFinalPriceEqPrice设置为true
	 * 3. 当前的时间不在 特价时间范围内
	 * @property  $price 		 | Float  产品的价格
	 * @property  $special_price | Float  产品的特价
	 * @property  $special_from  | Int    产品的特检开始时间
	 * @property  $special_to    | Int    产品的特检结束时间
	 * @return    boolean
	 
	 */
	protected function actionSpecialPriceisActive($price,$special_price,$special_from,$special_to){
		if(!$special_price){
			return false;
		}
		if($this->ifSpecialPriceGtPriceFinalPriceEqPrice){
			if($special_price > $price){
				return false;
			}
		}	
		$nowTimeStamp = time();
		if($special_from){
			if($special_from > $nowTimeStamp){
				return false;
			}
		}
		if($special_to){
			if($special_to < $nowTimeStamp){
				return false;
			}
		}
		return true;
	}
	/**
	 * 得到当前的货币信息，并保存到对象属性中，方便多次调用
	 */
	protected function getCurrentInfo(){
		if(!$this->_currencyInfo){
			$this->_currencyInfo = Yii::$service->page->currency->getCurrencyInfo();
		}
		return $this->_currencyInfo;
	}
	
	/**
	 * 通过该函数，得到产品的价格信息，如果特价是active的，则会有特价信息。
	 * @property  $price 		 | Float  产品的价格
	 * @property  $special_price | Float  产品的特价
	 * @property  $special_from  | Int    产品的特检开始时间
	 * @property  $special_to    | Int    产品的特检结束时间
	 * @return    $return        | Array  产品的价格信息
	 */
	protected function actionGetCurrentCurrencyProductPriceInfo($price,$special_price,$special_from,$special_to){
		$price_info = $this->formatPrice($price);
		$return['price'] = [
			'symbol' 	=> $price_info['symbol'],
			'value' 	=> $price_info['value'],
			'code' 		=> $price_info['code'],
		];
		$specialIsActive = $this->specialPriceisActive($price,$special_price,$special_from,$special_to);
		if($specialIsActive){
			$special_price_info = Yii::$service->product->price->formatPrice($special_price);
			$return['special_price'] = [
				'symbol' 	=> $special_price_info['symbol'],
				'value' 	=> $special_price_info['value'],
				'code' 		=> $special_price_info['code'],
			];
		}
		return $return;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}


