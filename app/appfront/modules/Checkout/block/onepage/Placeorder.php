<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\checkout\block\onepage;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Placeorder{
	/**
	 * 用户的账单地址信息，通过用户传递的信息计算而来。
	 */
	public $_billing;
	
	public $_address_id;
	/**
	 * 用户的货运方式
	 */
	public $_shipping_method;
	/**
	 * 用户的支付方式
	 */
	public $_payment_method;
	
	public function getLastData(){
		$post = Yii::$app->request->post();
		if(is_array($post) && !empty($post)){
			/**
			 * 对传递的数据，去除掉非法xss攻击部分内容（通过\Yii::$service->helper->htmlEncode()）
			 */
			$post = \Yii::$service->helper->htmlEncode($post);
			# 检查前台传递的数据的完整($this->checkOrderInfoAndInit($post)){
				# 如果游客用户勾选了注册账号，则注册，登录，并把地址写入到用户的address中
				$gus_status = $this->guestCreateAndLoginAccount($post);
				$save_address_status = $this->updateAddress($post);
				if($gus_status && $save_address_status){
					# 更新Cart信息  
					//$this->updateCart();
					# 设置checkout type
					$serviceOrder = Yii::$service->order;
					$checkout_type = $serviceOrder::CHECKOUT_TYPE_STANDARD;
					$serviceOrder->setCheckoutType($checkout_type);
					# 将购物车数据，生成订单。
					$genarateStatus = Yii::$service->order->generateOrderByCart($this->_billing,$this->_shipping_method,$this->_payment_method);
					if($genarateStatus){
						# 得到当前的订单信息
						//$orderInfo = Yii::$service->order->getCurrentOrderInfo();
						# 发送新订单邮件
						//Yii::$service->email->order->sendCreateEmail($orderInfo);
						# 得到支付跳转前的准备页面。
						$startUrl = Yii::$service->payment->getStandardStartUrl();
						Yii::$service->url->redirect($startUrl);
						exit;
						//return true;
					}
				}
			}else{
				
			}
		}
		//echo 333;exit;
		Yii::$service->page->message->addByHelperErrors();
		return false;
	}
	/**
	 * @property $post|Array，前台传递参数数组。 
	 * 如果游客选择了创建账户，并且输入了密码，则使用address email作为账号，
	 * 进行账号的注册和登录。
	 */
	public function guestCreateAndLoginAccount($post){
		$create_account = $post['create_account'];
		$billing		= $post['billing'];
		if(!is_array($billing) || empty($billing)){
			Yii::$service->helper->errors->add('billing must be array and can not empty');
			return false;	
		}
		if($create_account){
			$customer_password = $billing['customer_password'];
			$confirm_password  = $billing['confirm_password'];
			if($customer_password  != $confirm_password){
				Yii::$service->helper->errors->add('the passwords are inconsistent');
				return false;
			}
			$passMin = Yii::$service->customer->getRegisterPassMinLength();
			$passMax = Yii::$service->customer->getRegisterPassMaxLength();
			if(strlen($customer_password) < $passMin){
				Yii::$service->helper->errors->add('password must Greater than '.$passMin);
				return false;
			}
			if(strlen($customer_password) > $passMax){
				Yii::$service->helper->errors->add('password must less than '.$passMax);
				return false;
			}
			$param['email'] 	= $billing['email'];
			$param['password'] 	= $billing['customer_password'];
			$param['firstname'] = $billing['first_name'];
			$param['lastname'] 	= $billing['last_name'];
			if(!Yii::$service->customer->register($param)){
				return false;
			}else{
				Yii::$service->customer->Login([
					'email'		=> $billing['email'],
					'password'	=> $billing['customer_password']
				]);
			}
		}
		return true;
	}
	/**
	 * @property $post | Array
	 * 登录用户，保存货运地址到customer address ，然后把生成的
	 * address_id 写入到cart中。
	 * shipping method写入到cart中
	 * payment method 写入到cart中 updateCart 
	 */
	public function updateAddress($post){
		if(!Yii::$app->user->isGuest){
			$billing		= $post['billing'];
			$address_id 	= $post['address_id'];
			if(!$address_id){
				$identity = Yii::$app->user->identity;
				$customer_id = $identity['id'];
				$one = [
					'first_name' 	=> $billing['first_name'],
					'last_name' 	=> $billing['last_name'],
					'email' 		=> $billing['email'],
					'company' 		=> '',
					'telephone' 	=> $billing['telephone'],
					'fax' 			=> '',
					'street1' 		=> $billing['street1'],
					'street2' 		=> $billing['street2'],
					'city' 			=> $billing['city'],
					'state' 		=> $billing['state'],
					'zip' 			=> $billing['zip'],
					'country' 		=> $billing['country'],
					'customer_id' 	=> $customer_id,
					'is_default' 	=> 1,
				];
				$address_id = Yii::$service->customer->address->save($one);
				$this->_address_id = $address_id;
				if(!$address_id){
					Yii::$service->helper->errors->add('new customer address save fail');
					return false;
				}
				//echo "$address_id,$this->_shipping_method,$this->_payment_method";
				
			}
			return Yii::$service->cart->updateLoginCart($this->_address_id,$this->_shipping_method,$this->_payment_method);
		}else{
			return Yii::$service->cart->updateGuestCart($this->_billing,$this->_shipping_method,$this->_payment_method);
		}
		return true;
	}
	
	/**
	 * 如果是游客，那么保存货运地址到购物车表。
	 */
	/*
	public function updateCart(){
		if(Yii::$app->user->isGuest){
			return Yii::$service->cart->updateGuestCart($this->_billing,$this->_shipping_method,$this->_payment_method);
		}else{
			return Yii::$service->cart->updateLoginCart($this->_address_id,$this->_shipping_method,$this->_payment_method);
		}
	}
	*/
	
	/**
	 * @property $post | Array
	 * @return boolean 
	 * 检查前台传递的信息是否正确。同时初始化一部分类变量
	 */
	public function checkOrderInfoAndInit($post){
		$address_one = '';
		$address_id = isset($post['address_id']) ? $post['address_id'] : '';
		$billing = isset($post['billing']) ? $post['billing'] : '';
		if($address_id){
			$this->_address_id = $address_id;
			if(Yii::$app->user->isGuest){
				Yii::$service->helper->errors->add('address id can not use for guest');
				return false; # address_id 这种情况，必须是登录用户。
			}else{
				$customer_id = Yii::$app->user->identity->id;
				if(!$customer_id){
					Yii::$service->helper->errors->add('customer id is empty');
					return false;
				}else{
					$address_one = Yii::$service->customer->address->getAddressByIdAndCustomerId($address_id,$customer_id);
					if(!$address_one){
						Yii::$service->helper->errors->add('current address id is not belong to current user');
						return false;
					}else{
						# 从address_id中取出来的字段，查看是否满足必写的要求。
						if(!Yii::$service->order->checkRequiredAddressAttr($address_one)){
							return false;
						}
						$arr['customer_id'] = $customer_id;
						foreach($address_one as $k=>$v){
							$arr[$k] = $v;
						}
						$this->_billing = $arr;
					}
				}
			}	
		}else if($billing && is_array($billing)){
			# 检查address的必写字段是否都存在
			//var_dump($billing);exit;
			if(!Yii::$service->order->checkRequiredAddressAttr($billing)){
				
				return false;
			}
			$this->_billing = $billing;
		}
		$shipping_method= isset($post['shipping_method']) ? $post['shipping_method'] : '';
		$payment_method = isset($post['payment_method']) ? $post['payment_method'] : '';
		# 验证货运方式
		if(!$shipping_method){
			Yii::$service->helper->errors->add('shipping method can not empty');
			return false;
		}else{
			if(!Yii::$service->shipping->ifIsCorrect($shipping_method)){
				Yii::$service->helper->errors->add('shipping method is not correct');
				return false;
			}
		}
		# 验证支付方式
		if(!$payment_method){
			Yii::$service->helper->errors->add('payment method can not empty');
			return false;
		}else{
			if(!Yii::$service->payment->ifIsCorrectStandard($payment_method)){
				Yii::$service->helper->errors->add('payment method is not correct');
				return false;
			}
		}
		$this->_shipping_method = $shipping_method;
		$this->_payment_method = $payment_method;
		Yii::$service->payment->setPaymentMethod($this->_payment_method);
		
		return true;
	}
	
	
	
	
}
