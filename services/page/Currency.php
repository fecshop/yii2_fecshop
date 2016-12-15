<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\page;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fecshop\services\Service;
/**
 * Currency
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Currency extends Service
{
	
	const CURRENCY_CURRENT = 'currency_current';
	
	/**
	 * get all currency ,format:
	 * [
	 * 		'USD' => [
	 * 			'rate' 		=> 1,
	 * 			'symbol' 	=> '$',
	 * 		],
	 * 		'RMB' => [
	 * 			'rate' 		=> 6.3,
	 * 			'symbol' 	=> 'гд',
	 * 		],
	 * ]
	 * 
	 */
	public $currencys;
	/**
	 * base currency; product price value is  base currency value, 
	 */
	public $baseCurrecy ;
	/**
	 * default currency; if store is not set currency  , $defaultCurrency will set to this store
	 */
	public $defaultCurrency = 'USD';
	protected $_currentCurrency;
	protected $_currencys;
	
	/**
	 * Get all currencys info.
	 */
	protected function actionGetCurrencys($currencyCode=''){
		if(!$this->_currencys){
			foreach($this->currencys as $code => $info){
				$this->_currencys[$code] = [
					'code' 		=> $code ,
					'rate' 		=> $info['rate'] ,
					'symbol' 	=> $info['symbol'] ,
				];
			}
		}
		
		if($currencyCode)
			return $this->_currencys[$currencyCode];
		return $this->_currencys;
	}
	
	
	/**
	 * property $price|Float 
	 * Get current currency price.  price format is two decimal places, 
	 * if current currency is not find in object variable $currencys(maybe change config in online shop,but current user session is effective),
	 * current currency will set defaultCurrency, origin price will be return.
	 */
	protected function actionGetCurrentCurrencyPrice($price){
		
		
		if(isset($this->currencys[$this->getCurrentCurrency()]['rate'])){
			$rate = $this->currencys[$this->getCurrentCurrency()]['rate'];
			if($rate)
				return ceil($price * $rate  * 100)/100;
		}
		/**
		 * if error current will be set to default currency.
		 */
		$this->setCurrentCurrency($this->baseCurrecy);
		return $price;
	}
	/**
	 * 通过当前的货币价格得到默认货币的价格
	 */
	protected function actionGetDefaultCurrencyPrice($current_price){
		if(isset($this->currencys[$this->getCurrentCurrency()]['rate'])){
			$rate = $this->currencys[$this->getCurrentCurrency()]['rate'];
			if($rate)
				return ceil($current_price / $rate  * 100)/100;
		}
	}
	/**
	 * service Store bootstrap(Yii::$app->store->bootstrap()),
	 * call this function to init currency.
	 * 1. if current currency is set (get value from session), none will be done.
	 * 2. if store pass currency to this function, current currency will equals store currency.
	 * 3. if store not pass currency to this function ,defaultCurrency will be set.
	 */
	protected function actionInitCurrency($currency=''){
		if(!$this->getCurrentCurrency()){
			if(!$currency)
				$currency = $this->defaultCurrency;
			$this->setCurrentCurrency($currency);
		}
		
	}
	
	protected function actionGetCurrencyInfo($code=''){
		if(!$code)
			$code = $this->getCurrentCurrency();
		return $this->getCurrencys($code);
	}
	
	protected function actionGetCurrentCurrency(){
		
		if(!$this->_currentCurrency)
			$this->_currentCurrency = CSession::get(self::CURRENCY_CURRENT);
		return $this->_currentCurrency;
	}
	
	protected function actionSetCurrentCurrency($currency){
		if($this->isCorrectCurrency($currency)){
			CSession::set(self::CURRENCY_CURRENT,$currency);
			return true;
		}
	}
	/**
	 * check param currency if is contained in object variable $currencys.
	 */
	protected function isCorrectCurrency($currency){
		foreach($this->currencys as $code => $info){
			if($code == $currency)
				return true;
		}
		return false;
	}
	
}