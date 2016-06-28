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
	
	
	public function getUrlByPath($path){
		return CUrl::getHomeUrl().'/'.$path;
	}
	
	
}