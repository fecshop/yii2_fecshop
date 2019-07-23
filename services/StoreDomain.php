<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;

/**
 * Cart services. 购物车service， 执行购物车部分对应的方法。
 *
 * @property \fecshop\services\cart\Coupon $coupon coupon sub-service of cart
 * @property \fecshop\services\cart\Info $info info sub-service of cart
 * @property \fecshop\services\cart\Quote $quote quote sub-service of cart
 * @property \fecshop\services\cart\QuoteItem $quoteItem quoteItem sub-service of cart
 *
 * @method getCartInfo($activeProduct, $shippingMethod = '', $country = '', $region = '*') see [[\fecshop\services\Cart::actionGetCartInfo()]]
 * @method mergeCartAfterUserLogin() see [[\fecshop\services\Cart::actionmergeCartAfterUserLogin()]]
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StoreDomain extends Service
{
    
    public $numPerPage = 20;

    protected $_modelName = '\fecshop\models\mysqldb\StoreDomain';

    protected $_model;
    
    //protected $_serilizeAttr = [
    //    "service_db",
    //];
    
    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = Yii::mapGet($this->_modelName);
    }
    
    public function getPrimaryKey()
    {
        return 'id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_model->findOne($primaryKey);
            
            return $one;
        } else {
            return new $this->_modelName();
        }
    }
    
    public function getByKey($key)
    {
        if ($key) {
            $one = $this->_model->findOne(['key' => $key]);
            
            return $one;
        }
        
        return null;
    }
    

    /*
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
    public function coll($filter = '')
    {
        $query = $this->_model->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll = $query->all();
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    // 通过appName得到相应的store配置数组
    public function getCollByAppName($app_name)
    {
        $filter = [
            'where' => [
                ['app_name' => $app_name, 'status' => 1,],
                
            ],
            'fetchAll' => true,
            'asArray' => true,
        ];
        $query = $this->_model->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        return $query->all();
    }
    
    /**
     * @param $one|array
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        
        if ($primaryVal) {
            $model = $this->_model->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('config {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

                return;
            }
        } else {
            $model = new $this->_modelName();
            $model->created_at = time();
        }
        $model->updated_at = time();
        
        $primaryKey = $this->getPrimaryKey();
        $model      = Yii::$service->helper->ar->save($model, $one);
        $primaryVal = $model[$primaryKey];

        return true;
    }
    
    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_model->findOne($id);
                $model->delete();
            }
        } else {
            $id = $ids;
            $model = $this->_model->findOne($id);
            $model->delete();
        }

        return true;
    }
}
