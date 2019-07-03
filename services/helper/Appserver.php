<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\helper;

use fecshop\services\Service;
use Yii;

/**
 * 该类主要是给appserver端的api，返回的数据做格式输出，规范输出的各种状态。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Appserver extends Service
{
    /**
     * 公共状态码
     */
    public $status_success                                = 200;

    public $status_unknown                                = 1000000;   // 程序内部错误：未知错误

    public $status_mysql_disconnect                       = 1000001;   // 程序内部错误：mysql连接错误

    public $status_mongodb_disconnect                     = 1000002;   // 程序内部错误：mongodb连接错误

    public $status_redis_disconnect                       = 1000003;   // 程序内部错误：redis连接错误

    public $status_invalid_token                          = 1000004;   // 无效数据：token无效

    public $status_invalid_request_url                    = 1000005;   // 无效请求：该url不存在

    public $status_invalid_email                          = 1000006;   // 格式错误：邮箱格式无效

    public $status_invalid_captcha                        = 1000007;   // 无效数据：验证码错误

    public $status_invalid_param                          = 1000008;   // 无效参数

    public $status_miss_param                             = 1000009;   // 参数丢失

    public $status_limit_beyond                           = 1000010;   // 超出限制

    public $status_data_repeat                            = 1000011;   // 数据重复

    public $status_attack                                 = 1000012;   // 确定为攻击返回的状态

    public $status_invalid_code                           = 1000013;   // 程序内部错误：传递的无效code

    /**
     * 用户部分的状态码
     */
    public $account_register_email_exist                  = 1100000; // 注册：邮箱已经存在

    public $account_register_invalid_data                 = 1100001; // 注册：注册数据格式不正确

    public $account_login_invalid_email_or_password       = 1100002; // 登录：账户的邮箱或者密码不正确

    public $account_no_login_or_login_token_timeout       = 1100003; // 登录：账户的token已经过期,或者没有登录

    public $account_edit_invalid_data                     = 1100004; // 编辑：账户的编辑数据不正确

    public $account_contact_us_send_email_fail            = 1100005; // contact：发送邮件失败

    public $account_is_logined                            = 1100006; // 登录：用户已经登录

    public $account_register_fail                         = 1100007; // 注册：失败

    public $account_email_not_exist                       = 1100008; // 账户中该email不存在

    public $account_forget_password_token_timeout         = 1100009; // 忘记密码：token超时

    public $account_forget_password_reset_param_invalid   = 1100010; // 忘记密码：通过邮件重置密码，传递的参数缺失或不正确

    public $account_forget_password_reset_fail            = 1100011; // 忘记密码：重置密码失败

    public $account_address_is_not_exist                  = 1100012; // customer address：address id 不存在

    public $account_address_edit_param_invaild            = 1100013; // customer address：address 编辑传入的param存在问题，无效

    public $account_reorder_order_id_invalid              = 1100014; // customer order：reorder 传入的order_id 无效

    public $account_favorite_id_not_exist                 = 1100015; // custome favorite: favorite id is not exit

    public $account_facebook_login_error                  = 1100016; // 使用fb账户登录fecshop出错

    public $account_google_login_error                    = 1100017; // 使用google账户登录fecshop出错

    public $account_address_save_fail                     = 1100018;
    
    public $account_register_disable       = 1100019; // 注册后，账户disable，需要邮件激活
    public $account_register_resend_email_success       = 1100020; // 登录：账户的邮箱或者密码不正确
    public $account_register_send_email_fail       = 1100021; // 注册后，账户disable，需要邮件激活
    public $account_register_enable_token_invalid = 1100022;
    
    public $account_wx_get_user_info_fail = 1100023;  // 基于code，请求微信获取用户信息失败
    public $account_wx_user_login_fail = 1100024;   // wx登陆失败
    public $account_wx_get_customer_by_openid_fail = 1100025; // 通过openid 查找customer
    
    public $no_account_openid_and_session_key = 1100026;  // session中找不到 account_openid and session_key
    public $account_has_account_openid = 1100027;  //  openid 已经有存在的账户了
    public $account_login_and_get_access_token_fail = 1100028; // 登陆账户获取access_token失败
    public $account_register_email_exit                         = 1100029; // 注册：邮箱已经存在
    public $account_address_set_default_fail                         = 1100030; // 用户设置默认地址失败
    /** 
     * category状态码
     */
    public $category_not_exist                             = 1200000; // 分类：分类不存在
     
    /**
     * product状态码
     */
    public $product_favorite_fail                          = 1300000; // 产品：产品收藏失败

    public $product_not_active                             = 1300001; // 产品：已经下架
    
    public $product_id_not_exist                           = 1300002; // 产品：产品不存在

    public $product_save_review_fail                       = 1300003; // 产品：产品保存评论失败
    
    /**
     * cart
     */
    public $cart_product_add_fail                          = 1400001; // Cart：产品加入购物车失败

    public $cart_product_add_param_invaild                 = 1400002; // Cart：产品加入购物车传递参数无效

    public $cart_product_update_qty_fail                   = 1400003; // Cart：更改cart中product的个数失败

    public $cart_coupon_invalid                            = 1400004; // Cart：coupon不可用

    public $cart_product_select_fail                       = 1400005; // Cart：product 勾选出错
    
    /**
     * order
     */
    public $order_generate_product_stock_out               = 1500001; // Order: 下订单，产品库存不足。

    public $order_generate_fail                            = 1500002; // Order: 下订单，生成订单失败。

    public $order_paypal_express_get_token_fail            = 1500003; // Order: 通过paypal express方式支付，获取token失败

    public $order_generate_request_post_param_invaild      = 1500004; // Order: 下订单，必填的订单字段验证失败。

    public $order_generate_create_account_fail             = 1500005; // Order: 下订单，游客在下订单的同时直接生成账户失败。

    public $order_generate_save_address_fail               = 1500006; // Order: 下订单，游客在下订单的同时保存address信息失败。

    public $order_generate_cart_product_empty              = 1500007; // Order: 下订单，购物车数据为空

    public $order_shipping_country_empty                   = 1500008; // Order: 下订单页面，切换address，从customer address中无法获取country

    public $order_paypal_standard_get_token_fail           = 1500009; // Order: 通过paypal standard方式支付，获取token失败

    public $order_paypal_standard_payment_fail             = 1500010; // Order: 通过paypal standard方式支付，通过api支付失败

    public $order_paypal_standard_updateorderinfoafterpayment_fail  = 1500011; // Order: 通过paypal standard方式支付，api支付订单成功后，更新订单信息失败

    public $order_not_find_increment_id_from_dbsession     = 1500012; // order：无法从dbsession中获取order increment id
    
    public $order_paypal_express_payment_fail              = 1500013;           // Order: 通过paypal express方式支付，通过api支付失败

    public $order_paypal_express_updateorderinfoafterpayment_fail   = 1500014;  // Order: 通过paypal express方式支付，api支付订单成功后，更新订单信息失败

    public $order_paypal_express_get_PayerID_fail          = 1500015;           // Order: 通过paypal express方式支付，获取PayerID失败

    public $order_paypal_express_get_apiAddress_fail       = 1500016;           // Order: 通过paypal express方式支付，获取address失败
    
    public $order_has_been_paid                            = 1500017;           // Order: 下订单，订单已经被支付过

    public $order_not_exist                                = 1500018;           // Order: 下订单，订单不存在

    public $order_alipay_payment_fail                      = 1500019;           // Order: 下订单，支付宝支付订单失败
    
    public $order_payment_paypal_express_error             = 1500020;
    
    public $order_wxpay_payment_fail                      = 1500021;

    /**
     * cms
     */
    public $cms_article_not_exist                          = 1600001;           // Article: 文章不存在
    /**
     * 跨域访问cors
     */
    public $appserver_cors;
    /**
     *  用于vue端跨域访问的cors设置
     * @return array
     */
    public function getCors(){
        $cors_allow_headers = $this->getCorsAllowHeaders();
        $cors = $this->appserver_cors;
        $corsFilterArr = [];
        if (is_array($cors) && !empty($cors)) {
            if (isset($cors['Origin']) && $cors['Origin']) {
                $corsFilterArr['Origin'] = $cors['Origin'];
            }
            if (isset($cors['Access-Control-Request-Method']) && $cors['Access-Control-Request-Method']) {
                $corsFilterArr['Access-Control-Request-Method'] = $cors['Access-Control-Request-Method'];
            }
            if (isset($cors['Access-Control-Max-Age']) && $cors['Access-Control-Max-Age']) {
                $corsFilterArr['Access-Control-Max-Age'] = $cors['Access-Control-Max-Age'];
            }
            if (isset($cors['Access-Control-Allow-Headers']) && is_array($cors['Access-Control-Allow-Headers'])) {
                $cors_allow_headers = array_merge($cors_allow_headers, $cors['Access-Control-Allow-Headers']);
                $corsFilterArr['Access-Control-Request-Headers'] = $cors_allow_headers;
                $corsFilterArr['Access-Control-Expose-Headers'] = $cors_allow_headers;
            }
            $corsFilterArr['Access-Control-Allow-Credentials'] = true;
        }
        return $corsFilterArr;
        
    }

    public function getCorsAllowHeaders() {
        $fecshop_uuid = Yii::$service->session->fecshop_uuid;
        return ['Origin', 'X-Requested-With', 'Content-Type', 'Accept', $fecshop_uuid, 'fecshop-lang', 'fecshop-currency', 'access-token'];
    }
    /**
     * 用于vue端跨域访问的 customer token auth 的 cors设置
     * @return array
     */
    public function getYiiAuthCors(){
        $cors_allow_headers = $this->getCorsAllowHeaders();
        $cors = $this->appserver_cors;
        $corsFilterArr = [];
        if (is_array($cors) && !empty($cors)) {
            if (isset($cors['Origin']) && $cors['Origin']) {
                $corsFilterArr[] = 'Access-Control-Allow-Origin: ' .  implode(', ', $cors['Origin']);
            }
            
            if (isset($cors['Access-Control-Allow-Headers']) && is_array($cors['Access-Control-Allow-Headers'])) {
                $cors_allow_headers = array_merge($cors_allow_headers, $cors['Access-Control-Allow-Headers']);
            }
            $corsFilterArr[] = 'Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, ' . implode(', ', $cors_allow_headers);    
            
            if (isset($cors['Access-Control-Allow-Methods']) && is_array($cors['Access-Control-Allow-Methods'])) {
                $corsFilterArr[] = 'Access-Control-Allow-Methods: ' . implode(', ',$cors['Access-Control-Allow-Methods']);
            }
            $corsFilterArr[] = 'Access-Control-Allow-Credentials: true';
        }
        
        return $corsFilterArr;
    }
    /**
     * @param int $code 状态码
     * @param mixed $data 可以是数字，数组等格式，用于做返回给前端的数组。
     * @param string $message 选填，如果不填写，则使用函数返回的内容作为 message
     * @return array
     */
    public function getResponseData($code, $data, $message = '')
    {
        if (!$message) {
            $message = $this->getMessageByCode($code);
        }
        if ($message) {
            return [
                'code'    => $code,
                'message' => $message,
                'data'    => $data,
            ];
        } else {
            // 如果不存在，则说明系统内部调用不存在的 code，报错。
            $code = $this->status_invalid_code;
            $message = $this->getMessageByCode($code);
            return [
                'code'    => $code,
                'message' => $message,
                'data'    => '',
            ];
        }
    }
    
    /**
     * 得到 code 对应 message
     * @param int $code 状态码
     * @return string|array
     */
    public function getMessageByCode($code)
    {
        $messageArr = $this->getMessageArr();
        return isset($messageArr[$code]['message']) ? $messageArr[$code]['message'] : '';
    }

    /**
     * 得到 code 对应 message的数组
     */
    public function getMessageArr()
    {
        $arr = [
            /**
             * 公共状态码
             */
            $this->status_success => [
                'message' => 'process success',
            ],
            $this->status_unknown => [
                'message' => 'unknown errors',
            ],
            $this->status_mysql_disconnect => [
                'message' => 'mysql connect timeout',
            ],
            $this->status_mongodb_disconnect => [
                'message' => 'mongodb connect timeout',
            ],
            $this->status_redis_disconnect => [
                'message' => 'redis connect timeout',
            ],
            $this->status_invalid_token => [
                'message' => 'token is timeout or invalid',
            ],
            $this->status_invalid_request_url => [
                'message' => 'the request url is not exist',
            ],
            $this->status_invalid_email => [
                'message' => 'email format is not correct',
            ],
            $this->status_invalid_captcha => [
                'message' => 'captcha is not correct',
            ],
            $this->status_invalid_param => [
                'message' => 'incorrect request parameter',
            ],
            $this->status_invalid_code => [
                'message' => 'system error, invalid code',
            ],
            
            $this->status_miss_param => [
                'message' => 'required parameter does not exist',
            ],
            $this->status_limit_beyond => [
                'message' => 'beyond maximum limit',
            ],
            $this->status_data_repeat => [
                'message' => 'insert data is repeat',
            ],
            $this->status_attack => [
                'message' => 'access exception, the visit to determine the attack behavior',
            ],
            
            /**
             * 用户部分的状态码
             */
            $this->account_no_login_or_login_token_timeout => [
                'message' => 'account not login or token timeout',
            ],
            $this->account_register_email_exist => [
                'message' => 'account register email is exist',
            ],
            $this->account_register_invalid_data => [
                'message' => 'account register data is invalid',
            ],
            
            $this->account_login_invalid_email_or_password => [
                'message' => 'account login email or password is not correct',
            ],
            $this->account_edit_invalid_data => [
                'message' => 'account edit data is invalid',
            ],
            $this->account_contact_us_send_email_fail => [
                'message' => 'customer contact us send email fail',
            ],
            $this->account_is_logined => [
                'message' => 'account is logined',
            ],
            $this->account_register_fail => [
                'message' => 'account register fail',
            ],
            
            $this->account_email_not_exist => [
                'message' => 'account email not exist',
            ],
            
            
            $this->account_forget_password_token_timeout => [
                'message' => 'account forget password token timeout',
            ],
            $this->account_forget_password_reset_param_invalid => [
                'message' => 'account forget password reset param invalid',
            ],
            $this->account_forget_password_reset_fail => [
                'message' => 'account forget password reset fail',
            ],
            
            
            $this->account_address_is_not_exist => [
                'message' => 'account address id is not exist',
            ],
            
            $this->account_address_save_fail => [
                'message' => 'account address save fail',
            ],
            $this->account_register_disable => [
                'message' => 'account register is disable',
            ],
            $this->account_register_resend_email_success => [
                'message' => 'account register resend email success',
            ],
            $this->account_register_send_email_fail => [
                'message' => 'account_register_send_email_fail',
            ],
            $this->account_register_enable_token_invalid => [
                'message' => 'account_register_enable_token_invalid',
            ],
            $this->account_wx_get_user_info_fail => [
                'message' => 'use wxCode to get user info  fail',
            ],
            $this->account_wx_user_login_fail => [
                'message' => 'wx user login account fail',
            ],
            $this->account_wx_get_customer_by_openid_fail => [
                'message' => 'you should bind wx openid with one account',
            ],
            
            
            
            $this->no_account_openid_and_session_key => [
                'message' => 'no_account_openid_and_session_key',
            ],
            $this->account_has_account_openid => [
                'message' => 'account_has_account_openid',
            ],
            $this->account_login_and_get_access_token_fail => [
                'message' => 'account_login_and_get_access_token_fail',
            ],
            $this->account_register_email_exit => [
                'message' => 'account_register_email_exit',
            ],
            
            $this->account_address_set_default_fail => [
                'message' => 'account_address_set_default_fail',
            ],
            
            $this->account_address_edit_param_invaild => [
                'message' => 'account address edit param is invalid',
            ],
            $this->account_reorder_order_id_invalid => [
                'message' => 'customer reorder  order id is invalid',
            ],
            
            $this->account_favorite_id_not_exist => [
                'message' => 'customer favorite id is not exit',
            ],
            
            $this->account_facebook_login_error => [
                'message' => 'login F-E-C-shop with facebook account error',
            ],
            
            $this->account_google_login_error => [
                'message' => 'login F-e-c-shop with google account error',
            ],
            
            
            /**
             * category
             */
            $this->category_not_exist => [
                'message' => 'category is not exist',
            ],
            
            /**
             * product
             */
            $this->product_favorite_fail => [
                'message' => 'product favorite fail',
            ],
            $this->product_not_active => [
                'message' => 'product is not exist or off the shelf',
            ],
            
            
            $this->product_id_not_exist => [
                'message' => 'product id is not exist',
            ],
            $this->product_save_review_fail => [
                'message' => 'save product review fail',
            ],
            /**
             * Cart
             */
            $this->cart_product_add_fail => [
                'message' => 'product add to cart fail',
            ],
            $this->cart_product_add_param_invaild => [
                'message' => 'product add to cart request param is invalid',
            ],
            $this->cart_product_update_qty_fail => [
                'message' => 'update cart product qty fail',
            ],
            $this->cart_coupon_invalid => [
                'message' => 'coupon code is invalid',
            ],
            $this->cart_product_select_fail => [
                'message' => 'cart product select fail',
            ],
            
            
            
            /**
             * Order
             */
            $this->order_generate_product_stock_out => [
                'message' => 'before generate order,check product stock out ',
            ],
            
            $this->order_generate_fail => [
                'message' => 'generate order fail',
            ],
            $this->order_paypal_express_get_token_fail => [
                'message' => 'order pay by paypal express api, fetch token fail',
            ],
            $this->order_generate_request_post_param_invaild => [
                'message' => 'require order request param is invaild',
            ],
            $this->order_generate_create_account_fail => [
                'message' => 'order generate page, guest create account fail',
            ],
            $this->order_generate_save_address_fail => [
                'message' => 'order generate page, login account save address fail',
            ],
            $this->order_generate_cart_product_empty => [
                'message' => 'order generate page, cart product is empty',
            ],
            $this->order_shipping_country_empty => [
                'message' => 'order checkout one page, get shipping fail, country is empty',
            ],
            $this->order_paypal_standard_get_token_fail => [
                'message' => 'order pay by paypal standard api, fetch token fail',
            ],
            $this->order_paypal_standard_payment_fail => [
                'message' => 'order pay by paypal standard api, payment fail',
            ],
            $this->order_paypal_standard_updateorderinfoafterpayment_fail => [
                'message' => 'order pay by paypal standard api, update order fail after payment',
            ],
            $this->order_not_find_increment_id_from_dbsession => [
                'message' => 'can not find order increment id from db session storage',
            ],
            
            $this->order_paypal_express_payment_fail => [
                'message' => 'order pay by paypal express api, payment fail',
            ],
            $this->order_paypal_express_updateorderinfoafterpayment_fail => [
                'message' => 'order pay by paypal express api, update order info fail',
            ],
            $this->order_paypal_express_get_PayerID_fail => [
                'message' => 'order pay by paypal express api, fetch PayerID fail',
            ],
            $this->order_paypal_express_get_apiAddress_fail => [
                'message' => 'order pay by paypal express api, fetch address fail',
            ],
            
            $this->order_has_been_paid => [
                'message' => 'order has bean paid',
            ],
            $this->order_not_exist => [
                'message' => 'order is not exist',
            ],
            $this->order_alipay_payment_fail => [
                'message' => 'order pay by alipay payment fail',
            ],
            $this->order_wxpay_payment_fail => [
                'message' => 'order pay by wxpay payment fail',
            ],
            
            
            /**
             * cms
             */
            $this->cms_article_not_exist => [
                'message' => 'article is not exist',
            ],
            
        ];
        return $arr;
    }
}
