<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\page;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\services\Service;
/**
 * Translate services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Translate extends Service
{
	/**
	 * current i18n category. it will set in controller init .
	 * example: fecshop\app\appfront\modules\AppfrontController
	 * code: 	Yii::$service->page->translate->category = 'appfront';
	 */ 
	public $category;
	
	/**
	 * Yii::$service->page->translate->__('Hello, {username}!', ['username' => $username]);
	 */
	public function __($text,$arr=[]){
		if(!$this->category){
			return $text;
		}else{
			return Yii::t($this->category, $text ,$arr);
		}
	}
	
	protected function actionSetLanguage($language){
		Yii::$app->language = $language;
	}
	
	
	
}