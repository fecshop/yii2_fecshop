<?php
namespace fecshop\app\appfront\modules\checkout\block\onepage;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
class Placeorder{
	
	public $_check_error;
	public $_billing;
	public $_shipping_method;
	public $_payment_method;
	
	public function getLastData(){
		$post = Yii::$app->request->post();
		if(is_array($post) && !empty($post)){
			if($this->checkOrderInfoAndInit($post)){
				$this->guestCreateAndLoginAccountAndSaveAddress($post);
				$this->updateGuestCart($post);
				# 将购物车数据，生成订单。
				
			}
		}
		
		return [
		
		];
	}
	/**
	 * 如果游客选择了创建账户，并且输入了密码，则使用address email作为账号，
	 * 进行账号的注册和登录。
	 */
	public function guestCreateAndLoginAccountAndSaveAddress($post){
		$create_account = $post['create_account']j;
		if($create_account){
			$customer_password = $post['customer_password'];
			$confirm_password  = $post['confirm_password'];
			if($customer_password  != $confirm_password){
				$this->_check_error[] = 'the passwords are inconsistent';
				return false;
			}
			$passMin = Yii::$service->customer->getRegisterPassMinLength();
			$passMax = Yii::$service->customer->getRegisterPassMaxLength();
			if($customer_password < $passMin){
				$this->_check_error[] = 'password must Greater than '.$passMin;
				return false;
			}
			if($customer_password > $passMax){
				$this->_check_error[] = 'password must less than '.$passMax;
				return false;
			}
			$param['email'] 	= $post['email'];
			$param['password'] 	= $post['customer_password'];
			$param['firstname'] = $post['first_name'];
			$param['lastname'] 	= $post['last_name'];
			if(!Yii::$service->customer->register($param)){
				return false;
			}else{
				Yii::$service->customer->Login([
					'email'		=> $post['email'],
					'password'	=> $post['customer_password']
				]);
			}
		}
		# 保存货运地址到customer address ，然后把生成的
		# address_id 写入到cart中。
		# shipping method写入到cart中
		# payment method 写入到cart中
		if(!Yii::$app->user->isGuest){
			$identity = Yii::$app->user->identity;
			$customer_id = $identity['id'];
			$one = [
				'first_name' 	=> $post['first_name'],
				'last_name' 	=> $post['last_name'],
				'email' 		=> $post['email'],
				'company' 		=> '',
				'telephone' 	=> $post['telephone'],
				'fax' 			=> '',
				'street1' 		=> $post['street1'],
				'street2' 		=> $post['street2'],
				'city' 			=> $post['city'],
				'state' 		=> $post['state'],
				'zip' 			=> $post['zip'],
				'country' 		=> $post['country'],
				'customer_id' 	=> $customer_id,
				'is_default' 	=> 1,
			];
			$address_id = Yii::$service->customer->address->save($one);
			if(!$address_id){
				$this->_check_error[] = 'new customer address save fail';
				return false;
			}
			return Yii::$service->cart->updateLoginCart($address_id,$this->_shipping_method,$this->_payment_method);
			
		}
		
		return true;
	}
	/**
	 * 如果是游客，那么保存货运地址到购物车表。
	 */
	public function updateGuestCart(){
		if(Yii::$app->user->isGuest){
			Yii::$service->cart->updateGuestCart($this->_billing,$this->_shipping_method,$this->_payment_method);
		}
	}

	//$create_account = isset($billing['create_account']) ? $billing['create_account'] : '';
	//$customer_password 	= isset($billing['customer_password']) ? $billing['customer_password'] : '';
	//$confirm_password 	= isset($billing['confirm_password']) ? $billing['confirm_password'] : '';
	
	/**
	 * @property $post | Array
	 * @return boolean 
	 * 检查前台传递的信息是否正确。同时初始化一部分类变量
	 */
	public function checkOrderInfoAndInit($post){
		
		$address_one = '';
		$address_id = isset($post['address_id']) ? $post['address_id'] : '';
		$billing = isset($post['billing']) ? $post['billing'] : '';
		
		if($billing && is_array($billing)){
			# 检查address的必写字段是否都存在
			if(!Yii::$service->order->checkRequiredAddressAttr($billing)){
				$this->_check_error[] = Yii::$service->helper->errors->get();
				return false;
			}
			$this->_billing = $billing;
		}else if($address_id){
			if(Yii::$app->user->isGuest){
				$this->_check_error[] = 'address id can not use for guest';
				return false; # address_id 这种情况，必须是登录用户。
			}else{
				$customer_id = Yii::$app->user->identity->id;
				if(!$customer_id){
					$this->_check_error[] = 'customer id is empty';
					return false;
				}else{
					$address_one = Yii::$service->customer->address->getAddressByIdAndCustomerId($address_id,$customer_id);
					if(!$address_one){
						$this->_check_error[] = 'current address id is not belong to current user';
						return false;
					}else{
						# 从address_id中取出来的字段，查看是否满足必写的要求。
						if(!Yii::$service->order->checkRequiredAddressAttr($address_one)){
							$this->_check_error[] = Yii::$service->helper->errors->get();
							return false;
						}
						$this->_billing = $address_one;
					}
				}
			}	
		}
		$shipping_method= isset($billing['shipping_method']) ? $billing['shipping_method'] : '';
		$payment_method = isset($billing['payment_method']) ? $billing['payment_method'] : '';
		# 验证货运方式
		if(!$shipping_method){
			$this->_check_error[] = 'shipping method can not empty';
			return false;
		}else{
			if(!Yii::$service->shipping->ifIsCorrect($shipping_method)){
				$this->_check_error[] = 'shipping method is not correct';
				return false;
			}
		}
		# 验证支付方式
		if(!$payment_method){
			$this->_check_error[] = 'payment method can not empty';
			return false;
		}else{
			if(!Yii::$service->payment->ifIsCorrectStandard($payment_method)){
				$this->_check_error[] = 'payment method is not correct';
				return false;
			}
		}
		$this->_shipping_method = $shipping_method;
		$this->_payment_method = $payment_method;
		return true;
	}
	
	
	
	
}