<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\cart;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use fecshop\models\mysqldb\Cart\Item as MyCartItem;
use fecshop\models\mysqldb\cart\Coupon as MyCoupon;
use fecshop\models\mysqldb\cart\CouponUsage as MyCouponUsage;
/**
 * Cart services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Coupon extends Service
{
	
	protected $_useCouponInit; # 是否初始化这个百年两
	protected $_customer_id;   # 用户id
	protected $_coupon_code;   # 优惠卷码
	protected $_coupon_model;  # 优惠券model
	protected $_coupon_usage_model; # 优惠券使用次数记录model
	 
	
	protected function actionGetPrimaryKey(){
		
		return 'coupon_id';
	}
	/**
	 * @property $primaryKey | Int
	 * @return Object(MyCoupon)
	 * 通过id找到cupon的对象
	 */
	protected function actionGetByPrimaryKey($primaryKey){
		$one = MyCoupon::findOne($primaryKey);
		$primaryKey = $this->getPrimaryKey();
		if($one[$primaryKey]){
			return $one;
		}else{
			return new MyCoupon;
		}
	}
	
	
	
	protected function actionGetCouponUsageModel($customer_id='',$coupon_id=''){
	
		if(!$this->_coupon_usage_model){
			if(!$customer_id){
				$customer_id = $this->_customer_id;
			}
			if(!$coupon_id){
				$couponModel 	= $this->getCouponModel();
				$coupon_id 		= isset($couponModel['coupon_id']) ? $couponModel['coupon_id'] : '';
			}
			if($customer_id && $coupon_id){
				$one = MyCouponUsage::findOne([
					'customer_id' => $customer_id,
					'coupon_id'  => $coupon_id,
				]);
				if($one['customer_id']){
					$this->_coupon_usage_model = $one;
				}
			}
		}
		if($this->_coupon_usage_model){
			return $this->_coupon_usage_model;
		}
	}
	
	
	protected function actionGetCouponModel($coupon_code = ''){
		
		if(!$this->_coupon_model){
			if(!$coupon_code){
				$coupon_code = $this->_coupon_code;
			}
			if($coupon_code){
				$one = MyCoupon::findOne(['coupon_code' => $coupon_code]);
				
				if($one['coupon_code']){
					$this->_coupon_model = $one;
				}
			}
		}
		if($this->_coupon_model){
			return $this->_coupon_model;
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
		$query = MyCoupon::find();
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
			$model = MyCoupon::findOne($primaryVal);
			if(!$model){
				Yii::$service->helper->errors->add('coupon '.$this->getPrimaryKey().' is not exist');
				return;
			}else{
				$o_one = MyCoupon::find()
					->where(['coupon_code' =>$one['coupon_code']])
					->andWhere(['!=',$primaryKey,$primaryVal])
					->one()
					;
				if($o_one[$primaryKey]){
					Yii::$service->helper->errors->add('coupon_code must be unique');
					return;
				}
			}	
		}else{
			$o_one = MyCoupon::find()
				->where(['coupon_code' =>$one['coupon_code']])
				->one()
				;
			if($o_one[$primaryKey]){
				Yii::$service->helper->errors->add('coupon_code must be unique');
				return;
			}
			$model = new MyCoupon;
			$model->created_at = time();
			if(isset(Yii::$app->user)){
				$user = Yii::$app->user;
				if(isset($user->identity)){
					$identity = $user->identity;
					$person_id = $identity['id'];
					$model->created_person = $person_id;
				}
			}
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
	 * 如果传入的是Int，则删除一个coupon
	 * 
	 */
	protected function actionRemove($ids){
		if(!$ids){
			Yii::$service->helper->errors->add('remove id is empty');
			return false;
		}
		if(is_array($ids) && !empty($ids)){
				foreach($ids as $id){
					$model = MyCoupon::findOne($id);
					if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
						$model->delete();
					}else{
						Yii::$service->helper->errors->add("Coupon Remove Errors:ID $id is not exist.");
						return false;
					}
				}
		}else{
			$id = $ids;
			$model = MyCoupon::findOne($id);
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
	 * @property $coupon_code | String  优惠卷码
	 * 初始化对象变量，这个函数是在使用优惠券 和 取消优惠券的时候，
	 * 调用相应方法前，通过这个函数初始化类变量
	 * $_useCouponInit # 是否初始化过，如果初始化过了，则不会执行
	 * $_customer_id   # 用户id
	 * $_coupon_code   # 优惠卷码
	 * $_coupon_model  # 优惠券model
	 * $_coupon_usage_model # 优惠券使用次数记录model
	 * $
	 */
	
	protected function useCouponInit($coupon_code){
		if(!$this->_useCouponInit){
			# $this->_customer_id
			if(Yii::$app->user->isGuest){
				$this->_customer_id = '';
			}else{
				if(Yii::$app->user->identity->id){
					$this->_customer_id = Yii::$app->user->identity->id;
				}
			}
			# $this->getCouponUsageModel();
			# $this->getCouponModel();
			
			# $this->_coupon_code
			$this->_coupon_code = $coupon_code;
			
			$this->_useCouponInit = 1;
		}
	}
	/**
	 * 
	 */
	# $this->getCouponUsageModel();
	# $this->getCouponModel();
	protected function couponIsActive(){
		
		if($this->_customer_id){
			if($couponModel = $this->getCouponModel()){
				$expiration_date = $couponModel['expiration_date'];
				# 未过期
				if($expiration_date > time()){
					$couponUsageModel = $this->getCouponUsageModel();
					$times_used = 0;
					if($couponUsageModel['times_used']){
						$times_used = $couponUsageModel['times_used'];
					}
					$users_per_customer = $couponModel['users_per_customer'];
					# 次数限制
					if($times_used < $users_per_customer){
						return true;
					}else{
						Yii::$service->helper->errors->add("The coupon has exceeded the maximum number of uses");
					}
				}else{
					Yii::$service->helper->errors->add("coupon is expired");
				
				}
			}else{
				//Yii::$service->helper->errors->add("coupon is not exist");
				
			}
		}
	}
	
	protected function actionGetDiscount($coupon_code,$dc_price){
		$discount_cost = 0;
		$this->useCouponInit($coupon_code);
		if($this->couponIsActive()){
			$couponModel = $this->getCouponModel();
			$type 		= $couponModel['type'];
			$conditions = $couponModel['conditions'];
			$discount 	= $couponModel['discount'];
			//echo $conditions.'##'.$dc_price;;exit;
			if($conditions <= $dc_price){
				
				if($type == 1){ # 百分比
					$base_discount_cost = $discount/100 * $dc_price;
				}else if($type == 2){ # 直接折扣
					$base_discount_cost = $dc_price - $discount;
				}
				$curr_discount_cost = Yii::$service->page->currency->getCurrentCurrencyPrice($base_discount_cost);
			}
		}
		return [
			'baseCost' => $base_discount_cost,
			'currCost' => $curr_discount_cost
		];
	}
	
	/**
	 * @property $type | String     add or cancel
	 * @return boolean
	 * 增加或者减少优惠券使用的次数
	 */
	
	protected function updateCouponUse($type){
		if($this->_customer_id){
			$c_model = $this->getCouponModel();
			if($c_model){
				if($type == 'add'){
					$cu_model = $this->getCouponUsageModel();
						if(!$cu_model){
							$cu_model = new MyCouponUsage;
							$cu_model->times_used 	= 1; 
							$cu_model->customer_id 	= $this->_customer_id; 
							$cu_model->coupon_id 	= $c_model['coupon_id'];
						}else{
							$cu_model->times_used += 1; 
						}
						$cu_model->save();
						$times_used = $c_model->times_used ? $c_model->times_used : 0;
						$c_model->times_used = $times_used + 1;
						$c_model->save();
						return true;
					
				}else if($type == 'cancel'){
					
					$couponModel 	= $this->getCouponModel();
					
					$cu_model = $this->getCouponUsageModel();
					
					if($cu_model){
						
						$cu_model->times_used -= 1; 
						$times_used = $c_model->times_used ? $c_model->times_used : 0;
						$times_used = $times_used - 1;
						if($cu_model->times_used >= 0 && $times_used >= 0){
							
							$cu_model->save();
							$c_model->times_used = $times_used;
							$c_model->save();
							return true;
						}
					}
					
				}
			}
		}
	}
	
	/**
	 * @property $coupon_code | String 优惠卷码
	 * 检查当前购物车中是否存在优惠券，如果存在，则覆盖当前的优惠券
	 * 如果当前购物车没有使用优惠券，则检查优惠券是否可以使用
	 * 如果优惠券可以使用，则使用优惠券进行打折。更新购物车信息。
	 */
	
	protected function actionAddCoupon($coupon_code){
		$this->useCouponInit($coupon_code);
		
		if($this->couponIsActive()){
			$couponModel= $this->getCouponModel();
			$type 		= $couponModel['type'];
			$conditions = $couponModel['conditions'];
			$discount 	= $couponModel['discount'];
			# 判断购物车金额是否满足条件
			$cartProduct =  Yii::$service->cart->quoteItem->getCartProductInfo();
			$product_total = isset($cartProduct['product_total']) ? $cartProduct['product_total'] : 0;
			if($product_total){
				//var_dump($product_total);
				$dc_price = Yii::$service->page->currency->getBaseCurrencyPrice($product_total);
				if($dc_price > $conditions){
					# 更新购物侧的coupon 和优惠券的使用情况。
					# 在service中不要出现事务等操作。在调用层使用。
					//$innerTransaction = Yii::$app->db->beginTransaction();
					//try {
						$set_status = Yii::$service->cart->quote->setCartCoupon($coupon_code);
						$up_status  = $this->updateCouponUse('add');
						if($set_status && $up_status){
							//$innerTransaction->commit();
							return true;
						}else{
							Yii::$service->helper->errors->add('add coupon fail');
						}
						//Yii::$service->helper->errors->add('add coupon fail');
						//$innerTransaction->rollBack();
					//} catch (Exception $e) {
						//Yii::$service->helper->errors->add('add coupon fail');
						//$innerTransaction->rollBack();
					//}
				}else{
					Yii::$service->helper->errors->add('The coupon can not be used if the product amount in the shopping cart is less than {conditions} dollars',['conditions' => $conditions]);
				}
			}
		}else{
			Yii::$service->helper->errors->add('Coupon is not available or has expired');
		}
	}
	
	# 取消优惠券
	# $this->getCouponUsageModel();
	# $this->getCouponModel();
	# $this->useCouponInit($coupon_code);
	protected function actionCancelCoupon($coupon_code){
		
		$this->useCouponInit($coupon_code);
		if($this->_customer_id){
			$couponModel = $this->getCouponModel($coupon_code);
			if($couponModel){
				//$couponModel = $this->getCouponModel($coupon_code);
				//$couponUsageModel = $this->getCouponUsageModel($customer_id,$coupon_id);
				//$innerTransaction = Yii::$app->db->beginTransaction();
				//try {
					
					$up_status = $this->updateCouponUse('cancel');
					$cancel_status = Yii::$service->cart->quote->cancelCartCoupon($coupon_code);
					//echo $up_status.'##'.$set_status;
					//echo 555;
					if($up_status && $cancel_status){
						//$innerTransaction->commit();
						return true;
					}	
					//$innerTransaction->rollBack();
				//} catch (Exception $e) {
				//	$innerTransaction->rollBack();
				//}
			}
		}	
	}
	
}
