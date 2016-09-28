<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Customer\block\account;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Register {
	
	public function getLastData(){
		return [
			'minNameLength' => Yii::$service->customer->getRegisterNameMinLength(),
			'maxNameLength' => Yii::$service->customer->getRegisterNameMaxLength(),
			'minPassLength' => Yii::$service->customer->getRegisterPassMinLength(),
			'maxPassLength' => Yii::$service->customer->getRegisterPassMaxLength(),
		
		];
	}
}



