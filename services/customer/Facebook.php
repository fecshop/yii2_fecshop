<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\customer;

use fecshop\services\Service;
use Yii;

/**
 * Facebook  child services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Facebook extends Service
{
    public $facebook_app_id;

    public $facebook_app_secret;

    /**
     * @param $url | String , 用于得到返回url的字符串，$customDomain == false时，是urlKey，$customDomain == true时，是完整的url
     * @param $customDomain | boolean, 是否是自定义url
     * @return  得到跳转到facebook登录的url
     */
    public function getLoginUrl($url, $customDomain = false)
    {
        if (!$customDomain) {
            $redirectUrl = Yii::$service->url->getUrl($url);
        } else {
            $redirectUrl = $url;
        }
        $thirdLogin  = Yii::$service->store->thirdLogin;
        $this->facebook_app_id     = isset($thirdLogin['facebook']['facebook_app_id']) ? $thirdLogin['facebook']['facebook_app_id'] : '';
        $this->facebook_app_secret = isset($thirdLogin['facebook']['facebook_app_secret']) ? $thirdLogin['facebook']['facebook_app_secret'] : '';
        if (!$this->facebook_app_id || !$this->facebook_app_secret) {
            
            return '';
        }
        $fb = new \Facebook\Facebook([
            'app_id' => $this->facebook_app_id,
            'app_secret' => $this->facebook_app_secret,
            'default_graph_version' => 'v2.10',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($redirectUrl, $permissions);
        
        return $loginUrl;
    }
}
