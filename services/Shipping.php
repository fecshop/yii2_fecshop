<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Shipping services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Shipping extends Service
{
    public $shippingConfig;

    public $shippingCsvDir;

    // 存放运费csv表格的文件路径。
    // 体积重系数，新体积重计算 = 长(cm) * 宽(cm) * 高(cm) / 体积重系数 ， 因此一立方的体积的商品，体积重为200Kg
    public $volumeWeightCoefficient = 5000;

    // 在init函数初始化，将shipping method的配置值加载到这个变量
    protected $_shipping_methods ;

    // 是否缓存shipping method 配置数据（因为csv部分需要读取csv文件，稍微耗时一些，可以选择放到缓存里面）
    protected $_cache_shipping_methods_config = 0;

    // 可用的shipping method，计算出来的值保存到这个类变量中。
    protected $_available_shipping;

    // 缓存key
    const  CACHE_SHIPPING_METHODS_CONFIG = 'cache_shipping_methods_config';

    /**
     * 1.从配置中取出来所有的shipping method
     * 2.对于公式为csv的shipping method，将对应的csv文件中的配置信息取出来
     * 3.如果开启了数据缓存，那么直接从缓存中读取。
     * 最后合并成一个整体的配置文件。赋值与-> $this->_shipping_methods
     */
    public function init()
    {
        parent::init();
        if (!$this->_shipping_methods) {
            // 是否开启缓存，如果开启，则从缓存中直接读取
            if ($this->_cache_shipping_methods_config) {
                $cache = Yii::$app->cache->get(self::CACHE_SHIPPING_METHODS_CONFIG);
                if (is_array($cache) && !empty($cache)) {
                    $this->_shipping_methods = $cache;
                }
            }
            // 如果无法从缓存中读取
            if (!$this->_shipping_methods) {
                $allmethod = $this->shippingConfig;
                $this->_shipping_methods = [];
                // 从配置中读取shipping method的配置信息
                if (is_array($allmethod) && !empty($allmethod)) {
                    foreach ($allmethod as $s_method => $v) {
                        $formula = $v['formula'];
                        if ($formula == 'csv') {
                            $csv_content = $this->getShippingByTableCsv($s_method);
                            if ($csv_content) {
                                $this->_shipping_methods[$s_method] = $v;
                                $this->_shipping_methods[$s_method]['csv_content'] = $csv_content;
                            }
                        } else {
                            $this->_shipping_methods[$s_method] = $v;
                        }
                    }
                }
                if ($this->_cache_shipping_methods_config) {
                    Yii::$app->cache->set(self::CACHE_SHIPPING_METHODS_CONFIG, $this->_shipping_methods);
                }
            }
        }
        return $this->_shipping_methods;
    }

    /**
     * @param $long | Float ,长度，单位cm
     * @param $width | Float ,宽度，单位cm
     * @param $high | Float ,高度，单位cm
     * @return 体积重，单位Kg
     */
    public function getVolumeWeight($long, $width, $high)
    {
        $volume_weight = ($long * $width * $high) / $this->volumeWeightCoefficient;
        return (float)$volume_weight;
    }

    /**
     * @param $long | Float ,长度，单位cm
     * @param $width | Float ,宽度，单位cm
     * @param $high | Float ,高度，单位cm
     * @return 体积体积，单位cm
     */
    public function getVolume($long, $width, $high)
    {
        return Yii::$service->helper->format->number_format($long * $width * $high);
    }
    
    /**
     * @proeprty $shipping_method 货运方式的key
     * @param $shippingConfig Array （MIX），配置信息。
     * @proeprty $weight 产品的总重量
     * @proeprty $country 货运国家
     * @proeprty $region  货运省份
     * @return float 通过计算，得到在【基础货币】下的运费金额。
     * 本部分只针对csv类型的shipping进行计算。
     */
    protected function actionGetShippingCostByCsv($shipping_method, $shippingConfig, $weight, $country, $region)
    {
        if (!$weight) {
            return 0;
        }
        // 从配置中读取出来csv表格的数组信息（处理后的）。
        $shippingArr = $shippingConfig['csv_content'];
        $country = $country ? $country : '*';
        $region  = $region ? $region : '*';
        if (isset($shippingArr[$country][$region])) {
            $priceData = $shippingArr[$country][$region];
        } elseif (isset($shippingArr[$country]['*'])) {
            $priceData = $shippingArr[$country]['*'];
        } elseif (isset($shippingArr['*']['*'])) {
            $priceData = $shippingArr['*']['*'];
        } else {
            throw new InvalidConfigException('error,this country is config in csv table');
        }
        // 找到相应的配置后，是各个区间值，根据区间值，得到相应的运费。
        $prev_weight = 0;
        $prev_price  = 0;
        $last_price  = 0;
        if (is_array($priceData)) {
            foreach ($priceData as $data) {
                $csv_weight = (float) $data[0];
                $csv_price  = (float) $data[1];
                if ($weight >= $csv_weight) {
                    $prev_weight = $csv_weight;
                    $prev_price  = $csv_price;
                    continue;
                } else {
                    $last_price = $prev_price;
                    break;
                }
            }
            if (!$last_price) {
                $last_price = $csv_price;
            }
            return $last_price;
        } else {
            throw new InvalidConfigException('error,shipping info config is error');
        }
    }

    /**
     * @proeprty $shipping_method 货运方式的key
     * @proeprty $weight 产品的总重量
     * @proeprty $country 货运国家
     * @return array 当前货币下的运费的金额。
     * 此处只做运费计算，不管该shipping是否可用。
     * 结果数据示例：
     * [
     *  'currCost'   => 66,
     *  'baseCost'   => 11,
     * ]
     */
    protected function actionGetShippingCost($method, $shippingInfo, $weight, $country, $region)
    {
        // 得到shipping计算的字符串公式
        $formula = $shippingInfo['formula'];
        // 如果公式的值为`csv`
        if ($formula === 'csv') {
            // 通过csv表格的配置，得到运费（基础货币）
            $usdCost = $this->getShippingCostByCsv($method, $shippingInfo, $weight, $country, $region);
            // 当前货币
            $currentCost = Yii::$service->page->currency->getCurrentCurrencyPrice($usdCost);
            
            return [
                'currCost'   => $currentCost,
                'baseCost'     => $usdCost,
            ];
        } else {  // 通过公式计算得到运费。
            $formula = str_replace('[weight]', $weight, $formula);
            $currentCost = eval("return $formula;");
            
            return [
                'currCost'  => Yii::$service->helper->format->number_format($currentCost, 2),
                'baseCost'  => Yii::$service->helper->format->number_format($currentCost, 2),
            ];
        }
    }
    
    /**
     * @proeprty $customShippingMethod 自定义的货运方式，这个一般是通过前端传递过来的shippingMethod
     * @proeprty $cartShippingMethod   购物车中的货运方式，这个是从购物车表中取出来的。
     * @return string 返回当前的货运方式。
     */
    protected function actionGetCurrentShippingMethod($customShippingMethod, $cartShippingMethod, $country, $region, $weight)
    {
        $available_method = $this->getAvailableShippingMethods($country, $region, $weight);
        if ($customShippingMethod) {
            if (isset($available_method[$customShippingMethod])) {
                return $customShippingMethod;
            }
        }
        if ($cartShippingMethod) {
            if (isset($available_method[$cartShippingMethod])) {
                return $cartShippingMethod;
            }
        }
        // 如果都不存在，则将可用物流中的第一个取出来$available_method
        foreach ($available_method as $method => $v) {
            return $method;
        }
    }
    
    /**
     * @param $shipping_method | String
     * @return 得到货运方式的名字
     */
    protected function actionGetShippingLabelByMethod($shipping_method)
    {
        $s = $this->_shipping_methods[$shipping_method];

        return isset($s['label']) ? $s['label'] : '';
    }
    
    /**
     * @param $shipping_method | String
     * @return bool 发货方式
     * 判断前端传递的shipping method是否有效（做安全性验证）
     */
    protected function actionIfIsCorrect($country, $region, $shipping_method, $weight)
    {
        $available_method = $this->getAvailableShippingMethods($country, $region, $weight);
        if (isset($available_method[$shipping_method]) && !empty($available_method[$shipping_method])) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @param $countryCode | String
     * @param $region | String
     * @param weight | Float
     * 将可用的shipping method数组的第一个取出来作为默认的shipping。
     */
    public function getDefaultShippingMethod($countryCode, $region, $weight)
    {
        $available_method = $this->getAvailableShippingMethods($countryCode, $region, $weight);
        foreach ($available_method as $method => $v) {
            return $method;
        }
    }
    
    /**
     * @param $country | String 国家 如果没有国家，则传递空字符串
     * @param $region | String 省市 如果没有省市，则传递空字符串
     * @param $shipping_method | String 货运方式
     * @param $weight | Float ，重量，如果某些物流存在重量限制，那么，重量不满足的将会被剔除
     * @return   $availableShipping | Array ，可用的shipping method
     * 在全部的shipping method，存在1.国家省市限制，2.重量限制
     * 不符合条件的被剔除，剩下的就是可用的shipping method
     * 该函数调用的地方比较多，因此将结果存储到类变量中，节省计算。
     */
    public function getAvailableShippingMethods($countryCode, $region, $weight = 0)
    {
        $c_key = $countryCode ? $countryCode : 'noKey';
        $r_key = $region ? $region : 'noKey';
        $w_key = $weight ? $weight : 'noKey';
        // 通过类变量记录，只计算一次。
        if (!isset($this->_available_shipping[$c_key][$r_key][$w_key])) {
            $this->_available_shipping[$c_key][$r_key][$w_key] = [];
            //var_dump($this->_shipping_methods);
            $availableShipping = [];
            if (!$countryCode && !$region) {
                $availableShipping = $this->_shipping_methods;
            } else {
                foreach ($this->_shipping_methods as $shipping_method => $v) {
                    $countryLimit = isset($v['country']) ? $v['country'] : '';
                    if ($this->isCountryLimit($countryLimit, $countryCode)) {
                        continue;
                    }
                    // 如果是csv类型，查看在csv中是否存在
                    $formula = isset($v['formula']) ? $v['formula'] : '';
                    if ($formula === '') {
                        continue; // 代表shipping配置有问题，不可用
                    } elseif ($formula === 'csv') {
                        if ($this->isCsvCountryReginLimit($v, $countryCode, $region)) {
                            continue;
                        }
                    }
                    $availableShipping[$shipping_method] = $v;
                }
            }
            // 重量限制
            if ($weight) {
                $availableShipping = $this->wegihtAllowedShipping($availableShipping, $weight);
            }
            $this->_available_shipping[$c_key][$r_key][$w_key] = $availableShipping;
        }
        return $this->_available_shipping[$c_key][$r_key][$w_key];
    }
    
    /**
     * @param $shipping_method | String 货运方式的key
     * @return array ，通过csv表格，得到对应的运费数组信息
     * 内部函数，将csv表格中的shipping数据读出来
     * 返回的数据格式为：
     * [
     *     'fast_shipping' => [
     *         'US' => [
     *             '*' => [
     *                 [0.5100, 22.9],
     *                 [1.0100, 25.9],
     *                 [2.5100, 34.9],
     *             ]
     *     ],
     *         'DE' => [
     *             '*' => [
     *                 [0.5100, 22.9],
     *                 [1.0100, 25.9],
     *                 [2.5100, 34.9],
     *             ]
     *        ],
     *     ]
     * ]
     */
    protected function getShippingByTableCsv($shipping_method)
    {
        $shippingCsvArr = [];
        // 从csv文件中读取shipping信息。
        $commonDir = Yii::getAlias($this->shippingCsvDir);
        $csv = $commonDir.'/'.$shipping_method.'.csv';
        if (!file_exists($csv)) {
            return false;
        }
        $fp = fopen($csv, 'r');
        $i = 0;
        while (!feof($fp)) {
            if ($i) {
                $content = fgets($fp);
                $arr = explode(',', $content);
                $country = $arr[0];
                $Region = $arr[1];
                $Weight = $arr[3];
                $ShippingPrice = $arr[4];
                $shippingCsvArr[$country][$Region][] = [$Weight, $ShippingPrice];
            }
            $i++;
        }
        fclose($fp);
        return $shippingCsvArr;
    }
    
    /**
     * @param $countryLimit | Array 配置中的国家限制数组
     * @param $countryCode | String 判断的国家code
     * 判断 $countryCode 是否存在国家方面的限制
     */
    protected function isCountryLimit($countryLimit, $countryCode)
    {
        // 如果存在国家方面的限制
        if (is_array($countryLimit) && !empty($countryLimit)) {
            $type = isset($countryLimit['type']) ?  $countryLimit['type'] : '';
            $code = isset($countryLimit['code']) ?  $countryLimit['code'] : '';
            if ($type == 'allow') {
                // 如果不存在于数组，则代表不允许
                if (!in_array($countryCode, $code)) {
                    return true;
                }
            } elseif ($type == 'not_allow') {
                if (in_array($countryCode, $code)) {
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * @param $shippingConfig | Array ， shipping method对应的配置
     * @param $countryCode | String 国家简码
     * @param $region | String 省市
     * 根据csv content里面的配置，判断是否存在国家 省市限制
     */
    protected function isCsvCountryReginLimit($shippingConfig, $countryCode, $region)
    {
        $csv_content = isset($shippingConfig['csv_content']) ? $shippingConfig['csv_content'] : '';
        // 如果不存在全局国家，省市 的通用配置
        if (!isset($csv_content['*']['*'])) {
            // 如果当前的国家对应的配置不存在，则不可用
            if (!isset($csv_content[$countryCode])) {
                return true;
            } elseif ($region) { // 如果参数传递的$region不为空
                // 国家可用，如果不存在省市的通用配置
                if (!isset($csv_content[$countryCode]['*'])) {
                    // 如果不存在相应省市的配置，则不可用
                    if (!isset($csv_content[$countryCode][$region])) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    /**
     * @param $availableShipping | Array  ，shipping method 数组
     * @param $weight | Float 重量
     * @return Array
     * 返回满足重量限制的shipping method
     */
    protected function wegihtAllowedShipping($availableShipping, $weight)
    {
        $available_shipping = [];
        // 查看是否存在重量限制,如果存在，则不可用
        foreach ($availableShipping as $method => $v) {
            $weightLimit = isset($v['weight']) ? $v['weight'] : '';
            if (isset($weightLimit['min']) && $weightLimit['min'] > $weight) {
                continue;
            }
            if (isset($weightLimit['max']) && $weightLimit['max'] < $weight) {
                continue;
            }
            $available_shipping[$method] = $v;
        }
        
        return $available_shipping;
    }
}
