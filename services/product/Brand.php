<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

//use fecshop\models\mysqldb\cms\StaticBlock;
use Yii;
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Brand extends Service
{
    public $numPerPage = 20;

    protected $_modelName = '\fecshop\models\mysqldb\product\Brand';

    protected $_model;
    
    public $status_enable = 1;
    public $status_disable = 2;
    /**
     *  language attribute.
     */
    protected $_lang_attr = [
        'name',
    ];
    
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
            foreach ($this->_lang_attr as $attrName) {
                if (isset($one[$attrName])) {
                    $one[$attrName] = unserialize($one[$attrName]);
                }
            }

            return $one;
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
        $coll = $query->all();
        if (!empty($coll)) {
            foreach ($coll as $k => $one) {
                foreach ($this->_lang_attr as $attr) {
                    $one[$attr] = $one[$attr] ? unserialize($one[$attr]) : '';
                }
                $coll[$k] = $one;
            }
        }
        
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
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        
        if ($primaryVal) {
            $model = $this->_model->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('brand {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

                return;
            }
        } else {
            $model = new $this->_modelName();
            $model->created_at = time();
        }
        $model->updated_at = time();
        foreach ($this->_lang_attr as $attrName) {
            if (is_array($one[$attrName]) && !empty($one[$attrName])) {
                $one[$attrName] = serialize($one[$attrName]);
            }
        }
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
    
    /**
     * 得到状态数组
     */
    public function getStatusArr()
    {
       
        return [
            $this->status_enable => Yii::$service->page->translate->__('Enable'),
            $this->status_disable => Yii::$service->page->translate->__('Disable'),
        ];
    }
    
    /**
     * 得到品牌id 和 names 数组
     */
    public function getAllBrandIdAndNames()
    {
        $filter = [
            'where' => [
                ['status' => $this->status_enable]
            ],
            'fetchAll' => true,
            'asArray' => true,
        ];
        $data = $this->coll($filter);
        $arr = [];
        if (is_array($data['coll']) && !empty($data['coll'])) {
            foreach ($data['coll'] as $one) {
                $name = Yii::$service->store->getStoreAttrVal($one['name'], 'name');
                $arr[$one['id']] = $name;
            }
        }
        
        return $arr;
    }
    /**
     * @param $brandId | int,  品牌id
     * 根据品牌id，得到品牌的name
     */
    public function getBrandNameById($brandId)
    {
        $brandModel = $this->getByPrimaryKey($brandId);
        if (!$brandModel['id']) {
            
            return '';
        }
        
        return Yii::$service->store->getStoreAttrVal($brandModel['name'], 'name');
    }
    
    
    
    
}
