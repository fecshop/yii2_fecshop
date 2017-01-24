<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Customer\block\newsletter;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
use fecshop\app\appfront\helper\mailer\Email;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index {
	
	public function getLastData(){
		$email = Yii::$app->request->get('email');
		$status = Yii::$service->customer->newsletter->subscribe($email);
		$message = Yii::$service->helper->errors->get();
		if(!$message){
			$message = 'Your subscribed email was successful, You can click Here to 
				<a href="'.Yii::$service->url->homeUrl() .'">Home Page</a>
				, Thank You.';
			$param['email'] = $email;	
			Email::sendNewsletterSubscribeEmail($param);
		}
		return [
			'message' => $message,
		];
	}
	
	
	
}