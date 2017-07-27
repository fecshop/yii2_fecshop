<?php
/**
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

    // 得到facebook登录的url。
    public function getLoginUrl($urlKey)
    {
        $redirectUrl = Yii::$service->url->getUrl($urlKey);
        $thirdLogin  = Yii::$service->store->thirdLogin;
        $this->facebook_app_id     = isset($thirdLogin['facebook']['facebook_app_id']) ? $thirdLogin['facebook']['facebook_app_id'] : '';
        $this->facebook_app_secret = isset($thirdLogin['facebook']['facebook_app_secret']) ? $thirdLogin['facebook']['facebook_app_secret'] : '';
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
