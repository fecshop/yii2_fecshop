<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\search;

//use fecshop\models\mongodb\Product;
//use fecshop\models\xunsearch\Search as XunSearchModel;
use fecshop\services\Service;
use Yii;

/**
 * Search XunSearch Service.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class XunSearch extends Service implements SearchInterface
{
    public $searchIndexConfig;

    //public $searchLang;

    public $fuzzy = false;

    public $synonyms = false;

    //protected $_productModelName = '\fecshop\models\mongodb\Product';

    //protected $_productModel;

    protected $_searchModelName  = '\fecshop\models\xunsearch\Search';

    protected $_searchModel;
    
    public function init()
    {
        parent::init();
        //list($this->_productModelName, $this->_productModel) = \Yii::mapGet($this->_productModelName);
        list($this->_searchModelName, $this->_searchModel) = \Yii::mapGet($this->_searchModelName);
    }

    /**
     * 初始化xunSearch索引.
     */
    protected function actionInitFullSearchIndex()
    {
    }
    protected function getProductSelectData()
    {
        $productPrimaryKey = Yii::$service->product->getPrimaryKey(); 
        //echo $productPrimaryKey;exit;
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
    protected $_searchLangCode;
    
    protected function getActiveLangCode()
    {
        if (!$this->_searchLangCode) {
            $langArr = Yii::$app->store->get('mutil_lang');
            foreach ($langArr as $one) {
                if ($one['search_engine'] == 'xunSearch') {
                    $this->_searchLangCode[] = $one['lang_code'];
                }
            }
        }
        return $this->_searchLangCode;
    }
    /**
     * 将产品信息同步到xunSearch引擎中.
     */
    protected function actionSyncProductInfo($product_ids, $numPerPage)
    {
        if (is_array($product_ids) && !empty($product_ids)) {
            $productPrimaryKey    = Yii::$service->product->getPrimaryKey();
            $xunSearchModel       = new $this->_searchModelName();
            $filter['select']     = $this->getProductSelectData();
            $filter['asArray']    = true;
            $filter['where'][]    = ['in', $productPrimaryKey, $product_ids];
            $filter['numPerPage'] = $numPerPage;
            $filter['pageNum']    = 1;
            $coll = Yii::$service->product->coll($filter);
            $productPrimaryKey = Yii::$service->product->getPrimaryKey();
            if (is_array($coll['coll']) && !empty($coll['coll'])) {
                foreach ($coll['coll'] as $one) {
                    $one['_id'] = $one[$productPrimaryKey];
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
                    //unset($one[$productPrimaryKey]);
                    
                    $one_name = $one['name'];
                    $one_description = $one['description'];
                    $one_short_description = $one['short_description'];
                    $searchLangCode = $this->getActiveLangCode();
                    if (!empty($searchLangCode) && is_array($searchLangCode)) {
                        foreach ($searchLangCode as $langCode) {
                            //echo $langCode;
                            $xunSearchModel = new $this->_searchModelName();
                            $xunSearchModel->_id = (string) $one[$productPrimaryKey];
                            $one['name'] = Yii::$service->fecshoplang->getLangAttrVal($one_name, 'name', $langCode);
                            $one['description'] = Yii::$service->fecshoplang->getLangAttrVal($one_description, 'description', $langCode);
                            $one['short_description'] = Yii::$service->fecshoplang->getLangAttrVal($one_short_description, 'short_description', $langCode);
                            $one['sync_updated_at'] = time();
                            //echo $one['name']."\n";
                            $serialize = true;
                            Yii::$service->helper->ar->save($xunSearchModel, $one, $serialize);
                            if ($errors = Yii::$service->helper->errors->get()) {
                                // 报错。
                                var_dump($errors);
                                //return false;
                            }
                        }
                    }
                }
            }
        }
        //echo "XunSearch sync done ... \n";
        
        return true;
    }

    protected function actionDeleteNotActiveProduct($nowTimeStamp)
    {
    }

    /**
     * 删除在xunSearch的所有搜索数据，
     * 当您的产品有很多产品被删除了，但是在xunsearch 存在某些异常没有被删除
     * 您希望也被删除掉，那么，你可以通过这种方式批量删除掉产品
     * 然后重新跑一边同步脚本.
     */
    protected function actionXunDeleteAllProduct($numPerPage, $i)
    {
        //var_dump($index);
        $dbName = $this->_searchModel->projectName();
        // 删除索引
        Yii::$app->xunsearch->getDatabase($dbName)->getIndex()->clean();
        //$index = Yii::$app->xunsearch->getDatabase($dbName)->index;

        echo "begin delete Xun Search Date \n";
        $nowTimeStamp = (int) $nowTimeStamp;
        $XunSearchData = $this->_searchModel->find()
            ->limit($numPerPage)
            ->offset(($i - 1) * $numPerPage)
            ->all();
        foreach ($XunSearchData as $one) {
            $one->delete();
        }
    }

    /**
     * 得到搜索的产品列表.
     */
    protected function actionGetSearchProductColl($select, $where, $pageNum, $numPerPage, $product_search_max_count)
    {
        $collection = $this->fullTearchText($select, $where, $pageNum, $numPerPage, $product_search_max_count);

        $collection['coll'] = Yii::$service->category->product->convertToCategoryInfo($collection['coll']);
        //var_dump($collection);
        //exit;
        return $collection;
    }

    protected function fullTearchText($select, $where, $pageNum, $numPerPage, $product_search_max_count)
    {
        $enableStatus = Yii::$service->product->getEnableStatus();
        $searchText = $where['$text']['$search'];
        $productM = Yii::$service->product->getBySku($searchText);
        $productIds = [];
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        if ($productM && $enableStatus == $productM['status']) {
            $productIds[] = $productM[$productPrimaryKey];
        } else {
            if (!isset($where['status'])) {
                $where['status'] = Yii::$service->product->getEnableStatus();
            }
            $XunSearchQuery = $this->_searchModel->find()->asArray();
            $XunSearchQuery->fuzzy($this->fuzzy);
            $XunSearchQuery->synonyms($this->synonyms);

            if (is_array($where) && !empty($where)) {
                if (isset($where['$text']['$search']) && $where['$text']['$search']) {
                    $XunSearchQuery->where($where['$text']['$search']);
                } else {
                    return [];
                }
                foreach ($where as $k => $v) {
                    if ($k === '$text') {
                        continue;
                    }
                    if (is_array($v)) {
                        // 范围查询，类似 [ 'price' => [ '$gte'=> 100, '$lte' => 150 ] ] 的这种情况
                        // 如果$k的值为`price`, 改为 `final_price`
                        $k !== 'price' || $k = 'final_price';
                        // 得到范围查询的开始和结束值，如果范围查询只有开始，譬如  x < 3, 那么范围的结束用空字符串'', 不能使用null，使用null会跑出异常, 详细参看
                        // @vendor/hightman/xunsearch/wrapper/yii2-ext/QueryBuilder.php 281行函数。
                        $rangBegin = isset($v['$gte']) ? $v['$gte'] : (isset($v['$gt']) ? $v['$gt'] : '');
                        $rangEnd = isset($v['$lte']) ? $v['$lte'] : (isset($v['$lt']) ? $v['$lt'] : '');
                        // 关于xunsearch的查询，参看：https://github.com/hightman/xs-sdk-php#%E6%A3%80%E7%B4%A2%E5%AF%B9%E8%B1%A1
                        $XunSearchQuery->andWhere(['BETWEEN', $k, $rangBegin, $rangEnd]);
                    } else {
                        $XunSearchQuery->andWhere([$k => $v]);
                    }
                }
            }
            $XunSearchQuery->orderBy(['score' => SORT_DESC]);
            $XunSearchQuery->limit($product_search_max_count);
            $XunSearchQuery->offset(0);
            $search_data = $XunSearchQuery->all();
            /**
             * 在搜索页面, spu相同的sku，是否只显示其中score高的sku，其他的sku隐藏
             * 如果设置为true，那么在搜索结果页面，spu相同，sku不同的产品，只会显示score最高的那个产品
             * 如果设置为false，那么在搜索结果页面，所有的sku都显示。
             * 这里做设置的好处，譬如服装，一个spu的不同颜色尺码可能几十个产品，都显示出来会占用很多的位置，对于这种产品您可以选择设置true
             * 这个针对的京东模式的产品
             */
            $data = [];
            if (Yii::$service->search->productSpuShowOnlyOneSku) {
                foreach ($search_data as $one) {
                    if (!isset($data[$one['spu']])) {
                        $data[$one['spu']] = $one;
                    }
                }
            } else {
                $data = $search_data;
            }
            $count = count($data);
            $offset = ($pageNum - 1) * $numPerPage;
            $limit = $numPerPage;
            $productIds = [];
            foreach ($data as $d) {
                if ($productPrimaryKey == '_id') {
                    if (strlen($d['_id']) == 24) {
                        $productIds[] = new \MongoDB\BSON\ObjectId($d['_id']);
                    }
                } else {
                    $productIds[] = $d['_id'];
                }
                
            }
            $productIds = array_slice($productIds, $offset, $limit);
        }
        
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        if (!empty($productIds)) {
            //
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
            $data = $collData['coll'];
            /**
             * 下面的代码的作用：将结果按照上面in查询的顺序进行数组的排序，使结果和上面的搜索结果排序一致（_id）。
             */
            //var_dump($data);exit;
            $s_data = [];
            foreach ($data as $one) {
                if ($one[$productPrimaryKey]) {
                    $_id = (string) $one[$productPrimaryKey];
                    $s_data[$_id] = $one;
                }
            }
            $return_data = [];
            foreach ($productIds as $product_id) {
                $pid = (string) $product_id;
                if (isset($s_data[$pid]) && $s_data[$pid]) {
                    $return_data[] = $s_data[$pid];
                }
            }
            
            return [
                'coll' => $return_data,
                'count'=> $count,
            ];
        }
        
    }

    /**
     * 得到搜索的sku列表侧栏的过滤.
     */
    protected function actionGetFrontSearchFilter($filter_attr, $where)
    {
        //var_dump($where);
        $dbName = $this->_searchModel->projectName();
        $_search = Yii::$app->xunsearch->getDatabase($dbName)->getSearch();
        $text = isset($where['$text']['$search']) ? $where['$text']['$search'] : '';
        if (!$text) {
            return [];
        }
        $sh = '';
        foreach ($where as $k => $v) {
            if ($k != '$text') {
                if (!$sh) {
                    $sh = ' AND '.$k.':'.$v;
                } else {
                    $sh .= ' AND '.$k.':'.$v;
                }
            }
        }
        //echo $sh;

        $docs = $_search->setQuery($text.$sh)
            ->setFacets([$filter_attr])
            ->setFuzzy($this->fuzzy)
            ->setAutoSynonyms($this->synonyms)
            ->search();
        $filter_attr_counts = $_search->getFacets($filter_attr);
        $count_arr = [];
        if (is_array($filter_attr_counts) && !empty($filter_attr_counts)) {
            foreach ($filter_attr_counts as $k => $v) {
                $count_arr[] = [
                    '_id' => $k,
                    'count' => $v,
                ];
            }
        }

        return $count_arr;
    }

    /**
     * 通过product_id删除搜索数据.
     */
    protected function actionRemoveByProductId($product_id)
    {
        if (is_object($product_id)) {
            $product_id = (string) $product_id;
            $model = $this->_searchModel->findOne($product_id);
            if ($model) {
                $model->delete();
            }
        }
    }
}
