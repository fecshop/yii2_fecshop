<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use yii\base\BootstrapInterface;
use fecshop\models\mongodb\UrlRewrite;
use fec\helpers\CUrl;
/**
 * 
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Url extends Service 
{
	
	protected $_secure;
	protected $_http;
	protected $_baseUrl;
	/**
	 * save custom url to mongodb collection url_rewrite
	 * @param $urlArr|Array, example: 
	 * $urlArr = [
	 * 	'custom_url' 	=> '/xxxx.html',
	 *  'yii_url' 		=> '/product/index?_id=32',
	 * ];
	 * @param $type|String.
	 */
	public function saveCustomUrl($urlArr,$type='system'){
		
		$data = UrlRewrite::find()->where([
			'custom_url' => $urlArr['custom_url'],
		])->asArray()->one();
		if(isset($data['custom_url'])){
			throw new InvalidValueException('custom_url is exist in mongodb collection url_rewrite,which _id is:'.$data['_id']);
		}else{
			$arr = [
				'type' 		=> $type,
				'custom_url'=> $urlArr['custom_url'],
				'yii_url' 	=> $urlArr['yii_url'],
			];
			$UrlRewrite = UrlRewrite::getCollection();
			$UrlRewrite->save($arr);
			return true;
		}
		
	}
	
	
	/**
	 * @property $path|String, for example about-us.html,  fashion-handbag/women.html
	 * genarate current store url by path.
	 */
	public function getUrlByPath($path){
		return $this->getBaseUrl().'/'.$path;
	}
	
	
	
	/**
	 * get current base url , is was generate by http(or https ).'://'.store_code  
	 */
	public function getBaseUrl(){
		if(!$this->_baseUrl){
			$homeUrl = $this->homeUrl();
			if(!$this->_http)
				$this->_http = $this->secure() ? 'https' : 'http';
			$this->_baseUrl = str_replace("http",$this->_http,$homeUrl);
		}
		return $this->_baseUrl;
	}
	
	
	/**
	 * get current home url , is was generate by 'http://'.store_code  
	 */
	public function homeUrl(){
		return Yii::$app->getHomeUrl();
	}
	
	protected function secure(){
		if($this->_secure === null){
			$this->_secure = isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
		}
		return $this->_secure;
	}
	
}