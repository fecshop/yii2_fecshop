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
	 * 该变量用于：在配置文件中，配置所有的货币参数。
	 * 格式如下：
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
	 * 基础货币，产品的价格，填写的都是基础货币的价格。
	 * 该值需要在配置文件中进行配置
	 */
	public $baseCurrecy ;
	/**
	 * 网站的默认货币，需要注意的是，默认货币不要和基础货币混淆，举例：
	 * 后台产品统一使用的美元填写产品价格，但是我的网站前端的默认货币为人民币。
	 * 该值需要在配置文件中进行配置
	 */
	public $defaultCurrency = 'USD';
	/**
	 * 当前的货币简码
	 */
	protected $_currentCurrencyCode;
	/**
	 * 根据配置，保存所有货币的配置信息。
	 */
	protected $_currencys;
	
	
	/**
	 * @property $currencyCode | string 货币简码，譬如USD,RMB等
	 * @return Array 
	 * 如果不传递参数，得到所有的货币
	 * 如果传递参数，得到的是当前货币的信息。
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
	 * 得到当前货币的符号，譬如￥ $ 等。
	 * 如果当前的货币在配置中找不到，则会强制改成默认货币
	 */
	protected function actionGetCurrentSymbol(){
		if(isset($this->currencys[$this->getCurrentCurrency()]['symbol'])){
			return $this->currencys[$this->getCurrentCurrency()]['symbol'];
		}
	}
	
	/**
	 * @property $currencyCode | 货币简码
	 * 得到货币的符号，譬如￥ $ 等。
	 */
	protected function actionGetSymbol($currencyCode){
		if(isset($this->currencys[$currencyCode]['symbol'])){
			return $this->currencys[$currencyCode]['symbol'];
		}
	}
	/**
	 * property $price|Float ，默认货币的价格
	 * Get current currency price.  price format is two decimal places, 
	 * if current currency is not find in object variable $currencys(maybe change config in online shop,but current user session is effective),
	 * current currency will set defaultCurrency, origin price will be return.
	 * 通过传递默认货币的价格，得到当前货币的价格。
	 */
	protected function actionGetCurrentCurrencyPrice($price){
		
		
		if(isset($this->currencys[$this->getCurrentCurrency()]['rate'])){
			$rate = $this->currencys[$this->getCurrentCurrency()]['rate'];
			if($rate)
				return ceil($price * $rate  * 100)/100;
		}
		/**
		 * 如果上面出现错误，当前的货币在货币配置中找不到，则会使用默认货币
		 * 这种情况可能出现在货币配置调整的过程中，找不到则会被强制改成默认货币。
		 */
		$this->setCurrentCurrency($this->baseCurrecy);
		return $price;
	}
	/**
	 * @property $current_price | Float 当前货币下的价格
	 * @return 基础货币下的价格
	 * 通过当前的货币价格得到基础货币的价格，这是一个反推的过程，
	 * 需要特别注意的是：这种反推方法换算得到的基础货币的价格，和原来的基础货币价格，
	 * 可能有0.01的误差，因为默认货币换算成当前货币的算法为小数点后两位进一法得到的。
	 */
	protected function actionGetBaseCurrencyPrice($current_price,$current_currency=''){
		if(!$current_currency){
			$current_currency = $this->getCurrentCurrency();
		}
		if(isset($this->currencys[$current_currency]['rate'])){
			$rate = $this->currencys[$current_currency]['rate'];
			if($rate)
				return ceil($current_price / $rate  * 100)/100;
		}
	}
	/**
	 * @property $currencyCode | 货币简码
	 * 初始化货币信息，在service Store bootstrap(Yii::$app->store->bootstrap()), 中会被调用
	 * 1. 如果 $this->defaultCurrency 和 $this->baseCurrecy 没有设置，将会报错。
	 * 2. 如果 传递参数$currencyCode为空，则会使用默认货币
	 */
	protected function actionInitCurrency($currencyCode=''){
		if(!$this->defaultCurrency){
			throw new InvalidConfigException('defautlt currency must config');
		}
		if(!$this->baseCurrecy){
			throw new InvalidConfigException('base currency must config');
		}
		if(!$this->getCurrentCurrency()){
			if(!$currencyCode){
				$currencyCode = $this->defaultCurrency;
			}
			$this->setCurrentCurrency($currencyCode);
		}
		
	}
	/**
	 * @property $currencyCode | String ， 货币简码，如果参数$currencyCode为空，则取当前的货币简码
	 * @return Array
	 * 得到货币的详细信息,数据格式如下：
	 * [
	 *		'code' 		=> $code ,
	 *		'rate' 		=> $rate ,
	 *		'symbol' 	=> $symbol ,
	 *	]
	 */
	protected function actionGetCurrencyInfo($currencyCode = ''){
		if(!$currencyCode)
			$currencyCode = $this->getCurrentCurrency();
		return $this->getCurrencys($currencyCode);
	}
	/**
	 * 得到当前的货币。
	 */
	protected function actionGetCurrentCurrency(){
		
		if(!$this->_currentCurrencyCode)
			$this->_currentCurrencyCode = CSession::get(self::CURRENCY_CURRENT);
		return $this->_currentCurrencyCode;
	}
	/**
	 * @property $currencyCode | String， 当前的货币简码
	 * 设置当前的货币。
	 */
	protected function actionSetCurrentCurrency($currencyCode){
		if(!$this->isCorrectCurrency($currencyCode)){
			$currencyCode = $this->defaultCurrency;
		}
		if($currencyCode){
			CSession::set(self::CURRENCY_CURRENT,$currencyCode);
			return true;
		}
		
	}
	/**
	 * @property $currency | String 货币简码
	 * @return boolean
	 * 检测当前传递的货币简码，是否在配置中存在，如果存在则返回true
	 */
	protected function isCorrectCurrency($currencyCode){
		if(isset($this->currencys[$currencyCode])){
			return true;
		}else{
			return false;
		}
	}
	
}