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
 * Cart service.
 *
 * @method createCart() create cart
 * @see \fecshop\services\cart\Quote::actionCreateCart()
 * @method setCartId($cart_id) set cart id
 * @see \fecshop\services\cart\Quote::actionSetCartId()
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Quote extends Service
{
    /**
     * 购物车的个数计算，是否仅仅计算active状态的产品个数总和，如果设置false，则将购物车中所有的产品个数累加。
     */
    const SESSION_CART_ID = 'current_session_cart_id';

    protected $_cart_id;

    protected $_cart;

    protected $_shipping_cost;
    
    protected $_cartModelName = '\fecshop\models\mysqldb\Cart';

    protected $_cartModel;

    /**
     * 存储购物车的信息。
     */
    protected $cartInfo;
    
    public function init()
    {
        parent::init();
        list($this->_cartModelName, $this->_cartModel) = Yii::mapGet($this->_cartModelName);
    }

    /**
     * @return int|null get cart id, or null if user does not do any cart operation
     * @see \fecshop\services\cart\Quote::createCart()
     * @see \fecshop\services\cart\Quote::mergeCartAfterUserLogin()
     */
    public function getCartId()
    {
        if (!$this->_cart_id) {
            if (Yii::$service->store->isAppserver()) {
                // appserver 必须登陆用户才能加入购物车
                if (Yii::$app->user->isGuest) {
                    Yii::$service->helper->errors->add('appserver get cart id must login account');
                    
                    return false;
                }
                $customerId = Yii::$app->user->getId();
                $cart = $this->getCartByCustomerId($customerId);
                if ($cart && $cart['cart_id']) {
                    $this->setCartId($cart['cart_id']);
                }
            } else {
                $cart_id = Yii::$service->session->get(self::SESSION_CART_ID);

                if (! $cart_id) {
                    if (! Yii::$app->user->isGuest) {
                        $customerId = Yii::$app->user->getId();
                        $cart = $this->getCartByCustomerId($customerId);
                        if ($cart && $cart['cart_id']) {
                            $this->setCartId($cart['cart_id']);
                        }
                    }
                } else {
                    $this->_cart_id = $cart_id;
                }
            }
        }

        return $this->_cart_id;
    }

    /**
     * @param $address|array 地址信息数组，详细参看下面函数显示的字段。
     * @param $shipping_method | String 货运方式
     * @param $payment_method | String 支付方式
     * @param bool
     * 更新游客购物车信息，用户下次下单 或者 重新下单，可以不需要重新填写货运地址信息。
     */
    public function updateGuestCart($address, $shipping_method, $payment_method)
    {
        $cart = $this->getCurrentCart();
        if ($cart) {
            $cart->customer_firstname       = $address['first_name'];
            $cart->customer_lastname        = $address['last_name'];
            $cart->customer_email           = $address['email'];
            $cart->customer_telephone       = $address['telephone'];
            $cart->customer_address_street1 = $address['street1'];
            $cart->customer_address_street2 = $address['street2'];
            $cart->customer_address_country = $address['country'];
            $cart->customer_address_city    = $address['city'];
            $cart->customer_address_state   = $address['state'];
            $cart->customer_address_zip     = $address['zip'];
            $cart->shipping_method          = $shipping_method;
            $cart->payment_method           = $payment_method;

            return $cart->save();
        }
    }

    /**
     * @param $address_id | int 用户customer address id
     * @param $shipping_method 货运方式
     * @param $payment_method  支付方式
     * @param bool
     * 登录用户的cart信息，进行更新，更新cart的$address_id,$shipping_method,$payment_method。
     * 用途：对于登录用户，create new address（在下单页面），新创建的address会被保存，
     * 然后需要把address_id更新到cart中。
     * 对于 shipping_method 和 payment_method，保存到cart中，下次进入下单页面，会被记录
     * 下次登录用户进行下单，进入下单页面，会自动填写。
     */
    public function updateLoginCart($address_id, $shipping_method, $payment_method)
    {
        $cart = $this->getCurrentCart();
        if ($cart && $address_id) {
            $cart->customer_address_id  = $address_id;
            $cart->shipping_method      = $shipping_method;
            $cart->payment_method       = $payment_method;

            return $cart->save();
        }
    }

    /**
     * @return object
     *                得到当前的cart，如果当前的cart不存在，则返回为空
     *                注意：这是getCurrentCart() 和 getCart()两个函数的区别，getCart()函数在没有cart_id的时候会创建cart。
     */
    public function getCurrentCart()
    {
        if (!$this->_cart) {
            $cart_id = $this->getCartId();
            if ($cart_id) {
                $one = $this->_cartModel->findOne(['cart_id' => $cart_id]);
                if ($one['cart_id']) {
                    $this->_cart = $one;
                }
            }
        }

        return $this->_cart;
    }

    /**
     * 如果当前的Cart不存在，则创建Cart
     * 如果当前的cart存在，则查询，如果查询得到cart，则返回，如果查询不到，则重新创建
     * 设置$this->_cart 为 上面新建或者查询得到的cart对象。
     */
    public function getCart()
    {
        if (!$this->_cart) {
            $cart_id = $this->getCartId();
            if (!$cart_id) {
                $this->createCart();
            } else {
                $one = $this->_cartModel->findOne(['cart_id' => $cart_id]);
                if ($one['cart_id']) {
                    $this->_cart = $one;
                } else {
                    // 如果上面查询为空，则创建cart
                    $this->createCart();
                }
            }
        }

        return $this->_cart;
    }

    /**
     * @param $cart | $this->_cartModel Object
     * 设置$this->_cart 为 当前传递的$cart对象。
     */
    public function setCart($cart)
    {
        $this->_cart = $cart;
    }

    /**
     * @return $items_count | Int , 得到购物车中产品的个数。头部的ajax请求一般访问这个.
     * 目前是通过表查询获取的。
     */
    public function getCartItemCount()
    {
        $items_count = 0;
        if ($cart_id = $this->getCartId()) {
            if ($cart_id) {
                $cart = $this->getCart();
                //$one = $this->_cartModel->findOne(['cart_id' => $cart_id]);
                if (isset($cart['items_count']) && $cart['items_count']) {
                    $items_count = $cart['items_count'];
                }
            }
        }

        return $items_count;
    }

    /**
     * @param $item_qty | Int
     * 当$active_item_qty为null时，从cart items表中查询产品总数。
     * 当$item_qty 不等于null时，代表已经知道购物车中active产品的个数，不需要去cart_item表中查询，譬如清空购物车操作，直接就知道产品个数肯定为零。
     * 当购物车的产品变动后，会调用该函数，更新cart表的产品总数
     */
    public function computeCartInfo($active_item_qty = null)
    {
        $items_all_count = 0;
        if ($active_item_qty === null) {
            $active_item_qty = Yii::$service->cart->quoteItem->getActiveItemQty();
        }
        $items_all_count = Yii::$service->cart->quoteItem->getItemAllQty();
        $cart = $this->getCart();
        $cart->items_all_count = $items_all_count;
        $cart->items_count = $active_item_qty;
        $cart->save();

        return true;
    }

    /**
     * @param int $cart_id cart id
     * 设置cart_id类变量以及session中记录当前cartId的值
     * Cart的session的超时时间由session组件决定。
     */
    public function setCartId($cart_id)
    {
        $this->_cart_id = $cart_id;
        if (!Yii::$service->store->isAppserver()) {
            Yii::$service->session->set(self::SESSION_CART_ID, $cart_id);
        }
    }

    /**
     * 删除掉active状态的购物车产品
     * 对于active的产品，在支付成功后，这些产品从购物车清楚
     * 而对于noActive产品，这些产品并没有支付，因而在购物车中保留。
     */
    public function clearCart()
    {
        return Yii::$service->cart->quoteItem->removeNoActiveItemsByCartId();
    }

    /**
     * 初始化创建cart信息，
     * 在用户的第一个产品加入购物车时，会在数据库中创建购物车.
     */
    public function createCart()
    {
        $myCart = new $this->_cartModelName;
        $myCart->store = Yii::$service->store->currentStore;
        $myCart->created_at = time();
        $myCart->updated_at = time();
        if (!Yii::$app->user->isGuest) {
            $identity   = Yii::$app->user->identity;
            $id         = $identity['id'];
            $firstname  = $identity['firstname'];
            $lastname   = $identity['lastname'];
            $email      = $identity['email'];
            $myCart->customer_id        = $id;
            $myCart->customer_email     = $email;
            $myCart->customer_firstname = $firstname;
            $myCart->customer_lastname  = $lastname;
            $myCart->customer_is_guest  = 2;
        } else {
            $myCart->customer_is_guest  = 1;
        }
        $myCart->remote_ip  = \fec\helpers\CFunc::get_real_ip();
        $myCart->app_name   = Yii::$service->helper->getAppName();
        //if ($defaultShippingMethod = Yii::$service->shipping->getDefaultShippingMethod()) {
        //    $myCart->shipping_method = $defaultShippingMethod;
        //}
        $myCart->save();
        $cart_id = $myCart['cart_id'];
        $this->setCartId($cart_id);
        $this->setCart($this->_cartModel->findOne($cart_id));
    }

    /**
     * @param $activeProduct | boolean , 是否只要active的产品
     * @param $shipping_method | String  传递的货运方式
     * @param $country | String 货运国家
     * @param $region | String 省市
     * @return bool OR array ，如果存在问题返回false，对于返回的数组的格式参看下面$this->cartInfo[$cartInfoKey] 部分的数组。
     *              返回当前购物车的信息。包括购物车对应的产品信息。
     *              对于可选参数，如果不填写，就是返回当前的购物车的数据。
     *              对于填写了参数，返回的是填写参数后的数据，这个一般是用户选择了了货运方式，国家等，然后
     *              实时的计算出来数据反馈给用户，但是，用户选择的数据并没有进入cart表
     */
    public function getCartInfo($activeProduct = true, $shipping_method = '', $country = '', $region = '*')
    {
        // 根据传递的参数的不同，购物车数据计算一次后，第二次调用，不会重新计算数据。
        $cartInfoKey = $shipping_method.'-shipping-'.$country.'-country-'.$region.'-region';
        if (!isset($this->cartInfo[$cartInfoKey])) {
            $cart_id = $this->getCartId();
            if (!$cart_id) {
                
                return false;
            }
            $cart = $this->getCart();
            // 购物车中所有的产品个数
            $items_all_count = $cart['items_all_count'];
            // 购物车中active状态的产品个数
            $items_count = $cart['items_count'];
            if ($items_count <=0 && $items_all_count <= 0) {
                
                return false;
            }
            $coupon_code = $cart['coupon_code'];
            $cart_product_info = Yii::$service->cart->quoteItem->getCartProductInfo($activeProduct);
            //var_dump($cart_product_info);
            if (is_array($cart_product_info)) {
                $product_weight = $cart_product_info['product_weight'];
                $product_volume_weight = $cart_product_info['product_volume_weight'];
                $product_volume = $cart_product_info['product_volume'];
                $product_final_weight = max($product_weight, $product_volume_weight);
                $products = $cart_product_info['products'];
                $product_total = $cart_product_info['product_total'];
                $base_product_total = $cart_product_info['base_product_total'];
                $product_qty_total = $cart_product_info['product_qty_total'];
                if (is_array($products) && !empty($products)) {
                    $currShippingCost = 0;
                    $baseShippingCost = 0;
                    if ($shipping_method && $product_final_weight) {
                        $shippingCost = $this->getShippingCost($shipping_method, $product_final_weight, $country, $region);
                        $currShippingCost = $shippingCost['currCost'];
                        $baseShippingCost = $shippingCost['baseCost'];
                    }
                    $couponCost = $this->getCouponCost($base_product_total, $coupon_code);

                    $baseDiscountCost = $couponCost['baseCost'];
                    $currDiscountCost = $couponCost['currCost'];

                    $curr_grand_total = $product_total + $currShippingCost - $currDiscountCost;
                    $base_grand_total = $base_product_total + $baseShippingCost - $baseDiscountCost;
                    if (!$shipping_method) {
                        $shipping_method = $cart['shipping_method'];
                    }
                    $this->cartInfo[$cartInfoKey] = [
                        'cart_id'           => $cart_id,
                        'store'             => $cart['store'],          // store nme
                        'items_count'       => $product_qty_total,      // 因为购物车使用了active，因此生成订单的产品个数 = 购物车中active的产品的总个数（也就是在购物车页面用户勾选的产品的总数），而不是字段 $cart['items_count']
                        'coupon_code'       => $coupon_code,            // coupon卷码
                        'shipping_method'   => $shipping_method,
                        'payment_method'    => $cart['payment_method'],
                        'grand_total'       => Yii::$service->helper->format->numberFormat($curr_grand_total),       // 当前货币总金额
                        'shipping_cost'     => Yii::$service->helper->format->numberFormat($currShippingCost),       // 当前货币，运费
                        'coupon_cost'       => Yii::$service->helper->format->numberFormat($currDiscountCost),       // 当前货币，优惠券优惠金额
                        'product_total'     => Yii::$service->helper->format->numberFormat($product_total),          // 当前货币，购物车中产品的总金额

                        'base_grand_total'  => Yii::$service->helper->format->numberFormat($base_grand_total),       // 基础货币总金额
                        'base_shipping_cost'=> Yii::$service->helper->format->numberFormat($baseShippingCost),       // 基础货币，运费
                        'base_coupon_cost'  => Yii::$service->helper->format->numberFormat($baseDiscountCost),       // 基础货币，优惠券优惠金额
                        'base_product_total'=> Yii::$service->helper->format->numberFormat($base_product_total),     // 基础货币，购物车中产品的总金额

                        'products'          => $products,               //产品信息。
                        'product_weight'            => Yii::$service->helper->format->numberFormat($product_weight),         //产品的总重量。
                        'product_volume_weight'     => Yii::$service->helper->format->numberFormat($product_volume_weight),
                        'product_volume'            => Yii::$service->helper->format->numberFormat($product_volume),
                    ];
                }
            }
        }

        return $this->cartInfo[$cartInfoKey];
    }

    /**
     * @param $shippingCost | Array ,example:
     * 	[
     *		'currCost'   => 33.22, #当前货币的运费金额
     *		'baseCost'	 => 26.44,  #基础货币的运费金额
     *	];
     *  设置快递运费金额。根据国家地址和产品重量等信息计算出来的运费
     */
    public function setShippingCost($shippingCost)
    {
        $this->_shipping_cost = $shippingCost;
    }

    /**
     * @param $shipping_method | String 货运方式
     * @param $weight | Float 产品重量
     * @param $country | String 国家
     * @param $region | String 省/市
     * @return $this->_shipping_cost | Array ,format:
     *                               [
     *                               'currCost'   => 33.22, #当前货币的运费金额
     *                               'baseCost'	=> 26.44,  #基础货币的运费金额
     *                               ];
     *                               得到快递运费金额。
     */
    public function getShippingCost($shipping_method = '', $weight = '', $country = '', $region = '')
    {
        if (!$this->_shipping_cost) {
            $available_method = Yii::$service->shipping->getAvailableShippingMethods($country, $region, $weight);
            $shippingInfo = $available_method[$shipping_method];
            $shippingCost = Yii::$service->shipping->getShippingCost($shipping_method, $shippingInfo, $weight, $country, $region);
            $this->_shipping_cost = $shippingCost;
        }

        return $this->_shipping_cost;
    }

    /**
     * 得到优惠券的折扣金额.
     * @return array , example:
     *               [
     *               'baseCost' => $base_discount_cost, # 基础货币的优惠金额
     *               'currCost' => $curr_discount_cost  # 当前货币的优惠金额
     *               ]
     */
    public function getCouponCost($base_product_total, $coupon_code)
    {
        $dc_discount = Yii::$service->cart->coupon->getDiscount($coupon_code, $base_product_total);
        
        return $dc_discount;
    }

    /**
     * @param $coupon_code | String
     * 设置购物车的优惠券
     */
    public function setCartCoupon($coupon_code)
    {
        $cart = $this->getCart();
        $cart->coupon_code = $coupon_code;
        $cart->save();

        return true;
    }

    /**
     * @param $coupon_code | String
     * 取消购物车的优惠券
     */
    public function cancelCartCoupon($coupon_code)
    {
        $cart = $this->getCart();
        $cart->coupon_code = null;
        $cart->save();

        return true;
    }

    /**
     * 当用户登录账号后，将用户未登录时的购物车和用户账号中保存
     * 的购物车信息进行合并。
     */
    public function mergeCartAfterUserLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $identity           = Yii::$app->user->identity;
            $customer_id        = $identity['id'];
            $email              = $identity->email;
            $customer_firstname = $identity->firstname;
            $customer_lastname  = $identity->lastname;
            $customer_cart      = $this->getCartByCustomerId($customer_id);
            $cart_id            = $this->getCartId();
            if (!$customer_cart) {
                if ($cart_id) {
                    $cart = $this->getCart();
                    if ($cart) {
                        $cart['customer_email']     = $email;
                        $cart['customer_id']        = $customer_id;
                        $cart['customer_firstname'] = $customer_firstname;
                        $cart['customer_lastname']  = $customer_lastname;
                        $cart['customer_is_guest']  = 2;
                        $cart->save();
                    }
                }
            } else {
                $cart = $this->getCart();
                if (!$cart || !$cart_id) {
                    $cart_id = $customer_cart['cart_id'];
                    $this->setCartId($cart_id);
                } else {
                    // 将无用户产品（当前）和 购物车中的产品（登录用户对应的购物车）进行合并。
                    $new_cart_id = $customer_cart['cart_id'];
                    if ($cart['coupon_code']) {
                        // 如果有优惠券则取消，以登录用户的购物车的优惠券为准。
                        Yii::$service->cart->coupon->cancelCoupon($cart['coupon_code']);
                    }
                    // 将当前购物车产品表的cart_id 改成 登录用户对应的cart_id
                    if ($new_cart_id && $cart_id && ($new_cart_id != $cart_id)) {
                        Yii::$service->cart->quoteItem->updateCartId($new_cart_id, $cart_id);
                        // 当前的购物车删除掉
                        $cart->delete();
                        // 设置当前的cart_id
                        $this->setCartId($new_cart_id);
                        // 设置当前的cart
                        $this->setCart($customer_cart);
                        // 重新计算购物车中产品的个数
                        $this->computeCartInfo();
                    }
                }
            }
        }
    }

    /**
     * @param $customer_id | int
     * @return $this->_cartModel Object。
     *                通过用户的customer_id，在cart表中找到对应的购物车
     */
    public function getCartByCustomerId($customer_id)
    {
        if ($customer_id) {
            $one = $this->_cartModel->findOne(['customer_id' => $customer_id]);
            if ($one['cart_id']) {
                
                return $one;
            }
        }
    }
}
