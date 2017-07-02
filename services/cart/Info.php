<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\cart;

use fecshop\services\Service;
use Yii;

/**
 * Cart services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Info extends Service
{
    /**
     * 单个sku加入购物车的最大个数。
     */
    public $maxCountAddToCart = 100;

    // 上架状态产品加入购物车时，
    // 如果addToCartCheckSkuQty设置为true，则需要检查产品qty是否>购买qty，
    // 如果设置为false，则不需要，也就是说产品库存qty小于购买qty，也是可以加入购物车的。
    public $addToCartCheckSkuQty = false;

    /**
     * @property $item | Array  , example
     * $item = [
     *		'product_id' 		=> 22222,
     *		'custom_option_sku' => ['color'=>'red','size'=>'l'],
     *		'qty' 				=> 22,
     * ];
     * @proeprty $product | Object , Product Model
     * return boolean 是否满足条件
     * 在产品加入购物车之前，检查产品是否存在，产品的状态，库存状态等
     * 满足条件返回true
     */
    public function checkProductBeforeAdd($item, $product)
    {
        $qty = $item['qty'];
        $product_id = $item['product_id'];
        $custom_option_sku = $item['custom_option_sku'];
        // 验证产品是否是激活状态
        if ($product['status'] != 1) {
            Yii::$service->helper->errors->add('product is not active');

            return false;
        }
        // 加入购物车的产品个数超出 购物车中产品的最大个数。
        if ($qty > $this->maxCountAddToCart) {
            Yii::$service->helper->errors->add('The number of products added to the shopping cart can not exceed '.$this->maxCountAddToCart);

            return false;
        }
        // 验证提交产品数据
        // 验证产品是否存在
        if (!$product['sku']) {
            Yii::$service->helper->errors->add('this product is not exist');

            return false;
        }
        // 验证库存 是否库存满足？
        if ($this->addToCartCheckSkuQty) {
            // 验证：1.上架状态， 2.库存个数是否大于购买个数
            // 该验证方式是默认验证方式
            if (!Yii::$service->product->stock->productIsInStock($product, $qty, $custom_option_sku)) {
                Yii::$service->helper->errors->add('the qty of product stocks is less than your purchase qty');

                return false;
            }
        } else {
            // 验证：1.上架状态
            if (!Yii::$service->product->stock->checkOnShelfStatus($product['is_in_stock'])) {
                Yii::$service->helper->errors->add('product is Stock Out');

                return false;
            }
        }
        

        return true;
    }
    /**
     * @property $item | Array , 数据格式为：
     * [
     *    'product_id'  => xxxxx
     *    'qty'           => 55,
     *    'custom_option_sku' => [
     *        'color' => 'red',
     *        'size'  => 'L',
     *    ]
     * ]
     * @property $product | Product Model ， 产品的model对象
     * $product['custom_option'] 的数据格式如下：
     *  [
     *      "black-s-s2-s3": [
     *          "my_color": "black",
     *          "my_size": "S",
     *          "my_size2": "S2",
     *          "my_size3": "S3",
     *          "sku": "black-s-s2-s3",
     *          "qty": NumberInt(99999),
     *          "price": 0,
     *          "image": "/2/01/20161024170457_10036.jpg"
     *      ],
     *  ]
     * @return 得到custom option部分对应的sku
     * 当用户在产品加入购物车的时候选择了颜色尺码等属性，通过这个函数，可以得到这些属性对应的custom_option_sku的值。
     * 如果不存在，则返回为空。
     */
    public function getCustomOptionSku($item, $product)
    {
        $qty = $item['qty'];
        $custom_option_arr = $item['custom_option_sku'];
        $product_id = $item['product_id'];
        $co_sku = '';
        if ($custom_option_arr) {
            $product_custom_option = $product['custom_option'];
            $co_sku = Yii::$service->product->info->getProductCOSku($custom_option_arr, $product_custom_option);
            if ($co_sku) {
                return $co_sku;
            }
        }
    }
}
