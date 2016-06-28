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
use fecshop\services\ChildService;
/**
 * Currency
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Currency extends ChildService
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
	 * 			'symbol' 	=> '§Ô§Õ',
	 * 		],
	 * ]
	 * 
	 */
	public $currencys;
	/**
	 * default currency;
	 */
	public $defaultCurrency = 'USD';
	private $_currentCurrency;
	
	
	/**
	 * Get all currencys info.
	 */
	public function getAllCurrencys(){
		$arr = [];
		foreach($this->currencys as $code => $info){
			$arr[$code] = [
				'code' 		=> $code ,
				'rate' 		=> $info['rate'] ,
				'symbol' 	=> $info['symbol'] ,
			];
		}
		return $arr;
	}
	/**
	 * property $price|Float 
	 * Get current currency price.  price format is two decimal places, 
	 * if current currency is not find in object variable $currencys(maybe change config in online shop,but current user session is effective),
	 * current currency will set defaultCurrency, origin price will be return.
	 */
	public function getCurrentCurrencyPrice($price){
		if(isset($this->currencys[$this->getCurrentCurrency()]['rate'])){
			$rate = $this->currencys[$this->getCurrentCurrency()]['rate'];
			if($rate)
				return ceil($price * $rate  * 100)/100;
		}
		/**
		 * if error current will be set to default currency.
		 */
		$currency = $this->defaultCurrency ;
		$this->setCurrentCurrency($currency);
		return $price;
	}
	/**
	 * service Store bootstrap(Yii::$app->store->bootstrap()),
	 * call this function to init currency.
	 * 1. if current currency is set (get value from session), none will be done.
	 * 2. if store pass currency to this function, current currency will equals store currency.
	 * 3. if store not pass currency to this function ,defaultCurrency will be set.
	 */
	public function initCurrency($currency=''){
		if(!$this->getCurrentCurrency()){
			if(!$currency)
				$currency = $this->defaultCurrency;
			$this->setCurrentCurrency($currency);
		}
		
	}
	
	public function getCurrentCurrency(){
		if(!$this->_currentCurrency)
			$this->_currentCurrency = CSession::get(self::CURRENCY_CURRENT);
		return $this->_currentCurrency;
	}
	
	public function setCurrentCurrency($currency){
		if($this->isCorrectCurrency($currency)){
			CSession::set(self::CURRENCY_CURRENT,$currency);
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