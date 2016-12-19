<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
/**
 * Payment services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Payment extends Service
{
	
	public $paymentConfig;
	
	/**
	 * @return Array 得到所有支付的数组。
	 */
	public function getStandardPaymentArr(){
		$arr = [];
		
		if(
			isset($this->paymentConfig['standard']) && 
			is_array($this->paymentConfig['standard'])
		){
			foreach($this->paymentConfig['standard'] as $payment_type => $info){
				$label = $info['label'];
				$imageUrl = '';
				if(is_array($info['image'])){
					list($iUrl,$l) = $info['image'];
					if($iUrl){
						$imageUrl = Yii::$service->image->getImgUrl($iUrl,$l);
					}
				}
				$supplement = $info['supplement'];
				$arr[$payment_type] = [
					'label' => $label,
					'imageUrl' => $imageUrl,
					'supplement' => $supplement,
				];
			}
		}
		return $arr;
	}
	
	
	
}