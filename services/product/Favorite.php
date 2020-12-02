<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

//use fecshop\models\mongodb\product\Favorite as FavoriteModel;
use fecshop\services\Service;
use Yii;

/**
 * Product Favorite Services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Favorite extends Service
{
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage; //     = 'FavoriteMysqldb';   // FavoriteMysqldb | FavoriteMongodb 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';
    protected $_favorite;
    
    public function init()
    {
        parent::init();
        // 从数据库配置中得到值, 设置成当前service存储，是Mysqldb 还是 Mongodb
        $config = Yii::$app->store->get('service_db', 'product_favorite');
        $this->storage = 'FavoriteMysqldb';
        if ($config == Yii::$app->store->serviceMongodbName) {
            $this->storage = 'FavoriteMongodb';
        }
        $currentService = $this->getStorageService($this);
        $this->_favorite = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMongoStorage()
    {
        $this->storage     = 'FavoriteMongodb';
        $currentService = $this->getStorageService($this);
        $this->_favorite = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMysqlStorage()
    {
        $this->storage     = 'FavoriteMysqldb';
        $currentService = $this->getStorageService($this);
        $this->_favorite = new $currentService();
    }
    
    public function getPrimaryKey()
    {
        return $this->_favorite->getPrimaryKey();
    }
    
    
    public function getByPrimaryKey($val)
    {
        return $this->_favorite->getByPrimaryKey($val);
    }
    
    /**
     * @param $product_id | String ， 产品id
     * @param $user_id | Int ，用户id
     * @return $this->_favoriteModel ，如果用户在该产品收藏，则返回相应model。
     */
    public function getByProductIdAndUserId($product_id, $user_id = '')
    {
        return $this->_favorite->getByProductIdAndUserId($product_id, $user_id);
    }
    
    /**
     * @param $product_id | String ， 产品id
     * @param $user_id | Int ，用户id
     * @return boolean，用户收藏该产品时，执行的操作。
     */
    public function add($product_id, $user_id)
    {
        return $this->_favorite->add($product_id, $user_id);
    }
    
    /**
     * @param $product_id | String
     * 更新该产品被收藏的总次数。
     */
    public function updateProductFavoriteCount($product_id)
    {
        return $this->_favorite->updateProductFavoriteCount($product_id);
    }
    /**
     * @param $user_id | Int
     * 更新该用户总的收藏产品个数到用户表
     */
    public function updateUserFavoriteCount($user_id = '')
    {
        return $this->_favorite->updateUserFavoriteCount($user_id);
    }
    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> [$this->getPrimaryKey() => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function lists($filter)
    {
        return $this->_favorite->lists($filter);
    }
    
    
    public function coll($filter)
    {
        return $this->_favorite->coll($filter);
    }
    
    /**
     * @param $favorite_id | string
     * 通过id删除favorite
     */
    public function removeByProductIdAndUserId($product_id, $user_id)
    {
        return $this->_favorite->removeByProductIdAndUserId($product_id, $user_id);
    }
    /**
     * @param $favorite_id | string
     * 通过id删除favorite
     */
    public function currentUserRemove($favorite_id)
    {
        return $this->_favorite->currentUserRemove($favorite_id);
    }
    
}
