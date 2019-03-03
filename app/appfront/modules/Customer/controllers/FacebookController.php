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
class FacebookController extends AppfrontController
{
    // http://fecshop.appfront.fancyecommerce.com/customer/facebook/loginv
    public $enableCsrfValidation = false;
    /**
     * facebook 账号在facebook确认后，返回网站的url地址。
     */
    public function actionLoginv()
    {
        //Yii::$service->session->set('fbs', 1);
        $thirdLogin = Yii::$service->store->thirdLogin;
        $facebook_app_id = isset($thirdLogin['facebook']['facebook_app_id']) ? $thirdLogin['facebook']['facebook_app_id'] : '';
        $facebook_app_secret = isset($thirdLogin['facebook']['facebook_app_secret']) ? $thirdLogin['facebook']['facebook_app_secret'] : '';
        $fb = new \Facebook\Facebook([
            'app_id' => $facebook_app_id,
            'app_secret' => $facebook_app_secret,
            'default_graph_version' => 'v2.10',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }
        try {
            $accessToken = $helper->getAccessToken();
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
          exit;
        }
        $fb->setDefaultAccessToken($accessToken->getValue());
        $response = $fb->get('/me?locale=en_US&fields=name,email');
        $userNode = $response->getGraphUser();
        $email    = $userNode->getField('email');
        $name     = $userNode['name'];
        $fbid     = $userNode['id'];
        if ($email) {
            $this->accountLogin($fbid,$name,$email);
            exit;
        } else {
            $loginUrl = $helper->getLoginUrl();
            header('Location: '.$loginUrl);
        }
    }
    // facebook账户登录
    public function accountLogin($fbid,$name,$email)
    {
        $name_arr = explode(' ', $name);
        $first_name = $name_arr[0];
        $last_name = $name_arr[1];
        $user = [
            'first_name'    =>$first_name,
            'last_name'    =>$last_name,
            'email'        =>$email,
        ];
        Yii::$service->customer->registerThirdPartyAccountAndLogin($user, 'facebook');
        echo '<script>
					window.close();
					window.opener.location.reload();
				</script>';
        exit;
    }
}
