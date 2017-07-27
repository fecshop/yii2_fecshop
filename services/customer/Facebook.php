<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\customer;

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookSession;
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

    public function initParam()
    {
        $store = Yii::$service->store->store;
        if (isset($store['thirdLogin']['facebook']['facebook_app_secret'])) {
            $this->facebook_app_secret = $store['thirdLogin']['facebook']['facebook_app_secret'];
        }
        if (isset($store['thirdLogin']['facebook']['facebook_app_id'])) {
            $this->facebook_app_id = $store['thirdLogin']['facebook']['facebook_app_id'];
        }
    }

    // 得到facebook登录的url。
    public function getLoginUrl($urlKey)
    {
        $this->initParam();
        session_start();
        $thirdLogin = Yii::$service->store->thirdLogin;
        $this->facebook_app_id = isset($thirdLogin['facebook']['facebook_app_id']) ? $thirdLogin['facebook']['facebook_app_id'] : '';
        $this->facebook_app_secret = isset($thirdLogin['facebook']['facebook_app_secret']) ? $thirdLogin['facebook']['facebook_app_secret'] : '';

        if ($this->facebook_app_secret && $this->facebook_app_id) {
            echo $this->facebook_app_secret;
            echo $this->facebook_app_id;
            FacebookSession::setDefaultApplication($this->facebook_app_id, $this->facebook_app_secret);
            $redirectUrl = Yii::$service->url->getUrl($urlKey);
            //echo $redirectUrl;exit;
            $facebook = new FacebookRedirectLoginHelper($redirectUrl, $this->facebook_app_id, $this->facebook_app_secret);

            $facebook_login_url = $facebook->getLoginUrl([
                'req_perms' => 'email,publish_stream',
            ]);

            return $facebook_login_url;
        }
    }
}
