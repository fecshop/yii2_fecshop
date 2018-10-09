<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\category;

use fecshop\models\mongodb\Category;
use fecshop\services\Service;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CategoryMysqldb extends Service implements CategoryInterface
{
    public $numPerPage = 20;
    
    protected $_categoryModelName = '\fecshop\models\mysqldb\Category';

    protected $_categoryModel;
    
    public function init()
    {
        parent::init();
        list($this->_categoryModelName, $this->_categoryModel) = Yii::mapGet($this->_categoryModelName);
    }
    
    public function getByPrimaryKey($primaryKey)
    {
    }

    public function coll($filter)
    {
    }

    public function save($one, $originUrlKey)
    {
    }

    public function remove($ids)
    {
    }
}
