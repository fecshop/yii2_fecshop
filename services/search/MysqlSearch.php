<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\search;

use fecshop\services\Service;
use Yii;

/**
 * Search MongoSearch Service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class MysqlSearch extends Service implements SearchInterface
{
    public $searchIndexConfig;

    public $enable;

    protected $_searchModelName = '\fecshop\models\mysqldb\Search';

    protected $_searchModel;
    
    protected $_searchLangCode;
    
    public function init()
    {
        parent::init();
        list($this->_searchModelName, $this->_searchModel) = \Yii::mapGet($this->_searchModelName);
    }

    /**
     * 创建索引.  (mysql不需要)
     */
    public function initFullSearchIndex()
    {
        return;
    }
    
    protected function getProductSelectData()
    {
        $productPrimaryKey = Yii::$service->product->getPrimaryKey(); 
        
        return [
            $productPrimaryKey,
            'name',
            'spu',
            'sku',
            'score',
            'status',
            'is_in_stock',
            'url_key',
            'price',
            'cost_price',
            'special_price',
            'special_from',
            'special_to',
            'final_price',   // 算出来的最终价格。这个通过脚本赋值。
            'image',
            'short_description',
            'description',
            'created_at',
        ];
        
    }
    // 从配置中得到当前的搜索引擎对应的有效语言。
    protected function getActiveLangCode()
    {
        if (!$this->_searchLangCode) {
            $langArr = Yii::$app->store->get('mutil_lang');
            foreach ($langArr as $one) {
                if ($one['search_engine'] == 'mysqlSearch') {
                    $this->_searchLangCode[] = $one['lang_code'];
                }
            }
        }
        
        return $this->_searchLangCode;
    }
    /**
     * @param $product_ids |　Array ，里面的子项是MongoId类型。
     * 将产品表的数据同步到各个语言对应的搜索表中。
     */
    public function syncProductInfo($product_ids, $numPerPage)
    {
        $sModel = $this->_searchModel;
        if (is_array($product_ids) && !empty($product_ids)) {
            $productPrimaryKey = Yii::$service->product->getPrimaryKey();
            $searchModel = new $this->_searchModelName();
            $filter['select'] = $this->getProductSelectData();
            $filter['asArray'] = true;
            $filter['where'][] = ['in', $productPrimaryKey, $product_ids];
            $filter['numPerPage'] = $numPerPage;
            $filter['pageNum'] = 1;
            $coll = Yii::$service->product->coll($filter);
            if (is_array($coll['coll']) && !empty($coll['coll'])) {
                $productPrimaryKey = Yii::$service->product->getPrimaryKey();
                foreach ($coll['coll'] as $one) {
                    $one['product_id'] = $one[$productPrimaryKey];
                    $one['status'] = (int)$one['status'];
                    $one['score'] = (int)$one['score'];
                    $one['is_in_stock'] = (int)$one['is_in_stock'];
                    $one['created_at'] = (int)$one['created_at'];
                    $one['price'] = (float)$one['price'];
                    $one['cost_price'] = (float)$one['cost_price'];
                    $one['special_price'] = (float)$one['special_price'];
                    $one['special_from'] = (int)$one['special_from'];
                    $one['special_to'] = (int)$one['special_to'];
                    $one['final_price'] = (float)$one['final_price'];
                    unset($one[$productPrimaryKey]);
                    $one_name = $one['name'];
                    $one_description = $one['description'];
                    $one_short_description = $one['short_description'];
                    $searchLangCode = $this->getActiveLangCode();
                    if (!empty($searchLangCode) && is_array($searchLangCode)) {
                        foreach ($searchLangCode as $langCode) {
                            $one['lang'] = $langCode;
                            $one['image'] = serialize($one['image']);
                            $searchModel = $this->_searchModel->findOne([
                                'product_id' => $one['product_id'],
                                'lang'  => $langCode,
                            ]);
                            if (!$searchModel['product_id']) {
                                $searchModel = new $this->_searchModelName();
                            }
                            $one['name'] = Yii::$service->fecshoplang->getLangAttrVal($one_name, 'name', $langCode);
                            $one['description'] = Yii::$service->fecshoplang->getLangAttrVal($one_description, 'description', $langCode);
                            $one['short_description'] = Yii::$service->fecshoplang->getLangAttrVal($one_short_description, 'short_description', $langCode);
                            $one['sync_updated_at'] = time();
                            Yii::$service->helper->ar->save($searchModel, $one);
                            if ($errors = Yii::$service->helper->errors->get()) {
                                // 报错。
                                var_dump($errors);
                            }
                        }
                    }
                }
            }
        }
        
        return true;
    }

    /**
     * @param $nowTimeStamp | int
     * 批量更新过程中，被更新的产品都会更新字段sync_updated_at
     * 删除mysqlSearch引擎中sync_updated_at小于$nowTimeStamp的字段.
     */
    public function deleteNotActiveProduct($nowTimeStamp)
    {
        $sModel = $this->_searchModel;
        echo "begin delete Mongodb Search Date \n";
        $searchLangCode = $this->getActiveLangCode();
        if (!empty($searchLangCode) && is_array($searchLangCode)) {
            foreach ($searchLangCode as $langCode) {
                // 更新时间方式删除。
                $this->_searchModel->deleteAll([
                    'and',
                    ['<', 'sync_updated_at', (int) $nowTimeStamp],
                    ['lang' => $langCode],
                ]);
                // 不存在更新时间的直接删除掉。
                $this->_searchModel->deleteAll([
                    'sync_updated_at' => [
                        '?exists' => false,
                    ],
                ]);
            }
        }
    }

    public function removeByProductId($product_id)
    {
        $this->_searchModel->deleteAll([
            'product_id' => $product_id,
        ]);

        return true;
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
    public function getSearchProductColl($select, $where, $pageNum, $numPerPage, $product_search_max_count)
    {
        // 先进行sku搜索，如果有结果，说明是针对sku的搜索
        $enableStatus = Yii::$service->product->getEnableStatus();
        $searchText = $where['$text']['$search'];
        $productM = Yii::$service->product->getBySku($searchText);
        if ($productM && $enableStatus == $productM['status']) {
            $collection['coll'][] = $productM;
            $collection['count'] = 1;
        } else {
            $filter = [
                'pageNum'        => $pageNum,
                'numPerPage'    => $numPerPage,
                'where'        => $where,
                'product_search_max_count' => $product_search_max_count,
                'select'         => $select,
            ];
            $collection = $this->fullTearchText($filter);
        }
        $collection['coll'] = Yii::$service->category->product->convertToCategoryInfo($collection['coll']);
        
        return $collection;
    }

    /**
     * 全文搜索
     * $filter Example:
     *	$filter = [
     *		'pageNum'	  => $this->getPageNum(),
     *		'numPerPage'  => $this->getNumPerPage(),
     *		'where'  => $this->_where,
     *		'product_search_max_count' => 	Yii::$app->controller->module->params['product_search_max_count'],
     *		'select' 	  => $select,
     *	];
     *  因为mongodb的搜索涉及到计算量，因此产品过多的情况下，要设置 product_search_max_count的值。减轻服务器负担
     *  因为对客户来说，前10页的产品已经足矣，后面的不需要看了，限定一下产品个数，减轻服务器的压力。
     *  多个spu，取score最高的那个一个显示。
     *  按照搜索的匹配度来进行排序，没有其他排序方式.
     */
    protected function fullTearchText($filter)
    {
        $sModel = $this->_searchModel;
        $where = $filter['where'];
        $searchText = $where['$text']['$search'];
        unset($where['$text']);
        $whereArr[] = 'and';
        $whereArr[] = [ 'or', ['like', 'name', $searchText ], ['like', 'description', $searchText ] ];
        if (!isset($where['status'])) {
            $where['status'] = Yii::$service->product->getEnableStatus();
        }
        foreach ($where as $k=>$v) {
            if (is_array($v)) {
                $k !== 'price' || $k = 'final_price';
                $rangBegin = isset($v['$gte']) ? $v['$gte'] : (isset($v['$gt']) ? $v['$gt'] : '');
                $rangEnd = isset($v['$lte']) ? $v['$lte'] : (isset($v['$lt']) ? $v['$lt'] : '');
                if ($rangBegin) {
                    $whereArr[] = ['>=', $k, $rangBegin];
                }
                if ($rangEnd) {
                    $whereArr[] = ['<', $k, $rangEnd];
                }
            } else {
                $whereArr[][$k] = $v;
            }
        }
        // lang code
        $whereArr[] = ['lang' => Yii::$service->store->currentLangCode];
        $select = $filter['select'];
        $pageNum = $filter['pageNum'];
        $numPerPage = $filter['numPerPage'];
        $orderBy = $filter['orderBy'];
        $count = 0;
        $searchM = $this->_searchModel->find()->asArray()->where($whereArr);
        if (Yii::$service->search->productSpuShowOnlyOneSku) {
            /**
             * 如果产品spu存在多个sku（譬如同一款产品存在多个颜色尺码），但是分类页只显示一个sku，那么需要通过
             * 下面的逻辑，对spu进行group，对score倒序，取score最大的那个sku作为分类列表显示
             */
            $orderBy['score'] = SORT_DESC;
            $query = $searchM->orderBy($orderBy)->groupBy('spu')->limit($numPerPage)->offset(($pageNum-1)*$numPerPage);
            $search_data = $query->all();
            $count = $query->limit(null)->offset(null)->count();
        } else {
            if ($orderBy) {
                $searchM->orderBy($orderBy);
            } 
            $query = $searchM->limit($numPerPage)->offset(($pageNum-1)*$numPerPage);
            $search_data = $query->all();
            $count = $query->limit(null)->offset(null)->count();
        }   
        $productIds = [];
        foreach ($search_data as $d) {
            $productIds[] = $d['product_id'];
        }
        // 通过productIds数组 得到产品数据
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        if (!empty($productIds)) {
            foreach ($select as $sk => $se) {
                if ($se == 'product_id') {
                    unset($select[$sk]);
                }
            }
            $select[] = $productPrimaryKey;
            $filter = [
                'select' => $select,
                'where' => [
                    [ 'in', $productPrimaryKey, $productIds]
                ],
            ];
            $collData = Yii::$service->product->coll($filter);
            $return_data = $collData['coll'];
            
            return [
                'coll' => $return_data,
                'count'=> $count,
            ];
        }
        
        return [];
    }

    /**
     * @param $filter_attr | String 需要进行统计的字段名称
     * @propertuy $where | Array  搜索条件。这个需要些mongodb的搜索条件。
     * 得到的是个属性，以及对应的个数。
     * 这个功能是用于前端分类侧栏进行属性过滤。
     * mysql 功能受限，这个废掉了。
     */
    public function getFrontSearchFilter($filter_attr, $where)
    {
        return [];
    }
}
