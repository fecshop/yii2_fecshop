<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\page;

use fecshop\services\Service;
use Yii;

/**
 * page Footer services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Trace extends Service
{
    public $traceJsEnable = false;  // 是否打开js追踪
    public $website_id;     // 网站的id，在trace系统中获取
    public $trace_url;      // trace系统接收数据的url，在trace系统中获取
    // 通过trace系统得到的token
    public $access_token;  
    // api发送数据给trace系统的最大等待时间，超过这个时间将不继续等待
    public $api_time_out = 1.5;  
    
    protected $_fta;
    protected $_ftactivity;
    protected $_ftactivity_child;
    protected $_fto;
    protected $_ftreferdomain;
    protected $_ftreferurl;
    protected $_ftreturn;
    protected $_fta_site_id;  // website_id
    
    const LOGIN_EMAIL = 'loginEmail';
    const REGISTER_EMAIL = 'registerEmail';
    const PAYMENT_PENDING_ORDER = 'paymentPendingOrder';
    const PAYMENT_SUCCESS_ORDER = 'paymentSuccessOrder';
    
    /**
     * @return String, 通用的js部分，需要先设置 website_id 和 trace_url
     */
    public function getTraceCommonJsCode(){
        if ($this->traceJsEnable) {
            return "<script type=\"text/javascript\">
	var _maq = _maq || [];
	_maq.push(['website_id', '" . $this->website_id . "']);
	(function() {
		var ma = document.createElement('script'); ma.type = 'text/javascript'; ma.async = true;
		ma.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + '".$this->trace_url."';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ma, s);
	})();
</script>";
        } else {
            return '';
        }
    }
    /**
     * @property $categoryName | String ， 填写分类的name，如果是多语言网站，那么这里填写默认语言的分类name
     * @return String, 分类页面的js Code
     */
    public function getTraceCategoryJsCode($categoryName){
        if ($this->traceJsEnable && $categoryName) {
            return "<script type=\"text/javascript\">
	var _maq = _maq || [];
	_maq.push(['category', '".$categoryName."']);
</script>";
        } else {
            return '';
        }
    }
    
    /**
     * @property $sku | String ， 产品页面的sku编码
     * @return String, 产品页面的js Code
     * <?= Yii::$service->page->trace->getTraceProductJsCode($sku)  ?>
     */
    public function getTraceProductJsCode($sku){
        if ($this->traceJsEnable && $sku) {
            return "<script type=\"text/javascript\">
	var _maq = _maq || [];
	_maq.push(['sku', '".$sku."']);
</script>";
        } else {
            return '';
        }
    }
    
    /**
     * @property $cart | String ， 购物车数据，示例JSON数据：
        [
            {
                "sku":"grxjy56002622",
                "qty":1,
                "price":35.52,
                "currency":"RMB",
                "currency_rate":6.2
            },
            {
                "sku":"grxjy5606622",
                "qty":4,
                "price":75.11,
                "currency":"RMB",
                "currency_rate":6.2
            }
        ]
     * 
     * @return String, 购物车页面的js Code
     */
    public function getTraceCartJsCode($cart){
        if ($this->traceJsEnable && $cart) {
            return "<script type=\"text/javascript\">
	var _maq = _maq || [];
	_maq.push(['cart', ".$cart."]);
</script>";
        } else {
            return '';
        }
    }
    
    /**
     * @property $search | String ，搜索的json格式如下：
     {
        "text": "fashion handbag", // 搜索词
        "result_qty":5  // 搜索的产品个数
     }
     * @return String, 注册页面的js Code
     */
    public function getTraceSearchJsCode($search){
        if ($this->traceJsEnable && $search) {
            return "<script type=\"text/javascript\">
	var _maq = _maq || [];
	_maq.push(['search', ".$search." ]);
</script>";
        } else {
            return '';
        }
    }
    
    
    
    
    
    public function initCookie(){
        $this->_fta               = $_COOKIE['_fta'];
        $this->_ftactivity        = $_COOKIE['_ftactivity'];
        $this->_ftactivity_child  = $_COOKIE['_ftactivity_child'];
        $this->_fto               = $_COOKIE['_fto'];
        $this->_ftreferdomain     = $_COOKIE['_ftreferdomain'];
        $this->_ftreferurl        = $_COOKIE['_ftreferurl'];
        $this->_ftreturn          = $_COOKIE['_ftreturn'];
        $this->_fta_site_id       = $_COOKIE['_fta_site_id'];
    }
    
    // 登录账户，通过api传递数据给trace系统 【已经部署到customer service login函数里面】
    public function sendTraceLoginInfoByApi($login_email){
        if ($this->traceJsEnable && $login_email) {
            $this->apiSendTrace([
                self::LOGIN_EMAIL => $login_email,
            ]);
        }
    }
    // 注册账户，通过api传递数据给trace系统【已经部署到customer service register函数里面】
    public function sendTraceRegisterInfoByApi($register_email){
        if ($this->traceJsEnable && $register_email) {
            $this->apiSendTrace([
                self::REGISTER_EMAIL => $register_email,
            ]);
        }
    }
    // 订单生成成功，通过api传递数据给trace系统
    public function sendTracePaymentPendingOrderByApi($order){
        if ($this->traceJsEnable && $order) {
            $this->apiSendTrace([
                self::PAYMENT_PENDING_ORDER => $order,
            ]);
        }
    }
    // 订单支付成功，通过api传递数据给trace系统
    public function sendTracePaymentSuccessOrderByApi($order){
        if ($this->traceJsEnable && $order) {
            $this->apiSendTrace([
                self::PAYMENT_SUCCESS_ORDER => $order,
            ]);
        }
    }
    /**
     * @property $data | Array，目前分类四类:loginEmail, registerEmail, paymentPendingOrder, paymentSuccessOrder,
     * 
     *
     */
    public function apiSendTrace($data){
        // 发送的数据
        $this->initCookie();
        // 进行条件判断
        if ($this->_fta) {
            $data['_fta'] = $this->_fta;
            $data['_ftactivity'] = $this->_ftactivity;
            $data['_ftactivity_child'] = $this->_ftactivity_child;
            $data['_fto'] = $this->_fto;
            $data['_ftreferdomain'] = $this->_ftreferdomain;
            $data['_ftreferurl'] = $this->_ftreferurl;
            $data['_ftreturn'] = $this->_ftreturn;
            $data['_fta_site_id'] = $this->_fta_site_id;
            // 加入验证access_token
            
            // curl 发送数据
            
            // 完成
            return true;
        }
    }
    /**
     * @property $order | String ， 订单数据，示例JSON数据：
        {
            "invoice": "500023149", // 订单号
            "order_type": "standard or express", // standard（标准支付流程类型）express（基于api的支付类型，譬如paypal快捷支付。）
            "payment_status":"pending", // pending（未支付成功）
            "payment_type":"paypal", // 支付渠道，譬如是paypal还是西联等支付渠道
            "currency":"RMB", // 当前货币
            "currency_rate":6.2, // 公式：当前金额 * 汇率 = 美元金额
            "amount":35.52, // 订单总金额
            "shipping":0.00, // 运费金额
            "discount_amount":0.00, // 折扣金额
            "coupon":"xxxxx", // 优惠券，没有则为空
            "city":"fdasfds", // 城市
            
            "email":"2358269014@qq.com", // 下单填写的email
            "first_name":"terry", //
            "last_name":"water", //
            "zip":"266326", // 邮编
            "country_code":"US", // 国家简码
            "state_code":"CT", // 省或州
            "country_name":"Unit states", // 国家简码
            "state_name":"ctrssf", // 省或州
            "address1":"address street 1", // 详细地址1
            "address2":"address street 2", // 详细地址2
            "products":[ // 产品详情
                {
                    "sku":"xxxxyr", // sku
                    "name":"Fashion Solid Color Warm Coat", // 产品名称
                    "qty":1, // 个数
                    "price":25.92 // 产品单价
                },
                {
                    "sku":"yyyy", // sku
                    "name":"Fashion Waist Warm Coat", // 产品名称
                    "qty":1, // 个数
                    "price":34.16 // 产品单价
                }
            ]
        }
     * 
     * @return String, 未支付订单页面的js Code
     */
    /* 改成api发送数据
    public function getTraceOrderJsCode($order){
        if ($this->traceJsEnable && $order) {
            return "<script type=\"text/javascript\">
	var _maq = _maq || [];
	_maq.push(['order', ".$order."]);
</script>";
        } else {
            return '';
        }
    }
    */
    
    /**
     * @property $order | String ， 订单数据，示例JSON数据：
        {
            "invoice": "500023149", // 订单号
            "order_type": "standard or express", // standard（标准支付流程类型）express（基于api的支付类型，譬如paypal快捷支付。）
            "payment_status":"pending", // pending（未支付成功）
            "payment_type":"paypal", // 支付渠道，譬如是paypal还是西联等支付渠道
            "currency":"RMB", // 当前货币
            "currency_rate":6.2, // 公式：当前金额 * 汇率 = 美元金额
            "amount":35.52, // 订单总金额
            "shipping":0.00, // 运费金额
            "discount_amount":0.00, // 折扣金额
            "coupon":"xxxxx", // 优惠券，没有则为空
            "city":"fdasfds", // 城市
            
            "email":"2358269014@qq.com", // 下单填写的email
            "first_name":"terry", //
            "last_name":"water", //
            "zip":"266326", // 邮编
            "country_code":"US", // 国家简码
            "state_code":"CT", // 省或州
            "country_name":"Unit states", // 国家简码
            "state_name":"ctrssf", // 省或州
            "address1":"address street 1", // 详细地址1
            "address2":"address street 2", // 详细地址2
            "products":[ // 产品详情
                {
                    "sku":"xxxxyr", // sku
                    "name":"Fashion Solid Color Warm Coat", // 产品名称
                    "qty":1, // 个数
                    "price":25.92 // 产品单价
                },
                {
                    "sku":"yyyy", // sku
                    "name":"Fashion Waist Warm Coat", // 产品名称
                    "qty":1, // 个数
                    "price":34.16 // 产品单价
                }
            ]
        }
     * 
     * @return String, 支付成功订单页面的js Code
     */
    /* 改成api发送数据
    public function getTraceSuccessOrderJsCode($successOrder){
        if ($this->traceJsEnable && $successOrder) {
            return "<script type=\"text/javascript\">
	var _maq = _maq || [];
	_maq.push(['successOrder', ".$successOrder."]);
</script>";
        } else {
            return '';
        }
    }
    */
    /**
     * @property $login_email | String ， 登录的email
     * @return String, 登录页面的js Code
     */
    /* 改成api发送数据
    public function getTraceLoginJsCode($login_email){
        if ($this->traceJsEnable && $login_email) {
            return "<script type=\"text/javascript\">
	var _maq = _maq || [];
	_maq.push(['login_email', '".$login_email."']);
</script>";
        } else {
            return '';
        }
    }
    */
    /**
     * @property $register_email | String ， 注册的email
     * @return String, 注册页面的js Code
     */
    /* 改成api发送数据
    public function getTraceRegisterJsCode($register_email){
        if ($this->traceJsEnable && $register_email) {
            return "<script type=\"text/javascript\">
	var _maq = _maq || [];
	_maq.push(['register_email', '".$register_email."']);
</script>";
        } else {
            return '';
        }
    }
    */
    
    
}
