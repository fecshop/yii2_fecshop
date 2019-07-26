<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product\attr;

use fecshop\services\Service;
use yii\db\Query;
use Yii;

/**
 * Product ProductMysqldb Service 未开发。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AttrMysqldb extends Service implements AttrInterface
{
    public $numPerPage = 20;
    
    protected $_attrModelName = '\fecshop\models\mysqldb\product\Attr';

    protected $_attrModel;
    
    
    
    public function init()
    {
        parent::init();
        list($this->_attrModelName, $this->_attrModel) = \Yii::mapGet($this->_attrModelName);
    }
    
    public function getPrimaryKey()
    {
        return 'id';
    }
    
    

    /**
     * 得到分类激活状态的值
     */
    public function getEnableStatus()
    {
        $model = $this->_attrModel;
        return $model::STATUS_ENABLE;
    }
    
    
    public function getByPrimaryKey($primaryKey = null)
    {
        if ($primaryKey) {
            $one = $this->_attrModel->findOne($primaryKey);
            
            return $one;
            //return $this->unserializeData($one) ;
        } else {
            return new $this->_attrModel();
        }
    }
    
    
    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        $query = $this->_attrModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        
        $coll = $query->all();
        //$arr = [];
        //foreach ($coll as $one) {
        //    $arr[] = $this->unserializeData($one) ;
        //}
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    /**
     * @param $one|array
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        if ($primaryVal) {
            $model = $this->_attrModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('Product attr {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

                return;
            }
        } else {
            $model = new $this->_attrModelName();
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
                $model = $this->_attrModel->findOne($id);
                $model->delete();
            }
        } else {
            $id = $ids;
            $model = $this->_attrModel->findOne($id);
            $model->delete();
        }

        return true;
    }
    
    public function getActiveAllColl()
    {
        // attribute Group
        $filter = [
            'where' => [
                ['status' => $this->getEnableStatus()]
            ],
            'fetchAll' => true,
            'asArray' => true,
        ];
        $query = $this->_attrModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        
        $coll = $query->all();
        if (is_array($coll)) {
            foreach ($coll as $k => $one) {
                if ($one['display_data']) {
                    $coll[$k]['display_data'] = unserialize($one['display_data']);
                }
            }
            return $coll;
        }
        
        return null;
        
    }
    
}
