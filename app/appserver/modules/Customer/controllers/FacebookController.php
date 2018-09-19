<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class FacebookController extends AppserverController
{
    // http://fecshop.apphtml5.fancyecommerce.com/customer/facebook/loginv
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
            $content = 'Graph returned an error: ' . $e->getMessage();
            
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $content =  'Facebook SDK returned an error: ' . $e->getMessage();
           
        }
        if(!$content){
            $code = Yii::$service->helper->appserver->account_facebook_login_error;
            $data = [
                'content' => $content,
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        if (! isset($accessToken)) {
            if ($helper->getError()) {
                //header('HTTP/1.0 401 Unauthorized');
                $content =  "Error: " . $helper->getError() . "\n";
                $content .=  "Error Code: " . $helper->getErrorCode() . "\n";
                $content .=  "Error Reason: " . $helper->getErrorReason() . "\n";
                $content .=  "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                //header('HTTP/1.0 400 Bad Request');
                $content .=  'Bad request';
            }
        }
        if(!$content){
            $code = Yii::$service->helper->appserver->account_facebook_login_error;
            $data = [
                'content' => $content,
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $fb->setDefaultAccessToken($accessToken->getValue());
        $response = $fb->get('/me?locale=en_US&fields=name,email');
        $userNode = $response->getGraphUser();
        $email    = $userNode->getField('email');
        $name     = $userNode['name'];
        $fbid     = $userNode['id'];
        //echo $email.$name.$fbid;exit;
        if ($email) {
            $this->accountLogin($fbid,$name,$email);
            $code = Yii::$service->helper->appserver->status_success;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        } else {
            $code = Yii::$service->helper->appserver->account_facebook_login_error;
            $data = [
                'content' => 'no email find from fb',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        
            //$loginUrl = $helper->getLoginUrl();
            //header('Location: '.$loginUrl);
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
        
        return true;
        
        //echo '<script>
		//			window.close();
		//			window.opener.location.reload();
		//		</script>';
        //exit;
    }
}
