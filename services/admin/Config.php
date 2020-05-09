<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\admin;

use Yii;
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Config extends Service
{
    protected $_modelName = '\fecshop\models\mysqldb\admin\Config';

    protected $_model;

    /**
     *  language attribute.
     */
    protected $_lang_attr = [
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
     * @return mix, int or null
     */
    public function save($one)
    {
        $primaryKey = $this->getPrimaryKey();
        if ($one[$primaryKey]) {
            $this->_model = $this->_model->findOne($one[$primaryKey]);
        }
        $this->_model->attributes = $one;
        if ($this->_model->validate()) {
            $this->_model->save(); 
            
            return $this->_model[$primaryKey];
        } else {
            $errors = $this->_model->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);
        }     
        
        return null;
    }
    
    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $this->_model->findOne($id)->delete();
            }
        } else {
            $id = $ids;
            $this->_model->findOne($id)->delete();
        }

        return true;
    }
    
    
}
