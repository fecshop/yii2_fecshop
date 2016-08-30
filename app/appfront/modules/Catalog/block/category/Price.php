<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalog\block\category;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Price {
	public $price;
	public $special_price;
	
	public function getLastData(){
		$price_info = Yii::$service->product->price->formatPrice($this->price);
		$return =  [
			'price' => [
				'symbol' 	=> $price_info['symbol'],
				'value' 	=> $price_info['value'],
				'code' 		=> $price_info['code'],
			]
		];
		if($this->special_price){
			$special_price_info = Yii::$service->product->price->formatPrice($this->special_price);
			$return['special_price'] = [
				'symbol' 	=> $special_price_info['symbol'],
				'value' 	=> $special_price_info['value'],
				'code' 		=> $special_price_info['code'],
			];
		}
		return $return;
	}

}