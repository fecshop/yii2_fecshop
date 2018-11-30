<?php

/*
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
    public $traceJsEnable = true;  // 是否打开js追踪

    public $website_id;     // 网站的id，在trace系统中获取

    public $trace_url;      // trace系统接收数据的url，在trace系统中获取

    public $trace_api_url;

    // 通过trace系统得到的token
    public $access_token;

    // api发送数据给trace系统的最大等待时间，超过这个时间将不继续等待
    public $api_time_out = 1;

    protected $_fta;

    protected $_ftactivity;

    protected $_ftactivity_child;

    protected $_fto;

    protected $_ftreferdomain;

    protected $_ftreferurl;

    protected $_ftreturn;

    protected $_fta_site_id;  // website_id

    protected $_fid;  // 广告id

    protected $_fec_medium;     // 广告渠道

    protected $_fec_source;     // 广告子渠道

    protected $_fec_campaign;   // 广告活动

    protected $_fec_content;    // 广告推广员

    protected $_fec_design;     // 广告图片设计员

    const LOGIN_EMAIL = 'login_email';

    const REGISTER_EMAIL = 'register_email';

    const CART = 'cart';

    const PAYMENT_PENDING_ORDER = 'payment_pending_order';

    const PAYMENT_SUCCESS_ORDER = 'payment_success_order';

    /**
     * @return String, 通用的js部分，需要先设置 website_id 和 trace_url
     */
    public function getTraceCommonJsCode()
    {
        if ($this->traceJsEnable) {
            return "<script type=\"text/javascript\">
    var _maq = _maq || [];
    _maq.push(['website_id', '" . $this->website_id . "']);
    _maq.push(['fec_store', '" . Yii::$service->store->currentStore . "']);
    _maq.push(['fec_lang', '" . Yii::$service->store->currentLangCode . "']);
    _maq.push(['fec_app', '" . Yii::$service->store->getCurrentAppName() . "']);
    _maq.push(['fec_currency', '" . Yii::$service->page->currency->getCurrentCurrency() . "']);


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
     * @param $categoryName | String ， 填写分类的name，如果是多语言网站，那么这里填写默认语言的分类name
     * @return String, 分类页面的js Code
     */
    public function getTraceCategoryJsCode($categoryName)
    {
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
     * @param $sku | String ， 产品页面的sku编码
     * @return String, 产品页面的js Code
     * <?= Yii::$service->page->trace->getTraceProductJsCode($sku)  ?>
     */
    public function getTraceProductJsCode($sku)
    {
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
     * @param $cart | String ， 购物车数据，示例JSON数据：
     * [
     * {
     * "sku":"grxjy56002622",
     * "qty":1,
     * "price":35.52
     * },
     * {
     * "sku":"grxjy5606622",
     * "qty":4,
     * "price":75.11
     * }
     * ]
     *
     * @return String, 购物车页面的js Code
     */
    public function getTraceCartJsCode($cart)
    {
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
     * @param $search | String ，搜索的json格式如下：
     * {
     * "text": "fashion handbag", // 搜索词
     * "result_qty":5  // 搜索的产品个数
     * }
     * @return String, 注册页面的js Code
     */
    public function getTraceSearchJsCode($search)
    {
        if ($this->traceJsEnable && $search) {
            return "<script type=\"text/javascript\">
    var _maq = _maq || [];
    _maq.push(['search', ".$search." ]);
</script>";
        } else {
            return '';
        }
    }

    public function initCookie()
    {
        // 判断当前是否是appserver端，如果是，则从 Yii::$app->request->post('cookies') 中获取
        if (Yii::$service->helper->isApiApp()) {
            \Yii::info('is ApiApp', 'fecshop_debug');
            $cookies = Yii::$app->request->post('cookies');
        } else {
            \Yii::info('is not ApiApp', 'fecshop_debug');
            $cookies = $_COOKIE;
        }
        // params.uuid
        $this->_fta               = $cookies['_fta'];
        // params.cl_activity
        $this->_ftactivity        = $cookies['_ftactivity'];
        // params.cl_activity_child
        $this->_ftactivity_child  = $cookies['_ftactivity_child'];
        // params.first_page  if(_fto){ first_page = 0 } else { first_page = 1 }
        $this->_fto               = $cookies['_fto'];
        // params.first_referrer_domain
        $this->_ftreferdomain     = $cookies['_ftreferdomain'];
        // params.first_referrer_url
        $this->_ftreferurl        = $cookies['_ftreferurl'];
        // params.is_return
        $this->_ftreturn          = $cookies['_ftreturn'];
        // params.website_id
        $this->_fta_site_id       = $cookies['_fta_site_id'];
        if (!$this->_fta_site_id) {
            // 对于paypal ipn修改订单状态，website_id的值从配置中读取
            $this->_fta_site_id = $this->website_id;
        }
        // params.fid
        $this->_fid               = $cookies['fid'];
        // params.fec_medium
        $this->_fec_medium        = $cookies['fec_medium'];
        // params.fec_source
        $this->_fec_source        = $cookies['fec_source'];
        // params.fec_campaign
        $this->_fec_campaign      = $cookies['fec_campaign'];
        // params.fec_content
        $this->_fec_content       = $cookies['fec_content'];
        // params.fec_design
        $this->_fec_design        = $cookies['fec_design'];
    }

    // 登录账户，通过api传递数据给trace系统 【已经部署到customer service login函数里面】
    public function sendTraceLoginInfoByApi($login_email)
    {
        if ($this->traceJsEnable && $login_email) {
            $this->apiSendTrace([
                self::LOGIN_EMAIL => $login_email,
            ]);
        }
    }

    // 注册账户，通过api传递数据给trace系统【已经部署到customer service register函数里面】
    public function sendTraceRegisterInfoByApi($register_email)
    {
        if ($this->traceJsEnable && $register_email) {
            $this->apiSendTrace([
                self::REGISTER_EMAIL => $register_email,
            ]);
        }
    }

    // 产品加入购物车，通过api传递数据给trace系统 sku, qty, price
    public function sendTraceAddToCartInfoByApi($cart_info)
    {
        if ($this->traceJsEnable && $cart_info) {
            $this->apiSendTrace([
                self::CART => $cart_info,
            ]);
        }
    }

    // 订单生成成功，通过api传递数据给trace系统
    public function sendTracePaymentPendingOrderByApi($order)
    {
        if ($this->traceJsEnable && $order) {
            $this->apiSendTrace([
                self::PAYMENT_PENDING_ORDER => $order,
            ]);
        }
    }

    // 订单支付成功，通过api传递数据给trace系统
    public function sendTracePaymentSuccessOrderByApi($order)
    {
        if ($this->traceJsEnable && $order) {
            $this->apiSendTrace([
                self::PAYMENT_SUCCESS_ORDER => $order,
            ]);
        }
    }

    /**
     * @param $data | Array，目前分类四类:loginEmail, registerEmail, paymentPendingOrder, paymentSuccessOrder,
     *
     *
     */
    public function apiSendTrace($data)
    {
        \Yii::info('apiSendTrace-data', 'fecshop_debug');
        ob_start();
        ob_implicit_flush(false);
        var_dump($data);
        $post_log = ob_get_clean();
        \Yii::info($post_log, 'fecshop_debug');

        // 发送的数据
        $this->initCookie();
        \Yii::info('apiSendTrace', 'fecshop_debug');

        // 对于paypal ipn请求，website_id 从配置中读取。
        // params.website_id
        $data['website_id'] = $this->_fta_site_id;
        $data['fec_store'] = Yii::$service->store->currentStore;
        $data['fec_lang'] = Yii::$service->store->currentLangCode;
        $data['fec_app'] = Yii::$service->store->getCurrentAppName();
        $data['fec_currency'] = Yii::$service->page->currency->getCurrentCurrency();
        \Yii::info('begin apiSendTrace data', 'fecshop_debug');
        // 进行条件判断
        if ($this->_fta) {
            \Yii::info('_fta', 'fecshop_debug');
            // params.uuid
            $data['uuid'] = $this->_fta;
            // params.cl_activity
            $data['cl_activity'] = $this->_ftactivity;
            // params.cl_activity_child
            $data['cl_activity_child'] = $this->_ftactivity_child;
            // params.first_page  if(_fto){ first_page = 0 } else { first_page = 1 }
            // if ($this->_fto) {
            //    $data['first_page'] = '0';
            //} else {
            //    $data['first_page'] = '1';
            //}
            $data['first_page'] = '0';
            // params.first_referrer_domain
            $data['first_referrer_domain'] = $this->_ftreferdomain;
            // params.first_referrer_url
            $data['first_referrer_url'] = $this->_ftreferurl;
            // params.is_return
            $data['is_return'] = $this->_ftreturn;

            // params.fid
            $data['fid'] = $this->_fid;
            // params.fec_medium
            $data['fec_medium'] = $this->_fec_medium;
            // params.fec_source
            $data['fec_source'] = $this->_fec_source;
            // params.fec_campaign
            $data['fec_campaign'] = $this->_fec_campaign;
            // params.fec_content
            $data['fec_content'] = $this->_fec_content;
            // params.fec_design
            $data['fec_design'] = $this->_fec_design;


            //var_dump($data);
            ////var_dump($_COOKIE);
            //exit;
            // curl 发送数据
            $this->apiSend($data);
            // 完成
            return true;
        // 如果是paypal ipn发送订单支付成功信息，则使用下面的方式发送数据给trace系统，用于更新订单状态
        } elseif (isset($data[self::PAYMENT_SUCCESS_ORDER]) && $data[self::PAYMENT_SUCCESS_ORDER]) {
            \Yii::info(self::PAYMENT_SUCCESS_ORDER, 'fecshop_debug');
            $this->apiSend($data);
            return true;
        }
    }

    /**
     * @param $data | Array, 传递给统计系统的数据。
     * 通过curl函数，发送数据给统计系统，在使用前，您需要配置
     * `trace_api_url` `api_time_out` `access_token`
     */
    public function apiSend($data)
    {
        // var_dump($data);exit;
        $data = json_encode($data);
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $this->trace_api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->api_time_out);  //定义超时3秒钟
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                'Accept: application/json',
                'Content-Type: application/json',
                'Access-Token: '.$this->access_token,
                'Content-Length: ' . strlen($data)
            ]
        );
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //执行并获取url地址的内容
        $output = curl_exec($ch);
        // echo $this->trace_url."\n";
        // echo $this->api_time_out."\n";
        // echo $this->access_token."\n";
        // var_dump($data);
        // var_dump($output) ;exit;
        //释放curl句柄
        curl_close($ch);
        \Yii::info('#################', 'fecshop_debug');
        \Yii::info($output, 'fecshop_debug');
        //var_dump($output);exit;
        return $output;
    }

    /**
     * @param $order | String ， 订单数据，示例JSON数据：
     * {
     * "invoice": "500023149", // 订单号
     * "order_type": "standard or express", // standard（标准支付流程类型）express（基于api的支付类型，譬如paypal快捷支付。）
     * "payment_status":"pending", // pending（未支付成功）
     * "payment_type":"paypal", // 支付渠道，譬如是paypal还是西联等支付渠道
     * //"currency":"RMB", // 当前货币
     * //"currency_rate":6.2, // 公式：当前金额 * 汇率 = 美元金额
     * "amount":35.52, // 订单总金额
     * "shipping":0.00, // 运费金额
     * "discount_amount":0.00, // 折扣金额
     * "coupon":"xxxxx", // 优惠券，没有则为空
     * "city":"fdasfds", // 城市
     *
     * "email":"2358269014@qq.com", // 下单填写的email
     * "first_name":"terry", //
     * "last_name":"water", //
     * "zip":"266326", // 邮编
     * "country_code":"US", // 国家简码
     * "state_code":"CT", // 省或州
     * "country_name":"Unit states", // 国家简码
     * "state_name":"ctrssf", // 省或州
     * "address1":"address street 1", // 详细地址1
     * "address2":"address street 2", // 详细地址2
     * "products":[ // 产品详情
     * {
     * "sku":"xxxxyr", // sku
     * "name":"Fashion Solid Color Warm Coat", // 产品名称
     * "qty":1, // 个数
     * "price":25.92 // 产品单价
     * },
     * {
     * "sku":"yyyy", // sku
     * "name":"Fashion Waist Warm Coat", // 产品名称
     * "qty":1, // 个数
     * "price":34.16 // 产品单价
     * }
     * ]
     * }
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
     * @param $order | String ， 订单数据，示例JSON数据：
     * {
     * "invoice": "500023149", // 订单号
     * "order_type": "standard or express", // standard（标准支付流程类型）express（基于api的支付类型，譬如paypal快捷支付。）
     * "payment_status":"pending", // pending（未支付成功）
     * "payment_type":"paypal", // 支付渠道，譬如是paypal还是西联等支付渠道
     * // "currency":"RMB", // 当前货币
     * // "currency_rate":6.2, // 公式：当前金额 * 汇率 = 美元金额
     * "amount":35.52, // 订单总金额
     * "shipping":0.00, // 运费金额
     * "discount_amount":0.00, // 折扣金额
     * "coupon":"xxxxx", // 优惠券，没有则为空
     * "city":"fdasfds", // 城市
     *
     * "email":"2358269014@qq.com", // 下单填写的email
     * "first_name":"terry", //
     * "last_name":"water", //
     * "zip":"266326", // 邮编
     * "country_code":"US", // 国家简码
     * "state_code":"CT", // 省或州
     * "country_name":"Unit states", // 国家简码
     * "state_name":"ctrssf", // 省或州
     * "address1":"address street 1", // 详细地址1
     * "address2":"address street 2", // 详细地址2
     * "products":[ // 产品详情
     * {
     * "sku":"xxxxyr", // sku
     * "name":"Fashion Solid Color Warm Coat", // 产品名称
     * "qty":1, // 个数
     * "price":25.92 // 产品单价
     * },
     * {
     * "sku":"yyyy", // sku
     * "name":"Fashion Waist Warm Coat", // 产品名称
     * "qty":1, // 个数
     * "price":34.16 // 产品单价
     * }
     * ]
     * }
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
     * @param $login_email | String ， 登录的email
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
     * @param $register_email | String ， 注册的email
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
