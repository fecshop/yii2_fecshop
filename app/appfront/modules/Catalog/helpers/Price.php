<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Catalog\helpers;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Price
{
    public function getCategoryPrice($price, $special_price)
    {
        $price_info = Yii::$service->product->price->format_price($price);
        $return = [
            'price' => [
                'symbol' => $price_info['symbol'],
                'value' => $price_info['value'],
            ],
        ];
        if ($special_price) {
            $special_price_info = Yii::$service->product->price->format_price($special_price);
            $return['special_price'] = [
                'symbol' => $special_price_info['symbol'],
                'value' => $special_price_info['value'],
            ];
        }

        return $return;
    }
}
