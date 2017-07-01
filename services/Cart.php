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
 * Cart services. 购物车service， 执行购物车部分对应的方法。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Cart extends Service
{
    /**
     * 将某个产品加入到购物车中.
     * @property $item|array
     * $item = [
     *		'product_id' 		=> 22222,
     *		'custom_option_sku' => ['color'=>'red','size'=>'l'],
     *		'qty' 				=> 22,
     * ];
     * 注意： $item['custom_option_sku'] 除了为上面的数组格式，还可以为字符串
     * 为字符串的时候，字符串标示的就是产品的custom option  sku
     */
    protected function actionAddProductToCart($item)
    {
        $product = Yii::$service->product->getByPrimaryKey($item['product_id']);
        // 根据传递的值，得到custom_option_sku的值。
        if (isset($item['custom_option_sku']) && !empty($item['custom_option_sku'])) {
            if (is_array($item['custom_option_sku'])) {
                $custom_option_sku = Yii::$service->cart->info->getCustomOptionSku($item, $product);
                if (!$custom_option_sku) {
                    Yii::$service->helper->errors->add('product custom_option_sku is not exist');

                    return false;
                }
            }
            $item['custom_option_sku'] = $custom_option_sku;
        }
        // 检查产品满足加入购物车的条件
        $productValidate = Yii::$service->cart->info->checkProductBeforeAdd($item, $product);
        if (!$productValidate) {
            return false;
        }
        // 开始加入购物车
        // service 里面不允许有事务，请在调用层使用事务。
        $beforeEventName    = 'event_add_to_cart_before';
        $afterEventName     = 'event_add_to_cart_after';
        /**
         * 此处属于fecshop自造的简单事件event，比较简洁
         * 详情参看：http://www.fecshop.com/doc/fecshop-guide/instructions/cn-1.0/guide-fecshop_event.html
         */
        Yii::$service->event->trigger($beforeEventName, $item); // 触发事件 - 加购物车前事件
        Yii::$service->cart->quoteItem->addItem($item);
        Yii::$service->event->trigger($afterEventName, $item);  // 触发事件 - 加购物车后事件
        return true;
    }

    /**
     * 得到购物车中产品的个数，详情参看调用的函数注释
     */
    protected function actionGetCartItemQty()
    {
        return Yii::$service->cart->quote->getCartItemCount();
    }

    /**
     * @property $shipping_method | String 货运方式code
     * @property $country | String 国家code
     * @property $region | String 省市code
     * 得到购物车中的信息。详情参看调用的函数注释
     */
    protected function actionGetCartInfo($shipping_method = '', $country = '', $region = '*')
    {
        return Yii::$service->cart->quote->getCartInfo($shipping_method, $country, $region);
    }

    /**
     * @property $item_id | Int 购物车产品表的id字段
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
     * @property $item_id | Int 购物车产品表的id字段
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
     * @property $item_id | Int 购物车产品表的id字段
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
     * @property $address|array
     * @property $shipping_method | String 发货方式
     * @property $payment_method | String 支付方式
     * 此函数对应的是保存游客用户的购物车数据。
     * 保存购物车中的货运地址保存购物车中的货运地址(姓名，电话，邮编，地址等)，货运方式，支付方式等信息。
     * 详细参看相应函数
     */
    protected function actionUpdateGuestCart($address, $shipping_method, $payment_method)
    {
        return Yii::$service->cart->quote->updateGuestCart($address, $shipping_method, $payment_method);
    }
    /**
     * @property $address_id | Int
     * @property $shipping_method | String 货运方式
     * @property $payment_method | String 支付方式
     * 此函数对应的是登录用户的购物车数据的更新。
     */
    protected function actionUpdateLoginCart($address_id, $shipping_method, $payment_method)
    {
        return Yii::$service->cart->quote->updateLoginCart($address_id, $shipping_method, $payment_method);
    }

    /**
     * 清空购物车中的产品和优惠券
     * 在生成订单的时候被调用.
     */
    protected function actionClearCartProductAndCoupon()
    {
        Yii::$service->cart->quoteItem->removeItemByCartId();

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
