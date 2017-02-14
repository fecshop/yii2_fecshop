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

use fecshop\services\Service;
/**
 * Address  child services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Google extends Service
{
	protected  $currentCountry;
	protected  $currentState;
	
	
	# µÃµ½¹È¸èµÇÂ¼µÄurl
	public function getLoginUrl($urlKey){
		Global $googleapiinfo;
		$thirdLogin = Yii::$service->store->thirdLogin;
		$googleapiinfo['GOOGLE_CLIENT_ID'] = isset($thirdLogin['google']['CLIENT_ID']) ? $thirdLogin['google']['CLIENT_ID'] : '';
		$googleapiinfo['GOOGLE_CLIENT_SECRET'] = isset($thirdLogin['google']['CLIENT_SECRET']) ? $thirdLogin['google']['CLIENT_SECRET'] : '';
		//echo $lib_google_base.'/Social.php';exit;
		$lib_google_base = Yii::getAlias("@fecshop/lib/google");
		include $lib_google_base.'/Social.php';
		$redirectUrl = Yii::$service->url->getUrl($urlKey);
		$Social_obj= new \Social($redirectUrl,1);

		$url = $Social_obj->google();
		return $url;
	}
	
	
	
	
	
	
	
	
	
	
	
}