<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\helper;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
 
class Product extends \yii\base\BaseObject
{
    public static function getStatusArr()
    {
        return [
            1 => Yii::$service->page->translate->__('Product Enable'),
            2 => Yii::$service->page->translate->__('Product Disable'),
        ];
    }

    public static function getInStockArr()
    {
        return [
            1 => Yii::$service->page->translate->__('In stock'),
            2 => Yii::$service->page->translate->__('out of stock'),
        ];
    }
}
