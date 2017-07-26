<?php
/**
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
    public $searchLang;
    public $fuzzy = false;
    public $synonyms = false;
    
    protected $_productModelName = '\fecshop\models\mongodb\Product';
    protected $_productModel;
    protected $_searchModelName  = '\fecshop\models\xunsearch\Search';
    protected $_searchModel;
    
    public function init()
    {
        list($this->_productModelName,$this->_productModel) = \Yii::mapGet($this->_productModelName); 
        list($this->_searchModelName,$this->_searchModel) = \Yii::mapGet($this->_searchModelName); 
    }
    /**
     * 初始化xunSearch索引.
     */
    protected function actionInitFullSearchIndex()
    {
    }

    /**
     * 将产品信息同步到xunSearch引擎中.
     */
    protected function actionSyncProductInfo($product_ids, $numPerPage)
    {
        if (is_array($product_ids) && !empty($product_ids)) {
            $productPrimaryKey    = Yii::$service->product->getPrimaryKey();
            $xunSearchModel       = new $this->_searchModelName();
            $filter['select']     = $xunSearchModel->attributes();
            $filter['asArray']    = true;
            $filter['where'][]    = ['in', $productPrimaryKey, $product_ids];
            $filter['numPerPage'] = $numPerPage;
            $filter['pageNum']    = 1;
            $coll = Yii::$service->product->coll($filter);
            if (is_array($coll['coll']) && !empty($coll['coll'])) {
                foreach ($coll['coll'] as $one) {
                    $one_name = $one['name'];
                    $one_description = $one['description'];
                    $one_short_description = $one['short_description'];
                    if (!empty($this->searchLang) && is_array($this->searchLang)) {
                        foreach ($this->searchLang as $langCode => $langName) {
                            //echo $langCode;
                            $xunSearchModel = new $this->_searchModelName();
                            $xunSearchModel->_id = (string) $one['_id'];
                            $one['name'] = Yii::$service->fecshoplang->getLangAttrVal($one_name, 'name', $langCode);
                            $one['description'] = Yii::$service->fecshoplang->getLangAttrVal($one_description, 'description', $langCode);
                            $one['short_description'] = Yii::$service->fecshoplang->getLangAttrVal($one_short_description, 'short_description', $langCode);
                            $one['sync_updated_at'] = time();
                            //echo $one['name']."\n";
                            $serialize = true;
                            Yii::$service->helper->ar->save($xunSearchModel, $one, $serialize);
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
                if ($k != '$text') {
                    $XunSearchQuery->andWhere([$k => $v]);
                }
            }
        }
        $XunSearchQuery->orderBy(['score' => SORT_DESC]);
        $XunSearchQuery->limit($product_search_max_count);
        $XunSearchQuery->offset(0);
        $search_data = $XunSearchQuery->all();

        $data = [];
        foreach ($search_data as $one) {
            if (!isset($data[$one['spu']])) {
                $data[$one['spu']] = $one;
            }
        }

        $count = count($data);
        $offset = ($pageNum - 1) * $numPerPage;
        $limit = $numPerPage;
        $productIds = [];
        foreach ($data as $d) {
            $productIds[] = new \MongoDB\BSON\ObjectId($d['_id']);
        }

        $productIds = array_slice($productIds, $offset, $limit);

        if (!empty($productIds)) {
            $query = $this->_productModel->find()->asArray()
                    ->select($select)
                    ->where(['_id'=> ['$in'=>$productIds]]);
            $data = $query->all();
            /**
             * 下面的代码的作用：将结果按照上面in查询的顺序进行数组的排序，使结果和上面的搜索结果排序一致（_id）。
             */
            $s_data = [];
            foreach ($data as $one) {
                $_id = (string) $one['_id'];
                $s_data[$_id] = $one;
            }
            $return_data = [];
            foreach ($productIds as $product_id) {
                $return_data[] = $s_data[(string) $product_id];
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
        echo $sh;

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
            if($model){
                $model->delete();
            }
        }
    }
}
