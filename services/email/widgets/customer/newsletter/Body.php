<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\email\widgets\customer\newsletter;
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
		$identity = $this->params;
		//echo Yii::$service->image->getImgUrl('mail/logo.png','appfront');exit;
		return [
			'email'		=> $identity['email'],
			//'name'		=> $identity['name'],
			'logoImg'	=> Yii::$service->image->getImgUrl('mail/logo.png','appfront'),
			'homeUrl'	=> Yii::$service->url->homeUrl(),
			'storeName'	=> Yii::$service->store->currentStore,
			'identity'  => $identity,
		];
	}
	
	
}