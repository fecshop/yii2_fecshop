<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Payment\block\success;
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
		$increment_id 		= Yii::$service->order->getSessionIncrementId();
		$order 				= Yii::$service->order->getInfoByIncrementId($increment_id);
		
		# 清空购物车。这里针对的是未登录用户进行购物车清空。
		Yii::$service->cart->clearCart();
		return [
			'increment_id' => $increment_id,
			'order'			=> $order,
		];
	}
	
	
	
	
	
	
	
	
}