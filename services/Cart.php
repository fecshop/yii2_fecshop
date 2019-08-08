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

/**
 * Cart services. 购物车service， 执行购物车部分对应的方法。
 *
 * @property \fecshop\services\cart\Coupon $coupon coupon sub-service of cart
 * @property \fecshop\services\cart\Info $info info sub-service of cart
 * @property \fecshop\services\cart\Quote $quote quote sub-service of cart
 * @property \fecshop\services\cart\QuoteItem $quoteItem quoteItem sub-service of cart
 *
 * @method getCartInfo($activeProduct, $shippingMethod = '', $country = '', $region = '*') see [[\fecshop\services\Cart::actionGetCartInfo()]]
 * @method mergeCartAfterUserLogin() see [[\fecshop\services\Cart::actionmergeCartAfterUserLogin()]]
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Cart extends Service
{
    /**
     * 将某个产品加入到购物车中
     * @param array $item
     * example:
     * $item = [
     *		'product_id' 		=> 22222,
     *		'custom_option_sku' => ['color'=>'red','size'=>'l'],
     *		'qty' 				=> 22,
     * ];
     * 注意： $item['custom_option_sku'] 除了为上面的数组格式，还可以为字符串
     * 为字符串的时候，字符串标示的就是产品的custom option  sku
     * @return bool true if add product to cart successfully, false otherwise
     */
    protected function actionAddProductToCart($item)
    {
        $product = Yii::$service->product->getByPrimaryKey($item['product_id']);

        // 根据传递的值，得到 custom_option_sku 的值
        if (isset($item['custom_option_sku']) && !empty($item['custom_option_sku'])) {
            if (is_array($item['custom_option_sku'])) {
                $custom_option_sku = Yii::$service->cart->info->getCustomOptionSku($item, $product);
                if (!$custom_option_sku) {
                    Yii::$service->helper->errors->add('product custom_option_sku is not exist');

                    return false;
                }
                $item['custom_option_sku'] = $custom_option_sku;
            }
        }
        
        $item['sku'] = $product['sku'];
        $item['qty'] = $this->getCartQty($product['package_number'], $item['qty']);
        
        // 开始加入购物车
        // service 里面不允许有事务，请在调用层使用事务。
        $beforeEventName = 'event_add_to_cart_before';
        $afterEventName = 'event_add_to_cart_after';
        
        /**
         * 此处属于 fecshop 自造的简单事件 event ，比较简洁
         * 详情参看：http://www.fecshop.com/doc/fecshop-guide/instructions/cn-1.0/guide-fecshop_event.html
         */
        Yii::$service->event->trigger($beforeEventName, $item); // 触发事件 - 加购物车前事件
        if (!Yii::$service->cart->quoteItem->addItem($item, $product)) {
            return false;
        }
        Yii::$service->event->trigger($afterEventName, $item);  // 触发事件 - 加购物车后事件
        return true;
    }
    
    /**
     * @param int $package_number 打包销售的个数，这个是产品编辑的时候，如果某个商品想打包作为销售单位，填写的值
     * @param int $addQty 加入购物车的产品个数。
     * @return int 得到在购物车个数变动数，根据产品的打包销售数进行改变
     */
    public function getCartQty($package_number, $addQty)
    {
        if ($package_number >= 2) {
            return (int)($addQty * $package_number);
        } else {
            return $addQty;
        }
    }

    /**
     * 得到购物车中产品的个数，详情参看调用的函数注释
     */
    protected function actionGetCartItemQty()
    {
        return Yii::$service->cart->quote->getCartItemCount();
    }

    /**
     * @param $shipping_method | String 货运方式code
     * @param $country | String 国家code
     * @param $region | String 省市code
     * 得到购物车中的信息。详情参看调用的函数注释
     */
    protected function actionGetCartInfo($activeProduct = true, $shipping_method = '', $country = '', $region = '*')
    {
        return Yii::$service->cart->quote->getCartInfo($activeProduct, $shipping_method, $country, $region);
    }

    /**
     * @param $item_id | Int 购物车产品表的id字段
     * 通过item id 将购物车中的某个产品的个数加一
     */
    protected function actionAddOneItem($item_id)
    {
        $status = Yii::$service->cart->quoteItem->addOneItem($item_id);
        if (!$status) {
            return false;
        }
        Yii::$service->cart->quote->computeCartInfo();
        return true;
    }

    /**
     * @param $item_id | Int 购物车产品表的id字段
     * 通过item id 将购物车中的某个产品的个数减一
     */
    protected function actionLessOneItem($item_id)
    {
        $status = Yii::$service->cart->quoteItem->lessOneItem($item_id);
        if (!$status) {
            return false;
        }
        Yii::$service->cart->quote->computeCartInfo();
        return true;
    }

    /**
     * @param $item_id | Int 购物车产品表的id字段
     * 通过item id 删除购物车中的某个产品
     */
    protected function actionRemoveItem($item_id)
    {
        $status = Yii::$service->cart->quoteItem->removeItem($item_id);
        if (!$status) {
            return false;
        }
        Yii::$service->cart->quote->computeCartInfo();
        return true;
    }
    
    /**
     * @param $item_id | Int 购物车产品表的id字段
     * 通过item id 将购物车中的某个产品的个数加一
     */
    protected function actionSelectOneItem($item_id, $checked)
    {
        $status = Yii::$service->cart->quoteItem->selectOneItem($item_id, $checked);
        if (!$status) {
            return false;
        }
        Yii::$service->cart->quote->computeCartInfo();
        return true;
    }
    
    /**
     * @param $item_id | Int 购物车产品表的id字段
     * 通过item id 将购物车中的某个产品的个数加一
     */
    protected function actionSelectAllItem($checked)
    {
        $status = Yii::$service->cart->quoteItem->selectAllItem($checked);
        if (!$status) {
            return false;
        }
        Yii::$service->cart->quote->computeCartInfo();
        return true;
    }

    /**
     * 购物车合并：对应的是用户登录前后购物车的合并
     * 1. 用户未登录账号，把一部分产品加入购物车
     * 2. 当用户登录账号的时候，账号对应的购物车信息和用户未登录前的购物车产品信息进行合并的操作
     * 在用户登录账户的时候，会执行该方法。
     */
    protected function actionMergeCartAfterUserLogin()
    {
        Yii::$service->cart->quote->mergeCartAfterUserLogin();
    }

    /**
     * @param $address|array
     * @param $shipping_method | String 发货方式
     * @param $payment_method | String 支付方式
     * 此函数对应的是保存游客用户的购物车数据。
     * 保存购物车中的货运地址保存购物车中的货运地址(姓名，电话，邮编，地址等)，货运方式，支付方式等信息。
     * 详细参看相应函数
     */
    protected function actionUpdateGuestCart($address, $shipping_method, $payment_method)
    {
        return Yii::$service->cart->quote->updateGuestCart($address, $shipping_method, $payment_method);
    }

    /**
     * @param $address_id | Int
     * @param $shipping_method | String 货运方式
     * @param $payment_method | String 支付方式
     * 此函数对应的是登录用户的购物车数据的更新。
     */
    protected function actionUpdateLoginCart($address_id, $shipping_method, $payment_method)
    {
        return Yii::$service->cart->quote->updateLoginCart($address_id, $shipping_method, $payment_method);
    }

    /**
     * 清空购物车中的产品和优惠券
     * 在生成订单的时候被调用.
     * 清空cart item active的产品，而对于noActive的购物车产品保留
     */
    protected function actionClearCartProductAndCoupon()
    {
        Yii::$service->cart->quoteItem->removeNoActiveItemsByCartId();

        // 清空cart中的优惠券
        $cart = Yii::$service->cart->quote->getCurrentCart();
        if (!$cart['cart_id']) {
            Yii::$service->helper->errors->add('current cart is empty');

            return false;
        }
        // 如果购物车中存在优惠券，则清空优惠券。
        if ($cart->coupon_code) {
            $cart->coupon_code = null;
            $cart->save();
        }

        return true;
    }

    /**
     * 完全与当前购物车脱节，执行该函数后，如果产品添加购物车，会创建新的cart_id
     * 目前仅仅在登录用户退出账号的时候使用。
     * 该操作仅仅remove掉session保存的cart_id，并没有删除购物车的数据。
     */
    protected function actionClearCart()
    {
        Yii::$service->cart->quote->clearCart();
    }

    /** 该函数被遗弃
     * add cart items by pending order Id
     * 1. check if the order is exist ,and belong to current customer.
     * 2. get all item sku and custom option.
     * 3. add to cart like in product page ,click add to cart button.
     */
    protected function actionAddItemsByPendingOrder($order_id)
    {
    }
}
