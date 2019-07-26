<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use Yii;

/**
 * Product Service is the component that you can get product info from it.
 *
 * @property \fecshop\services\Image | \fecshop\services\Product\Image $image image service or product image sub-service
 * @property \fecshop\services\product\Info $info product info sub-service
 * @property \fecshop\services\product\Stock $stock stock sub-service of product service
 *
 * @method getByPrimaryKey($primaryKey) get product model by primary key
 * @see \fecshop\services\Product::actionGetByPrimaryKey()
 * @method getEnableStatus() get enable status
 * @see \fecshop\services\Product::actionGetEnableStatus()
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Attr extends Service
{
    
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage  = 'AttrMysqldb';   // AttrMysqldb | AttrMongodb 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';

    /**
     * @var \fecshop\services\product\ProductInterface 根据 $storage 及 $storagePath 配置的 Product 的实现
     */
    protected $_attr;


    public function init()
    {
        parent::init();
        // 从数据库配置中得到值, 设置成当前service存储，是Mysqldb 还是 Mongodb
        //$config = Yii::$app->store->get('service_db', 'category_and_product');
        //$this->storage = 'ProductMysqldb';
        //if ($config == Yii::$app->store->serviceMongodbName) {
        //    $this->storage = 'ProductMongodb';
        //}
        $currentService = $this->getStorageService($this);
        $this->_attr = new $currentService();
    }
    // 动态更改为mongodb model
    public function changeToMongoStorage()
    {
        $this->storage     = 'AttrMongodb';
        $currentService = $this->getStorageService($this);
        $this->_attr = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMysqlStorage()
    {
        $this->storage     = 'AttrMysqldb';
        $currentService = $this->getStorageService($this);
        $this->_attr = new $currentService();
    }

    protected function actionGetEnableStatus()
    {
        return $this->_attr->getEnableStatus();
    }
    
    /**
     * get artile's primary key.
     */
    protected function actionGetPrimaryKey()
    {
        return $this->_attr->getPrimaryKey();
    }

    /**
     * get artile model by primary key.
     */
    protected function actionGetByPrimaryKey($primaryKey)
    {
        return $this->_attr->getByPrimaryKey($primaryKey);
    }
    
    protected function actionColl($filter = '')
    {
        return $this->_attr->coll($filter);
    }

    /**
     * @param $one|array , save one data .
     * @param $originUrlKey|string , article origin url key.
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    protected function actionSave($one)
    {
        return $this->_attr->save($one);
    }

    protected function actionRemove($ids)
    {
        return $this->_attr->remove($ids);
    }
    
    protected function actionGetActiveColl($ids)
    {
        return $this->_attr->remove($ids);
    }
    
    public function getActiveAllColl()
    {
        return $this->_attr->getActiveAllColl();
    }
    
    
    public function getAttrTypes()
    {
        return [
            'spu_attr' => Yii::$service->page->translate->__('Spu Attr'),
            'general_attr' => Yii::$service->page->translate->__('General Attr'),
        ];
    }
    
    public function getDbTypes()
    {
        return [
            'String' => 'String',
        ];
        
    }
    
    
    public function getDisplayTypes()
    {
        return [
            'inputString' => 'inputString',
            'inputString-Lang' => 'inputString-Lang',
            'inputEmail' => 'inputEmail',
            'inputDate' => 'inputDate',
            'editSelect' => 'editSelect',
            'select' => 'select',
        ];
        
    }
    
    
    
    
    
    
    
    
}
