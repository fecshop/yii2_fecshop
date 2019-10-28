<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\customer;

//use fecshop\models\mysqldb\customer\Address as MyAddress;
use fecshop\services\Service;
use Yii;

/**
 * Address  child services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Address extends Service
{
    protected $currentCountry;

    protected $currentState;

    protected $_addressModelName = '\fecshop\models\mysqldb\customer\Address';

    protected $_addressModel;
    public $is_default = 1;
    public $is_not_default = 2;
    
    public function init()
    {
        parent::init();
        list($this->_addressModelName, $this->_addressModel) = \Yii::mapGet($this->_addressModelName);
    }
    
    protected function actionGetPrimaryKey()
    {
        return 'address_id';
    }

    /**
     * @param $primaryKey | Int
     * @return Object(MyCoupon)
     *                          通过id找到customer address的对象
     */
    protected function actionGetByPrimaryKey($primaryKey)
    {
        $one = $this->_addressModel->findOne($primaryKey);
        $primaryKey = $this->getPrimaryKey();
        if ($one[$primaryKey]) {
            return $one;
        } else {
            return new $this->_addressModelName();
        }
    }

    /**
     * @param $address_id | Int , address表的id
     * @param $customer_id | Int ， 用户id
     * 在这里在主键查询的同时，加入customer_id，这样查询的肯定是这个用户的，
     * 这样就防止有的用户去查询其他用户的address信息。
     */
    protected function actionGetAddressByIdAndCustomerId($address_id, $customer_id)
    {
        $primaryKey = $this->getPrimaryKey();
        $one = $this->_addressModel->findOne([
            $primaryKey    => $address_id,
            'customer_id'    => $customer_id,
        ]);
        if ($one[$primaryKey]) {
            return $one;
        } else {
            return false;
        }
    }

    /**
     * @param $filter|array
     * @return Array;
     *  通过过滤条件，得到coupon的集合。
     *  example filter:
     *  [
     *  'numPerPage' 	=> 20,
     *  'pageNum'		=> 1,
     *  'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     *  'where'			=> [
     *      ['>','price',1],
     *      ['<=','price',10]
     * 		['sku' => 'uk10001'],
     * 	],
     * 	'asArray' => true,
     *  ]
     */
    protected function actionColl($filter = '')
    {
        $query = $this->_addressModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll = $query->all();
        
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    
    /**
     * @return Array
     * 得到当前用户的所有货运地址数组
     */
    protected function actionCurrentAddressList()
    {
        $arr = [];
        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            $customer_id = $identity['id'];
            if ($customer_id) {
                $filter = [
                    'numPerPage'    => 30,
                    'pageNum'        => 1,
                    'orderBy'        => ['is_default' => SORT_ASC   ,'updated_at' => SORT_DESC],
                    'where'            => [
                        ['customer_id' => $customer_id],
                    ],
                    'asArray' => true,
                ];
                $coll = $this->coll($filter);
                $ii = 0;
                if (is_array($coll['coll']) && !empty($coll['coll'])) {
                    foreach ($coll['coll'] as $one) {
                        $address_id = $one['address_id'];
                        $first_name = $one['first_name'];
                        $last_name = $one['last_name'];
                        $email = $one['email'];
                        $telephone = $one['telephone'];
                        $street1 = $one['street1'];
                        $street2 = $one['street2'];
                        $is_default = $one['is_default'];
                        $city = $one['city'];
                        $state = $one['state'];
                        $zip = $one['zip'];
                        $area = $one['area'];
                        $country = Yii::$service->helper->country->getCountryNameByKey($one['country']);
                        $state = Yii::$service->helper->country->getStateByContryCode($one['country'],$one['state']);
                        
                        $address_info = [
                            'address_id' => $address_id,
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'email' => $email,
                            'telephone' => $telephone,
                            'street1' => $street1,
                            'is_default' => $is_default,
                            'city' => $city,
                            'state' => $state,
                            'zip' => $zip,
                            'area' => $area,
                        ];

                        //$state = Yii::$service->helper->country->getStateByContryCode($one['country'],$one['state']);
                        
                        
                        $str = $first_name.' '.$last_name.' '.$email.' '.
                                $street1.' '.$street2.' '.$city.' '.$state.' '.$country.' '.
                                $zip.' '.$telephone;
                        if ($is_default == 1) {
                            $ii = 1;
                        }
                        $arr[$address_id] = [
                            'address' => $str,
                            'is_default'=>$is_default,
                            'address_info' => $address_info,
                        ];
                    }
                    if (!$ii) {
                        // 如果没有默认的地址，则取第一个当默认
                        foreach ($arr as $k=>$v) {
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
     * @param $one|array , 保存的address数组
     * @return int 返回保存的 address_id 的值。
     */
    protected function actionSave($one)
    {
        if (!is_array($one) || empty($one)) {
            Yii::$service->helper->errors->add('address data is empty');

            return false;
        }
        $primaryKey = $this->getPrimaryKey();
        $primaryVal = isset($one[$primaryKey]) ? $one[$primaryKey] : '';
        if ($primaryVal) {
            $model = $this->_addressModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('address {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

                return false;
            }
        } else {
            $model = new $this->_addressModelName();
            $model->created_at = time();
        }
        $model->attributes = $one;
        // 规则验证
        if ($model->validate()) {
            $model->updated_at = time();
            // 保存地址。
            $model = Yii::$service->helper->ar->save($model, $one);
            if (!$model) {
                return false;
            }
        } else {
            
            $errors = $model->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);

            return false;
        }
        $primaryVal = $model[$primaryKey];
        if ($one['is_default'] == 1) {
            $customer_id = $one['customer_id'];
            if ($customer_id && $primaryVal) {
                $this->_addressModel->updateAll(
                    ['is_default'=>2],  // $attributes
                    'customer_id = :customer_id and  '.$primaryKey.' != :primaryVal ',      // $condition
                    [
                        'customer_id' => $customer_id,
                        'primaryVal'  => $primaryVal,
                    ]
                );
            }
        }

        return $primaryVal;
    }

    /**
     * @param $ids | Int or Array
     * @return bool
     * 如果传入的是id数组，则删除多个address,如果传入的是Int，则删除一个address
     * 删除address的同时，删除掉购物车中的address_id
     * 删除address的同时，如果删除的是default address，那么重新找出来一个address作为default address并保存到表中。
     */
    protected function actionRemove($ids, $customer_id)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_addressModel->findOne($id);
                if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                    if ($customer_id) {
                        if ($model['customer_id'] == $customer_id) {
                            $this->removeCartAddress($model['customer_id'], $id);
                            $model->delete();
                        } else {
                            Yii::$service->helper->errors->add('remove address is not current customer address');
                        }
                    }
                    //} else {
                    //    $this->removeCartAddress($model['customer_id'], $id);
                    //    $model->delete();
                    //}
                } else {
                    Yii::$service->helper->errors->add('Address Remove Errors:ID {id} is not exist', ['id' => $id]);

                    return false;
                }
            }
        } else {
            $id = $ids;
            $model = $this->_addressModel->findOne($id);
            if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                if ($customer_id) {
                    if ($model['customer_id'] == $customer_id) {
                        $this->removeCartAddress($model['customer_id'], $id);
                        $model->delete();
                    } else {
                        Yii::$service->helper->errors->add('remove address is not current customer address');
                    }
                }
                //} else {
                //    $this->removeCartAddress($model['customer_id'], $id);
                //    $model->delete();
                //}
            } else {
                Yii::$service->helper->errors->add('Address Remove Errors:ID:{id} is not exist', ['id'=> $id]);

                return false;
            }
        }
        // 查看是否有默认地址？如果该用户存在记录，但是没有默认地址，
        // 则查找用户是否存在非默认地址，如果存在，则取一个设置为默认地址
        $addressOne = $this->_addressModel->find()->asArray()
                    ->where(['customer_id' => $customer_id, 'is_default' => 1])
                    ->one();
        if (!$addressOne['address_id']) {
            $assOne = $this->_addressModel->find()
                    ->where(['customer_id' => $customer_id])
                    ->one();
            if ($assOne['address_id']) {
                $assOne->is_default = 1;
                $assOne->updated_at = time();
                $assOne->save();
            }
        }
        
        return true;
    }

    /**
     * @param $customer_id | Int ,
     * @param $address_id | Int，address id
     * 删除购物车中的address部分。
     */
    protected function removeCartAddress($customer_id, $address_id)
    {
        $cart = Yii::$service->cart->quote->getCartByCustomerId($customer_id);
        if (isset($cart['customer_address_id']) && !empty($cart['customer_address_id'])) {
            if ($cart['customer_address_id'] == $address_id) {
                $cart->customer_address_id = '';
                $cart->save();
            }
        }
    }
    
    /**
     * @param $customer_id | int 用户id
     * @param $address_id | int 用户地址id
     * @return boolean
     * 将某个address_id对应的地址数据，设置成默认地址
     */
    public function setDefault($customer_id, $address_id)
    {
        $primaryKey = $this->getPrimaryKey();
        // 当前的设置成1
        $address_one = $this->_addressModel->findOne([
            'customer_id' => $customer_id,
            $primaryKey  => $address_id,
        ]);
        if (!$address_one[$primaryKey]) {
            Yii::$service->helper->errors->add('customer address is empty');
            
            return false;
        }
        $address_one->is_default = 1;
        $address_one->updated_at = time();
        $address_one->save();
        
        // 将其他的设置成2
        $this->_addressModel->updateAll(
            ['is_default'=>2],  // $attributes
            'customer_id = :customer_id and  '.$primaryKey.' != :primaryVal ',      // $condition
            [
                'customer_id' => $customer_id,
                'primaryVal'  => $address_id,
            ]
        );
        
        return true;
        
    }
    
    public function getDefualtAddressId($customer_id = '')
    {
        if(!$customer_id){
            $identity = Yii::$app->user->identity;
            $customer_id = $identity['id'];
        }
        if (!$customer_id) {
            return null;
        }
        $addressOne = $this->_addressModel->find()->asArray()
                            ->where(['customer_id' => $customer_id,'is_default' => 1])
                            ->one();
        if($addressOne['address_id']){
            return $addressOne['address_id'];
        }
        
        return null;
    }

    /*
     * @param $customer_id | int 用户的id
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
            $addressOne = $this->_addressModel->find()->asArray()
                            ->where(['customer_id' => $customer_id,'is_default' => 1])
                            ->one();
            if($addressOne['address_id']){
                return $addressOne;
            }else{
                $assOne = $this->_addressModel->find()->asArray()
                            ->where(['customer_id' => $customer_id])
                            ->one();
                if($assOne['address_id']){
                    return $assOne;
                }
            }
        }
    }
    */
    /**
     * 得到登陆用户的默认货运地址。
     */
    public function getDefaultAddress(){
        if (Yii::$app->user->isGuest) {
            Yii::$service->helper->errors->add('customer is guest');
            
            return null;
        }
        $identity = Yii::$app->user->identity;
        $customer_id = $identity->id;
        $one = $this->_addressModel->findOne([
            'customer_id'    => $customer_id,
            'is_default'  => $this->is_default,
        ]);
        if ($one['customer_id']) {
            
            return $one;
        }
        
        return null;
    }
}
