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
 * Cart services. 此部分是缓存配置的读取，各个页面譬如首页，产品，分类页面
 * 调用这里的方法读取具体配置，然后来决定缓存的开启和过期时间。
 * 整页缓存的具体使用，还是在相应的controller中，譬如 @appfront/modules/Catalog/controllers/CategoryController.php 中 behaviors() 方法中的使用，Yii2是通过行为的方式做绑定的。
 * 对于Yii2全页缓存在controller中的使用，可以参看文档：http://www.yiichina.com/doc/guide/2.0/caching-page
 * 对于fecshop缓存的使用，可以参看文档：http://www.fecshop.com/doc/fecshop-guide/instructions/cn-1.0/guide-fecshop_cache.html
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Cache extends Service
{
    // 各个页面cache的配置
    public $cacheConfig;

    // cache 总开关
    protected $enable = false;
    // 在store config中的cache配置
    protected $_cache_config;
    // 各个页面对应的 store cache config的key
    public $cacheArr = [
        'category'  => 'categoryPageCache',
        'product'   => 'productPageCache',
        'home'      => 'homePageCache',
        'article'     => 'articlePageCache',
    ];

    /**
     * 得到当前的入口对应的cache的配置信息
     *
     */
    public function getCacheConfig()
    {
        $appName = Yii::$service->helper->getAppName();
        $cacheConfig = Yii::$app->store->get($appName.'_cache');
        if (!$cacheConfig || !is_array($cacheConfig)) {
            return null;
        }
        if (isset($cacheConfig['allPageCache']) && $cacheConfig['allPageCache'] == Yii::$app->store->enable) {
             $this->enable = true;
        }
        $this->_cache_config = $cacheConfig;
        return true;
    }
    public function init()
    {
        parent::init();
        $this->getCacheConfig();
    }
    /**
     * @param $cacheKey | String , 具体的缓存名字，譬如 product  category
     * @return boolean, 如果enable为true，则返回为true
     * 根据传递的$cacheKey，从配置中读取是否开启cache
     */
    public function isEnable($cacheKey)
    {
        $cacheConfigKey = $this->cacheArr[$cacheKey];
        if ($this->enable && isset($this->_cache_config[$cacheConfigKey]) &&
            $this->_cache_config[$cacheConfigKey] == Yii::$app->store->enable
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $cacheKey | String , 具体的缓存名字，譬如 product  category
     * @return int, 如果enable为true，则返回为true
     * 得到$cacheKey 对应的超时时间
     */
    public function timeout($cacheKey)
    {
        if (isset($this->cacheConfig[$cacheKey]['timeout'])) {
            return $this->cacheConfig[$cacheKey]['timeout'];
        } else {
            return 0;
        }
    }

    /**
     * @param $cacheKey | String , 具体的缓存名字，譬如 product  category
     * @return string, 如果enable为true，则返回为true
     */
    public function disableUrlParam($cacheKey)
    {
        if (isset($this->cacheConfig[$cacheKey]['disableUrlParam'])) {
            return $this->cacheConfig[$cacheKey]['disableUrlParam'];
        } else {
            return '';
        }
    }

    /**
     * @param $cacheKey | String , 具体的缓存名字，譬如 product  category
     * @return string, 如果enable为true，则返回为true
     *                 url的参数，哪一些参数作为缓存唯一的依据，譬如p（分页的值）
     */
    public function cacheUrlParam($cacheKey)
    {
        if (isset($this->cacheConfig[$cacheKey]['cacheUrlParam'])) {
            return $this->cacheConfig[$cacheKey]['cacheUrlParam'];
        } else {
            return '';
        }
    }
}
