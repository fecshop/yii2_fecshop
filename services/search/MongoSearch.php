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
//use fecshop\models\mongodb\Search;
use fecshop\services\Service;
use Yii;

/**
 * Search MongoSearch Service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class MongoSearch extends Service implements SearchInterface
{
    public $searchIndexConfig;

    //public $searchLang;

    public $enable;
    
    // https://docs.mongodb.com/manual/reference/text-search-languages/#text-search-languages
    public $searchLanguages = [
        'da' => 'danish',
        'nl' => 'dutch',
        'en' => 'english',
        'fi' => 'finnish',
        'fr' => 'french',
        'de' => 'german',
        'hu' => 'hungarian',
        'it' => 'italian',
        'nb' => 'norwegian',
        'pt' => 'portuguese',
        'ro' => 'romanian',
        'ru' => 'russian',
        'es' => 'spanish',
        'sv' => 'swedish',
        'tr' => 'turkish',
    ];

    //protected $_productModelName = '\fecshop\models\mongodb\Product';

    //protected $_productModel;

    protected $_searchModelName = '\fecshop\models\mongodb\Search';

    protected $_searchModel;
    
    public function init()
    {
        parent::init();
        //list($this->_productModelName, $this->_productModel) = \Yii::mapGet($this->_productModelName);
        list($this->_searchModelName, $this->_searchModel) = \Yii::mapGet($this->_searchModelName);
        $sModel = $this->_searchModel;
        /**
         * 初始化search model 的属性，将需要过滤的属性添加到search model的类属性中。
         *  $searchModel 		= new $this->_searchModelName;
         *  $searchModel->attributes();
         *	上面的获取的属性，就会有下面添加的属性了。
         *  将产品同步到搜索表的时候，就会把这些字段也添加进去.
         */
        $filterAttr = Yii::$service->search->filterAttr;
        if (is_array($filterAttr) && !empty($filterAttr)) {
            $sModel::$_filterColumns = $filterAttr;
        }
    }
    
    protected $_searchLang;
    
    protected function getActiveLangConfig()
    {
        if (!$this->_searchLang) {
            $langArr = Yii::$app->store->get('mutil_lang');
            foreach ($langArr as $one) {
                if ($one['search_engine'] == 'mongoSearch') {
                    $langCode = $one['lang_code'];
                    $langName = isset($this->searchLanguages[$langCode]) ? $this->searchLanguages[$langCode] : $this->searchLanguages['en'];
                    $this->_searchLang[$langCode] = $langName;
                }
            }
        }
        return $this->_searchLang;
    }

    /**
     * 创建索引.
     */
    protected function actionInitFullSearchIndex()
    {
        $sModel = $this->_searchModel;
        $config1 = [];
        $config2 = [];
        //var_dump($this->searchIndexConfig);exit;
        if (is_array($this->searchIndexConfig) && (!empty($this->searchIndexConfig))) {
            foreach ($this->searchIndexConfig as $column => $weight) {
                $config1[$column] = 'text';
                $config2['weights'][$column] = (int) $weight;
            }
        }

        //$langCodes = Yii::$service->fecshoplang->allLangCode;
        $searchLang = $this->getActiveLangConfig();
        if (!empty($searchLang) && is_array($searchLang)) {
            foreach ($searchLang as $langCode => $mongoSearchLangName) {
                /*
                 * 如果语言不存在，譬如中文，mongodb的fullSearch是不支持中文的，
                 * 这种情况是不能搜索的。
                 * 能够进行搜索的语言列表：https://docs.mongodb.com/manual/reference/text-search-languages/#text-search-languages
                 */
                if ($mongoSearchLangName) {
                    $sModel::$_lang = $langCode;
                    $searchModel = new $this->_searchModelName();
                    $colltionM = $searchModel->getCollection();
                    $config2['default_language'] = $mongoSearchLangName;
                    $colltionM->createIndex($config1, $config2);
                }
            }
        }
    }
    // 
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

    /**
     * @param $product_ids |　Array ，里面的子项是MongoId类型。
     * 将产品表的数据同步到各个语言对应的搜索表中。
     */
    protected function actionSyncProductInfo($product_ids, $numPerPage)
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
                    //$langCodes = Yii::$service->fecshoplang->allLangCode;
                    //if(!empty($langCodes) && is_array($langCodes)){
                    //	foreach($langCodes as $langCodeInfo){
                    $one_name = $one['name'];
                    $one_description = $one['description'];
                    $one_short_description = $one['short_description'];
                    $searchLang = $this->getActiveLangConfig();
                    if (!empty($searchLang) && is_array($searchLang)) {
                        foreach ($searchLang as $langCode => $mongoSearchLangName) {
                            $sModel::$_lang = $langCode;
                            $searchModel = $this->_searchModel->findOne(['product_id' => $one['product_id']]);
                            
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
                                echo  $errors;
                                //return false;
                            }
                        }
                    }
                }
            }
        }
        //echo "MongoSearch sync done ... \n";
        
        return true;
    }

    /**
     * @param $nowTimeStamp | int
     * 批量更新过程中，被更新的产品都会更新字段sync_updated_at
     * 删除xunSearch引擎中sync_updated_at小于$nowTimeStamp的字段.
     */
    protected function actionDeleteNotActiveProduct($nowTimeStamp)
    {
        $sModel = $this->_searchModel;
        echo "begin delete Mongodb Search Date \n";
        //$langCodes = Yii::$service->fecshoplang->allLangCode;
        //if(!empty($langCodes) && is_array($langCodes)){
        //	foreach($langCodes as $langCodeInfo){
        $searchLang = $this->getActiveLangConfig();
        if (!empty($searchLang) && is_array($searchLang)) {
            foreach ($searchLang as $langCode => $mongoSearchLangName) {
                $sModel::$_lang = $langCode;
                // 更新时间方式删除。
                $this->_searchModel->deleteAll([
                    '<', 'sync_updated_at', (int) $nowTimeStamp,
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

    protected function actionRemoveByProductId($product_id)
    {
        $sModel = $this->_searchModel;
        $searchLang = $this->getActiveLangConfig();
        if (!empty($searchLang) && is_array($searchLang)) {
            foreach ($searchLang as $langCode => $mongoSearchLangName) {
                $sModel::$_lang = $langCode;
                $this->_searchModel->deleteAll([
                    '_id' => $product_id,
                ]);
            }
        }

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
    protected function actionGetSearchProductColl($select, $where, $pageNum, $numPerPage, $product_search_max_count)
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
            //var_dump($filter);exit;
            $collection = $this->fullTearchText($filter);
        }
        $collection['coll'] = Yii::$service->category->product->convertToCategoryInfo($collection['coll']);
        //var_dump($collection);
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
        if (!isset($where['status'])) {
            $where['status'] = Yii::$service->product->getEnableStatus();
        }
        $product_search_max_count = $filter['product_search_max_count'] ? $filter['product_search_max_count'] : 1000;

        $select = $filter['select'];
        $pageNum = $filter['pageNum'];
        $numPerPage = $filter['numPerPage'];
        $orderBy = $filter['orderBy'];
        //
        /*
         * 说明：1.'search_score'=>['$meta'=>"textScore" ，这个是text搜索为了排序，
         *		    详细参看：https://docs.mongodb.com/manual/core/text-search-operators/
         *		 2. sort排序：search_score是全文搜索匹配后的得分，score是product表的一个字段，这个字段可以通过销售量或者其他作为参考设置。
         */
        $sModel::$_lang = Yii::$service->store->currentLangCode;
        //$search_data = $this->_searchModel->getCollection();

        //$mongodb = Yii::$app->mongodb;
        //$search_data = $mongodb->getCollection('full_search_product_en')

        $search_data = $this->_searchModel->getCollection()->find(
            $where,
            ['search_score'=>['$meta'=>'textScore'], 'id' => 1, 'spu'=> 1, 'score' => 1,'product_id' => 1],
            [
                'sort' => ['search_score'=> ['$meta'=> 'textScore'], 'score' => -1],
                'limit'=> $product_search_max_count,
            ]
        );
        //var_dump($search_data);exit;
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
            $productIds[] = $d['product_id'];
        }
        
        $productIds = array_slice($productIds, $offset, $limit);
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
     * @param $filter_attr | String 需要进行统计的字段名称
     * @propertuy $where | Array  搜索条件。这个需要些mongodb的搜索条件。
     * 得到的是个属性，以及对应的个数。
     * 这个功能是用于前端分类侧栏进行属性过滤。
     */
    protected function actionGetFrontSearchFilter($filter_attr, $where)
    {
        if (empty($where)) {
            return [];
        }
        $group['_id'] = '$'.$filter_attr;
        $group['count'] = ['$sum'=> 1];
        $project = [$filter_attr => 1];
        $pipelines = [
            [
                '$match'    => $where,
            ],
            [
                '$project'    => $project,
            ],
            [
                '$group'    => $group,
            ],
        ];
        $sModel = $this->_searchModel;
        $sModel::$_lang = Yii::$service->store->currentLangCode;
        $filter_data = $this->_searchModel->getCollection()->aggregate($pipelines);

        return $filter_data;
    }
}
