<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Payment\block\paypal\standard;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Placeorder extends \yii\base\BaseObject
{
    /**
     * 用户的账单地址信息，通过用户传递的信息计算而来。
     */
    public $_billing;

    public $_address_id;
    /**
     * 用户的货运方式.
     */
    public $_shipping_method;
    /**
     * 用户的支付方式.
     */
    public $_payment_method;

    public function getLastData()
    {
        $token = Yii::$app->request->get('token');
        if(!$token){
            Yii::$service->helper->errors->add('token can not empty');
            return false;
        }
        //echo $token.'<br>';
        // 得到当前的订单信息
        $doCheckoutReturn = $this->doCheckoutPayment($token);
        if ($doCheckoutReturn) {
            //var_dump($doCheckoutReturn);
            $orderPayment = Yii::$service->payment->paypal->updateOrderPayment($doCheckoutReturn,$token);
            // 如果支付成功，并把信息更新到了订单数据中，则进行下面的操作。
            //echo 444;
            //var_dump($orderPayment);
            if ($orderPayment) {
                //echo 55;
                // 支付成功后，在清空购物车数据。而不是在生成订单的时候。
                Yii::$service->cart->clearCartProductAndCoupon();
                // (删除)支付成功后，扣除库存。
                // (删除)Yii::$service->product->stock->deduct();
                // echo 555;
                // 发送新订单邮件

                // 扣除库存和优惠券
                // 在生成订单的时候已经扣除了。参看order service GenerateOrderByCart() function

                // 得到支付跳转前的准备页面。
                $paypal_standard = Yii::$service->payment->paypal->standard_payment_method;
                //echo '<br>';
                //echo $paypal_standard;
                $successRedirectUrl = Yii::$service->payment->getStandardSuccessRedirectUrl($paypal_standard);
                //echo '<br>';
                //echo $successRedirectUrl;
                Yii::$service->url->redirect($successRedirectUrl);

                return true;
            }
        }else{
            Yii::$service->url->redirectByUrlKey('checkout/onepage');
        }
        // 如果订单支付过程中失败，将订单取消掉
        if (!$doCheckoutReturn || !$orderPayment) {
            $innerTransaction = Yii::$app->db->beginTransaction();
            try {
                if(Yii::$service->order->cancel()){
                    $innerTransaction->commit();
                }else{
                    $innerTransaction->rollBack();
                }
            } catch (\Exception $e) {
                $innerTransaction->rollBack();
            }
        }
        Yii::$service->page->message->addByHelperErrors();

        return false;
    }
    /**
     * @param $token | String 
     * 通过paypal的api接口，进行支付下单
     */
    public function doCheckoutPayment($token)
    {
        $methodName_ = 'DoExpressCheckoutPayment';
        $nvpStr_ = Yii::$service->payment->paypal->getCheckoutPaymentNvpStr($token);
        //echo '<br/>nvpStr_:<br/>"'.$nvpStr_.'<br/><br/>';
        $doCheckoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);
        //echo '<br/>doCheckoutReturn <br/><br/>';
        //var_dump($doCheckoutReturn);
        //echo '<br/>doCheckoutReturn <br/><br/>';
        //exit;
        if (strstr(strtolower($doCheckoutReturn['ACK']), 'success')) {
            return $doCheckoutReturn;
        } else {
            if ($doCheckoutReturn['ACK'] == 'Failure') {
                $message = $doCheckoutReturn['L_LONGMESSAGE0'];
                // 添加报错信息。
                //Message::error($message);
                Yii::$service->helper->errors->add($message);
            } else {
                Yii::$service->helper->errors->add('paypal express payment error.');
            }
            
            return false;
        }
    }

    /**
     * @param $post | Array
     * 登录用户，保存货运地址到customer address ，然后把生成的
     * address_id 写入到cart中。
     * shipping method写入到cart中
     * payment method 写入到cart中 updateCart
     */
    public function updateAddress($post)
    {
        return Yii::$service->cart->updateGuestCart($this->_billing, $this->_shipping_method, $this->_payment_method);
    }

    /**
     * 如果是游客，那么保存货运地址到购物车表。
     */
    /*
    public function updateCart(){
        if(Yii::$app->user->isGuest){
            return Yii::$service->cart->updateGuestCart($this->_billing,$this->_shipping_method,$this->_payment_method);
        }else{
            return Yii::$service->cart->updateLoginCart($this->_address_id,$this->_shipping_method,$this->_payment_method);
        }
    }
    */

    /**
     * @param $post | Array
     * @return bool
     *              检查前台传递的信息是否正确。同时初始化一部分类变量
     */
    public function checkOrderInfoAndInit($post)
    {
        $address_one = '';
        $billing = isset($post['billing']) ? $post['billing'] : '';
        if (!Yii::$service->order->checkRequiredAddressAttr($billing)) {
            return false;
        }
        $this->_billing = $billing;

        $shipping_method = isset($post['shipping_method']) ? $post['shipping_method'] : '';
        $payment_method = isset($post['payment_method']) ? $post['payment_method'] : '';
        // 验证货运方式
        if (!$shipping_method) {
            Yii::$service->helper->errors->add('shipping method can not empty');

            return false;
        } else {
            if (!Yii::$service->shipping->ifIsCorrect($shipping_method)) {
                Yii::$service->helper->errors->add('shipping method is not correct');

                return false;
            }
        }

        $this->_shipping_method = $shipping_method;
        $this->_payment_method = $payment_method;
        Yii::$service->payment->setPaymentMethod($this->_payment_method);

        return true;
    }
}
