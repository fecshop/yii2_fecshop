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
class Review extends Service
{
    
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage; //     = 'ReviewMysqldb';   // ReviewMysqldb | ReviewMongodb 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';
    
    public $filterByLang;

    // 用户购物过的产品才能评论。
    public $reviewOnlyOrderedProduct = true;

    // 订单创建后，多久内可以进行评论，超过这个期限将不能评论产品（单位为月）
    public $reviewMonth = 6;
    protected $_review;
    
    public function init()
    {
        parent::init();
        // 初始化$this->filterByLang
        $appName = Yii::$service->helper->getAppName();
        $reviewFilterByLang = Yii::$app->store->get($appName.'_catalog','review_filterByLang');
        $this->filterByLang = ($reviewFilterByLang == Yii::$app->store->enable) ? true : false;
        $reviewOnlyOrderedProduct = Yii::$app->store->get($appName.'_catalog','review_OnlyOrderedProduct');
        $this->reviewOnlyOrderedProduct = ($reviewOnlyOrderedProduct == Yii::$app->store->enable) ? true : false;
        $this->reviewMonth = Yii::$app->store->get($appName.'_catalog','review_MonthLimit');
        //$this->reviewOnlyOrderedProduct = ($reviewOnlyOrderedProduct == Yii::$app->store->enable) ? true : false;
        // 从数据库配置中得到值, 设置成当前service存储，是Mysqldb 还是 Mongodb
        $config = Yii::$app->store->get('service_db', 'product_review');
        $this->storage = 'ReviewMysqldb';
        if ($config == Yii::$app->store->serviceMongodbName) {
            $this->storage = 'ReviewMongodb';
        }
        $currentService = $this->getStorageService($this);
        $this->_review = new $currentService();
        //var_dump([$this->filterByLang , $this->reviewOnlyOrderedProduct, $this->reviewMonth]);
    }
    // 动态更改为mongodb model
    public function changeToMongoStorage()
    {
        $this->storage     = 'ReviewMongodb';
        $currentService = $this->getStorageService($this);
        $this->_review = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMysqlStorage()
    {
        $this->storage     = 'ReviewMysqldb';
        $currentService = $this->getStorageService($this);
        $this->_review = new $currentService();
    }

    public function isReviewRole($product_id)
    {
        return $this->_review->isReviewRole($product_id);
    }
    
    public function noActiveStatus()
    {
        return $this->_review->noActiveStatus();
    }
    
    public function activeStatus()
    {
        return $this->_review->activeStatus();
    }
    public function refuseStatus()
    {
        return $this->_review->refuseStatus();
    }
    
    public function getPrimaryKey()
    {
        return $this->_review->getPrimaryKey();
    }
    /**
     * @param $spu | String.
     * 通过spu找到评论总数。
     */
    public function getCountBySpu($spu)
    {
        return $this->_review->getCountBySpu($spu);
    }
    /**
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['review_date' => SORT_DESC],
     * 		where'			=> [
     * 			['spu' => 'uk10001'],
     * 		],
     * 		'asArray' => true,
     * ]
     * 通过spu找到评论listing.
     */
    public function getListBySpu($filter)
    {
        return $this->_review->getListBySpu($filter);
    }
    /**
     * @param $review_data | Array
     *
     * 增加评论 前台增加评论调用的函数。
     */
    public function addReview($review_data)
    {
        return $this->_review->addReview($review_data);
    }
    public function updateReview($review_data)
    {
        return $this->_review->updateReview($review_data);
    }
    /**
     * 查看review 的列表
     */
    public function actionList($filter)
    {
        return $this->_review->list($filter);
    }
    public function getByReviewId($_id)
    {
        return $this->_review->getByReviewId($_id);
    }
    public function getByPrimaryKey($primaryKey)
    {
        return $this->_review->getByPrimaryKey($primaryKey);
    }
    
    
    public function coll($filter = '')
    {
        return $this->_review->coll($filter);
    }
    public function save($one)
    {
        return $this->_review->save($one);
    }
    /**
     * @param $ids | Array or String
     * @return boolean
     * 根据提供的ReviewId，删除产品评论
     */
    public function remove($ids)
    {
        return $this->_review->remove($ids);
    }
    
    public function auditReviewByIds($ids)
    {
        return $this->_review->auditReviewByIds($ids);
    }
    public function auditRejectedReviewByIds($ids)
    {
        return $this->_review->auditRejectedReviewByIds($ids);
    }
    
    
    public function updateProductSpuReview($spu, $lang_code)
    {
        return $this->_review->updateProductSpuReview($spu, $lang_code);
    }
    
    public function getReviewsByUserId($filter)
    {
        return $this->_review->getReviewsByUserId($filter);
    }
    
    
    
    
}
