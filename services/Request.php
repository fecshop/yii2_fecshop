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
use yii\base\InvalidConfigException;
use fecshop\models\mongodb\UrlRewrite;

/**
 * rewrite class \yii\web\Request
 * use custom url in our system, example: www.example.com/xxxxx.html, this file is not
 * exit in our system, In order to consider SEO, we can use db storage  map between custom url and yii url
 * when request visit /xxxx.html, select this custom url in mongodb, return the yii url ,ex.  /product/index?_id=3
 * then , resolve /product/index?_id=3 .
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Request extends \yii\web\Request
{
	/**
	 * rewrite yii\web\Request  resolveRequestUri()
	 */
	protected function resolveRequestUri()
    {
        if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // IIS
            $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
            if ($requestUri !== '' && $requestUri[0] !== '/') {
                $requestUri = preg_replace('/^(http|https):\/\/[^\/]+/i', '', $requestUri);
            }
        } elseif (isset($_SERVER['ORIG_PATH_INFO'])) { // IIS 5.0 CGI
            $requestUri = $_SERVER['ORIG_PATH_INFO'];
            if (!empty($_SERVER['QUERY_STRING'])) {
                $requestUri .= '?' . $_SERVER['QUERY_STRING'];
            }
        } else {
            throw new InvalidConfigException('Unable to determine the request URI.');
        }
		
		/**
		 * Replace Code 
		 * //return $requestUri;
		 * To:
		 */
		return $this->getRewriteUri($requestUri);
    }
	
	/**
	 * get module request url by db ;
	 */
	protected function getRewriteUri($requestUri){
		$urlPath  = '';
		$urlParam = '';
		$urlParamSuffix = '';
		
		if(strstr($requestUri,"#")){
			list($urlNoSuffix,$urlParamSuffix)=  explode("#",$requestUri);
			if(strstr($urlNoSuffix,"?")){
				list($urlPath,$urlParam)=  explode("?",$urlNoSuffix);
			}
		}else if(strstr($requestUri,"?")){
			list($urlPath,$urlParam)= explode("?",$requestUri);
		}else{
			$urlPath 	= $requestUri;
		}
		if($urlParamSuffix){
			$urlParamSuffix = '#'.$urlParamSuffix;
		}
		if($yiiUrlPath = $this->getYiiUrl($urlPath)){
			if(strstr($yiiUrlPath,'?')){
				if($urlParam){
					$url = $yiiUrlPath.'&'.$urlParam.$urlParamSuffix;
				}else{
					$url = $yiiUrlPath.$urlParamSuffix;
				}
				$this->setRequestParam($yiiUrlPath);
			}else{
				if($urlParam){
					$url = $yiiUrlPath.'?'.$urlParam.$urlParamSuffix;
				}else{
					$url = $yiiUrlPath.$urlParamSuffix;
				}
			}
			return $url;
		}else{
			return $requestUri;
		}
		
	}
	
	/**
	 * after get urlPath from db, if urlPath has get param ,
	 * set the param to $_GET
	 */
	public function setRequestParam($yiiUrlPath){
		$arr    = explode("?",$yiiUrlPath);
		$yiiUrlParam = $arr[1];
		$arr    = explode("&",$yiiUrlParam);
		foreach($arr as $a){
			list($key,$val) = explode("=",$a);
			$_GET[$key] = $val;
		}
	}
    
	/**
	 *  mongodb url_rewrite collection columns: _id,  type ,custom_url, yii_url,
	 *	if selete date from UrlRewrite, return the yii url.
	 */
	protected function getYiiUrl($urlPath){
		$UrlData = UrlRewrite::find()->where([
			'custom_url' => $urlPath,
		])->asArray()->one();
		if($UrlData['custom_url']){
			return $UrlData['yii_url'];
		}
		return ;
	}
	
	

	
}