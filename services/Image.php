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
use fec\helpers\CSession;
use fec\helpers\CUrl;
/**
 * Image services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Image extends Service
{
	public $appbase;
	/**
	 *  1.1 app front image  Dir
	 */
	protected function actionGetImgDir($str='',$app='common'){
		if($appbase = $this->appbase){
			if(isset($appbase[$app]['basedir'])){
				if($str){
					return $appbase[$app]['basedir'].'/'.$str;
				}
				return $appbase[$app]['basedir'];
			}
		}
	}
	/**
	 *  1.2 app front image  Url* 
	 *  example : <?= Yii::$service->image->getImgUrl('custom/logo.png','appfront'); ?>
	 *  it will find image in @appimage/$app	
	 */
	protected function actionGetImgUrl($str,$app='common'){
		//echo "$str,$app";
		if($appbase = $this->appbase){
			if(isset($appbase[$app]['basedomain'])){
				if($str){
					return $appbase[$app]['basedomain'].'/'.$str;
				}
				return $appbase[$app]['basedomain'];
			}
		}
		return ;
	}
	/**
	 *  2.1 app front image base dir
	 */
	protected function actionGetBaseImgDir($app='common'){
		return $this->getImgDir('',$app);
	}
	/**
	 *  2.2 app front image base Url
	 */
	protected function actionGetBaseImgUrl($app='common'){
		return $this->getImgUrl('',$app);
	}
	
}