<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Payment\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CheckmoneyController extends AppfrontController
{
    public $enableCsrfValidation = true;
	
	public function actionStart(){
		$increment_id = Yii::$service->order->getSessionIncrementId();
		if($increment_id){
			$orderModel = Yii::$service->order->GetByIncrementId($increment_id);
			$payment_method = isset($orderModel['payment_method']) ? $orderModel['payment_method'] : '';
			if($payment_method){
				$complateUrl = Yii::$service->payment->getStandardSuccessRedirectUrl($payment_method);
				if($complateUrl){
					Yii::$service->url->redirect($complateUrl);
					exit;
				}
			}
		}
		$homeUrl = Yii::$service->url->homeUrl();
		Yii::$service->url->redirect($homeUrl);
	}
	/**
	 *
	 *
	 */
	public function actionSuccess(){
		$increment_id = Yii::$service->order->getSessionIncrementId();
		if($increment_id){
			$data = [
				'increment_id' => $increment_id,
			];
			# 清理购物车中的产品。
			Yii::$service->cart->clearCart();
			return $this->render('../../payment/checkmoney/success',$data);
		}else{
			$homeUrl = Yii::$service->url->homeUrl();
			Yii::$service->url->redirect($homeUrl);
		}
	}
	
	public function actionIpn(){
		
	}
	
}
















