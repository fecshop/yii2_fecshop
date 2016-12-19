<?php

namespace fecshop\app\appfront\modules\checkout\block\onepage;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
class Index {
	
	protected $_payment_mothod;
	
	
	
	public function getLastData(){
		
		
		return [
			'payments' => $this->getPayment(),
			'current_payment_mothod' => $this->_payment_mothod,
		];
	}
	
	
	public function getPayment(){
		$paymentArr = Yii::$service->payment->getStandardPaymentArr();
		if(!$this->_payment_mothod){
			foreach($paymentArr as $k => $v){
				$this->_payment_mothod = $k;
				break;
			}
		}
		return $paymentArr;
	}
	
}