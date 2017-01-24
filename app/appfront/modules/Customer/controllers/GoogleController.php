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
   
	
	public function actionLoginv(){
		Session::set("logintype","google");
		$lib_google_base = Yii::getAlias("@common/lib/google");
		include $lib_google_base.'/Social.php';
		$redirectUrl = Url::getUrl("google/account/loginv");
		$Social_obj= new \Social($redirectUrl);
		$user = $Social_obj->google();
		
		if(is_array($user) && !empty($user)){
			$fullname = $user['name'];
			$email = $user['email'];
			if($email){
				$this->accountLogin($fullname,$email);
			}
		}
		
	}
	
	# googleÕË»§µÇÂ¼
	public function accountLogin($full_name,$email){
		
		$name_arr = explode(" ",$full_name);
		$first_name = $name_arr[0];
		$last_name = $name_arr[1];
		$user = [
			'first_name' 	=>$first_name,
			'last_name' 	=>$last_name,
			'email' 		=>$email,
		
		];
		User::registerThirdPartyAccountAndLogin($user,"google");
			
		
		echo "<script>
					window.close();
					window.opener.location.reload();
				</script>";
				exit;
	}
	
	
	
}
















