<?php
/**
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
    public $status_unkown                                 = 1000000;   // 程序内部错误：未知错误
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
    public $account_register_invalid_email                = 1100001; // 注册：邮箱格式不正确 
    public $account_register_invalid_password             = 1100002; // 注册：邮箱密码格式不正确
    public $account_register_invalid_password_and_confirm = 1100003; // 注册：账户密码和确认密码不一致
    public $account_register_invalid_first_name           = 1100004; // 注册：账户的firstname格式不正确
    public $account_register_invalid_last_name            = 1100005; // 注册：账户的lastname格式不正确
    public $account_login_invalid_email_or_password       = 1100006; // 登录：账户的邮箱或者密码不正确
    public $account_login_token_timeout                   = 1100007; // 登录：账户的token已经过期
    public $account_edit_invalid_password                 = 1100008; // 编辑：账户的密码格式不正确
    public $account_edit_invalid_first_name               = 1100009; // 编辑：账户的firstname格式不正确 
    public $account_edit_invalid_last_name                = 1100010; // 编辑：账户的lastname格式不正确
    public $account_edit_invalid_password_and_confirm     = 1100011; // 编辑：账户的密码和确认面不一致
    
    
    /**
     * @property $code | String 状态码
     * @property $data | 混合状态，可以是数字，数组等格式，用于做返回给前端的数组。
     */
    public function getReponseData($code,$data){
        $message = $this->getMessageByCode($code);
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
     * @property $code | String ，状态码
     * 得到 code 对应 message的数组
     */
    public function getMessageByCode($code){
        $messageArr = $this->getMessageArr();
        return isset($messageArr[$code]['message']) ? $messageArr[$code]['message'] : '';
    }
    /**
     * 得到 code 对应 message的数组
     */
    public function getMessageArr(){
        $arr = [
            /**
             * 公共状态码
             */
            $this->status_unkown => [
                'message' => 'unknow errors',
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
           
        ];
        return $arr;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
