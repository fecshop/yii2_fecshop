<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\customer;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
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
use fecshop\services\Service;
/**
 * Address  child services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Facebook extends Service
{
	public  $facebook_app_id;
	public  $facebook_app_secret;
	
	public function initParam(){
		//$currentStore = Yii::$service->store->currentStore;
		$store = Yii::$service->store->store;
		//$stores
		if(isset($store['thirdLogin']['facebook']['facebook_app_secret'])){
			$this->facebook_app_secret = $store['thirdLogin']['facebook']['facebook_app_secret'];
		}
		if(isset($store['thirdLogin']['facebook']['facebook_app_id'])){
			$this->facebook_app_id = $store['thirdLogin']['facebook']['facebook_app_id'];
		}
	}
	# 得到facebook登录的url。
	public function getLoginUrl($urlKey){
		$this->initParam();
		session_start();
		$thirdLogin = Yii::$service->store->thirdLogin;
		$this->facebook_app_id = isset($thirdLogin['facebook']['facebook_app_id']) ? $thirdLogin['facebook']['facebook_app_id'] : '';
		$this->facebook_app_secret = isset($thirdLogin['facebook']['facebook_app_secret']) ? $thirdLogin['facebook']['facebook_app_secret'] : '';
		
		if($this->facebook_app_secret && $this->facebook_app_id){
			FacebookSession::setDefaultApplication($this->facebook_app_id,$this->facebook_app_secret);
			$redirectUrl = Yii::$service->url->getUrl($urlKey);
			//echo $redirectUrl;exit;
			$facebook = new FacebookRedirectLoginHelper($redirectUrl,$this->facebook_app_id,$this->facebook_app_secret);

			$facebook_login_url = $facebook->getLoginUrl(array(
				'req_perms' => 'email,publish_stream',
			));
			return $facebook_login_url;
		}
	}
	
	
	
	
	
	
	
	
	
	
}