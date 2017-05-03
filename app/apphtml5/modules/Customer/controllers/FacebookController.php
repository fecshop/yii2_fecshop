<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\apphtml5\modules\Customer\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\apphtml5\modules\AppfrontController;

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class FacebookController extends AppfrontController
{
    
	# http://fecshop.apphtml5.fancyecommerce.com/customer/facebook/loginv
	/**
	 * facebook 账号在facebook确认后，返回网站的url地址。
	 */
	public function actionLoginv(){
		Yii::$app->session->set('fbs',1);
		$thirdLogin = Yii::$service->store->thirdLogin;
		$facebook_app_id 		= isset($thirdLogin['facebook']['facebook_app_id']) ? $thirdLogin['facebook']['facebook_app_id'] : '';
		$facebook_app_secret 	= isset($thirdLogin['facebook']['facebook_app_secret']) ? $thirdLogin['facebook']['facebook_app_secret'] : '';
		FacebookSession::setDefaultApplication($facebook_app_id,$facebook_app_secret);
		$redirectUrl = Yii::$service->url->getUrl("customer/facebook/loginv");
		$helper = new FacebookRedirectLoginHelper($redirectUrl,$facebook_app_id,$facebook_app_secret);
		try {
			$session = $helper->getSessionFromRedirect();
		} catch( FacebookRequestException $ex ) {
			// When Facebook returns an error
		} catch( Exception $ex ) {
			// When validation fails or other local issues
		}
		//echo 1;
		//var_dump($session);
		// see if we have a session
		if ( isset( $session ) ) {
			// graph api request for user data
			$request = new FacebookRequest( $session, 'GET', '/me' );
			$response = $request->execute();
			// get response
			$graphObject = $response->getGraphObject();
			$fbid = $graphObject->getProperty('id');              // To Get Facebook ID
			$fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
			$femail = $graphObject->getProperty('email');    // To Get Facebook email ID
			/* ---- Session Variables -----*/
			$_SESSION['FBID'] = $fbid;           
			$_SESSION['FULLNAME'] = $fbfullname;
			$_SESSION['EMAIL'] =  $femail;
			$this->accountLogin();
			exit;
		}else {
			$loginUrl = $helper->getLoginUrl();
			header("Location: ".$loginUrl);
		}
	}
	
	# facebook账户登录
	public function accountLogin(){
		$fb_id 		= $_SESSION['FBID'];
		$full_name 	= $_SESSION['FULLNAME'];
		$email 		= $_SESSION['EMAIL'];
		$name_arr = explode(" ",$full_name);
		$first_name = $name_arr[0];
		$last_name = $name_arr[1];
		$user = [
			'first_name' 	=>$first_name,
			'last_name' 	=>$last_name,
			'email' 		=>$email,
		];
		Yii::$service->customer->registerThirdPartyAccountAndLogin($user,"facebook");	
		echo "<script>
					window.close();
					window.opener.location.reload();
				</script>";
		exit;
	}
}
















