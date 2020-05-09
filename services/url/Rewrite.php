<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\url;

use fecshop\services\Service;
use fecshop\services\url\rewrite\RewriteMongodb;
use fecshop\services\url\rewrite\RewriteMysqldb;
use Yii;
/**
 * Url Rewrite services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Rewrite extends Service
{
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage; //     = 'RewriteMongodb';   // RewriteMongodb | RewriteMysqldb 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';

    protected $_urlRewrite;

    public function init()
    {
        parent::init();
        // 从数据库配置中得到值, 设置成当前service存储，是Mysqldb 还是 Mongodb
        $config = Yii::$app->store->get('service_db', 'url_rewrite');
        $this->storage = 'RewriteMysqldb';
        if ($config == Yii::$app->store->serviceMongodbName) {
            $this->storage = 'RewriteMongodb';
        }
        $currentService = $this->getStorageService($this);
        $this->_urlRewrite = new $currentService();
    }

    /**
     * @param $urlKey | string
     * 通过重写后的urlkey字符串，去url_rewrite表中查询，找到重写前的url字符串。
     */
    public function getOriginUrl($urlKey)
    {
        return $this->_urlRewrite->getOriginUrl($urlKey);
    }

    /**
     * get artile's primary key.
     */
    public function getPrimaryKey()
    {
        return $this->_urlRewrite->getPrimaryKey();
    }

    /**
     * get artile model by primary key.
     */
    public function getByPrimaryKey($primaryKey)
    {
        return $this->_urlRewrite->getByPrimaryKey($primaryKey);
    }

    /**
     * @param $filter|array
     * get artile collection by $filter
     * example filter:
     * [
     *     'numPerPage'     => 20,
     *     'pageNum'        => 1,
     *     'orderBy'        => ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     *     'where'           => [
     *         ['>','price',1],
     *         ['<=','price',10]
     *         ['sku' => 'uk10001'],
     *     ],
     *     'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        return $this->_urlRewrite->coll($filter);
    }

    /**
     * @param $one|array , save one data .
     * @param $originUrlKey|string , article origin url key.
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        return $this->_urlRewrite->save($one);
    }

    /**
     * @param $ids | Array or String or Int
     * 删除相应的url rewrite 记录
     */
    public function remove($ids)
    {
        return $this->_urlRewrite->remove($ids);
    }

    /**
     * @param $time | Int
     * 根据updated_at 更新时间，删除相应的url rewrite 记录
     */
    public function removeByUpdatedAt($time)
    {
        return $this->_urlRewrite->removeByUpdatedAt($time);
    }

    /**
     * 返回url rewrite model 对应的query
     */
    public function find()
    {
        return $this->_urlRewrite->find();
    }

    /**
     * 返回url rewrite 查询结果
     */
    public function findOne($where)
    {
        return $this->_urlRewrite->findOne($where);
    }

    /**
     * 返回url rewrite model
     */
    public function newModel()
    {
        return $this->_urlRewrite->newModel();
    }
}
