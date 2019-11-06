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
 * Google  child services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Google extends Service
{
    protected $currentCountry;

    protected $currentState;

    /**
     * @param $url | String , 用于得到返回url的字符串，$customDomain == false时，是urlKey，$customDomain == true时，是完整的url
     * @param $customDomain | boolean, 是否是自定义url
     * @return  得到跳转到google登录的url
     */
    public function getLoginUrl($url, $customDomain = false)
    {
        if (!$customDomain) {
            $redirectUrl = Yii::$service->url->getUrl($url);
        } else {
            $redirectUrl = $url;
        }
        global $googleapiinfo;
        $thirdLogin = Yii::$service->store->thirdLogin;
        $googleapiinfo['GOOGLE_CLIENT_ID'] = isset($thirdLogin['google']['CLIENT_ID']) ? $thirdLogin['google']['CLIENT_ID'] : '';
        $googleapiinfo['GOOGLE_CLIENT_SECRET'] = isset($thirdLogin['google']['CLIENT_SECRET']) ? $thirdLogin['google']['CLIENT_SECRET'] : '';
        //echo $lib_google_base.'/Social.php';exit;
        if (!$googleapiinfo['GOOGLE_CLIENT_ID']  || !$googleapiinfo['GOOGLE_CLIENT_SECRET'] ) {
            return '';
        }
        $lib_google_base = Yii::getAlias('@fecshop/lib/google');
        include $lib_google_base.'/Social.php';
        
        $Social_obj = new \Social($redirectUrl, 1);

        $url = $Social_obj->google();

        return $url;
    }
}
