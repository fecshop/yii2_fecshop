<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\customer;

//use fecshop\models\mongodb\customer\Newsletter as MongoNewsletter;
use fecshop\services\Service;
use Yii;

/**
 * Page Newsletter services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Contacts extends Service
{
    public $numPerPage = 20;

    protected $_modelName = '\fecshop\models\mysqldb\customer\Contacts';

    protected $_model;
    
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
            
            return $this->_model->findOne($primaryKey);
        } else {
            
            return new $this->_modelName();
        }
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

        return [
            'coll' => $query->all(),
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    
    /**
     * @param $param | array, data format:
     * $paramData = [
     *       'name'        => $name,
     *       'telephone' => $telephone,
     *       'comment'    => $comment,
     *       'email'        => $email,
     *   ];
     * 用户提交联系我们表单，保存数据
     */
    public function addCustomerContacts($param)
    {
        $model = new $this->_modelName();
        $model['name'] = $param['name'];
        $model['telephone'] = $param['telephone'];
        $model['comment'] = $param['comment'];
        $model['email'] = $param['email'];
        $model['updated_at'] = time();
        $model['created_at'] = time();
        
        return $model->save();
    }
   
    
    
    
    
}
