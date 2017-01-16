<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\customer;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\models\mysqldb\customer\Address as MyAddress;
use fecshop\services\Service;
/**
 * Address  child services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Address extends Service
{
	protected  $currentCountry;
	protected  $currentState;
	
	protected function actionGetPrimaryKey(){
		
		return 'address_id';
	}
	/**
	 * @property $primaryKey | Int
	 * @return Object(MyCoupon)
	 * 通过id找到cupon的对象
	 */
	protected function actionGetByPrimaryKey($primaryKey){
		$one = MyAddress::findOne($primaryKey);
		$primaryKey = $this->getPrimaryKey();
		if($one[$primaryKey]){
			return $one;
		}else{
			return new MyAddress;
		}
	}
	
	protected function actionGetAddressByIdAndCustomerId($address_id,$customer_id){
		$primaryKey = $this->getPrimaryKey();
		$one = MyAddress::findOne([
			$primaryKey 	=> $address_id,
			'customer_id' 	=> $customer_id,
		]);
		
		if($one[$primaryKey]){
			return $one;
		}else{
			return false;
		}
	}
	
	
	/**
	 * @property $filter|Array
	 * @return Array;
	 * 通过过滤条件，得到coupon的集合。
	 * example filter:
	 * [
	 * 		'numPerPage' 	=> 20,  	
	 * 		'pageNum'		=> 1,
	 * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
			'where'			=> [
				['>','price',1],
				['<=','price',10]
	 * 			['sku' => 'uk10001'],
	 * 		],
	 * 	'asArray' => true,
	 * ]
	 */
	protected function actionColl($filter=''){
		$query = MyAddress::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		$coll  = $query->all();
		if(!empty($coll)){
			foreach($coll as $k => $one){
				$coll[$k] = $one;
			}
		}
		//var_dump($one);
		return [
			'coll' => $coll,
			'count'=> $query->count(),
		];
	}
	
	protected function actionCurrentAddress(){
		
		
	}
	
	/**
	 * 
	 *
	 */
	protected function actionCurrentAddressList(){
		$arr = [];
		if(!Yii::$app->user->isGuest){
			$identity = Yii::$app->user->identity;
			$customer_id = $identity['id'];
			if($customer_id ){
				$filter = [
					'numPerPage' 	=> 30,  	
					'pageNum'		=> 1,
					'orderBy'		=> ['updated_at' => SORT_DESC, ],
					'where'			=> [
						['customer_id' => $customer_id],
					],
					'asArray' => true,
				];
				$coll = $this->coll($filter);
				$ii = 0;
				if(is_array($coll['coll']) && !empty($coll['coll'])){
					foreach($coll['coll'] as $one){
						$address_id = $one['address_id'];
						$first_name = $one['first_name'];
						$last_name = $one['last_name'];
						$email = $one['email'];
						$telephone = $one['telephone'];
						$street1 = $one['street1'];
						$street2 = $one['street2'];
						$is_default = $one['is_default'];
						$city = $one['city'];
						
						//$state = Yii::$service->helper->country->getStateByContryCode($one['country'],$one['state']);
						$state = $one['state'];
						$zip = $one['zip'];
						$country = Yii::$service->helper->country->getCountryNameByKey($one['country']);
						$str = $first_name.' '.$last_name.' '.$email.' '.
								$street1.' '.$street2.' '.$city.' '.$state.' '.$country.' '.
								$zip.' '.$telephone;
						if($is_default == 1){
							$ii = 1;
						}
						$arr[$address_id] = [
							'address' => $str,
							'is_default'=>$is_default,
						];
					}
					if(!$ii){
						# 如果没有默认的地址，则取第一个当默认
						foreach($arr as $k=>$v){
							$arr[$k]['is_default'] = 1;
							break;
						}
					}
				}
			}
		}
		return $arr;
	}
	
	/**
	 * @property $one|Array , save one data .
	 * @return  Int  保存coupon成功后，返回保存的id。
	 * example $one = [
		'first_name' => '',
		'last_name' => '',
		'email' => '',
		'company' => '',
		'telephone' => '',
		'fax' => '',
		'street1' => '',
		'street2' => '',
		'city' => '',
		'state' => '',
		'zip' => '',
		'country' => '',
		'customer_id' => '',
		'is_default' => '',
		
	 ];
	 */
	protected function actionSave($one){
		$time = time();
		$primaryKey = $this->getPrimaryKey();
		$primaryVal = isset($one[$primaryKey]) ? $one[$primaryKey] : '';
		if($primaryVal){
			$model = MyAddress::findOne($primaryVal);
			if(!$model){
				Yii::$service->helper->errors->add('address '.$this->getPrimaryKey().' is not exist');
				return;
			}
		}else{
			
			$model = new MyAddress;
			$model->created_at = time();
			if(isset(Yii::$app->user)){
				$user = Yii::$app->user;
				if(isset($user->identity)){
					$identity = $user->identity;
					$person_id = $identity['id'];
					//$model->created_person = $person_id;
				}
			}
		}
		$model->updated_at = time();
		$saveStatus = Yii::$service->helper->ar->save($model,$one);
		
		if(!$primaryVal){
			$primaryVal = Yii::$app->db->getLastInsertID();
		}
		if($one['is_default'] == 1){
			$customer_id = $one['customer_id'];
			MyAddress::updateAll(
				['is_default'=>2],  # $attributes
				'customer_id = '.$customer_id.' and  '.$primaryKey.' != ' .$primaryVal      # $condition
				//[':customer_id' => $customer_id]
			);
		}
		return $primaryVal;
	}
	
	
	/**
	 * @property $ids | Int or Array   
	 * @return boolean
	 * 如果传入的是id数组，则删除多个
	 * 如果传入的是Int，则删除一个coupon
	 * 
	 */
	protected function actionRemove($ids,$customer_id=''){
		if(!$ids){
			Yii::$service->helper->errors->add('remove id is empty');
			return false;
		}
		if(is_array($ids) && !empty($ids)){
				foreach($ids as $id){
					$model = MyAddress::findOne($id);
					if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
						if($customer_id){
							if($model['customer_id'] == $customer_id){
								
								$this->removeCartAddress($model['customer_id'],$id);
								$model->delete();
							}else{
								Yii::$service->helper->errors->add("remove address is not current customer address");
							}
						}else{
							$this->removeCartAddress($model['customer_id'],$id);
							$model->delete();
							
						}
						
					}else{
						Yii::$service->helper->errors->add("address Remove Errors:ID $id is not exist.");
						return false;
					}
				}
		}else{
			$id = $ids;
			$model = MyAddress::findOne($id);
			if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
				if($customer_id){
					if($model['customer_id'] == $customer_id){
						$this->removeCartAddress($model['customer_id'],$id);
						$model->delete();
					}else{
						Yii::$service->helper->errors->add("remove address is not current customer address");
				
					}
				}else{
					$this->removeCartAddress($model['customer_id'],$id);
					$model->delete();
				}
						
			}else{
				Yii::$service->helper->errors->add("Address Remove Errors:ID:$id is not exist.");
				return false;
			}
		}
		# 查看是否有默认地址？如果该用户存在记录，但是没有默认地址，
		# 则查找用户是否存在非默认地址，如果存在，则取一个设置为默认地址
		if($customer_id){
			$addressOne = MyAddress::find()->asArray()
						->where(['customer_id' => $customer_id,'is_default' => 1])
						->one();
			if(!$addressOne['address_id']){
				$assOne = MyAddress::find()
						->where(['customer_id' => $customer_id])
						->one();
				if($assOne['address_id']){
					$assOne->is_default = 1;
					$assOne->updated_at = time();
					$assOne->save();
				}
			}
			
		}
		return true;
	}
	
	# 删除购物车中的address部分。
	protected function removeCartAddress($customer_id,$address_id){
		$cart = Yii::$service->cart->quote->getCartByCustomerId($customer_id);
		if(isset($cart['customer_address_id']) &&  !empty($cart['customer_address_id'])){
			if($cart['customer_address_id'] == $address_id){
				$cart->customer_address_id = '';
				$cart->save();
			}
		}
	}
	
	/**
	 * @property $customer_id | int 用户的id
	 * @return Array Or ''
	 * 得到customer的默认地址。
	 */
	/*
	protected function actionGetDefaultAddress($customer_id = ''){
		if(!$customer_id){
			$identity = Yii::$app->user->identity;
			$customer_id = $identity['id'];
		}
		if($customer_id ){
			$addressOne = MyAddress::find()->asArray()
							->where(['customer_id' => $customer_id,'is_default' => 1])
							->one();
			if($addressOne['address_id']){
				return $addressOne;
			}else{
				$assOne = MyAddress::find()->asArray()
							->where(['customer_id' => $customer_id])
							->one();
				if($assOne['address_id']){
					return $assOne;
				}
			}
		}
	}
	*/
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}