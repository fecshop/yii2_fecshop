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

/**
 * Shipping services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Shipping extends Service
{
    public $shippingConfig;
    public $shippingCsvDir; // 存放运费csv表格的文件路径。
    public $defaultShippingMethod;

    /**
     * @property $method | String ，shipping_method key
     * @return array ，得到配置
     */
    protected function actionGetShippingMethod($shipping_method = '')
    {
        $allmethod = $this->shippingConfig;
        if ($shipping_method) {
            return isset($allmethod[$shipping_method]) ? $allmethod[$shipping_method] : '';
        } else {
            return $allmethod;
        }
    }

    /**
     * @property $shipping_method | String
     * @return bool 发货方式
     */
    protected function actionIfIsCorrect($shipping_method)
    {
        $allmethod = $this->shippingConfig;
        if (isset($allmethod[$shipping_method]) && !empty($allmethod[$shipping_method])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string ,得到默认的运费方法 shipping_method key
     *                配置中$this->shippingConfig 第一个参数就是默认
     */
    protected function actionGetDefaultShippingMethod()
    {
        if ($shippingMethod = $this->defaultShippingMethod) {
            if (isset($shippingMethod['enable']) && $shippingMethod['enable']) {
                $shipping = isset($shippingMethod['shipping']) ? $shippingMethod['shipping'] : '';
                if ($shipping && $this->getShippingMethod($shipping)) {
                    return $shipping;
                }
            }
        }

        return '';
    }

    /**
     * @proeprty $customShippingMethod 自定义的货运方式，这个一般是通过前端传递过来的shippingMethod
     * @proeprty $cartShippingMethod   购物车中的货运方式，这个是从购物车表中取出来的。
     * @return string 返回当前的货运方式。
     */
    protected function actionGetCurrentShippingMethod($customShippingMethod = '', $cartShippingMethod = '')
    {
        if ($customShippingMethod) {
            return $customShippingMethod;
        }
        if ($cartShippingMethod) {
            return $cartShippingMethod;
        } else {
            return Yii::$service->shipping->getDefaultShippingMethod();
        }
    }

    // 通过方法，重量，国家，省，得到美元状态的运费金额

    /**
     * @proeprty $shipping_method 货运方式的key
     * @proeprty $weight 产品的总重量
     * @proeprty $country 货运国家
     * @proeprty $region  货运省份
     * @return float 通过计算，得到在默认货币下的运费金额。
     */
    protected function actionGetShippingCostByCsvWeight($shipping_method, $weight, $country, $region = '*')
    {
        if (!$weight) {
            return 0;
        }
        $shippingArr = $this->getShippingByTableCsv($shipping_method);
        $priceData = [];
        if (isset($shippingArr[$country][$region])) {
            $priceData = $shippingArr[$country][$region];
        } elseif (isset($shippingArr[$country]['*'])) {
            $priceData = $shippingArr[$country]['*'];
        } else {
            $priceData = $shippingArr['*']['*'];
        }
        //var_dump($priceData);
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
        }
    }

    /**
     * @proeprty $shipping_method 货运方式的key
     * @proeprty $weight 产品的总重量
     * @proeprty $country 货运国家
     * @return array 当前货币下的运费的金额。
     *               运费是通过csv表格内容计算而来，如果cost==0，那么代表免邮的方式。
     *               该方法为：当前重量下，所有的运费方式对应的运费都计算出来，展示在下单页面，让用户选择。
     */
    protected function actionGetShippingCost($shipping_method, $weight, $country = '', $region = '*')
    {
        $allmethod = $this->getShippingMethod();
        $m = $allmethod[$shipping_method];
        //var_dump($m );exit;
        if (!empty($m) && is_array($m)) {
            $cost = $m['cost'];
            // csv方式
            if ($cost === 'csv') {

                //通过 运费方式，重量，国家，得到美元的运费
                $usdCost = $this->getShippingCostByCsvWeight($shipping_method, $weight, $country, $region);
                //echo $usdCost;
                $currentCost = Yii::$service->page->currency->getCurrentCurrencyPrice($usdCost);

                return [
                    'currCost'   => $currentCost,
                    'baseCost'     => $usdCost,
                ];
            // $cost = 0 代表为free shipping方式
            } elseif ($cost == 0) {
                return [
                    'currCost'  => number_format(0, 2),
                    'baseCost'    => number_format(0, 2),
                ];
            }
        }
    }

    /**
     * @property $shipping_method | String
     * @return 得到货运方式的名字
     */
    protected function actionGetShippingLabelByMethod($shipping_method)
    {
        $s = $this->getShippingMethod($shipping_method);

        return $s['label'];
    }

    /**
     * @property $shipping_method | String 货运方式的key
     * @return array ，通过csv表格，得到对应的运费数组信息
     */
    protected function getShippingByTableCsv($shipping_method)
    {
        //$shippingCsvDir = '@common/config/shipping';
        $commonDir = Yii::getAlias($this->shippingCsvDir);
        $csv = $commonDir.'/'.$shipping_method.'.csv';
        $fp = fopen($csv, 'r');
        $shippingArr = [];
        $i = 0;
        while (!feof($fp)) {
            if ($i) {
                $content = fgets($fp);
                $arr = explode(',', $content);
                $country = $arr[0];
                $Region = $arr[1];
                $Weight = $arr[3];
                $ShippingPrice = $arr[4];
                $shippingArr[$country][$Region][] = [$Weight, $ShippingPrice];
            }
            $i++;
        }
        fclose($fp);

        return $shippingArr;
    }
}
