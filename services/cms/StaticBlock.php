<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\cms;

use fecshop\services\cms\staticblock\StaticBlockMongodb;
use fecshop\services\cms\staticblock\StaticBlockMysqldb;
use fecshop\services\Service;
use Yii;

/**
 * Cms StaticBlock services. 静态块部分，譬如首页的某个区块，类似走马灯图，广告图等经常需要改动的部分，可以在后台进行改动。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Staticblock extends Service
{
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage; //     = 'StaticBlockMysqldb';   // StaticBlockMongodb | StaticBlockMysqldb 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';

    protected $_static_block;

    /**
     * init static block db.
     */
    public function init()
    {
        parent::init();
        // 从数据库配置中得到值, 设置成当前service存储，是Mysqldb 还是 Mongodb
        $config = Yii::$app->store->get('service_db', 'article_and_staticblock');
        $this->storage = 'StaticBlockMysqldb';
        if ($config == Yii::$app->store->serviceMongodbName) {
            $this->storage = 'StaticBlockMongodb';
        }
        $currentService = $this->getStorageService($this);
        $this->_static_block = new $currentService();
    }

    /**
     * get store static block content by identify
     * example <?=  Yii::$service->cms->staticblock->getStoreContentByIdentify('home-big-img','appfront') ?>.
     */
    public function getStoreContentByIdentify($identify, $app = 'common')
    {
        $staticBlock    = $this->_static_block->getByIdentify($identify);
        // 没有可用的static block
        if (!isset($staticBlock['content']) || !$staticBlock['content']) {
            
            return '';
        }
        $storeContent   = Yii::$service->store->getStoreAttrVal($staticBlock['content'], 'content');
        $_params_       = $this->getStaticBlockVariableArr($app);
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        foreach ($_params_ as $k => $v) {
            $key = '{{'.$k.'}}';
            if (strstr($storeContent, $key)) {
                $storeContent = str_replace($key, $v, $storeContent);
            }
        }
        echo $storeContent;

        return ob_get_clean();
    }

    /**
     * staticblock中的变量，可以通过{{homeUlr}},来获取下面的值。
     */
    protected function getStaticBlockVariableArr($app)
    {
        return [
            'homeUrl'   => Yii::$service->url->homeUrl(),
            'imgBaseUrl'=> Yii::$service->image->getBaseImgUrl(),
        ];
    }

    /**
     * get artile's primary key.
     */
    public function getPrimaryKey()
    {
        return $this->_static_block->getPrimaryKey();
    }

    /**
     * get artile model by primary key.
     */
    public function getByPrimaryKey($primaryKey)
    {
        return $this->_static_block->getByPrimaryKey($primaryKey);
    }

    /**
     * @param $filter|array
     * get artile collection by $filter
     * example filter:
     * [
     *     'numPerPage' => 20,
     *     'pageNum'    => 1,
     *     'orderBy'    => ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     *     'where'      => [
     *         ['>','price',1],
     *         ['<=','price',10]
     *         ['sku' => 'uk10001'],
     *     ],
     *     'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        return $this->_static_block->coll($filter);
    }

    /**
     * @param $one|array , save one data .
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        return $this->_static_block->save($one);
    }

    public function remove($ids)
    {
        return $this->_static_block->remove($ids);
    }
    
   
    public function getEnableStatus()
    {
        return $this->_static_block->getEnableStatus();
    }
    
    public function getDisableStatus()
    {
        return $this->_static_block->getDisableStatus();
    }
    
}
