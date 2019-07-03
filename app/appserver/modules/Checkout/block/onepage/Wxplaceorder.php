<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Checkout\block\onepage;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Wxplaceorder
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
    
    public $_order_remark;

    public function getLastData()
    {
        $post = Yii::$app->request->post();
        if (is_array($post) && !empty($post)) {
            /**
             * 对传递的数据，去除掉非法xss攻击部分内容（通过\Yii::$service->helper->htmlEncode()）.
             */
            $post = \Yii::$service->helper->htmlEncode($post);
            // 如果是支付宝，那么更改货币为人民币
             Yii::$service->page->currency->setCurrentCurrency2CNY(); 
            // 得到默认的  address_id payment_method
            $post['payment_method'] = 'wxpay_jsapi';
            $post['address_id'] = Yii::$service->customer->address->getDefualtAddressId();
             
            // 检查前台传递的数据的完整
            $checkInfo = $this->checkOrderInfoAndInit($post);
            if ($checkInfo !== true) {
                $code = Yii::$service->helper->appserver->order_generate_request_post_param_invaild;
                $data = [];
                $message = $checkInfo;
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data, $message);
                
                return $responseData;
            }
            
            
            // 更新Cart信息
            //$this->updateCart();
            // 设置checkout type
            $serviceOrder = Yii::$service->order;
            $checkout_type = $serviceOrder::CHECKOUT_TYPE_STANDARD;
            $serviceOrder->setCheckoutType($checkout_type);
            // 将购物车数据，生成订单。
            $innerTransaction = Yii::$app->db->beginTransaction();
            try {
                # 生成订单，扣除库存，但是，不清空购物车。
                $genarateStatus = Yii::$service->order->generateOrderByCart($this->_billing, $this->_shipping_method, $this->_payment_method, false, '', $this->_order_remark);
                if ($genarateStatus) {
                    // 得到当前的订单信息
                    //$orderInfo = Yii::$service->order->getCurrentOrderInfo();
                    // 发送新订单邮件
                    //Yii::$service->email->order->sendCreateEmail($orderInfo);
                    // 得到支付跳转前的准备页面。
                    $createdOrder = Yii::$service->order->createdOrder;
                    $startUrl = Yii::$service->payment->getStandardStartUrl('','appserver');
                    $innerTransaction->commit();
                    $code = Yii::$service->helper->appserver->status_success;
                    $data = [
                            'increment_id' => $createdOrder['increment_id'],
                            'grand_total' => $createdOrder['grand_total'],
                            'symbol' => Yii::$service->page->currency->getSymbol($createdOrder['order_currency_code']),
                           // 'increment_id' => $createdOrder['increment_id'],
                    ];
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                    
                    return $responseData;
                } else {
                    
                    $innerTransaction->rollBack();
                    $error = Yii::$service->helper->errors->get(',');
                    $code = Yii::$service->helper->appserver->order_generate_fail;
                    $data = [
                        'error' => $error,
                    ];
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                    
                    return $responseData;
                }
            } catch (\Exception $e) {
                $innerTransaction->rollBack();
            }
            
            
        }
        
        //echo 333;exit;
        //Yii::$service->page->message->addByHelperErrors();

        //return false;
    }

    /**
     * @param $post|Array，前台传递参数数组。
     * 如果游客选择了创建账户，并且输入了密码，则使用address email作为账号，
     * 进行账号的注册和登录。
     */
    public function guestCreateAndLoginAccount($post)
    {
        $create_account = $post['create_account'];
        $billing = $post['billing'];
        //var_dump($create_account);
        if ($create_account) {
            //echo 22;
            //echo $create_account;exit;
            $customer_password = $billing['customer_password'];
            $confirm_password = $billing['confirm_password'];
            if ($customer_password != $confirm_password) {
                
                return 'the passwords are inconsistent';
            }
            $passMin = Yii::$service->customer->getRegisterPassMinLength();
            $passMax = Yii::$service->customer->getRegisterPassMaxLength();
            if (strlen($customer_password) < $passMin) {
                
                return 'password must Greater than '.$passMin;
            }
            if (strlen($customer_password) > $passMax) {
                
                return 'password must less than '.$passMax;
            }
            $param['email'] = $billing['email'];
            $param['password'] = $billing['customer_password'];
            $param['firstname'] = $billing['first_name'];
            $param['lastname'] = $billing['last_name'];
            if (!Yii::$service->customer->register($param)) {
                
                return 'customer register account fail';
            } else {
                Yii::$service->customer->Login([
                    'email'        => $billing['email'],
                    'password'    => $billing['customer_password'],
                ]);
            }
        }

        return true;
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
        if (!Yii::$app->user->isGuest) {
            $billing = $post['billing'];
            $address_id = $post['address_id'];
            if (!$address_id) {
                $identity = Yii::$app->user->identity;
                $customer_id = $identity['id'];
                $one = [
                    'first_name'    => $billing['first_name'],
                    'last_name'    => $billing['last_name'],
                    'email'        => $billing['email'],
                    'company'        => '',
                    'telephone'    => $billing['telephone'],
                    'fax'            => '',
                    'street1'        => $billing['street1'],
                    'street2'        => $billing['street2'],
                    'city'            => $billing['city'],
                    'state'        => $billing['state'],
                    'zip'            => $billing['zip'],
                    'country'        => $billing['country'],
                    'customer_id'    => $customer_id,
                    'is_default'    => 1,
                ];
                $address_id = Yii::$service->customer->address->save($one);
                $this->_address_id = $address_id;
                if (!$address_id) {
                    
                    return 'new customer address save fail';
                }
                //echo "$address_id,$this->_shipping_method,$this->_payment_method";
            }

            Yii::$service->cart->updateLoginCart($this->_address_id, $this->_shipping_method, $this->_payment_method);
        } else {
            Yii::$service->cart->updateGuestCart($this->_billing, $this->_shipping_method, $this->_payment_method);
        }
        return true;

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
        $address_id = isset($post['address_id']) ? $post['address_id'] : '';
        $billing = isset($post['billing']) ? $post['billing'] : '';
        if ($address_id) {
            $this->_address_id = $address_id;
            if (Yii::$app->user->isGuest) {
                return 'address id can not use for guest';
                //return false; // address_id 这种情况，必须是登录用户。
            } else {
                $customer_id = Yii::$app->user->identity->id;
                if (!$customer_id) {
                    return 'customer id is empty';
                } else {
                    $address_one = Yii::$service->customer->address->getAddressByIdAndCustomerId($address_id, $customer_id);
                    if (!$address_one) {
                        return 'current address id is not belong to current user';
                    } else {
                        // 从address_id中取出来的字段，查看是否满足必写的要求。
                        if (!Yii::$service->order->checkRequiredAddressAttr($address_one)) {
                            return 'address info error';
                        }
                        $arr['customer_id'] = $customer_id;
                        foreach ($address_one as $k=>$v) {
                            $arr[$k] = $v;
                        }
                        $this->_billing = $arr;
                    }
                }
            }
        } elseif ($billing && is_array($billing)) {
            // 检查address的必写字段是否都存在
            //var_dump($billing);exit;
            if (!Yii::$service->order->checkRequiredAddressAttr($billing)) {
                return 'address require attr is empty';
            }
            $this->_billing = $billing;
        }
        $shipping_method = isset($post['shipping_method']) ? $post['shipping_method'] : '';
        $payment_method = isset($post['payment_method']) ? $post['payment_method'] : '';
        // 验证货运方式
        if (!$shipping_method) {
            return 'shipping method can not empty';
        } else {
            // if (!Yii::$service->shipping->ifIsCorrect($shipping_method)) {
            //     return 'shipping method is not correct';
            // }
        }
        // 验证支付方式
        if (!$payment_method) {
            return 'payment method can not empty';
        } else {
            if (!Yii::$service->payment->ifIsCorrectStandard($payment_method)) {
                return 'payment method is not correct';
            }
        }
        // 订单备注信息不能超过1500字符
        $orderRemarkStrMaxLen = Yii::$service->order->orderRemarkStrMaxLen;
        $order_remark = isset($post['order_remark']) ? $post['order_remark'] : '';
        if ($order_remark && $orderRemarkStrMaxLen) {
            $order_remark_strlen = strlen($order_remark);
            if ($order_remark_strlen > $orderRemarkStrMaxLen) {
                Yii::$service->helper->errors->add('order remark string length can not gt {orderRemarkStrMaxLen}', ['orderRemarkStrMaxLen' => $orderRemarkStrMaxLen]);
                
                return false;
            } else {
                // 去掉xss攻击字符，关于防止xss攻击的yii文档参看：http://www.yiichina.com/doc/guide/2.0/security-best-practices#fang-zhi-xss-gong-ji
                $this->_order_remark = $order_remark;
            }
        }
        $this->_shipping_method = $shipping_method;
        $this->_payment_method  = $payment_method;
        Yii::$service->payment->setPaymentMethod($this->_payment_method);

        return true;
    }
}
