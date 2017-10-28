<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AddressController extends AppserverTokenController
{
    public $enableCsrfValidation = false ;
    /**
     * 登录用户的部分
     */
    public function actionIndex(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'addressList' => $this->coll(),
        ];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
        
    }
    
    
    public function actionEdit(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $address = [];
        $country = '';
        $address_id = Yii::$app->request->get('address_id');
        if($address_id){
            $addressModel = Yii::$service->customer->address->getByPrimaryKey($address_id);
            $identity = Yii::$app->user->identity;
            $customer_id = $identity['id'];
            
            if ($addressModel['address_id']) {
                // 该id必须是当前用户的
                if ($customer_id == $addressModel['customer_id']) {
                    foreach ($addressModel as $k=>$v) {
                        $address[$k] = $v;
                    }
                }
            }
            $country = isset($address['country']) ? $address['country'] : '';
            
        }else{
            
        }
        if(!$country){
            $country = Yii::$service->helper->country->getDefaultCountry();
        }
        $countryArr = Yii::$service->helper->country->getAllCountryArray();
        $address['countryArr'] = $countryArr;
        $state = isset($address['state']) ? $address['state'] : '';
        $stateArr = Yii::$service->helper->country->getStateByContryCode($country);
        $stateIsSelect = 0;
        if(!empty($stateArr)){
            $stateIsSelect = 1;
        }
        $address['stateArr'] = $stateArr;
        $address['stateIsSelect'] = $stateIsSelect;
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'address' => $address,
        ];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
        
    }
    
    
    
    public function actionRemove(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $address_id = Yii::$app->request->post('address_id');
        if($address_id){
            $this->removeAddressById($address_id);
            
            $code = Yii::$service->helper->appserver->status_success;
            $data = [];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }else{
            $code = Yii::$service->helper->appserver->account_address_is_not_exist;
            $data = [];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
        }
    }
    
    public function removeAddressById($address_id)
    {
        $identity = Yii::$app->user->identity;
        $customer_id = $identity['id'];
        Yii::$service->customer->address->remove($address_id, $customer_id);
    }
    
    
    public function coll()
    {
        $identity = Yii::$app->user->identity;
        $customer_id = $identity['id'];
        $filter = [
                'numPerPage'    => 100,
                'pageNum'        => 1,
                'orderBy'    => ['updated_at' => SORT_DESC],
                'where'            => [
                    ['customer_id' => $customer_id],
                ],
            'asArray' => true,
          ];
        $coll = Yii::$service->customer->address->coll($filter);
        $arr = [];
        if (isset($coll['coll']) && !empty($coll['coll'])) {
            foreach($coll['coll'] as $one){
                $one['stateName'] = Yii::$service->helper->country->getStateByContryCode($one['country'],$one['state']);
                $one['countryName'] = Yii::$service->helper->country->getCountryNameByKey($one['country']); 
                $arr[] = $one;
            }
        }
        return $arr;
    }
    
    public function actionChangecountry()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $country = Yii::$app->request->get('country');
        if($country){
           $stateArr = Yii::$service->helper->country->getStateByContryCode($country);
           $stateIsSelect = 0;
            if(!empty($stateArr)){
                $stateIsSelect = 1;
            }
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'stateIsSelect' => $stateIsSelect,
                'stateArr' => $stateArr,
            ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
    }
    
    public function actionSave(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $address_id         = Yii::$app->request->post('address_id'); 
        $first_name         = Yii::$app->request->post('first_name'); 
        $last_name          = Yii::$app->request->post('last_name'); 
        $email              = Yii::$app->request->post('email'); 
        $telephone          = Yii::$app->request->post('telephone'); 
        $addressCountry     = Yii::$app->request->post('addressCountry'); 
        $addressState       = Yii::$app->request->post('addressState'); 
        $city               = Yii::$app->request->post('city'); 
        $street1            = Yii::$app->request->post('street1'); 
        $street2            = Yii::$app->request->post('street2'); 
        $zip                = Yii::$app->request->post('zip'); 
        $isDefaultActive    = Yii::$app->request->post('isDefaultActive'); 
        if($address_id){
            $addressModel = Yii::$service->customer->address->getByPrimaryKey($address_id);
            $identity = Yii::$app->user->identity;
            $customer_id = $identity['id'];
            if ($customer_id != $addressModel['customer_id']) {
                $code = Yii::$service->helper->appserver->account_address_is_not_exist;
                $data = [];
                $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
                
                return $reponseData;
            }
        }
        
        $arr = [];
        if (!$email) {
            $error[] = ['email'];
        } else {
            $arr['email'] = $email;
        }
        if (!$first_name) {
            $error[] = ['first_name'];
        } else {
            $arr['first_name'] = $first_name;
        }
        if (!$last_name) {
            $error[] = ['last_name'];
        } else {
            $arr['last_name'] = $last_name;
        }
        if (!$telephone) {
            $error[] = ['telephone'];
        } else {
            $arr['telephone'] = $telephone;
        }
        if (!$addressCountry) {
            $error[] = ['country'];
        } else {
            $arr['country'] = $addressCountry;
        }
        if (!$addressState) {
            $error[] = ['state'];
        } else {
            $arr['state'] = $addressState;
        }
        if (!$street1) {
            $error[] = ['street1'];
        } else {
            $arr['street1'] = $street1;
        }
        if ($street2) {
            $arr['street2'] = $street2;
        }
        if (!$city) {
            $error[] = ['city'];
        } else {
            $arr['city'] = $city;
        }
        if (!$zip) {
            $error[] = ['zip'];
        } else {
            $arr['zip'] = $zip;
        }
        if (!empty($error)) {
            $str = implode(',', $error).' can not empty';
            $code = Yii::$service->helper->appserver->account_address_edit_param_invaild;
            $data = [
                'error' => $str,
            ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
       
        if ($isDefaultActive) {
            $arr['is_default'] = $isDefaultActive ? 1 : 2;
        }
        
        if (isset($address_id)) {
            $arr['address_id'] = $address_id;
        }
        $identity = Yii::$app->user->identity;
        $arr['customer_id'] = $identity['id'];
        Yii::$service->customer->address->save($arr);
        $code = Yii::$service->helper->appserver->status_success;
        $data = [ ];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
    }
    
   
}