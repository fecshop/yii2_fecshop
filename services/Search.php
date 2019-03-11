<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use fecshop\services\search\MongoSearch;
use Yii;

/**
 * Product Search.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Search extends Service
{
    /**
     * 在搜索页面侧栏的搜索过滤属性字段.
     */
    public $filterAttr;
    /**
     * 在搜索页面, spu相同的sku，是否只显示其中score高的sku，其他的sku隐藏
     * 如果设置为true，那么在搜索结果页面，spu相同，sku不同的产品，只会显示score最高的那个产品
     * 如果设置为false，那么在搜索结果页面，所有的sku都显示。
     * 这里做设置的好处，譬如服装，一个spu的不同颜色尺码可能几十个产品，都显示出来会占用很多的位置，对于这种产品您可以选择设置true
     * 这个针对的京东模式的产品
     */
    public $productSpuShowOnlyOneSku = true;

    public function init()
    {
        //if($this->currentSearchEngine == 'MongoSearch'){
        //	$this->_searchEngine = new MongoSearch;
        //}else if($this->currentSearchEngine == 'XunSearch'){
        //	$this->_searchEngine = new XunSearch;
        //}
        parent::init();
    }

    /**
     * init search engine index.
     */
    protected function actionInitFullSearchIndex()
    {
        //exit;
        $searchEngineList = $this->getAllChildServiceName();
        if (is_array($searchEngineList) && !empty($searchEngineList)) {
            foreach ($searchEngineList as $sE) {
                $model = $this->{$sE};
                $model->initFullSearchIndex();
            }
        }
    }

    /**
     * @param  $product_ids | Array  产品id数组
     * 批量处理，将所有产品批量同步到搜索工具的库里面。
     */
    protected function actionSyncProductInfo($product_ids, $numPerPage = 20)
    {
        $searchEngineList = $this->getAllChildServiceName();
        if (is_array($searchEngineList) && !empty($searchEngineList)) {
            foreach ($searchEngineList as $sE) {
                $model = $this->{$sE};
                $model->syncProductInfo($product_ids, $numPerPage);
            }
        }
    }

    /**
     * @param $nowTimeStamp | int
     * 批量更新过程中，被更新的产品都会更新字段sync_updated_at
     * 删除xunSearch引擎中sync_updated_at小于$nowTimeStamp的字段.
     */
    protected function actionDeleteNotActiveProduct($nowTimeStamp)
    {
        $searchEngineList = $this->getAllChildServiceName();
        if (is_array($searchEngineList) && !empty($searchEngineList)) {
            foreach ($searchEngineList as $sE) {
                $model = $this->{$sE};
                $model->deleteNotActiveProduct($nowTimeStamp);
            }
        }
    }

    /**
     * @param $select | Array
     * @param $where | Array
     * @param $pageNum | Int
     * @param $numPerPage | Array
     * @param $product_search_max_count | Int ， 搜索结果最大产品数。
     * 对于上面的参数和以前的$filter类似，大致和下面的类似
     * [
     *	'category_id' 	=> 1,
     *	'pageNum'		=> 2,
     *	'numPerPage'	=> 50,
     *	'orderBy'		=> 'name',
     *	'where'			=> [
     *		['>','price',11],
     *		['<','price',22],
     *	],
     *	'select'		=> ['xx','yy'],
     *	'group'			=> '$spu',
     * ]
     * 得到搜索的产品列表.
     */
    protected function actionGetSearchProductColl($select, $where, $pageNum, $numPerPage, $product_search_max_count, $filterAttr = [])
    {
        $currentLangCode = Yii::$service->store->currentLangCode;
        if (!$currentLangCode) {
            Yii::$service->helper->errors->add('current language code is empty');
            return;
        }
        $searchEngineList = $this->getAllChildServiceName();
        // 根据当前store的语言，选择相应的搜索引擎
        if (is_array($searchEngineList) && !empty($searchEngineList)) {
            foreach ($searchEngineList as $sE) {
                $service = $this->{$sE};
                $searchLang = $service->searchLang;
                if (is_array($searchLang) && !empty($searchLang)) {
                    $searchLangCode = array_keys($searchLang);
                    // 如果当前store的语言，在当前的搜索引擎中支持，则会使用这个搜索，作为支持。
                    if (in_array($currentLangCode, $searchLangCode)) {
                        return $service->getSearchProductColl($select, $where, $pageNum, $numPerPage, $product_search_max_count, $filterAttr);
                    }
                }
            }
        }
    }

    /**
     * 得到搜索的sku列表侧栏的过滤.
     * @param $filter_attr | Array
     * @param $where | Array , like
     *  [
     *		['>','price',11],
     *		['<','price',22],
     *	],
     */
    protected function actionGetFrontSearchFilter($filter_attr, $where)
    {
        $currentLangCode = Yii::$service->store->currentLangCode;
        if (!$currentLangCode) {
            return;
        }
        $searchEngineList = $this->getAllChildServiceName();
        if (is_array($searchEngineList) && !empty($searchEngineList)) {
            foreach ($searchEngineList as $sE) {
                $service = $this->{$sE};
                $searchLang = $service->searchLang;
                if (is_array($searchLang) && !empty($searchLang)) {
                    $searchLangCode = array_keys($searchLang);
                    // 如果当前store的语言，在当前的搜索引擎中支持，则会使用这个搜索，作为支持。

                    if (in_array($currentLangCode, $searchLangCode)) {
                        return $service->getFrontSearchFilter($filter_attr, $where);
                    }
                }
            }
        }
    }

    /**
     * 通过product_id删除搜索数据.
     * @param $product_id | \mongoId
     */
    protected function actionRemoveByProductId($product_id)
    {
        $searchEngineList = $this->getAllChildServiceName();
        if (is_array($searchEngineList) && !empty($searchEngineList)) {
            foreach ($searchEngineList as $sE) {
                $service = $this->{$sE};
                $service->removeByProductId($product_id);
            }
        }
    }
}
