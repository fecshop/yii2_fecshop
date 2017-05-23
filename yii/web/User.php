<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\yii\web;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class User extends \yii\web\User
{
    /**
     * 重写该方法的作用为：当用户登录账户设置为cookie的时候
     * 购物车信息，通过cookie获取的用户id，在session中设置cart_id.
     */
    protected function loginByCookie()
    {
        $data = $this->getIdentityAndDurationFromCookie();
        if (isset($data['identity'], $data['duration'])) {
            $identity = $data['identity'];
            $duration = $data['duration'];
            if ($this->beforeLogin($identity, true, $duration)) {
                $this->switchIdentity($identity, $this->autoRenewCookie ? $duration : 0);
                $id = $identity->getId();
                $ip = Yii::$app->getRequest()->getUserIP();
                Yii::info("User '$id' logged in from $ip via cookie.", __METHOD__);
                $this->afterLogin($identity, true, $duration);
                /**
                 * 如果user组件配置enableAutoLogin = true
                 * 当session失效后，就会调用当前方法，cookie获取信息后，重新使用session登录
                 * 因此，在账号重新恢复登录状态后，当前账户的购物车也要恢复。
                 * 下面的代码就是在cookie恢复登录状态后，通过当前账户的id，搜索出来购物车信息
                 * 然后把对应的购物车的cart_id,保存到cookie中。
                 */
                $customer_cart = Yii::$service->cart->quote->getCartByCustomerId($id);
                $cart_id = isset($customer_cart['cart_id']) ? $customer_cart['cart_id'] : '';
                //echo $cart_id;
                if ($cart_id) {
                    Yii::$service->cart->quote->setCartId($cart_id);
                }
                    //Yii::$service->cart->mergeCartAfterUserLogin();
            }
        }
    }
}
