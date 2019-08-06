<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

use fecshop\services\Service;
use Yii;

/**
 * Product Price Services
 * Product Service is the component that you can get product info from it.
 * @param Image|\fecshop\services\Product\Image $image ,This property is read-only.
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
    
    // 执行函数 getCurrentCurrencyProductPriceInfo，会将折扣数保存到 currentOff 中，
    // 您可以通过 Yii::$service->product->price->currentOff 直接获取。
    public $currentOff = 0;
    
    public function init()
    {
        parent::init();
        $ifSpecialPriceGtPriceFinalPriceEqPrice = Yii::$app->store->get('product','ifSpecialPriceGtPriceFinalPriceEqPrice');
        if ($ifSpecialPriceGtPriceFinalPriceEqPrice == Yii::$app->store->enable) {
            $this->ifSpecialPriceGtPriceFinalPriceEqPrice = true;
        } else {
            $this->ifSpecialPriceGtPriceFinalPriceEqPrice = false;
        }
    }
    /**
     * @param  $price 		 | Float  产品的价格
     * 得到当前货币状态下的产品的价格信息。
     */
    protected function actionFormatPrice($price)
    {
        $currencyInfo = $this->getCurrentInfo();
        $price = Yii::$service->helper->format->number_format($price * $currencyInfo['rate']);
        return [
            'code'   => $currencyInfo['code'],
            'symbol' => $currencyInfo['symbol'],
            'value'  => $price,
        ];
    }

    /**
     * @param $price | Float 产品价格
     * @return String ， 带有相应货币符号的价格
     */
    protected function actionFormatSamplePrice($price)
    {
        $currencyInfo = $this->getCurrentInfo();
        $price = $price * $currencyInfo['rate'];
        $price = Yii::$service->helper->format->number_format($price);

        return $currencyInfo['symbol'].$price;
    }

    /**
     * 得到单个产品的最终价格。支持tier price 如果是tier price 需要把qty 以及tier Price传递过来.
     * @param  $price 		 | Float  产品的价格
     * @param  $special_price | Float  产品的特价
     * @param  $special_from  | Int    产品的特检开始时间
     * @param  $special_to    | Int    产品的特检结束时间
     * @param  $qty    		 | Int 	  产品的个数，这个用于一次性购买多个产品的优惠，这些是用于批发客户
     * @param  $tier_price    | Array  ，Example:
     * $tier_price = [
     *		['qty'=>2,'price'=>33],
     *		['qty'=>4,'price'=>30],
     *	];
     *
     * @return float
     */
    protected function actionGetFinalPrice(
        $price,
        $special_price,
        $special_from,
        $special_to,
        $qty = '',
        $tier_price = []
    ) {
        if ($this->specialPriceisActive($price, $special_price, $special_from, $special_to)) {
            $return_price = $special_price;
        } else {
            $return_price = $price;
        }
        if ($qty > 1) {
            $return_price = $this->getTierPrice($qty, $tier_price, $return_price);
        }

        return $return_price;
    }

    /**
     * @param $productId | String
     * @param $qty | Int
     * @param $custom_option_sku | String
     * @param $format | Int , 返回的价格的格式，0代表为美元格式，1代表为当前货币格式，2代表美元和当前货币格式都有
     * 通过产品以及个数，custonOptionSku 得到产品的最终价格
     */
    protected function actionGetCartPriceByProductId($productId, $qty, $custom_option_sku, $format = 1)
    {
        $product = Yii::$service->product->getByPrimaryKey($productId);
        $custom_option_price = 0;
        $status = isset($product['status']) ? $product['status'] : 0;

        if ($product['price'] && Yii::$service->product->isActive($status)) {
            $price = $product['price'];
            $special_price = isset($product['special_price']) ? $product['special_price'] : 0;
            $special_from  = isset($product['special_from']) ? $product['special_from'] : '';
            $special_to    = isset($product['special_to']) ? $product['special_to'] : '';
            $tier_price    = isset($product['tier_price']) ? $product['tier_price'] : [];
            $custom_option = isset($product['custom_option']) ? $product['custom_option'] : '';

            if (!empty($custom_option) && $custom_option_sku && isset($custom_option[$custom_option_sku])) {
                if ($co = $custom_option[$custom_option_sku]) {
                    $custom_option_price = isset($co['price']) ? $co['price'] : 0;
                }
            }

            return $this->getCartPrice(
                $price,
                $special_price,
                $special_from,
                $special_to,
                $qty,
                $custom_option_price,
                $tier_price,
                $format
            );
        }
    }

    // 产品加入购物车，得到相应个数的最终价格。

    /**
     * @param $price | Float
     * @param $special_price | Float
     * @param $special_from | Int
     * @param $special_to | Int
     * @param $qty | Int
     * @param $custom_option_price | Float
     * @param $tier_price | Array ， 例子：
     * $tier_price = [
     *		['qty'=>2,'price'=>33],
     *		['qty'=>4,'price'=>30],
     *	];
     * @param $format | Int , 返回的价格的格式，0代表为美元格式，1代表为当前货币格式，2代表美元和当前货币格式都有
     */
    protected function actionGetCartPrice(
        $price,
        $special_price,
        $special_from,
        $special_to,
        $qty = '',
        $custom_option_price,
        $tier_price = [],
        $format = 1
    ) {
        if ($this->specialPriceisActive($price, $special_price, $special_from, $special_to)) {
            $return_price = $special_price;
        } else {
            $return_price = $price;
        }

        if ($qty > 1) {
            $return_price = $this->getTierPrice($qty, $tier_price, $return_price);
        }
        $return_price = $return_price + $custom_option_price;
        if ($format == 1) {
            $format_price = $this->formatPrice($return_price);

            return $format_price;
        } elseif ($format == 2) {
            $format_price = $this->formatPrice($return_price);

            return [
                'base_price' => $return_price,
                'curr_price' => $format_price,
            ];
        } else {
            return $return_price;
        }
    }

    /**
     * @param $qty | Int
     * @param $price | Float 一个产品的单价(如果有特价，那么这个值是一个产品的特价)
     * @param $tier_price_arr | Array  , example:
     * $tier_price = [
     *		['qty'=>2,'price'=>33],
     *		['qty'=>4,'price'=>30],
     *	];
     * 传递过来的tier_price 数组，必须是按照qty进行排序好了的数组
     */
    protected function actionGetTierPrice($qty, $tier_price_arr, $price)
    {
        if ($qty <= 1) {
            return $price;
        }
        $t_price = $price;
        if (is_array($tier_price_arr) && !empty($tier_price_arr)) {
            foreach ($tier_price_arr  as $one) {
                $t_qty = $one['qty'];
                $t_price = $one['price'];

                if ($t_qty <= $qty) {
                    $parent_price = $t_price;
                    continue;
                } else {
                    if ($parent_price) {
                        return $parent_price;
                    } else {
                        return $price;
                    }
                }
            }
        }

        return $t_price;
    }

    /**
     * 判断产品的special_price是否有效，下面几种情况会无效
     * 1. $special_price为空
     * 2. 产品的$special_price 大于 $price，并且，ifSpecialPriceGtPriceFinalPriceEqPrice设置为true
     * 3. 当前的时间不在 特价时间范围内.
     * @param  $price 		 | Float  产品的价格
     * @param  $special_price | Float  产品的特价
     * @param  $special_from  | Int    产品的特检开始时间
     * @param  $special_to    | Int    产品的特检结束时间
     * @return bool
     */
    protected function actionSpecialPriceisActive($price, $special_price, $special_from, $special_to)
    {
        if (!$special_price || $special_price == 0.00) {  // 浮点数需要这样判断float 0
            return false;
        }
        if ($this->ifSpecialPriceGtPriceFinalPriceEqPrice) {
            if ($special_price > $price) {
                return false;
            }
        }
        $nowTimeStamp = time();
        if ($special_from) {
            if ($special_from > $nowTimeStamp) {
                return false;
            }
        }
        if ($special_to) {
            if ($special_to < $nowTimeStamp) {
                return false;
            }
        }

        return true;
    }

    /**
     * 得到当前的货币信息，并保存到对象属性中，方便多次调用.
     */
    protected function getCurrentInfo()
    {
        if (!$this->_currencyInfo) {
            $this->_currencyInfo = Yii::$service->page->currency->getCurrencyInfo();
        }

        return $this->_currencyInfo;
    }

    /**
     * 通过该函数，得到产品的价格信息，如果特价是active的，则会有特价信息。
     * @param  $price 		 | Float  产品的价格
     * @param  $special_price | Float  产品的特价
     * @param  $special_from  | Int    产品的特检开始时间
     * @param  $special_to    | Int    产品的特检结束时间
     * @return $return | Array  产品的价格信息
     */
    protected function actionGetCurrentCurrencyProductPriceInfo($price, $special_price, $special_from, $special_to)
    {
        $price = (float)$price;
        $special_price = (float)$special_price;
        $special_from = (int)$special_from;
        $special_to = (int)$special_to;
        $this->currentOff = 0;
        $price_info = $this->formatPrice($price);
        $return['price'] = [
            'symbol'    => $price_info['symbol'],
            'value'    => $price_info['value'],
            'code'        => $price_info['code'],
        ];
        $specialIsActive = $this->specialPriceisActive($price, $special_price, $special_from, $special_to);
        if ($specialIsActive) {
            $special_price_info = Yii::$service->product->price->formatPrice($special_price);
            $return['special_price'] = [
                'symbol'        => $special_price_info['symbol'],
                'value'         => $special_price_info['value'],
                'code'          => $special_price_info['code'],
            ];
            $off = ($price_info['value'] - $special_price_info['value']) / $price_info['value'];
            $this->currentOff = round($off * 100);
        }

        return $return;
    }
}
