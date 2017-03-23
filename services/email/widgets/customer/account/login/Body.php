<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\email\widgets\customer\account\login;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
use fecshop\app\appfront\helper\mailer\Email;
use fecshop\services\email\widgets\BodyBase;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Body extends BodyBase
{
	public function getLastData(){
		$identity = Yii::$app->user->identity;
		//echo Yii::$service->image->getImgUrl('mail/logo.png','appfront');exit;
		return [
			'name'		=> $identity['firstname'].' '. $identity['lastname'],
			'email'		=> $identity['email'],
			'password'	=> 'xxx',
			'storeName' 			=> Yii::$service->email->storeName(),
			'contactsEmailAddress'	=> Yii::$service->email->contactsEmailAddress(),
			'contactsPhone'			=> Yii::$service->email->contactsPhone(),
			'homeUrl'	=> Yii::$service->url->homeUrl(),
			'logoImg'	=> Yii::$service->image->getImgUrl('mail/logo.png','appfront'),
			
			'loginUrl'	=> Yii::$service->url->getUrl("customer/account/index"),
			'accountUrl'=> Yii::$service->url->getUrl("customer/account/index"),
			
			'identity'  => $identity,
		];
	}
	
	
}