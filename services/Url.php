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
use fec\helpers\CUrl;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Url extends Service 
{
	public 	  $randomCount = 8;
	
	protected $_secure;
	protected $_currentBaseUrl;
	protected $_origin_url;
	protected $_httpType;
	protected $_httpBaseUrl;
	protected $_httpsBaseUrl;
	protected $_currentUrl;
	
	/**
	 * save custom url to mongodb collection url_rewrite
	 * @param $str|String, example:  fashion handbag women
	 * @param $originUrl|String , origin url ,example: /cms/home/index?id=5
	 * @param $originUrlKey|String,origin url key, it can be empty ,or generate by system , or custom url key.
	 * @param $type|String, url rewrite type.
	 * @return  rewrite Key. 
	 */
	public function saveRewriteUrlKeyByStr($str,$originUrl,$originUrlKey,$type='system'){
		$originUrl = $originUrl ? '/'.trim($originUrl,'/') : '';
		$originUrlKey = $originUrlKey ? '/'.trim($originUrlKey,'/') : '';
		if($originUrlKey){
			/**
			 * if originUrlKey and  originUrl is exist in url rewrite collectons.
			 */
			$model = $this->find();
			$data = $model->where([
				'custom_url_key' 	=> $originUrlKey,
				'origin_url' 		=> $originUrl,
			])->asArray()->one();
			if(isset($data['custom_url_key'])){
				return $originUrlKey;
			}
		}
		if($originUrlKey){
			$urlKey = $this->generateUrlByName($originUrlKey);
		}else{
			$urlKey = $this->generateUrlByName($str);
		}
		if(strlen($urlKey)<=1){
			$urlKey .= $this->getRandom();
		}
		if(strlen($urlKey)<=2){
			$urlKey .= '-'.$this->getRandom();
		}
		$urlKey = $this->getRewriteUrlKey($urlKey,$originUrl);
		$UrlRewrite = $this->findOne([
			'origin_url' => $originUrl
		]);
		if(!isset($UrlRewrite['origin_url'])){
			$UrlRewrite = $this->newModel();
		}
		$UrlRewrite->type = $type;
		$UrlRewrite->custom_url_key = $urlKey;
		$UrlRewrite->origin_url = $originUrl;
		$UrlRewrite->save();
		return $urlKey;
	}
	
	/**
	 * @property $url_key|String 
	 * remove url rewrite data by $url_key,which is custom url key that saved in custom url modules,like articcle , product, category ,etc..
	 */
	public function removeRewriteUrlKey($url_key){
		$model = $this->findOne([
				'custom_url_key' => $url_key,
			]);
		if($model['custom_url_key']){
			$model->delete();
		}
		
	}
	
	public function getCurrentUrl(){
		if(!$this->_currentUrl){
			$pageURL = 'http';
			if ($_SERVER["HTTPS"] == "on"){
				$pageURL .= "s";
			}
			$pageURL .= "://";
			if ($_SERVER["SERVER_PORT"] != "80"){
				$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
			}else{
				$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
			}
			$this->_currentUrl = $pageURL;
		}
		return $this->_currentUrl;
	}
	
	/**
	 *  @property $urlKey|String 
	 *  get $origin_url by $custom_url_key ,it is used for yii2 init,
	 *  in (new fecshop\services\Request)->resolveRequestUri(),  ## fecshop\services\Request is extend  yii\web\Request
	 */
	public function getOriginUrl($urlKey){
		
		return Yii::$service->url->rewrite->getOriginUrl($urlKey);
	}
	
	/**
	 * @property $path|String, for example about-us.html,  fashion-handbag/women.html
	 * genarate current store url by path.
	 */
	public function getUrlByPath($path,$https=false){
		if($https){
			$baseUrl 	= $this->getHttpsBaseUrl();
		}else{
			$baseUrl 	= $this->getHttpBaseUrl();
		}
		return $baseUrl.'/'.$path;
	}
	
	/**
	 * get current base url , is was generate by http(or https ).'://'.store_code  
	 */
	public function getCurrentBaseUrl(){
		if(!$this->_currentBaseUrl){
			$homeUrl = $this->homeUrl();
			if(!$this->_httpType)
				$this->_httpType = $this->secure() ? 'https' : 'http';
			$this->_currentBaseUrl = str_replace("http",$this->_httpType,$homeUrl);
		}
		return $this->_currentBaseUrl;
	}
	
	
	/**
	 * get current home url , is was generate by 'http://'.store_code  
	 */
	public function homeUrl(){
		return Yii::$app->getHomeUrl();
	}
	
	
	
	/**
	 * get http format base url.
	 */
	protected function getHttpBaseUrl(){
		if(!$this->_httpBaseUrl){
			$homeUrl = $this->homeUrl();
			if(strstr($homeUrl,'https://')){
				$this->_httpBaseUrl = str_replace('https://','http://',$homeUrl);
			}else{
				$this->_httpBaseUrl = $homeUrl;
			}
		}
		return $this->_httpBaseUrl;
	}
	/**
	 * get https format base url.
	 */
	protected function getHttpsBaseUrl(){
		if(!$this->_httpsBaseUrl){
			$homeUrl = $this->homeUrl();
			if(strstr($homeUrl,'http://')){
				$this->_httpsBaseUrl = str_replace('http://','https://',$homeUrl);
			}else{
				$this->_httpsBaseUrl = $homeUrl;
			}
		}
		return $this->_httpsBaseUrl;
	}
	
	
	
	
	
	
	
	protected function newModel(){
		return Yii::$service->url->rewrite->newModel();
	}
	protected function find(){
		return Yii::$service->url->rewrite->find();
	}
	
	
	protected function findOne($where){
		return Yii::$service->url->rewrite->findOne($where);
	}
	
	
	
	/**
	 * check current url type is http or https. https is secure url type.
	 */ 
	protected function secure(){
		if($this->_secure === null){
			$this->_secure = isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0;
		}
		return $this->_secure;
	}
	
	/**
	 * get rewrite url key.
	 */
	protected function getRewriteUrlKey($urlKey,$originUrl){
		$model = $this->find();
		$data = $model->where([
			'custom_url_key' => $urlKey,
		])->andWhere(['<>','origin_url',$originUrl])
		->asArray()->one();
		if(isset($data['custom_url_key'])){
			$urlKey = $this->getRandomUrlKey($urlKey);
			return $this->getRewriteUrlKey($urlKey,$originUrl);
		}else{
			return $urlKey;
		}
	}
	
	
	/**
	 * generate random string.
	 */
	protected function getRandom($length=''){
		if(!$length ){
			$length = $this->randomCount;
		}
		$str = null;
		$strPol = "123456789";
		$max = strlen($strPol)-1;
		for($i=0;$i<$length;$i++){
			$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
		}
		return $str;
	  
	}
	/**
	 * if url key is exist in url_rewrite table ,Behind url add some random string 
	 */
	protected function getRandomUrlKey($url){
		if($this->_origin_url){
			$suffix = '';
			$o_url = $this->_origin_url;
			if(strstr($this->_origin_url,'.')){
				list($o_url,$suffix) = explode('.',$this->_origin_url);
				$randomStr = $this->getRandom();
				return $o_url.'-'.$randomStr.'.'.$suffix;
			}
			$randomStr = $this->getRandom();
			return $this->_origin_url.'-'.$randomStr;
		}
	}
	
	/**
	 * clear character that can not use for url.
	 */ 
	protected function generateUrlByName($name){
		$url = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
		$url = preg_replace("{[^a-zA-Z0-9_.| -]}", '', $url);
		$url = strtolower(trim($url, '-'));
		$url = preg_replace("{[_| -]+}", '-', $url);
		$url = '/'.trim($url,'/');
		$this->_origin_url = $url;
		return $url;
	}
	
}