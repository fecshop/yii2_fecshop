<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\page;

use fecshop\services\Service;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Page Currency services 货币部分
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
     * ].
     */
    public $currencys;

    /**
     * 基础货币，产品的价格，填写的都是基础货币的价格。
     * 该值需要在配置文件中进行配置.
     */
    public $baseCurrecy;

    /**
     * 网站的默认货币，需要注意的是，默认货币不要和基础货币混淆，举例：
     * 后台产品统一使用的美元填写产品价格，但是我的网站前端的默认货币为人民币。
     * 该值需要在配置文件中进行配置.
     */
    public $defaultCurrency;

    /**
     * 当前的货币简码
     */
    protected $_currentCurrencyCode;

    /**
     * 根据配置，保存所有货币的配置信息。
     */
    protected $_currencys;
    
    public function init()
    {
        parent::init();
        // init default and base currency
        $this->defaultCurrency = Yii::$app->store->get('base_info', 'default_currency');
        $this->baseCurrecy = Yii::$app->store->get('base_info', 'base_currency');
        
        // init all currency
        $currencys = Yii::$app->store->get('currency');
        if (is_array($currencys)) {
            foreach ($currencys as $currency) {
                $currency_code = $currency['currency_code'];
                $currency_symbol = $currency['currency_symbol'];
                $currency_rate = $currency['currency_rate'];
                $this->currencys[$currency_code] = [
                    'rate' => $currency_rate,
                    'symbol' => $currency_symbol
                ];
            }
        }
    }
    
    /**
     * @param $currencyCode | string 货币简码，譬如USD,RMB等
     * @return array
     *               如果不传递参数，得到所有的货币
     *               如果传递参数，得到的是当前货币的信息。
     */
    protected function actionGetCurrencys($currencyCode = '')
    {
        if (!$this->_currencys) {
            foreach ($this->currencys as $code => $info) {
                $this->_currencys[$code] = [
                    'code'        => $code,
                    'rate'        => $info['rate'],
                    'symbol'    => $info['symbol'],
                ];
            }
        }
        if ($currencyCode) {
            if (isset($this->_currencys[$currencyCode])) {
                
                return $this->_currencys[$currencyCode];
            } else {
                $currencyCode = $this->defaultCurrency;
                
                return $this->_currencys[$currencyCode];
            }
        }

        return $this->_currencys;
    }

    /**
     * 得到当前货币的符号，譬如￥ $ 等。
     * 如果当前的货币在配置中找不到，则会强制改成默认货币
     */
    protected function actionGetCurrentSymbol()
    {
        if (isset($this->currencys[$this->getCurrentCurrency()]['symbol'])) {
            return $this->currencys[$this->getCurrentCurrency()]['symbol'];
        }
    }

    /**
     * @param $currencyCode | 货币简码
     * 得到货币的符号，譬如￥ $ 等。
     */
    protected function actionGetSymbol($currencyCode)
    {
        if (isset($this->currencys[$currencyCode]['symbol'])) {
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
    protected function actionGetCurrentCurrencyPrice($price)
    {
        $currencyCode  = $this->getCurrentCurrency();
        $currencyPrice = $this->getCurrencyPrice($price, $currencyCode);
        if ($currencyPrice !== null) {
            return $currencyPrice;
        }
        /*
         * 如果上面出现错误，当前的货币在货币配置中找不到，则会使用默认货币
         * 这种情况可能出现在货币配置调整的过程中，找不到则会被强制改成默认货币。
         */
        $this->setCurrentCurrency($this->baseCurrecy);

        return $price;
    }

    /**
     * property $price|Float ，默认货币的价格
     * property $currencyCode|String，货币简码,譬如 USD
     * 根据基础货币，得到相应货币的价格
     */
    protected function actionGetCurrencyPrice($price, $currencyCode)
    {
        if (isset($this->currencys[$currencyCode]['rate'])) {
            $rate = $this->currencys[$currencyCode]['rate'];
            if ($rate) {
                return bcmul($price, $rate, 2);
            }
        }
        
        return null;
    }

    /**
     * @param $current_price | Float 当前货币下的价格
     * @return 基础货币下的价格
     *                                  通过当前的货币价格得到基础货币的价格，这是一个反推的过程，
     *                                  需要特别注意的是：这种反推方法换算得到的基础货币的价格，和原来的基础货币价格，
     *                                  可能有0.01的误差，因为默认货币换算成当前货币的算法为小数点后两位进一法得到的。
     */
    protected function actionGetBaseCurrencyPrice($current_price, $current_currency = '')
    {
        if (!$current_currency) {
            $current_currency = $this->getCurrentCurrency();
        }
        if (isset($this->currencys[$current_currency]['rate'])) {
            $rate = $this->currencys[$current_currency]['rate'];
            if ($rate) {
                return bcdiv($current_price, $rate, 2);
            }
        }
    }

    /**
     * @param $currencyCode | 货币简码
     * 初始化货币信息，在service Store bootstrap(Yii::$app->store->bootstrap()), 中会被调用
     * 1. 如果 $this->defaultCurrency 和 $this->baseCurrecy 没有设置，将会报错。
     * 2. 如果 传递参数$currencyCode为空，则会使用默认货币
     */
    protected function actionInitCurrency($currencyCode = '')
    {
        if (!$this->defaultCurrency) {
            throw new InvalidConfigException('defautlt currency must config');
        }
        if (!$this->baseCurrecy) {
            throw new InvalidConfigException('base currency must config');
        }
        if (!$this->getCurrentCurrency()) {
            if (!$currencyCode) {
                $currencyCode = $this->defaultCurrency;
            }
            $this->setCurrentCurrency($currencyCode);
        }
    }

    /**
     * @param $currencyCode | String ， 货币简码，如果参数$currencyCode为空，则取当前的货币简码
     * @return array
     *               得到货币的详细信息,数据格式如下：
     *               [
     *               'code' 		=> $code ,
     *               'rate' 		=> $rate ,
     *               'symbol' 	=> $symbol ,
     *               ]
     */
    protected function actionGetCurrencyInfo($currencyCode = '')
    {
        if (!$currencyCode) {
            $currencyCode = $this->getCurrentCurrency();
        }

        return $this->getCurrencys($currencyCode);
    }

    /**
     * 得到当前的货币。
     */
    protected function actionGetCurrentCurrency()
    {
        if (!$this->_currentCurrencyCode) {
            $this->_currentCurrencyCode = Yii::$service->session->get(self::CURRENCY_CURRENT);
        }

        return $this->_currentCurrencyCode;
    }

    /**
     * @param $currencyCode | String， 当前的货币简码
     * 设置当前的货币。
     */
    protected function actionSetCurrentCurrency($currencyCode)
    {
        if (!$this->isCorrectCurrency($currencyCode)) {
            $currencyCode = $this->defaultCurrency;
        }
        if ($currencyCode) {
            if (!Yii::$service->store->isAppserver()) {
                Yii::$service->session->set(self::CURRENCY_CURRENT, $currencyCode);
            }
            $this->_currentCurrencyCode = $currencyCode;
            return true;
        }
    }
    
    protected $appserverCurrencyHeaderName = 'fecshop-currency';
    /**
     * appserver端初始化currency
     * 初始化货币services，直接从headers中取出来currency。进行set，这样currency就不会从session中读取
     * fecshop-2版本对于appserver已经抛弃session servcies
     */
    public function appserverSetCurrentCurrency()
    {
        if ($this->_currentCurrencyCode) {
            return true;
        }
        $header = Yii::$app->request->getHeaders();
        $currentCurrencyCode = $header[$this->appserverCurrencyHeaderName];
        
        if (!$currentCurrencyCode) {
            $currentCurrencyCode = $this->defaultCurrency;
        }
        if (!$this->isCorrectCurrency($currentCurrencyCode)) {
            $currentCurrencyCode = $this->defaultCurrency;
        }
        $this->_currentCurrencyCode = $currentCurrencyCode;
        
        Yii::$app->response->getHeaders()->set($this->appserverCurrencyHeaderName, $this->_currentCurrencyCode);
    }

    /**
     * @param $currency | String 货币简码
     * @return bool
     *              检测当前传递的货币简码，是否在配置中存在，如果存在则返回true
     */
    protected function isCorrectCurrency($currencyCode)
    {
        if (isset($this->currencys[$currencyCode])) {
            return true;
        } else {
            return false;
        }
    }

    public function setCurrentCurrency2CNY()
    {
        return $this->setCurrentCurrency('CNY');
    }
}
