<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\apphtml5\modules\Customer\block\address;
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
		$method = Yii::$app->request->get('method');
		$address_id = Yii::$app->request->get('address_id');
		if($method == 'remove' && $address_id){
			$this->removeAddressById($address_id);
		}
		return [
			'coll' => $this->coll(),
		];
	}
	
	public function coll(){
		$identity = Yii::$app->user->identity;
		$customer_id = $identity['id'];
		$filter = [
				'numPerPage' 	=> 100,  	
				'pageNum'		=> 1,
				'orderBy'	=> ['updated_at' => SORT_DESC],
				'where'			=> [
					['customer_id' => $customer_id],
				],
			'asArray' => true,
		  ];
		$coll = Yii::$service->customer->address->coll($filter);
		if(isset($coll['coll']) && !empty($coll['coll'])){
			return $coll['coll'];
		}
		
	}
	
	public function removeAddressById($address_id){
		$identity = Yii::$app->user->identity;
		$customer_id = $identity['id'];
		Yii::$service->customer->address->remove($address_id,$customer_id);
	}

	
	
	
	
	
	
	
	
}