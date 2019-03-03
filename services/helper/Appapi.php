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
class Appapi extends Service
{
    /**
     * 公共状态码
     */
    public $status_success                                = 200;

    public $status_unknown                                 = 1000000;   // 程序内部错误：未知错误

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
    public $account_no_login_or_login_token_timeout       = 1100003; // 登录：账户的token已经过期,或者没有登录
    
    /**
     * @param $code | String 状态码
     * @param $data | 混合状态，可以是数字，数组等格式，用于做返回给前端的数组。
     * @param $message | String ，选填，如果不填写，则使用  函数 返回的内容作为message
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
        } else { // 如果不存在，则说明系统内部调用不存在的code，报错。
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
     * @param $code | String ，状态码
     * 得到 code 对应 message的数组
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
            
            
        ];
        return $arr;
    }
}
