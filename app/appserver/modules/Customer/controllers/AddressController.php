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
        $identity = Yii::$app->user->identity;
        return [
            'code' => 200,
            'addressList' => $this->coll(),
        ];
        
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
    
    public function actionRemove(){
        $address_id = Yii::$app->request->post('address_id');
        if($address_id){
            $this->removeAddressById($address_id);
            return [
                'code' => 200,
                'content' => 'remove customer address success',
                
            ];
        }else{
            return [
                'code' => 401,
                'content' => 'address id is not exist',
            ];
        }
    }
    
    public function removeAddressById($address_id)
    {
        $identity = Yii::$app->user->identity;
        $customer_id = $identity['id'];
        Yii::$service->customer->address->remove($address_id, $customer_id);
    }
    
}