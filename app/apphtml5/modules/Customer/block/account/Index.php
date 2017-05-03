<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\apphtml5\modules\Customer\block\account;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index {
	
	public function getLastData(){
		$identity = Yii::$app->user->identity;
		return [
			'accountEditUrl' => Yii::$service->url->getUrl('customer/editaccount'),  
			'email'			=> $identity['email'],
			'accountAddressUrl' => Yii::$service->url->getUrl('customer/address'),  
			'accountOrderUrl' => Yii::$service->url->getUrl('customer/order'),  
		];
	}
	
	
	
	
	
	
	
	
	
	
}