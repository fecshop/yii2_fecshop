<?php

/*
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
 * Info sub-service of cart service.
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
    
    public function init()
    {
        parent::init();
        // get Config from store config
        $this->maxCountAddToCart = Yii::$app->store->get('cart', 'maxCountAddToCart');
        $this->addToCartCheckSkuQty = (Yii::$app->store->get('cart', 'addToCartCheckSkuQty') == Yii::$app->store->enable ) ? true : false;
    }
    /**
     * Checks several conditions before you add product to cart
     * @param array $item
     * example:
     * $item = [
     *		'product_id' 		=> 22222,
     *		'custom_option_sku' => ['color'=>'red','size'=>'l'],
     *		'qty' 				=> 22,
     * ];
     * @param \fecshop\models\mongodb\Product $product
     * @return bool true if can add product to cart, false otherwise
     */
    public function checkProductBeforeAdd($item, $product)
    {
        // 加入购物车后的产品个数不能小于设置的最小购买数量
        $qty = $item['qty'];
        $min_sales_qty = $product['min_sales_qty'];
        if (($min_sales_qty > 0) && ($min_sales_qty > $qty)) {
            Yii::$service->helper->errors->add('The minimum number of shopping carts for this item is {min_sales_qty}', ['min_sales_qty' => $min_sales_qty]);
            
            return false;
        }
        
        // 验证产品是否是激活状态
        if ($product['status'] != Yii::$service->product->getEnableStatus()) {
            Yii::$service->helper->errors->add('product is not active');

            return false;
        }
        
        // 加入购物车的产品个数超出 购物车中产品的最大个数。
        if ($qty > $this->maxCountAddToCart) {
            Yii::$service->helper->errors->add('The number of products added to the shopping cart can not exceed {max_count_add_to_cart}', ['max_count_add_to_cart' => $this->maxCountAddToCart]);

            return false;
        }
        // 验证提交产品数据
        // 验证产品是否存在
        if (!$product['sku']) {
            Yii::$service->helper->errors->add('The product is not exist');

            return false;
        }
        // 验证库存 是否库存满足
        $product_id = $item['product_id'];
        $custom_option_sku = $item['custom_option_sku'];
        
        if ($this->addToCartCheckSkuQty) {
            // 验证：1.上架状态， 2.库存个数是否大于购买个数
            // 该验证方式是默认验证方式
            if (!Yii::$service->product->stock->productIsInStock($product, $qty, $custom_option_sku)) {
                Yii::$service->helper->errors->add('The qty of product in stock is less than your purchase qty');

                return false;
            }
        } else {
            // 验证：1.上架状态
            if (!Yii::$service->product->stock->checkOnShelfStatus($product['is_in_stock'])) {
                Yii::$service->helper->errors->add('The product is out of stock');

                return false;
            }
        }

        return true;
    }

    /**
     * @param $item | Array , 数据格式为：
     * [
     *    'product_id'  => xxxxx
     *    'qty'           => 55,
     *    'custom_option_sku' => [
     *        'color' => 'red',
     *        'size'  => 'L',
     *    ]
     * ]
     * @param $product | Product Model ， 产品的model对象
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
     * @return string 得到 custom option 部分对应的sku
     * 当用户在产品加入购物车的时候选择了颜色尺码等属性，通过这个函数，可以得到这些属性对应的custom_option_sku的值。
     * 如果不存在，则返回为空。
     */
    public function getCustomOptionSku($item, $product)
    {
        $custom_option_arr = $item['custom_option_sku'];
        if ($custom_option_arr) {
            $product_custom_option = $product['custom_option'];
            $co_sku = Yii::$service->product->info->getProductCOSku($custom_option_arr, $product_custom_option);
            if ($co_sku) {
                return $co_sku;
            }
        }
        return '';
    }
}
