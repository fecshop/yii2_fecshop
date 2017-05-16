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
 * Breadcrumbs services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Message extends Service
{
	protected $_correctName = 'correct_message';
	protected $_errorName = 'error_message';
	/**
	 * 增加 correct message
	 * @property $message | String
	 */ 
	protected function actionAddCorrect($message){
		
		if(empty($message)){
			return;
		}
		if(is_string($message)){
			$message = [$message];
		}
		$correct = $this->getCorrects();
		if(is_array($correct) && is_array($message)){
			$message = array_merge($correct,$message);
		}
		return Yii::$app->session->setFlash($this->_correctName,$message);
	}
	/**
	 * 增加 error message
	 * @property $message | String
	 */ 
	protected function actionAddError($message){
		
		if(empty($message)){
			return;
		}
		if(is_string($message)){
			$message = [$message];
		}
		$error = $this->getErrors();
		if(is_array($error) && is_array($message)){
			$message = array_merge($error,$message);
		}
		return Yii::$app->session->setFlash($this->_errorName,$message);
	}
	/**
	 * 从service->helper_errors中取出来错误信息，加入到
	 *   service->page->message 中。
	 */ 
	protected function actionAddByHelperErrors(){
		$errors = Yii::$service->helper->errors->get(true);
		//var_dump($errors);
		if($errors){
			if(is_array($errors) && !empty($errors)){
				foreach($errors as $error){
					//if(is_array($error) && !empty($error)){
					//	foreach($error as $er){
							Yii::$service->page->message->addError($error);
						//}
					//}
				}
			} 
			return true;
		}
		
	}
	/**
	 * 获取 correct message
	 * @return Array
	 */ 
	protected function actionGetCorrects(){
		return Yii::$app->session->getFlash($this->_correctName);
	}
	/**
	 * 获取 error message
	 * @return Array
	 */ 
	protected function actionGetErrors(){
		return Yii::$app->session->getFlash($this->_errorName);
	}
	
	
}

?>