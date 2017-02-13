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



/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class GoogleController extends AppfrontController
{
   public $enableCsrfValidation = false;
	
	public function actionLoginv(){
		
		Yii::$app->session->set("logintype","google");
		$thirdLogin = Yii::$service->store->thirdLogin;
		//var_dump($thirdLogin);
		//echo 1111;
		$googleapiinfo['GOOGLE_CLIENT_ID'] = isset($thirdLogin['google']['CLIENT_ID']) ? $thirdLogin['google']['CLIENT_ID'] : '';
		$googleapiinfo['GOOGLE_CLIENT_SECRET'] = isset($thirdLogin['google']['CLIENT_SECRET']) ? $thirdLogin['google']['CLIENT_SECRET'] : '';
		$lib_google_base = Yii::getAlias("@fecshop/lib/google");
		
		include $lib_google_base.'/Social.php';
		$urlKey = "customer/google/loginv";
		$redirectUrl = Yii::$service->url->getUrl($urlKey);
		$Social_obj= new \Social($redirectUrl);
		
		$user = $Social_obj->google();
		var_dump($user);exit;
		if(is_array($user) && !empty($user)){
			$fullname = $user['name'];
			$email = $user['email'];
			if($email){
				$this->accountLogin($fullname,$email);
			}
		}
		
	}
	
	# googleÕË»§µÇÂ¼
	# http://fecshop.appfront.fancyecommerce.com/index.php/customer/google/login
	public function accountLogin($full_name,$email){
		
		$name_arr = explode(" ",$full_name);
		$first_name = $name_arr[0];
		$last_name = $name_arr[1];
		$user = [
			'first_name' 	=>$first_name,
			'last_name' 	=>$last_name,
			'email' 		=>$email,
		
		];
		var_dump($user);exit;
		//User::registerThirdPartyAccountAndLogin($user,"google");
			
		
		echo "<script>
					window.close();
					window.opener.location.reload();
				</script>";
				exit;
	}
	
	
	
}
















