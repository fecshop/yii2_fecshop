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
 * Format services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
// use \fecshop\services\helper\Format;
class Wx extends Service
{
    public $wxApiBaseUrl = 'https://api.weixin.qq.com';
    public $configFile;
    
    // APPID：绑定支付的APPID
    public $microProgramAppId ;
    // 小程序secert
    public $microProgramSecret;
    
    public function init()
    {
        parent::init();
        $wxpayConfigFile = Yii::getAlias($this->configFile);
        if (!is_file($wxpayConfigFile)) {
            throw new InvalidConfigException('wxpay config file:['.$wxpayConfigFile.'] is not exist');
        }
        $appId = Yii::$app->store->get('payment_wxpay', 'wechat_micro_app_id' );
        $appSecret = Yii::$app->store->get('payment_wxpay', 'wechat_micro_app_secret');
        $mchKey = Yii::$app->store->get('payment_wxpay', 'merchant_key');
        $mchId = Yii::$app->store->get('payment_wxpay', 'merchant_mch_id');
        define('WX_APP_ID', $appId);
        define('WX_APP_SECRET', $appSecret);
        define('WX_MCH_KEY', $mchKey);
        define('WX_MCH_ID', $mchId);
        
        require_once($wxpayConfigFile);
        // 通过上面的小程序，设置配置信息 
        $this->microProgramAppId = \WxPayConfig::APPID;
        $this->microProgramSecret = \WxPayConfig::APPSECRET;
    }
    
    
    /**
     * @param $code | string, 微信登陆的code
     * @return array ， example： ['session_key' => '', 'openid' => '']
     */
    public function getUserInfoByCode($code)
    {
        $urlKey = '/sns/jscode2session';
        $apiId = $this->microProgramAppId;
        $secret = $this->microProgramSecret;
        $grant_type = 'authorization_code';
        
        $url = $this->wxApiBaseUrl .  $urlKey . "?appid=$apiId&secret=$secret&js_code=$code&grant_type=$grant_type";
        //echo $url; exit;
        $returnStr =  \fec\helpers\CApi::getCurlData($url);
        $wxUserInfo = json_decode($returnStr, true);
        if (!isset($wxUserInfo['session_key']) || !isset($wxUserInfo['openid']) ) {
            return null;
        }
        // 保存到session
        //Yii::$service->helper->wx->setWxSessionKeyAndOpenid($wxUserInfo['session_key'], $wxUserInfo['openid']);
            
        return $wxUserInfo;
    }
    
    /**
     * @param $session_key | string, 微信登陆返回的session_key
     * @param $openid | string, 微信登陆返回的openid
     * @return bolean，将值保存到session中
     */ 
    //public function setWxSessionKeyAndOpenid($session_key, $openid)
    //{
    //    $openidStatus = Yii::$service->session->set('wx_openid', $openid);
    //    $sessionKeyStatus = Yii::$service->session->set('wx_session_key', $session_key);
        //var_dump([Yii::$service->session->get('wx_session_key'), Yii::$service->session->get('wx_openid')]);
        //exit;
    //    return $openidStatus && $sessionKeyStatus;
    //}
    /**
     * @return string， 从session中取出来session_key
     */ 
    //public function getWxSessionKey()
    //{
    //    return Yii::$service->session->get('wx_session_key');
    //}
    
    /**
     * @return string， 从session中取出来 openid
     */ 
    //public function getWxOpenid()
    //{
    //    return Yii::$service->session->get('wx_openid');
    //}
    
    /*
    public function createQRCode()
    {
        
        /cgi-bin/wxaapp/createwxaqrcode?access_token=ACCESS_TOKEN
        
        $urlKey = '/sns/jscode2session';
        $apiId = $this->microProgramAppId;
        $secret = $this->microProgramSecret;
        $grant_type = 'authorization_code';
        
        $url = $this->wxApiBaseUrl .  $urlKey . "?appid=$apiId&secret=$secret&js_code=$code&grant_type=$grant_type";
        // echo $url;
        $returnStr =  \fec\helpers\CApi::getCurlData($url);
        $wxUserInfo = json_decode($returnStr, true);
        if (!isset($wxUserInfo['session_key']) || !isset($wxUserInfo['openid']) ) {
            return null;
        }
        // 保存到session
        Yii::$service->helper->wx->setWxSessionKeyAndOpenid($wxUserInfo['session_key'], $wxUserInfo['openid']);
            
        return $wxUserInfo;
        
        
        
    }
    */
    
    
    
    
    
    
    
}
