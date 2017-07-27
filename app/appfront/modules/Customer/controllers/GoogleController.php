<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class GoogleController extends AppfrontController
{
    public $enableCsrfValidation = false;

    /**
     * google登录确认成功后，返回的url
     * 通过下面，得到用户的email，first_name，last_name
     * 然后登录。
     * 由于阿里云是国内服务器，暂时还没有具体测试，这个需要
     * 用国外的服务器才可以。因为需要服务器方面访问google的接口。国内服务器会被墙的。
     */
    public function actionLoginv()
    {
        Yii::$service->session->set('logintype', 'google');
        $thirdLogin = Yii::$service->store->thirdLogin;
        global $googleapiinfo;
        $googleapiinfo['GOOGLE_CLIENT_ID'] = isset($thirdLogin['google']['CLIENT_ID']) ? $thirdLogin['google']['CLIENT_ID'] : '';
        $googleapiinfo['GOOGLE_CLIENT_SECRET'] = isset($thirdLogin['google']['CLIENT_SECRET']) ? $thirdLogin['google']['CLIENT_SECRET'] : '';
        $lib_google_base = Yii::getAlias('@fecshop/lib/google');
        include $lib_google_base.'/Social.php';
        $urlKey = 'customer/google/loginv';
        $redirectUrl = Yii::$service->url->getUrl($urlKey);
        $Social_obj = new \Social($redirectUrl);
        $user = $Social_obj->google();
        // 服务器放到国外才行。不然上面无法返回数据。
        if (is_array($user) && !empty($user)) {
            $fullname = $user['name'];
            $email = $user['email'];
            if ($email) {
                $this->accountLogin($fullname, $email);
            }
        }
    }

    /**
     * google账户登录.
     */
    public function accountLogin($full_name, $email)
    {
        $name_arr = explode(' ', $full_name);
        $first_name = $name_arr[0];
        $last_name = $name_arr[1];
        $user = [
            'first_name'    =>$first_name,
            'last_name'    =>$last_name,
            'email'        =>$email,
        ];
        Yii::$service->customer->registerThirdPartyAccountAndLogin($user, 'google');
        echo '<script>
					window.close();
					window.opener.location.reload();
				</script>';
        exit;
    }
}
