<?php
/**
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
     * @property  $product_ids | Array  产品id数组
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
     * @property $nowTimeStamp | int 
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
     * @property $select | Array 
     * @property $where | Array 
     * @property $pageNum | Int
     * @property $numPerPage | Array 
     * @property $product_search_max_count | Int ， 搜索结果最大产品数。 
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
    protected function actionGetSearchProductColl($select, $where, $pageNum, $numPerPage, $product_search_max_count)
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
                        return $service->getSearchProductColl($select, $where, $pageNum, $numPerPage, $product_search_max_count);
                    }
                }
            }
        }
    }

    /**
     * 得到搜索的sku列表侧栏的过滤.
     * @property $filter_attr | Array
     * @property $where | Array , like 
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
     * @property $product_id | \mongoId
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
