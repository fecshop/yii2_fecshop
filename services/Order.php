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
use fecshop\models\mysqldb\Order as MyOrder;
use fecshop\models\mysqldb\Order\Item as MyOrderItem;
/**
 * Order services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Order extends Service
{
	public $requiredAddressAttr; # 必填的订单字段。
	public $paymentStatus; # 订单支付状态。
	protected $checkout_type;
	const CHECKOUT_TYPE_STANDARD = 'standard';
	const CHECKOUT_TYPE_EXPRESS = 'express';
	
	protected function actionSetCheckoutType($checkout_type){
		$arr = [self::CHECKOUT_TYPE_STANDARD,self::CHECKOUT_TYPE_EXPRESS];
		if(in_array($checkout_type,$arr)){
			$this->checkout_type = $checkout_type;
			return true;
		}
		return false;
	}
	protected function actionGetCheckoutType(){
		return $this->checkout_type;
	}
	/**
	 * @property $billing | Array
	 * @return boolean
	 * 检查地址的必填。
	 */
	protected function actionCheckRequiredAddressAttr($billing){
		//$this->requiredAddressAttr;
		if(is_array($this->requiredAddressAttr) && !empty($this->requiredAddressAttr)){
			foreach($this->requiredAddressAttr as $attr){
				if(!isset($billing[$attr]) || empty($billing[$attr])){
					Yii::$service->helper->errors->add($attr.' can not empty');
					return false;
				}
			}
		}
	}
	
	
	protected function actionGetPrimaryKey(){
		
		return 'order_id';
	}
	/**
	 * @property $primaryKey | Int
	 * @return Object(MyOrder)
	 * 通过id找到cupon的对象
	 */
	protected function actionGetByPrimaryKey($primaryKey){
		$one = MyOrder::findOne($primaryKey);
		$primaryKey = $this->getPrimaryKey();
		if($one[$primaryKey]){
			return $one;
		}else{
			return new MyOrder;
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
		$query = MyOrder::find();
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
	
	/**
	 * @property $one|Array , save one data .
	 * @return  Int  保存coupon成功后，返回保存的id。    
	 */
	protected function actionSave($one){
		$time = time();
		$primaryKey = $this->getPrimaryKey();
		$primaryVal = isset($one[$primaryKey]) ? $one[$primaryKey] : '';
		if($primaryVal){
			$model = MyOrder::findOne($primaryVal);
			if(!$model){
				Yii::$service->helper->errors->add('coupon '.$this->getPrimaryKey().' is not exist');
				return;
			}
		}else{
			
			$model = new MyOrder;
			$model->created_at = time();
			/*
			if(isset(Yii::$app->user)){
				$user = Yii::$app->user;
				if(isset($user->identity)){
					$identity = $user->identity;
					$person_id = $identity['id'];
					$model->created_person = $person_id;
				}
			}
			*/
		}
		$model->updated_at = time();
		$saveStatus = Yii::$service->helper->ar->save($model,$one);
		if(!$primaryVal){
			$primaryVal = Yii::$app->db->getLastInsertID();
		}
		return $primaryVal;
	}
	
	
	/**
	 * @property $ids | Int or Array   
	 * @return boolean
	 * 如果传入的是id数组，则删除多个
	 * 如果传入的是Int，则删除一个
	 * 
	 */
	protected function actionRemove($ids){
		if(!$ids){
			Yii::$service->helper->errors->add('remove id is empty');
			return false;
		}
		if(is_array($ids) && !empty($ids)){
				foreach($ids as $id){
					$model = MyOrder::findOne($id);
					if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
						$model->delete();
					}else{
						Yii::$service->helper->errors->add("Coupon Remove Errors:ID $id is not exist.");
						return false;
					}
				}
		}else{
			$id = $ids;
			$model = MyOrder::findOne($id);
			if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
				$model->delete();
			}else{
				Yii::$service->helper->errors->add("Coupon Remove Errors:ID:$id is not exist.");
				return false;
			}
		}
		return true;
	}
	
	/**
	 * @property $address | Array 货运地址
	 * @property $shipping_method | String 货运快递方式
	 * @property $payment_method | Array 支付方式、
	 * @return boolean 通过购物车的数据生成订单是否成功
	 * 通过购物车中的产品信息，以及传递的货运地址，货运快递方式，支付方式生成订单。
	 */
	protected function actionGenerateOrderByCart($address,$shipping_method,$payment_method){
		$cart = Yii::$service->cart->quote->getCurrentCart();
		if(!$cart){
			Yii::$service->helper->errors->add('current cart is empty');
		}
		$currency_info = Yii::$service->page->currency->getCurrencyInfo();
		$currency_code = $currency_info['code'];
		$currency_rate = $currency_info['rate'];
		$cartInfo = Yii::$service->cart->getCartInfo($shipping_method,$weight,$country,$region);
		$myOrder = new MyOrder;
		$paymentStatus = $this->paymentStatus;
		$myOrder['order_status'] 	= $paymentStatus['pending'];
		$myOrder['store'] 			= $cartInfo['store'];
		$myOrder['created_at'] 		= time();
		$myOrder['update_at'] 		= time();
		$myOrder['items_count']		= $cartInfo['items_count'];
		$myOrder['total_weight']	= $cartInfo['product_weight'];
		$myOrder['order_currency_code']		= $currency_code;
		$myOrder['order_to_base_rate']		= $currency_rate;
		
		$myOrder['grand_total']				= $cartInfo['grand_total'];
		$myOrder['base_grand_total']		= $cartInfo['base_grand_total'];
		$myOrder['subtotal']				= $cartInfo['product_total'];
		$myOrder['base_subtotal']			= $cartInfo['base_product_total'];
		$myOrder['subtotal_with_discount']	= $cartInfo['coupon_cost'];
		$myOrder['base_subtotal_with_discount']	= $cartInfo['base_coupon_cost'];
		$myOrder['shipping_total']			= $cartInfo['shipping_cost'];
		$myOrder['base_shipping_total']		= $cartInfo['base_shipping_cost'];
		
		$myOrder['checkout_method']			= $this->getCheckoutType();
		if($address['customer_id']){
			$is_guest = 2;
		}else{
			$is_guest = 1;
		}
		$myOrder['customer_id']				= $address['customer_id'];
		$myOrder['customer_email']			= $address['email'];
		$myOrder['customer_firstname']		= $address['first_name'];
		$myOrder['customer_lastname']		= $address['last_name'];
		$myOrder['customer_is_guest']		= $is_guest;
		$myOrder['customer_telephone']		= $address['telephone'];
		$myOrder['customer_address_country']= $address['country'];
		$myOrder['customer_address_state']	= $address['state'];
		$myOrder['customer_address_city']	= $address['city'];
		$myOrder['customer_address_zip']	= $address['zip'];
		$myOrder['customer_address_street1']= $address['street1'];
		$myOrder['customer_address_street2']= $address['street2'];
		
		$myOrder['coupon_code']				= $cartInfo['coupon_code'];
		$myOrder['payment_method']			= $shipping_method;
		$myOrder['shipping_method']			= $payment_method;
		$myOrder->save();
		
		
		
	}
	
	/**
	 * get order list by customer account id.
	 */
	protected function actionGetCustomerOrderList($customer_id = ''){
		
		
	}
	/**
	 * @property $order_id 订单id
	 * 订单支付成功后，更改订单的状态为支付成功状态。
	 */
	protected function actionOrderPaySuccess($order_id){
		
		
	}
	
	
	
}