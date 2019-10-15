<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

use fecshop\services\Service;
use yii\db\Query;
use Yii;

/**
 * Product ProductMysqldb Service 未开发。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductMysqldb extends Service implements ProductInterface
{
    public $numPerPage = 20;
    
    protected $_productModelName = '\fecshop\models\mysqldb\Product';

    protected $_productModel;
    
    protected $_categoryProductModelName = '\fecshop\models\mysqldb\CategoryProduct';

    protected $_categoryProductModel;
    
    protected $serializeAttrs = [
        'name',
        'meta_title',
        'tier_price',
        
        'meta_keywords',
        'meta_description',
        'image',
        'description',
        'short_description',
        //'custom_option',
        'remark',
        'relation_sku',
        'buy_also_buy_sku',
        
        'see_also_see_sku',
        'attr_group_info',
        'reviw_rate_star_average_lang',
        'review_count_lang',
        'reviw_rate_star_info',
        'reviw_rate_star_info_lang',
    ];
    
    public function init()
    {
        parent::init();
        list($this->_productModelName, $this->_productModel) = \Yii::mapGet($this->_productModelName);
        list($this->_categoryProductModelName, $this->_categoryProductModel) = \Yii::mapGet($this->_categoryProductModelName);
        
    }
    
    public function getPrimaryKey()
    {
        return 'id';
    }
    
    public function serviceStorageName()
    {
        return 'mysqldb';
    }

    /**
     * 得到分类激活状态的值
     */
    public function getEnableStatus()
    {
        $model = $this->_productModel;
        return $model::STATUS_ENABLE;
    }
    
    public function getByPrimaryKey($primaryKey = null)
    {
        if ($primaryKey) {
            $one = $this->_productModel->findOne($primaryKey);
            return $this->unserializeData($one) ;
        } else {
            return new $this->_productModelName();
        }
    }
    
    /**
     * @param $ids | Array
     * 通过产品ids得到产品sku
     */
    public function getSkusByIds($ids)
    {
        $skus = [];
        $_id = $this->getPrimaryKey();
        if (!empty($ids) && is_array($ids)) {
            $ids_ob_arr = [];
            foreach ($ids as $id) {
                $ids_ob_arr[] = $id;
            }
            $filter = [
                'where'            => [
                    ['in', $_id, $ids_ob_arr],

                ],
                'asArray' => true,
            ];
            $coll = $this->coll($filter);
            $data = $coll['coll'];
            if (!empty($data) && is_array($data)) {
                foreach ($data as $one) {
                    $skus[(string) $one[$_id]] = $one['sku'];
                }
            }
        }

        return $skus;
    }

    /**
     * @param $sku|array
     * @param $returnArr|bool 返回的数据是否是数组格式，如果设置为
     *		false，则返回的是对象数据
     * @return array or Object
     *               通过sku 获取产品，一个产品
     */
    public function getBySku($sku, $returnArr = true)
    {
        if ($sku) {
            if ($returnArr) {
                $product = $this->_productModel->find()->asArray()
                    ->where(['sku' => $sku])
                    ->one();
            } else {
                $product = $this->_productModel->findOne(['sku' => $sku]);
            }
            $primaryKey = $this->getPrimaryKey();
            if (isset($product[$primaryKey]) && !empty($product[$primaryKey])) {
                return $this->unserializeData($product) ;
            }
        }
    }

    /**
     * @param $spu|array
     * @param $returnArr|bool 返回的数据是否是数组格式，如果设置为
     *		false，则返回的是对象数据
     * @return array or Object
     *               通过spu 获取产品数组
     */
    public function getBySpu($spu, $returnArr = true)
    {
        if ($spu) {
            if ($returnArr) {
                return $this->_productModel->find()->asArray()
                    ->where(['spu' => $spu])
                    ->all();
            } else {
                return $this->_productModel->find()
                    ->where(['spu' => $spu])
                    ->all();
            }
        }
    }

    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter = '')
    {
        $query = $this->_productModel->find();
        // 对于存在select的查询，自动加上主键值。
        if (isset($filter['select']) && is_array($filter['select'])) {
            $primaryKey = $this->getPrimaryKey();
            if (!in_array($primaryKey, $filter['select'])) {
                $filter['select'][] = $primaryKey;
            }
        }
        //var_dump($filter['select']);exit; 
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        
        $coll = $query->all();
        $arr = [];
        foreach ($coll as $one) {
            $arr[] = $this->unserializeData($one) ;
        }
        return [
            'coll' => $arr,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
    
    public function spuCollData($select, $spuAttrArr, $spu)
    {
        $select[] = 'attr_group_info';
        //var_dump($select);exit;
        $filter = [
            'select'    => $select,
            'where'            => [
                ['spu' => $spu],
            ],
            'asArray' => true,
            'fetchAll' => true,
        ];
        
        $data = Yii::$service->product->coll($filter);
        
        $coll = $data['coll'];
        $arr = [];
        foreach ($coll as $one) {
            $ar = [];
            foreach ($one as $k=>$v) {
                if ($k != 'attr_group_info') {
                    $ar[$k] = $v;
                } else {
                    if (is_array($v)) {
                        foreach ($v as $k2=>$v2) {
                            $ar[$k2] = $v2;
                        }
                    }
                }
            }
            $arr[] = $ar;
        }
        
        return $arr;
    }

    /**
     * 
     */
    public function apicoll()
    {
        $data = $this->coll();
        $coll = $data['coll'];
        $count = $data['count'];
        $collArr = [];
        if (is_array($coll)) {
            foreach ($coll as $one) {
                $arr = [];
                foreach ($one as $k=>$v) {
                    if ($k != 'attr_group_info') {
                        $arr[$k] = $v;
                    } else if (is_array($v)){
                        foreach ($v as $spu_attr=>$spu_val) {
                            $arr[$spu_attr] = $spu_val;
                        }
                    }
                    
                }
                $collArr[] = $arr;
            }
        }
        
        return [
            'coll' => $collArr,
            'count'=> $count,
        ];
    }

    /**
     * @param $primaryKey | String 主键
     * @return  array ，和getByPrimaryKey()的不同在于，该方式不走active record，因此可以获取产品的所有数据的。
     */
    public function apiGetByPrimaryKey($primaryKey)
    {
        $collection = $this->_productModel->find()->getCollection();
        $cursor = $collection->findOne(['_id' => $primaryKey]);
        $arr = [];
        foreach ($cursor as $k => $v) {
            $arr[$k] = $v;
        }

        return $arr;
    }

    /**
     * @param $product_one | String 产品数据数组。这个要和mongodb里面保存的产品数据格式一致。
     * 通过api保存产品
     */
    public function apiSave($product_one)
    {
        $this->save($product_one);

        return true;
    }

    /**
     * @param $primaryKey | String
     * 通过api删除产品
     */
    public function apiDelete($primaryKey)
    {
        $this->remove($primaryKey);

        return true;
    }

    /*
     * @param $filter | Array ， example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
     *          ['>','price',1],
     *          ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	    'asArray' => true,
     * ]
     * 得到总数。
     */
    public function collCount($filter = [])
    {
        $query = $this->_productModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return $query->count();
    }

    /**
     * @param  $product_id_arr | Array
     * @param  $category_id | String
     * 在给予的产品id数组$product_id_arr中，找出来那些产品属于分类 $category_id
     * 该功能是后台分类编辑中，对应的分类产品列表功能
     * 也就是在当前的分类下，查看所有的产品，属于当前分类的产品，默认被勾选。
     */
    public function getCategoryProductIds($product_id_arr, $category_id)
    {
        $category_product_ids = $this->getProductIdsByCategoryId($category_id);
        $product_ids = array_intersect($category_product_ids, $product_id_arr);
        
        $id_arr = [];
        if (is_array($product_ids) && !empty($product_ids)) {
            $query = $this->_productModel->find()->asArray();
            $query->where(['in', $this->getPrimaryKey(), $product_ids]);
            $data = $query->all();
            if (is_array($data) && !empty($data)) {
                foreach ($data as $one) {
                    $id_arr[] = $one[$this->getPrimaryKey()];
                }
            }
        }
        
        return $id_arr;
    }

    /**
     * @param $attr_group | String
     * 根据产品的属性组名，得到属性数组，然后将属性数组附加到Product(model)的属性中。
     */
    public function addGroupAttrs($attr_group)
    {
        $attrInfo = Yii::$service->product->getGroupAttrInfo($attr_group);
        if (is_array($attrInfo) && !empty($attrInfo)) {
            $attrs = array_keys($attrInfo);
            $this->_productModel->addCustomProductAttrs($attrs);
        }
    }
    // 进行spu对应的属性进行检查，相同spu的产品，他们的sku属性不能全部相同，否则返回false
    public function checkSpuAttrUnique($spuAttrArr, $product_colls)
    {
        if (is_array($product_colls)) {
            foreach ($product_colls as $sar) {
                $sar_attr_group_info = unserialize($sar['attr_group_info']);
                $si = 0;
                if (is_array($sar_attr_group_info)) {
                    foreach ($spuAttrArr as $sar_key => $sar_val) {
                        if (!isset($sar_attr_group_info[$sar_key])) {
                            $si = 1;
                        } else if ( isset($sar_attr_group_info[$sar_key]) && $sar_attr_group_info[$sar_key] != $sar_val) {
                            
                            $si = 1;
                        }
                    }
                }
                if ($si  == 0) {
                    Yii::$service->helper->errors->add('product Spu of the same,  Spu attributes cannot be the same');

                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * @param $one|array , 产品数据数组
     * @param $originUrlKey|string , 产品的原来的url key ，也就是在前端，分类的自定义url。
     * 保存产品（插入和更新），以及保存产品的自定义url
     * 如果提交的数据中定义了自定义url，则按照自定义url保存到urlkey中，如果没有自定义urlkey，则会使用name进行生成。
     */
    public function save($one, $originUrlKey = 'catalog/product/index')
    {
        if (!$this->initSave($one)) {
            return false;
        }
        $url_key = isset($one['url_key']) ? $one['url_key'] : ''; 
        unset($one['url_key']);
        
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        // 得到group spu attr
        $attr_group = $one['attr_group'];
        $groupSpuAttrs = Yii::$service->product->getGroupSpuAttr($attr_group);
        $spuAttrArr = [];
        if (is_array($groupSpuAttrs)) {
            foreach ($groupSpuAttrs as $groupSpuOne) {
                $spuAttrArr[$groupSpuOne['name']] = $one[$groupSpuOne['name']];
            }
        }
        
        if ($primaryVal) {
            $model = $this->_productModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('Product {primaryKey} is not exist', ['primaryKey'=>$this->getPrimaryKey()]);

                return false;
            }

            //验证sku 是否重复
            $product_one = $this->_productModel->find()->asArray()->where([
                '<>', $this->getPrimaryKey(), $primaryVal,
            ])->andWhere([
                'sku' => $one['sku'],
            ])->one();
            if ($product_one['sku']) {
                Yii::$service->helper->errors->add('Product Sku is exist，please use other sku');

                return false;
            }
            // spu 下面的各个sku的spu属性不能相同
            if (!empty($spuAttrArr)) {
                $product_colls = $this->_productModel->find()->asArray()->where([
                    '<>', $this->getPrimaryKey(), $primaryVal,
                ])->andWhere([
                    'spu' => $one['spu'],
                ])->all();
                /////////////////
                if (!$this->checkSpuAttrUnique($spuAttrArr, $product_colls)) {
                    return false;
                }
            }
        } else {
            
            $model = new $this->_productModelName();
            $model->created_at = time();
            $created_user_id = Yii::$app->user->identity->id;
            $model->created_user_id = $created_user_id ;
            //$primaryVal = new \MongoDB\BSON\ObjectId();
            //$model->{$this->getPrimaryKey()} = $primaryVal;
            //验证sku 是否重复
            
            $product_one = $this->_productModel->find()->asArray()->where([
                'sku' => $one['sku'],
            ])->one();
            
            if ($product_one['sku']) {
                Yii::$service->helper->errors->add('Product Sku is exist，please use other sku');

                return false;
            }
            
            // spu 下面的各个sku的spu属性不能相同
            if (!empty($spuAttrArr)) {
                $product_colls = $this->_productModel->find()->asArray()->where([
                    'spu' => $one['spu'],
                ])->all();
                /////////////////
                if (!$this->checkSpuAttrUnique($spuAttrArr, $product_colls)) {
                    return false;
                }
            }
            
        }
        
        $model->updated_at = time();
        // 计算出来产品的最终价格。
        $one['final_price'] = Yii::$service->product->price->getFinalPrice($one['price'], $one['special_price'], $one['special_from'], $one['special_to']);
        $one['score'] = (int) $one['score'];
        unset($one['id']);
        unset($one['custom_option']);
        /**
         * 如果 $one['custom_option'] 不为空，则计算出来库存总数，填写到qty
         */
        
        //if (is_array($one['custom_option']) && !empty($one['custom_option'])) {
        //    $custom_option_qty = 0;
        //    foreach ($one['custom_option'] as $co_one) {
        //        $custom_option_qty += $co_one['qty'];
        //    }
        //    $one['qty'] = $custom_option_qty;
        //}
        
        
        /**
         * 保存产品
         */
        $one = $this->serializeSaveData($one);
        $saveStatus = Yii::$service->helper->ar->save($model, $one);
        $product_id = $model->{$this->getPrimaryKey()};
        // 保存分类
        
        $this->updateProductCategory($one['category'], $product_id);
        // 自定义url部分
        if ($originUrlKey) {
            $originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $product_id;
            $originUrlKey = $url_key;
            $defaultLangTitle = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['name'], 'name');
            $urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle, $originUrl, $originUrlKey);
            $model->url_key = $urlKey;
             
            $model->save();
        }
        
        
        
        /**
         * 更新产品库存。
         */
        Yii::$service->product->stock->saveProductStock($product_id, $one);
        /**
         * 更新产品信息到搜索表。
         */
         
        Yii::$service->search->syncProductInfo([$product_id]);
        
        return $model;
    }
    
    
    /**
     * @param $one|array , 产品数据数组
     * 用于将mongodb的数据，同步到mysql中 
     */
    public function sync($one)
    {
        if (!$this->initSave($one)) {
            return false;
        }
        $url_key = isset($one['url_key']) ? $one['url_key'] : ''; 
        unset($one['url_key']);
        $defaultLangTitle = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['name'], 'name');
        $product_one = $this->_productModel->find()->where([
            'sku' => $one['sku'],
        ])->one();
        if ($product_one['sku']) {
            $model = $product_one;
        } else {
            $model = new $this->_productModelName();
            $model->created_at = time();
        }
        // 保存mongodb表中的产品id到字段origin_mongo_id
        $model->origin_mongo_id = $one['_id'];
        $model->updated_at = time();
        // 计算出来产品的最终价格。
        $one['final_price'] = Yii::$service->product->price->getFinalPrice($one['price'], $one['special_price'], $one['special_from'], $one['special_to']);
        $one['score'] = (int) $one['score'];
        unset($one['_id']);
        unset($one['custom_option']);
        /**
         * 如果 $one['custom_option'] 不为空，则计算出来库存总数，填写到qty
         */
        //if (is_array($one['custom_option']) && !empty($one['custom_option'])) {
        //    $custom_option_qty = 0;
        //    foreach ($one['custom_option'] as $co_one) {
        //        $custom_option_qty += $co_one['qty'];
        //    }
        //    $one['qty'] = $custom_option_qty;
        //}
        
        /**
         * 保存产品
         */
        $one = $this->serializeSaveData($one);
        $saveStatus = Yii::$service->helper->ar->save($model, $one);
        $product_id = $model->{$this->getPrimaryKey()};
        // 保存分类
        
        $this->syncProductCategory($one['category'], $product_id);
        // 自定义url部分
        $originUrl = 'catalog/product/index' . '?' . $this->getPrimaryKey() .'='. $product_id;
        $originUrlKey = $url_key;
        //var_dump([$defaultLangTitle, $originUrl, $originUrlKey]);
        //echo $defaultLangTitle;
        $urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle, $originUrl, $originUrlKey);
        $model->url_key = $urlKey;
        $model->save();
        /**
         * 更新产品库存。
         */
        Yii::$service->product->stock->saveProductStock($product_id, $one);
        /**
         * 更新产品信息到搜索表。
         */
        Yii::$service->search->syncProductInfo([$product_id]);
        
        return $model;
    }
    /**
     * @param $category_ids | array,  mongodb表中的产品对应的分类id（分类id是mongo表的，在下面的代码可以看到，需要转换成mysql的categoryId）
     * @param $product_id | int, mysql 表中的产品id
     */
    protected function syncProductCategory($category_ids, $product_id)
    {
        if (!is_array($category_ids)) {
            return ;
        }
        Yii::$service->category->changeToMysqlStorage();
        $one = $this->_categoryProductModel->deleteAll([
            'product_id' => $product_id,
        ]);
        $categoryPrimaryKey = Yii::$service->category->getPrimaryKey();
        foreach ($category_ids as $mongo_category_id) {
            $category_id = '';
            // 通过mongodb中的categoryId，查找到对应的mysql中的categoryId
            $category = Yii::$service->category->findOne(['origin_mongo_id' => $mongo_category_id]);
            if ($category[$categoryPrimaryKey]) {
                $category_id = $category[$categoryPrimaryKey];
            }
            $m = new $this->_categoryProductModelName;
            $m->product_id = $product_id;
            $m->category_id = $category_id;
            $m->created_at = time();
            $m->save();
        }
        
        return ;
    }
    
    // 保存的数据进行serialize序列化
    protected function serializeSaveData($one) 
    {
        // 得到
        $attr_group = $one['attr_group'];
        $groupAttrs = Yii::$service->product->getGroupAttr($attr_group);
        $groupArr = [];
        if (is_array($one['attr_group_info']) && !empty($one['attr_group_info'])) {
            $groupArr = $one['attr_group_info'];
        }
        foreach ($one as $k => $v) {
            if (in_array($k, $this->serializeAttrs)) {
                $one[$k] = serialize($v);
            }
            if (is_array($groupAttrs) && in_array($k, $groupAttrs)) {
                $groupArr[$k] = $v;
                unset($one[$k]);
            }
        }
        
        $one['attr_group_info'] = serialize($groupArr);
        return $one;
    }
    
    // 保存的数据进行serialize序列化
    protected function unserializeData($one, $withCategory = false) 
    {
        
        if (!is_array($one) && !is_object($one)) {
            return $one;
        }
        foreach ($one as $k => $v) {
            if (in_array($k, $this->serializeAttrs)) {
                $one[$k] = unserialize($v);
            }
        }
        if ($withCategory) {
            $one['category'] = $this->getCategoryIdsByProductId($one['id']);
        }
        return $one;
    }
    
    public function getCategoryIdsByProductId($product_id)
    {
        if (empty($product_id)) {
            return [];
        }
        $coll = $this->_categoryProductModel->find()
            ->asArray()
            ->where([
                'product_id' => $product_id
            ])->all();
        $arr = [];
        foreach ($coll as $one) {
            $arr[] = (int)$one['category_id'];
        }
        
        return $arr;
    }
    
    public function getProductIdsByCategoryId($category_id)
    {
        $coll = $this->_categoryProductModel->find()
            ->asArray()
            ->where([
                'category_id' => $category_id
            ])->all();
        $arr = [];
        foreach ($coll as $one) {
            $arr[] = $one['product_id'];
        }
        
        return $arr;
    }

    /**
     * @param $one|array
     * 对保存的数据进行数据验证
     * sku  spu   默认语言name ， 默认语言description不能为空。
     */
    protected function initSave(&$one)
    {
        $primaryKey = $this->getPrimaryKey();
        $PrimaryVal = 1;
        if (!isset($one[$primaryKey]) || !$one[$primaryKey]) {
            $PrimaryVal = 0;
        }
        if (!$PrimaryVal && (!isset($one['sku']) || empty($one['sku']))) {
            Yii::$service->helper->errors->add('sku must exist');

            return false;
        }
        if (!$PrimaryVal && (!isset($one['spu']) || empty($one['spu']))) {
            Yii::$service->helper->errors->add('spu must exist');

            return false;
        }
        $defaultLangName = \Yii::$service->fecshoplang->getDefaultLangAttrName('name');
        if ($PrimaryVal && $one['name'] && empty($one['name'][$defaultLangName])) {
            Yii::$service->helper->errors->add('name {default_lang_name} can not empty', ['default_lang_name' => $defaultLangName]);

            return false;
        }
        if (!isset($one['name'][$defaultLangName]) || empty($one['name'][$defaultLangName])) {
            Yii::$service->helper->errors->add('name {default_lang_name} can not empty', ['default_lang_name' => $defaultLangName]);

            return false;
        }
        $defaultLangDes = \Yii::$service->fecshoplang->getDefaultLangAttrName('description');
        if ($PrimaryVal && $one['description'] && empty($one['description'][$defaultLangDes])) {
            Yii::$service->helper->errors->add('description {default_lang_des} can not empty', ['default_lang_des' => $defaultLangDes]);

            return false;
        }
        if (!isset($one['description'][$defaultLangDes]) || empty($one['description'][$defaultLangDes])) {
            Yii::$service->helper->errors->add('description {default_lang_des} can not empty', ['default_lang_des' => $defaultLangDes]);

            return false;
        }
        //if (is_array($one['custom_option']) && !empty($one['custom_option'])) {
        //    $new_custom_option = [];
        //    foreach ($one['custom_option'] as $k=>$v) {
        //        $k = preg_replace('/[^A-Za-z0-9\-_]/', '', $k);
        //        $new_custom_option[$k] = $v;
        //    }
        //    $one['custom_option'] = $new_custom_option;
        //}

        return true;
    }

    /**
     * @param $ids | Array or String
     * 删除产品，如果ids是数组，则删除多个产品，如果是字符串，则删除一个产品
     * 在产品产品的同时，会在url rewrite表中删除对应的自定义url数据。
     */
    public function remove($ids)
    {
        if (empty($ids)) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids)) {
            $removeAll = 1;
            foreach ($ids as $id) {
                $model = $this->_productModel->findOne($id);
                if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                    $url_key = $model['url_key'];
                    // 删除在重写url里面的数据。
                    Yii::$service->url->removeRewriteUrlKey($url_key);
                    // 删除在搜索表（各个语言）里面的数据
                    Yii::$service->search->removeByProductId($id);
                    Yii::$service->product->stock->removeProductStock($id);
                    $model->delete();
                //$this->removeChildCate($id);
                } else {
                    Yii::$service->helper->errors->add('Product Remove Errors:ID:{id} is not exist', ['id'=>$id]);
                    $removeAll = 0;
                }
                $this->removeCategoryProductRelationByProductId($id);
            }
            if (!$removeAll) {
                return false;
            }
        } else {
            $id = $ids;
            $model = $this->_productModel->findOne($id);
            if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                $url_key = $model['url_key'];
                // 删除在重写url里面的数据。
                Yii::$service->url->removeRewriteUrlKey($url_key);
                // 删除在搜索里面的数据
                Yii::$service->search->removeByProductId($model[$this->getPrimaryKey()]);
                Yii::$service->product->stock->removeProductStock($id);
                $model->delete();
                $this->removeCategoryProductRelationByProductId($id);
            //$this->removeChildCate($id);
            } else {
                Yii::$service->helper->errors->add('Product Remove Errors:ID:{id} is not exist.', ['id'=>$id]);

                return false;
            }
        }

        return true;
    }
    
    public function updateProductCategory($category_ids, $product_id)
    {
        if (!is_array($category_ids)) {
            return ;
        }
        $one = $this->_categoryProductModel->deleteAll([
            'product_id' => $product_id,
        ]);
        foreach ($category_ids as $category_id) {
            $m = new $this->_categoryProductModelName;
            $m->product_id = $product_id;
            $m->category_id = $category_id;
            $m->created_at = time();
            $m->save();
        }
        
        return ;
    }
    
    /**
     * @param $category_id | String  分类的id的值
     * @param $addCateProductIdArr | Array 分类中需要添加的产品id数组，也就是给这个分类增加这几个产品。
     * @param $deleteCateProductIdArr | Array 分类中需要删除的产品id数组，也就是在这个分类下面去除这几个产品的对应关系。
     * 这个函数是后台分类编辑功能中使用到的函数，在分类中可以一次性添加多个产品，也可以删除多个产品，产品和分类是多对多的关系。
     */
    public function addAndDeleteProductCategory($category_id, $addCateProductIdArr, $deleteCateProductIdArr)
    {
        
        // 删除
        if (is_array($deleteCateProductIdArr) && !empty($deleteCateProductIdArr) && $category_id) {
            $this->_categoryProductModel->deleteAll([
                'and',
                ['category_id' => $category_id],
                ['in','product_id',$deleteCateProductIdArr]
            ]);
        }
        
        // 添加
        if (is_array($addCateProductIdArr) && !empty($addCateProductIdArr) && $category_id) {
            foreach ($addCateProductIdArr as $product_id) {
                $one = $this->_categoryProductModel->findOne([
                    'category_id' => $category_id,
                    'product_id' => $product_id,
                ]);
                if (!$one['id']) {
                    $m = new $this->_categoryProductModelName;
                    $m->product_id = $product_id;
                    $m->category_id = $category_id;
                    $m->created_at = time();
                    $m->save();
                }
            }
        }
        
        return true;
    }

    /**
     * 通过where条件 和 查找的select 字段信息，得到产品的列表信息，
     * 这里一般是用于前台的区块性的不分页的产品查找。
     * 结果数据没有进行进一步处理，需要前端获取数据后在处理。
     */
    public function getProducts($filter)
    {
        $where = $filter['where'];
        if (empty($where)) {
            return [];
        }
        $select = $filter['select'];
        if (!in_array('id', $select)) {
            $select[] = 'id';
        }
        $query = $this->_productModel->find()->asArray();
        $query->where($where);
        $query->andWhere(['status' => $this->getEnableStatus()]);
        if (is_array($select) && !empty($select)) {
            $query->select($select);
        }

        $coll = $query->all();
        $arr = [];
        foreach ($coll as $one) {
            $arr[] = $this->unserializeData($one) ;
        }
        
        return $arr;
    }
    /**
     * 得到分类页面的产品列表
     * $filter 参数的详细，参看函数 getFrontCategoryProductsGroupBySpu($filter);
     */
    public function getFrontCategoryProducts($filter){
        if (Yii::$service->product->productSpuShowOnlyOneSku) {
            
            return $this->getFrontCategoryProductsGroupBySpu($filter);
        } else {
            
            return $this->getFrontCategoryProductsAll($filter);
        }
    }
    /**
     * 得到分类页面的产品（All）
     * $filter 参数的详细，参看函数 getFrontCategoryProductsGroupBySpu($filter);
     */
    public function getFrontCategoryProductsAll($filter){
        $where = $filter['where'];
        if (empty($where)) {
            return [];
        }
        if (!isset($where['status'])) {
            $where['status'] = $this->getEnableStatus();
        }
        // where条件处理
        if ($categoryId = $where['category']) {
            $productIds = $this->getProductIdsByCategoryId($categoryId);
            unset($where['category']);
            $arr = [];
            $whereArr = [
                ['in', 'id', $productIds]
            ];
            foreach ($where as $k=>$v) {
                if ($k == 'price' && is_array($v)) {  // 价格数据处理。
                    foreach ($v as $k1=>$v1) {
                        $fh = '';
                        if ($k1 == '$gte') $fh = '>=' ;
                        if ($k1 == '$gt') $fh = '>' ;
                        if ($k1 == '$lte') $fh = '<=' ;
                        if ($k1 == '$lt') $fh = '<' ;
                        $whereArr[] = [$fh, 'price', $v1];
                    }
                } else {
                    $whereArr[] = [$k =>$v];
                }
                
            }
            $where = $whereArr;
        }
        $orderBy = $filter['orderBy'];
        $pageNum = $filter['pageNum'];
        $numPerPage = $filter['numPerPage'];
        $select = $filter['select'];
        
        $filter = [
            'numPerPage' 	=> $numPerPage,
     		'pageNum'		    => $pageNum,
      		'orderBy'	        => $orderBy,
      		'where'			    => $where,
      	    'asArray'           => true,
        ];
        
        return $this->coll($filter);
    }
    
    
    /**
     * 相同spu下面的所有sku，只显示一个，取score值最高的那个显示
     *[
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
     * 得到分类下的产品，在这里需要注意的是：
     * 1.同一个spu的产品，有很多sku，但是只显示score最高的产品，这个score可以通过脚本取订单的销量（最近一个月，或者
     *   最近三个月等等），或者自定义都可以。
     * 2.结果按照filter里面的orderBy排序
     * 3.由于使用的是mongodb的aggregate(管道)函数，因此，此函数有一定的限制，就是该函数
     *   处理后的结果不能大约32MB，因此，如果一个分类下面的产品几十万的时候可能就会出现问题，
     *   这种情况可以用专业的搜索引擎做聚合工具。
     *   不过，对于一般的用户来说，这个不会成为瓶颈问题，一般一个分类下的产品不会出现几十万的情况。
     * 4.最后就得到spu唯一的产品列表（多个spu相同，sku不同的产品，只要score最高的那个）.
     */
    public function getFrontCategoryProductsGroupBySpu($filter)
    {
        $orderBy = $filter['orderBy'];
        $pageNum = $filter['pageNum'];
        $numPerPage = $filter['numPerPage'];
        $select = $filter['select'];
        
        $where = $filter['where'];
        if (empty($where)) {
            return [];
        }
        if (!isset($where['status'])) {
            $where['status'] = $this->getEnableStatus();
        }
        // where条件处理
        if ($categoryId = $where['category']) {
            $productIds = $this->getProductIdsByCategoryId($categoryId);
            unset($where['category']);
            $arr = [];
            $whereArr = [
                'and',
                ['in', 'id', $productIds]
            ];
            foreach ($where as $k=>$v) {
                if ($k == 'price' && is_array($v)) {  // 价格数据处理。
                    foreach ($v as $k1=>$v1) {
                        $fh = '';
                        if ($k1 == '$gte') $fh = '>=' ;
                        if ($k1 == '$gt') $fh = '>' ;
                        if ($k1 == '$lte') $fh = '<=' ;
                        if ($k1 == '$lt') $fh = '<' ;
                        $whereArr[] = [$fh, 'price', $v1];
                    }
                } else {
                    $whereArr[] = [$k =>$v];
                }
                
            }
            $where = $whereArr;
        }
        // spu 进行group
        $subQuery = $this->_productModel->find()
                    ->select($select)
                    ->where($where)
                    ->orderBy(['score' => SORT_DESC])
                    ->groupBy('spu')
                    ;
        // 总数    
        $product_total_count = $this->_productModel->find()
                    ->select($select)
                    ->where($where)
                    ->orderBy(['score' => SORT_DESC])
                    ->groupBy('spu')
                    ->count();
                    
        // 进行查询coll
        $products = (new Query())  //->select($field)
			->from(['product' => $subQuery]) // 在这里使用了子查询
            ->orderBy($orderBy)
            ->offset(($pageNum -1) * $numPerPage)
            ->limit($numPerPage)
			->createCommand()
            //->getRawSql();  //
            ->queryAll();
        foreach ($products as $k => $product) {
            $products[$k]['name'] = unserialize($product['name']);
            $products[$k]['image'] = unserialize($product['image']);
        }
        return [
            'coll' => $products,
            'count' => $product_total_count,
        ];
    }

    /**
     * @param $filter_attr | String 需要进行统计的字段名称
     * @propertuy $where | Array  搜索条件。这个需要些mongodb的搜索条件。
     * 得到的是个属性，以及对应的个数。
     * 这个功能是用于前端分类侧栏进行属性过滤。
     * @return 
         [
            ['_id' => 'white', 'count' => 3],
            ['_id' => 'multicolor', 'count' => 6],
            ['_id' => 'black', 'count' => 13],
        ]
     */
    public function getFrontCategoryFilter($filter_attr, $where)
    {
        if (empty($where)) {
            return [];
        }
        if (!isset($where['status'])) {
            $where['status'] = $this->getEnableStatus();
        }
        if (!$this->_productModel->hasAttribute($filter_attr)) {
            return [];
        }
        
        // where条件处理
        if ($categoryId = $where['category']) {
            $productIds = $this->getProductIdsByCategoryId($categoryId);
            unset($where['category']);
            $arr = [];
            $whereArr = [
                'and',
                ['in', 'id', $productIds]
            ];
            foreach ($where as $k=>$v) {
                $whereArr[] = [$k =>$v];
            }
            $where = $whereArr;
        }
        
        // 总数    
        $filter_data = $this->_productModel->find()
                    ->select($filter_attr.' as _id ,   COUNT(*) as count')
                    ->where($where)
                    ->groupBy($filter_attr)
                    ->all();
        
        return $filter_data;
    }
    
    
    

    /**
     * @param $spu | String
     * @param $avag_rate | Int ，平均评星
     * @param $count | Int ，评论次数
     * @param $lang_code | String ，语言简码
     * @param $avag_lang_rate | Int ，语言下平均评星
     * @param $lang_count | Int ， 语言下评论次数。
     * @param $rate_total_arr | Array, 各个评星对应的个数
     * @param $rate_lang_total_arr | Array, 该语言下各个评星对应的个数
     */
    public function updateProductReviewInfo($spu, $avag_rate, $count, $lang_code, $avag_lang_rate, $lang_count, $rate_total_arr, $rate_lang_total_arr)
    {
        $data = $this->_productModel->find()->where([
            'spu' => $spu,
        ])->all();
        if (!empty($data) && is_array($data)) {
            $attrName = 'reviw_rate_star_average_lang';
            $review_star_lang = Yii::$service->fecshoplang->getLangAttrName($attrName, $lang_code);
            $attrName = 'review_count_lang';
            $review_count_lang = Yii::$service->fecshoplang->getLangAttrName($attrName, $lang_code);
            $reviw_rate_star_info_lang = Yii::$service->fecshoplang->getLangAttrName('reviw_rate_star_info_lang', $lang_code);
            foreach ($data as $one) {
                $one['reviw_rate_star_average'] = $avag_rate;
                $one['review_count']            = $count;
                $a                              = $one['reviw_rate_star_average_lang'];
                //$a[$review_star_lang]           = $avag_lang_rate;
                $b                              = $one['review_count_lang'];
                //$b[$review_count_lang]          = $lang_count;
                $one['reviw_rate_star_average_lang'] = $a;
                $one['review_count_lang']           = $b;
                $one['reviw_rate_star_info']        = serialize($rate_total_arr);
                $c                                  = $one['reviw_rate_star_info_lang'];
                //$c[$reviw_rate_star_info_lang]      = $rate_lang_total_arr;
                $one['reviw_rate_star_info_lang']   = $c;
                $one->save();
            }
        }
    }
    
    public function updateProductFavoriteCount($product_id, $count)
    {
        $product = $this->_productModel->findOne($product_id);
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        if ($product[$productPrimaryKey]) {
            $product->favorite_count = $count;
            $product->save();
        }
    }

    public function updateAllScoreToZero(){
        return $this->_productModel->getCollection()->update([], ['score' => 0]);
    }
    
    
    public function removeCategoryProductRelationByProductId($product_id)
    {
        return $this->_categoryProductModel->deleteAll(['product_id' => $product_id]);
    }
    
    
    /**
     * 保存Excel上传文件的数据
     */
    public function excelSave($one, $originUrlKey = 'catalog/product/index')
    {
        $sku = $one['sku'];
        // 查询出来主键。
        $url_key = isset($one['url_key']) ? $one['url_key'] : ''; 
        unset($one['url_key']);
        
        $primaryKey = $this->getPrimaryKey();
        $productModel = $this->getBySku($sku);
        if (isset($productModel['sku']) && $productModel['sku']) {
            $one[$primaryKey] = $productModel[$primaryKey];
        }
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        // 得到group spu attr
        $attr_group = $one['attr_group'];
        $groupSpuAttrs = Yii::$service->product->getGroupSpuAttr($attr_group);
        $spuAttrArr = [];
        if (is_array($groupSpuAttrs)) {
            foreach ($groupSpuAttrs as $groupSpuOne) {
                $spuAttrArr[$groupSpuOne['name']] = $one[$groupSpuOne['name']];
            }
        }
        if ($primaryVal) {
            $model = $this->_productModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('Product {primaryKey} is not exist', ['primaryKey'=>$this->getPrimaryKey()]);

                return false;
            }
            //验证sku 是否重复
            $product_one = $this->_productModel->find()->asArray()->where([
                '<>', $this->getPrimaryKey(), $primaryVal,
            ])->andWhere([
                'sku' => $one['sku'],
            ])->one();
            if ($product_one['sku']) {
                Yii::$service->helper->errors->add('Product Sku is exist，please use other sku');

                return false;
            }
            // spu 下面的各个sku的spu属性不能相同
            if (!empty($spuAttrArr)) {
                $product_colls = $this->_productModel->find()->asArray()->where([
                    '<>', $this->getPrimaryKey(), $primaryVal,
                ])->andWhere([
                    'spu' => $one['spu'],
                ])->all();
                /////////////////
                if (!$this->checkSpuAttrUnique($spuAttrArr, $product_colls)) {
                    return false;
                }
            }
            // 多语言属性，如果您有其他的多语言属性，可以自行二开添加。
            $name =$model['name'];
            $meta_title = $model['meta_title'];
            $meta_keywords = $model['meta_keywords'];
            $meta_description = $model['meta_description'];
            $short_description = $model['short_description'];
            $description = $model['description'];
            if (is_array($one['name']) && !empty($one['name'])) {
                $one['name'] = array_merge((is_array($name) ? $name : []), $one['name']);
            }
            if (is_array($one['meta_title']) && !empty($one['meta_title'])) {
                $one['meta_title'] = array_merge((is_array($meta_title) ? $meta_title : []), $one['meta_title']);
            }
            if (is_array($one['meta_keywords']) && !empty($one['meta_keywords'])) {
                $one['meta_keywords'] = array_merge((is_array($meta_keywords) ? $meta_keywords : []), $one['meta_keywords']);
            }
            if (is_array($one['meta_description']) && !empty($one['meta_description'])) {
                $one['meta_description'] = array_merge((is_array($meta_description) ? $meta_description : []), $one['meta_description']);
            }
            if (is_array($one['short_description']) && !empty($one['short_description'])) {
                $one['short_description'] = array_merge((is_array($short_description) ? $short_description : []), $one['short_description']);
            }
            if (is_array($one['description']) && !empty($one['description'])) {
                $one['description'] = array_merge((is_array($description) ? $description : []), $one['description']);
            }
        } else {
            $model = new $this->_productModelName();
            $model->created_at = time();
            $created_user_id = Yii::$app->user->identity->id;
            $model->created_user_id = $created_user_id ;
            //$primaryVal = new \MongoDB\BSON\ObjectId();
            //$model->{$this->getPrimaryKey()} = $primaryVal;
            //验证sku 是否重复
            
            $product_one = $this->_productModel->find()->asArray()->where([
                'sku' => $one['sku'],
            ])->one();
            
            if ($product_one['sku']) {
                Yii::$service->helper->errors->add('Product Sku is exist，please use other sku');

                return false;
            }
            
            // spu 下面的各个sku的spu属性不能相同
            if (!empty($spuAttrArr)) {
                $product_colls = $this->_productModel->find()->asArray()->where([
                    'spu' => $one['spu'],
                ])->all();
                /////////////////
                if (!$this->checkSpuAttrUnique($spuAttrArr, $product_colls)) {
                    return false;
                }
            }
        }
        $model->updated_at = time();
        // 计算出来产品的最终价格。
        $one['final_price'] = Yii::$service->product->price->getFinalPrice($one['price'], $one['special_price'], $one['special_from'], $one['special_to']);
        $one['score'] = (int) $one['score'];
        unset($one['id']);
        unset($one['custom_option']);
        /**
         * 保存产品
         */
        
        $one = $this->serializeSaveData($one);
        $saveStatus = Yii::$service->helper->ar->save($model, $one);
        $product_id = $model->{$this->getPrimaryKey()};
        // 保存分类
        
        $this->updateProductCategory($one['category'], $product_id);
        // 自定义url部分
        if ($originUrlKey) {
            $originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $product_id;
            $originUrlKey = $url_key;
            $defaultLangTitle = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['name'], 'name');
            $urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle, $originUrl, $originUrlKey);
            $model->url_key = $urlKey;
             
            $model->save();
        }
        /**
         * 更新产品库存。
         */
        Yii::$service->product->stock->saveProductStock($product_id, $one);
        /**
         * 更新产品信息到搜索表。
         */
         
        Yii::$service->search->syncProductInfo([$product_id]);
        
        return $model;
    }
    
}
