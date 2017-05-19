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
use fecshop\models\mongodb\customer\Newsletter as MongoNewsletter;
/**
 * Page Newsletter services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Newsletter extends Service
{
	
	/**
	 * newsletter subscription
	 */
	protected function actionSubscription($email){
		$mongoNewsletter = new MongoNewsletter();
		$mongoNewsletter->attributes = [
			'email' => $email,
		];
		if($mongoNewsletter->validate()){
			$one = MongoNewsletter::find()->where(['email' => $email])->one();
			if($one['id']){
				return [
					'code' => 300,
					'description' => 'subscription email is exist',
				];
			}else{
				$mongoNewsletter->save();
				return [
				'code' => 200,
					'description' => 'subscription email success'
				];
			}
			
		}else{
			return [
				'code' => 300,
				'description' => 'subscription email format is not correct'
			];
		}
	}
	
	/**
	 * @property $filter|array
	 * get subscription email collection
	 */
	protected function actionGetSubscriptionList($filter){
	
	
	}
	
	
}
