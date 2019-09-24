<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;
/**
 * Category service.
 *
 * @method coll($filters = [])
 * @see \fecshop\services\Category::actionColl()
 * @method getCategoryEnableStatus()
 * @see \fecshop\services\Category::actionGetCategoryEnableStatus()
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Category extends Service
{
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage; //     = 'CategoryMysqldb';   //  CategoryMongodb | CategoryMysqldb  当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';

    /**
     * @var \fecshop\services\category\CategoryInterface
     */
    protected $_category;

    /**
     * init function , 初始化category，使用哪一个category service.
     * 目前只支持mongodb，不支持mysql
     */
    public function init()
    {
        parent::init();
        // 从数据库配置中得到值, 设置成当前service存储，是Mysqldb 还是 Mongodb
        $config = Yii::$app->store->get('service_db', 'category_and_product');
        $this->storage = 'CategoryMysqldb';
        if ($config == Yii::$app->store->serviceMongodbName) {
            $this->storage = 'CategoryMongodb';
        }
        $currentService = $this->getStorageService($this);
        $this->_category = new $currentService();
        
    }
    // 动态更改为mongodb model
    public function changeToMongoStorage()
    {
        $this->storage     = 'CategoryMongodb';
        $currentService = $this->getStorageService($this);
        $this->_category = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMysqlStorage()
    {
        $this->storage     = 'CategoryMysqldb';
        $currentService = $this->getStorageService($this);
        $this->_category = new $currentService();
    }
    
    protected function actionGetCategoryEnableStatus()
    {
        return $this->_category->getCategoryEnableStatus();
    }

    protected function actionGetCategoryMenuShowStatus()
    {
        return $this->_category->getCategoryMenuShowStatus();
    }

    /**
     * 得到当前的category service 对应的主键名称，譬如如果是mongo，返回的是 _id.
     */
    protected function actionGetPrimaryKey()
    {
        return $this->_category->getPrimaryKey();
    }

    /**
     * 得到category model的全名.
     */
    protected function actionGetModelName()
    {
        return get_class($this->_category->getByPrimaryKey());
    }

    /**
     * @param $primaryKey | String or Int , 主键
     * 通过主键，得到category info
     */
    protected function actionGetByPrimaryKey($primaryKey)
    {
        return $this->_category->getByPrimaryKey($primaryKey);
    }

    /**
     * @param $urlKey | String or Int , Url Key
     * 通过主键，得到category info
     */
    protected function actionGetByUrlKey($urlKey)
    {
        return $this->_category->getByUrlKey($urlKey);
    }

    protected function actionCollCount($filter = '')
    {
        return $this->_category->collCount($filter);
    }

    /**
     * Get category collection by $filter.
     * @param array $filter
     * example filter:
     * [
     *      'numPerPage'    => 20,
     *      'pageNum'       => 1,
     *      'orderBy'   => ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     *      'where'         => [
     *          ['>','price','1'],
     *          ['<','price','10'],
     *          ['sku' => 'uk10001'],
     *          ],
     *      'asArray' => true,
     * ]
     */
    protected function actionColl($filter = [])
    {
        return $this->_category->coll($filter);
    }
    
    public function apiColl($filter = [])
    {
        return $this->_category->apiColl($filter);
    }
    
    public function findOne($where)
    {
        return $this->_category->findOne($where);
    }
    
    
    

    /**
     *  得到分类的树数组。
     *  数组中只有  id  name(default language), child(子分类) 等数据。
     *  目前此函数仅仅用于后台对分类的编辑使用。 appadmin.
     */
    protected function actionGetTreeArr($rootCategoryId = 0, $lang = '', $appserver = false, $level = 1)
    {
        return $this->_category->getTreeArr($rootCategoryId, $lang, $appserver);
    }

    /**
     * @param $one|array , save one data . 分类数组
     * @param $originUrlKey|string , 分类的在修改之前的url key.（在数据库中保存的url_key字段，如果没有则为空）
     * 保存分类，同时生成分类的伪静态url（自定义url），如果按照name生成的url或者自定义的urlkey存在，系统则会增加几个随机数字字符串，来增加唯一性。
     */
    protected function actionSave($one, $originUrlKey = 'catalog/category/index')
    {
        return $this->_category->save($one, $originUrlKey);
    }
    /**
     *
     */
    public function sync($arr)
    {
        return $this->_category->sync($arr);
    }

    /**
     * @param $id | String  主键值
     * 通过主键值找到分类，并且删除分类在url rewrite表中的记录
     * 查看这个分类是否存在子分类，如果存在子分类，则删除所有的子分类，以及子分类在url rewrite表中对应的数据。
     */
    protected function actionRemove($id)
    {
        return $this->_category->remove($id);
    }

    /**
     * @param $parent_id|string
     * 通过当前分类的parent_id字段（当前分类的上级分类id），得到所有的上级分类数组。
     * 里面包含的信息为：name，url_key。
     * 譬如一个分类为三级分类，将他的parent_id传递给这个函数，那么，他返回的数组信息为[一级分类的信息（name，url_key），二级分类的信息（name，url_key）].
     * 目前这个功能用于前端分类页面的面包屑导航。
     */
    protected function actionGetAllParentInfo($parent_id)
    {
        return $this->_category->getAllParentInfo($parent_id);
    }

    /**
     * @param $category_id|string  当前的分类_id
     * @param $parent_id|string  当前的分类上级id parent_id
     * 这个功能是点击分类后，在产品分类页面侧栏的子分类菜单导航，详细的逻辑如下：
     * 1.如果level为一级，那么title部分为当前的分类，子分类为一级分类下的二级分类
     * 2.如果level为二级，那么将所有的二级分类列出，当前的二级分类，会列出来当前二级分类对应的子分类
     * 3.如果level为三级，那么将所有的二级分类列出。当前三级分类的所有姊妹分类（同一个父类）列出，当前三级分类如果有子分类，则列出
     * 4.依次递归。
     * 具体的显示效果，请查看appfront 对应的分类页面。
     */
    protected function actionGetFilterCategory($category_id, $parent_id)
    {
        return $this->_category->getFilterCategory($category_id, $parent_id);
    }
    
    /**
     * 得到category model的全名.
     */
    public function getChildCategory($category_id)
    {
        return $this->_category->getChildCategory($category_id);
    }
}
