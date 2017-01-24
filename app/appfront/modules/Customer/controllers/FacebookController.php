<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Customer\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;

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
   
	public function actionLoginv(){
		Session::set('fbs',1);
		$facebook_app_id = Config::param("facebook_app_id");
		$facebook_app_secret = Config::param("facebook_app_secret");
	
	
		FacebookSession::setDefaultApplication($facebook_app_id,$facebook_app_secret);
		$redirectUrl = Url::getUrl("customer/facebook/loginv");
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
		User::registerThirdPartyAccountAndLogin($user,"facebook");
		echo "<script>
					window.close();
					window.opener.location.reload();
				</script>";
	}
	
	
	#2. 创建第三方用户的账户，密码自动生成
	public static function registerThirdPartyAccountAndLogin($user,$type){
		if(!(isset($user['password']) && $user['password'] )){
			$user['password'] = self::getRandomPassword();
		}
		# 查看邮箱是否存在
		$email = $user['email'];
		$model = Help::getModel('customer_accout');
		$customer = $model->findOne(["email"=>$email]);
		# 如果存在，则不需要注册。
		if(is_array($customer) && !empty($customer)){
			self::Login($customer);
			return true;
		# 不存在，注册。
		}else{
			if($user = self::registerAccount($user,$type)){
				self::Login($user);
				return true;
			}
		}
	
	}
}
















