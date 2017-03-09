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
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\services\Service;
use fecshop\models\mongodb\customer\Newsletter as NewsletterModel;
/**
 * Address  child services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Newsletter extends Service
{
	/**
	 * @property $emailAddress | String
	 * @return boolean 
	 * 检查邮件是否之前被订阅过
	 */
	protected function emailIsExist($emailAddress){
		$primaryKey = NewsletterModel::primaryKey();
		$one = NewsletterModel::findOne(['email' => $emailAddress]);
		if($one[$primaryKey]){
			return true;
		}
		return false;
	}
	
	/**
	 * @property $emailAddress | String
	 * @return boolean 
	 * 订阅邮件
	 */
	protected function actionSubscribe($emailAddress){
		if(!$emailAddress){
			Yii::$service->helper->errors->add('newsletter email address is empty');
			return;
		}else if(!Yii::$service->email->validateFormat($emailAddress)){
			Yii::$service->helper->errors->add('The email address format is incorrect!');
			return;
		}else if($this->emailIsExist($emailAddress)){
			Yii::$service->helper->errors->add('ERROR,Your email address has subscribe , Please do not repeat the subscription');
			return;
		}
		$newsletterModel = new NewsletterModel;
		$newsletterModel->email = $emailAddress;
		$newsletterModel->created_at = time();
		$newsletterModel->status = NewsletterModel::ENABLE_STATUS;
		$newsletterModel->save();
		return true;
	}
	
	
	
	
	
	
	
	
	
	
	
}