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
class Edit {
	
	public $_address_id;
	public $_country;
	public $_state;
	public $_address;
	
	
	public function initAddress(){
		$address = Yii::$app->request->post('address');
		$isSave = 0;
		if(is_array($address) && !empty($address)){
			$address = \Yii::$service->helper->htmlEncode($address);
			$this->save($address);
			$isSave = 1;
		}
		if(!$isSave){
			$this->_address_id = Yii::$app->request->get('address_id');
			if($this->_address_id){
				$addressModel = Yii::$service->customer->address->getByPrimaryKey($this->_address_id);
				$identity = Yii::$app->user->identity;
				$customer_id = $identity['id'];
				if($addressModel['address_id']){
					# 该id必须是当前用户的
					if($customer_id == $addressModel['customer_id']){
						foreach($addressModel as $k=>$v){
							$this->_address[$k] = $v;
						}
					}
				}
			
			}
		}else{
			$this->_address = $address;
		}
		$country = isset($this->_address['country']) ? $this->_address['country'] : '';
		if(!$country){
			$country = Yii::$service->helper->country->getDefaultCountry();
		}
		$this->_country = $country;
		$this->getCountrySelect();
		$this->getState();
		if(!isset($this->_address['email']) || empty($this->_address['email'])){
			$identity = Yii::$app->user->identity;
			$email = $identity['email'];
			$this->_address['email'] = $email ;
		}
		if(!isset($this->_address['first_name']) || empty($this->_address['first_name'])){
			$identity = Yii::$app->user->identity;
			$first_name = $identity['firstname'];
			$this->_address['first_name'] = $first_name ;
		}
		if(!isset($this->_address['last_name']) || empty($this->_address['last_name'])){
			$identity = Yii::$app->user->identity;
			$last_name = $identity['lastname'];
			$this->_address['last_name'] = $last_name ;
		}
	}
	
	public function getLastData(){
		$this->initAddress();
		if(empty($this->_address)){
			$this->_address = [];
		}
		$this->getIsDefault();
		return $this->_address;
	}
	
	public function getIsDefault(){
		$is_default_str = '';
		$is_default = $this->_address['is_default'];
		if(!$is_default){
			$address_id = $this->_address['address_id'];
			if(!$address_id){
				$is_default_str = 'checked="checked"';
			}
		}else{
			if($is_default == 1){
				$is_default_str = 'checked="checked"';
			}
		}
		$this->_address['is_default_str'] = $is_default_str;
		
	}
	
	public function getCountrySelect(){
		
		$countrySelect = Yii::$service->helper->country->getAllCountryOptions('','',$this->_country);
		$this->_address['countrySelect'] = $countrySelect;
		
	}
	
	public function getState($country = ''){
		$state = isset($this->_address['state']) ? $this->_address['state'] : '';
		if(!$country){
			$country = $this->_country;
		}
		$stateHtml = Yii::$service->helper->country->getStateOptionsByContryCode($country,$state);
		if(!$stateHtml){
			$stateHtml = '<input id="state" name="address[state]" value="'.$state.'" title="State" class="input-text" style="" type="text">';
		}else{
			$stateHtml = '<select id="address:state" class="address_state validate-select" title="State" name="address[state]">
							<option value="">Please select region, state or province</option>'
						.$stateHtml.'</select>';
												
		}
		$this->_address['stateHtml'] = $stateHtml;
		return $stateHtml;
	}
	
	public function getAjaxState(){
		$country = Yii::$app->request->get('country');
		$state = $this->getState($country);
		echo json_encode([
			'state' => $state,
		]);
		exit;
	}
	
	
	
	
	public function save($address){
		$arr = [];
		$email = isset($address['email']) ? $address['email'] : '';
		$first_name = isset($address['first_name']) ? $address['first_name'] : '';
		$last_name = isset($address['last_name']) ? $address['last_name'] : '';
		$telephone = isset($address['telephone']) ? $address['telephone'] : '';
		$country = isset($address['country']) ? $address['country'] : '';
		$state = isset($address['state']) ? $address['state'] : '';
		//$company = isset($address['company']) ? $address['company'] : '';
		//$fax = isset($address['fax']) ? $address['fax'] : '';
		$street1 = isset($address['street1']) ? $address['street1'] : '';
		$street2 = isset($address['street2']) ? $address['street2'] : '';
		
		$city = isset($address['city']) ? $address['city'] : '';
		$zip = isset($address['zip']) ? $address['zip'] : '';
		$is_default = isset($address['is_default']) ? $address['is_default'] : '';
		if(!$email){
			$error[] = ['email'];
		}else{
			$arr['email'] = $email;
		}
		if(!$first_name){
			$error[] = ['first_name'];
		}else{
			$arr['first_name'] = $first_name;
		}
		if(!$last_name){
			$error[] = ['last_name'];
		}else{
			$arr['last_name'] = $last_name;
		}
		if(!$telephone){
			$error[] = ['telephone'];
		}else{
			$arr['telephone'] = $telephone;
		}
		if(!$country){
			$error[] = ['country'];
		}else{
			$arr['country'] = $country;
		}
		if(!$state){
			$error[] = ['state'];
		}else{
			$arr['state'] = $state;
		}
		if(!$street1){
			$error[] = ['street1'];
		}else{
			$arr['street1'] = $street1;
		}
		if(!$city){
			$error[] = ['city'];
		}else{
			$arr['city'] = $city;
		}
		if(!$zip){
			$error[] = ['zip'];
		}else{
			$arr['zip'] = $zip;
		}
		if(!empty($error)){
			$str = implode(',',$error).' can not empty';
			Yii::$service->page->message->addError($str);
			return;
		}
		if($street2){
			$arr['street2'] = $street2;
		}
		if($is_default){
			$arr['is_default'] = $is_default;
		}
		if($is_default){
			$arr['is_default'] = $is_default;
		}
		if(isset($address['address_id'])){
			$arr['address_id'] = $address['address_id'];
		}
		//var_dump($address);exit;
		$identity = Yii::$app->user->identity;
		$arr['customer_id'] = $identity['id'];
		Yii::$service->customer->address->save($arr);
		return Yii::$service->url->redirectByUrlKey('customer/address');
	}
	
	
	
	
	
	
	
}
