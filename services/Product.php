<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Product Service is the component that you can get product info from it.
 *
 * @property \fecshop\services\Image | \fecshop\services\Product\Image $image image service or product image sub-service
 * @property \fecshop\services\product\Info $info product info sub-service
 * @property \fecshop\services\product\Stock $stock stock sub-service of product service
 *
 * @method getByPrimaryKey($primaryKey) get product model by primary key
 * @see \fecshop\services\Product::actionGetByPrimaryKey()
 * @method getEnableStatus() get enable status
 * @see \fecshop\services\Product::actionGetEnableStatus()
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Product extends Service
{
    /**
     * @var array 自定义的属性组配置数组
     */
    public $customAttrGroup;

    public $categoryAggregateMaxCount = 5000; // Yii::$service->product->categoryAggregateMaxCount;
    /**
      * 分类页面的产品，如果一个spu下面由多个sku同时在这个分类，
      * 那么，是否只显示一个sku（score最高），而不是全部sku
      * true： 代表只显示一个sku
      * false: 代表产品全部显示
      */
    public $productSpuShowOnlyOneSku = true;
    
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage; //    = 'ProductMysqldb';   // ProductMysqldb | ProductMongodb 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';

    /**
     * @var \fecshop\services\product\ProductInterface 根据 $storage 及 $storagePath 配置的 Product 的实现
     */
    protected $_product;

    /**
     * @var string 默认属性组名称
     */
    protected $_defaultAttrGroup = 'default';

    public function init()
    {
        parent::init();
        // init $this->productSpuShowOnlyOneSku
        $appName = Yii::$service->helper->getAppName();
        $productSpuShowOnlyOneSku = Yii::$app->store->get($appName.'_catalog','category_productSpuShowOnlyOneSku');
        $this->productSpuShowOnlyOneSku = ($productSpuShowOnlyOneSku == Yii::$app->store->enable) ? true : false;
        // 从数据库配置中得到值, 设置成当前service存储，是Mysqldb 还是 Mongodb
        $config = Yii::$app->store->get('service_db', 'category_and_product');
        $this->storage = 'ProductMysqldb';
        if ($config == Yii::$app->store->serviceMongodbName) {
            $this->storage = 'ProductMongodb';
        }
        $currentService = $this->getStorageService($this);
        $this->_product = new $currentService();
        // 从数据库配置数据，初始化customAttrGroup
        $this->initCustomAttrGroup();
    }
    // 动态更改为mongodb model
    public function changeToMongoStorage()
    {
        $this->storage     = 'ProductMongodb';
        $currentService = $this->getStorageService($this);
        $this->_product = new $currentService();
    }
    
    
    public function serviceStorageName()
    {
        return $this->_product->serviceStorageName();
    }
    // 动态更改为mongodb model
    public function changeToMysqlStorage()
    {
        $this->storage     = 'ProductMysqldb';
        $currentService = $this->getStorageService($this);
        $this->_product = new $currentService();
    }

    protected function actionGetEnableStatus()
    {
        return $this->_product->getEnableStatus();
    }
    // 从数据库配置数据，初始化customAttrGroup
    protected function initCustomAttrGroup()
    {
        $attrPrimaryKey =$this->attr->getPrimaryKey();
        $attrGroupPrimaryKey = $this->attrGroup->getPrimaryKey();
        $allGroupColl = $this->attrGroup->getActiveAllColl();
        // attr
        $allAttrColl = $this->attr->getActiveAllColl();
        $attrTypeColl = [];
        if ($allAttrColl) {
            foreach ($allAttrColl as $one) {
                $attrTypeColl[$one[$attrPrimaryKey]] = $one;
            }
        }
        $customAttrGroupArr = [];
        if ($allGroupColl) {
            foreach ($allGroupColl as $one) {
                $groupName = $one['name'];
                $attr_ids = $one['attr_ids'];
                if (!is_array($attr_ids) || empty($attr_ids)) {
                    continue;
                }
                $attr_ids = \fec\helpers\CFunc::array_sort($attr_ids, 'sort_order', 'desc');
                //var_dump($attr_ids);exit;
                foreach ($attr_ids as $attr_id_one) {
                    if (!is_array($attr_id_one)) {
                        continue;
                    }
                    $attr_id = $attr_id_one['attr_id'];
                    $attr_sort_order = $attr_id_one['sort_order'];
                    $attrOne = $attrTypeColl[$attr_id];
                    if (!$attrOne) {
                        continue;
                    }
                    $attrName = $attrOne['name'];
                    $attrType = $attrOne['attr_type'];
                    
                    $attrInfo = [
                        'dbtype'     => $attrOne['db_type'],
                        'name'       => $attrName,
                        'showAsImg'  => $attrOne['show_as_img'] == 1 ? true : false ,
                        'sort_order'   => $attr_sort_order,
                    ];
                    $displayType = $attrOne['display_type'];
                    $displayInfo = [];
                    if ($displayType == 'inputString-Lang') {
                        $displayInfo['type'] = 'inputString';
                        $displayInfo['lang'] = true;
                    } else {
                        $displayInfo['type'] = $displayType;
                    }
                    if (is_array($attrOne['display_data'])) {
                        $d_arr = [];
                        foreach ($attrOne['display_data'] as $o) {
                            if ($o['key']) {
                                $d_arr[] = $o['key'];
                            }
                        }
                        $displayInfo['data'] = $d_arr;
                    }
                    $attrInfo['display'] = $displayInfo;
                    
                    $customAttrGroupArr[$groupName][$attrType][$attrName] = $attrInfo;
                }
            }
        }
        $this->customAttrGroup = $customAttrGroupArr;
    }
    
    /**
     * 得到产品的所有的属性组。
     */
    protected function actionGetCustomAttrGroup()
    {
        $customAttrGroup = $this->customAttrGroup;
        $arr = array_keys($customAttrGroup);
        $arr[] = $this->_defaultAttrGroup;

        return $arr;
    }

    /**
     * @param $productAttrGroup|string
     * 得到这个产品属性组里面的所有的产品属性详细，
     * 注解：不同类型的产品，对应不同的属性组，譬如衣服有颜色尺码，电脑类型的有不同cpu型号等
     * 属性组，以及属性组对应的属性，是在Product Service config中配置的。
     */
    protected function actionGetGroupAttrInfo($productAttrGroup)
    {
        $arr = [];
        if ($productAttrGroup == $this->_defaultAttrGroup) {
            return [];
        }
        // 得到普通属性
        if (isset($this->customAttrGroup[$productAttrGroup]['general_attr'])
                && is_array($this->customAttrGroup[$productAttrGroup]['general_attr'])
        ) {
            $arr = array_merge($arr, $this->customAttrGroup[$productAttrGroup]['general_attr']);
        }
        // 得到用于spu，细分sku的属性，譬如颜色尺码之类。
        if (isset($this->customAttrGroup[$productAttrGroup]['spu_attr'])
                && is_array($this->customAttrGroup[$productAttrGroup]['spu_attr'])
        ) {
            $arr = array_merge($arr, $this->customAttrGroup[$productAttrGroup]['spu_attr']);
        }
        return $arr;
    }
    
    public function getGroupGeneralAttr($productAttrGroup)
    {
        $arr = [];
        if ($productAttrGroup == $this->_defaultAttrGroup) {
            return [];
        }
        // 得到普通属性
        if (isset($this->customAttrGroup[$productAttrGroup]['general_attr'])
                && is_array($this->customAttrGroup[$productAttrGroup]['general_attr'])
        ) {
            $arr = array_merge($arr, $this->customAttrGroup[$productAttrGroup]['general_attr']);
        }
        
        return $arr;
    }
    
    public function getGroupSpuAttr($productAttrGroup)
    {
        $arr = [];
        if ($productAttrGroup == $this->_defaultAttrGroup) {
            return [];
        }
        // 得到用于spu，细分sku的属性，譬如颜色尺码之类。
        if (isset($this->customAttrGroup[$productAttrGroup]['spu_attr'])
                && is_array($this->customAttrGroup[$productAttrGroup]['spu_attr'])
        ) {
            $arr = array_merge($arr, $this->customAttrGroup[$productAttrGroup]['spu_attr']);
        }
        
        return $arr;
    }
    
    /**
     * @param $productAttrGroup|string
     * 得到这个产品属性组里面的所有的产品属性，
     * 注解：不同类型的产品，对应不同的属性组，譬如衣服有颜色尺码，电脑类型的有不同cpu型号等
     * 属性组，以及属性组对应的属性，是在Product Service config中配置的。
     */
    protected function actionGetGroupAttr($productAttrGroup)
    {
        $arr = [];
        
        // 得到普通属性
        if (isset($this->customAttrGroup[$productAttrGroup]['general_attr'])
                && is_array($this->customAttrGroup[$productAttrGroup]['general_attr'])
        ) {
            $general_attr = $this->customAttrGroup[$productAttrGroup]['general_attr'];
            if (is_array($general_attr)) {
                foreach ($general_attr as $attr => $info) {
                    $arr[] = $attr;
                }
            }
        }
        // 得到用于spu，细分sku的属性，譬如颜色尺码之类。
        if (isset($this->customAttrGroup[$productAttrGroup]['spu_attr'])
                && is_array($this->customAttrGroup[$productAttrGroup]['spu_attr'])
        ) {
            $spu_attr = $this->customAttrGroup[$productAttrGroup]['spu_attr'];
            if (is_array($spu_attr)) {
                foreach ($spu_attr as $attr => $info) {
                    $arr[] = $attr;
                }
            }
        }
        return $arr;
    }

    /**
     * @param $productAttrGroup|string
     * @return array 一维数组
     * 得到这个产品属性组里面的属性,也就是原来的产品属性+属性组对应的属性
     */
    protected function actionGetSpuAttr($productAttrGroup)
    {
        $arr = [];
        if ($productAttrGroup == $this->_defaultAttrGroup) {
            return [];
        }

        // 得到用于spu，细分sku的属性，譬如颜色尺码之类。
        if (isset($this->customAttrGroup[$productAttrGroup]['spu_attr'])
                && is_array($this->customAttrGroup[$productAttrGroup]['spu_attr'])
        ) {
            $arr = array_merge($arr, $this->customAttrGroup[$productAttrGroup]['spu_attr']);
        }

        return array_keys($arr);
    }

    /**
     * @param $productAttrGroup | String
     * @return string 显示图片的spu属性。
     */
    protected function actionGetSpuImgAttr($productAttrGroup)
    {
        if ($productAttrGroup == $this->_defaultAttrGroup) {
            return '';
        }

        // 得到用于spu，细分sku的属性，譬如颜色尺码之类。
        if (isset($this->customAttrGroup[$productAttrGroup]['spu_attr'])
                && is_array($this->customAttrGroup[$productAttrGroup]['spu_attr'])
        ) {
            foreach ($this->customAttrGroup[$productAttrGroup]['spu_attr'] as $attr => $one) {
                if (isset($one['showAsImg']) && $one['showAsImg']) {
                    return $attr;
                }
            }
        }

        return '';
    }

    /**
     * 产品状态是否是 active
     * @param int $status
     * @return boolean 如果产品状态是 active 返回 true, 否则返回 false
     */
    protected function actionIsActive($status)
    {
        return ($status == 1) ? true : false;
    }

    /**
     * @param $productAttrGroup | String  产品属性组
     * 通过产品属性组，从配置中得到对应的custom_options部分的配置
     * @return array
     */
    protected function actionGetCustomOptionAttrInfo($productAttrGroup)
    {
        if ($productAttrGroup == $this->_defaultAttrGroup) {
            return [];
        }
        if (isset($this->customAttrGroup[$productAttrGroup]['custom_options'])
                && is_array($this->customAttrGroup[$productAttrGroup]['custom_options'])
        ) {
            return $this->customAttrGroup[$productAttrGroup]['custom_options'];
        }
        return [];
    }

    /**
     * 得到默认的产品属性组。
     */
    protected function actionGetDefaultAttrGroup()
    {
        return $this->_defaultAttrGroup;
    }

    /**
     * 得到主键的名称.
     */
    protected function actionGetPrimaryKey()
    {
        return $this->_product->getPrimaryKey();
    }
    
    public function getCategoryIdsByProductId($product_id)
    {
        return $this->_product->getCategoryIdsByProductId($product_id);
    }
    
    public function getProductIdsByCategoryId($category_id)
    {
        return $this->_product->getProductIdsByCategoryId($category_id);
    }
    

    /**
     * get Product model by primary key.
     */
    protected function actionGetByPrimaryKey($primaryKey)
    {
        return $this->_product->getByPrimaryKey($primaryKey);
    }
    
    /**
     * get Product model by primary key.
     */
    protected function actionGetArrByPrimaryKey($primaryKey)
    {
        return $this->_product->getArrByPrimaryKey($primaryKey);
    }

    /**
     * @param $attr_group | String , 属性组名称
     * 给product model 增加相应的属性组对应的属性。
     */
    protected function actionAddGroupAttrs($attr_group)
    {
        return $this->_product->addGroupAttrs($attr_group);
    }

    /**
     * api部分
     * 和coll()的不同在于，该方式不走active record，因此可以获取产品的所有数据的。
     */
    protected function actionApicoll()
    {
        return $this->_product->apicoll();
    }

    /**
     * api部分
     */
    protected function actionApiGetByPrimaryKey($primaryKey)
    {
        return $this->_product->apiGetByPrimaryKey($primaryKey);
    }

    /**
     * api部分
     */
    protected function actionApiSave($product_one)
    {
        return $this->_product->apiSave($product_one);
    }

    /**
     * api部分
     */
    protected function actionApiDelete($primaryKey)
    {
        return $this->_product->apiDelete($primaryKey);
    }
    
    public function updateProductFavoriteCount($product_id, $count)
    {
        return $this->_product->updateProductFavoriteCount($product_id, $count);
    }
    

    /**
     * 得到Product model的全名.
     */
    protected function actionGetModelName()
    {
        return get_class($this->_product->getByPrimaryKey());
    }

    /**
     * @param $sku | string
     * @param $returnArr | boolean ， 是否返回数组格式
     * 通过sku查询产品
     */
    protected function actionGetBySku($sku, $returnArr = true)
    {
        return $this->_product->getBySku($sku, $returnArr);
    }

    /**
     * @param $spu | string
     * 通过spu查询产品
     */
    protected function actionGetBySpu($spu)
    {
        return $this->_product->getBySpu($spu);
    }

    /**
     * @param $filter|array
     * get artile collection by $filter
     * example filter:
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
     * 根据传入的查询条件，得到产品的列表
     */
    protected function actionColl($filter = [])
    {
        return $this->_product->coll($filter);
    }

    protected function actionCollCount($filter = [])
    {
        return $this->_product->collCount($filter);
    }

    /**
     * 通过where条件 和 查找的select 字段信息，得到产品的列表信息，
     * 这里一般是用于前台的区块性的不分页的产品查找。
     * 结果数据没有进行进一步处理，需要前端获取数据后在处理。
     */
    protected function actionGetProducts($filter)
    {
        return $this->_product->getProducts($filter);
    }

    /**
     * @param  $product_id_arr | Array
     * @param  $category_id | String
     * 在给予的产品id数组$product_id_arr中，找出来那些产品属于分类 $category_id
     * 该功能是后台分类编辑中，对应的分类产品列表功能
     * 也就是在当前的分类下，查看所有的产品，属于当前分类的产品，默认被勾选。
     */
    protected function actionGetCategoryProductIds($product_id_arr, $category_id)
    {
        return $this->_product->getCategoryProductIds($product_id_arr, $category_id);
    }

    /**
     * @param $one|array , 产品数据数组
     * @param $originUrlKey|string , 分类的原来的url key ，也就是在前端，分类的自定义url。
     * 保存产品（插入和更新），以及保存产品的自定义url
     * 如果提交的数据中定义了自定义url，则按照自定义url保存到urlkey中，如果没有自定义urlkey，则会使用name进行生成。
     */
    protected function actionSave($one, $originUrlKey = 'catalog/product/index', $isLoginUser=true)
    {
        return $this->_product->save($one, $originUrlKey, $isLoginUser);
    }

    /**
     * @param $ids | Array or String
     * 删除产品，如果ids是数组，则删除多个产品，如果是字符串，则删除一个产品
     * 在产品产品的同时，会在url rewrite表中删除对应的自定义url数据。
     */
    protected function actionRemove($ids)
    {
        return $this->_product->remove($ids);
    }
    
    public function spuCollData($select, $spuAttrArr, $spu)
    {
        return $this->_product->spuCollData($select, $spuAttrArr, $spu);
    }

    /**
     * @param $category_id | String  分类的id的值
     * @param $addCateProductIdArr | Array 分类中需要添加的产品id数组，也就是给这个分类增加这几个产品。
     * @param $deleteCateProductIdArr | Array 分类中需要删除的产品id数组，也就是在这个分类下面去除这几个产品的对应关系。
     * 这个函数是后台分类编辑功能中使用到的函数，在分类中可以一次性添加多个产品，也可以删除多个产品，产品和分类是多对多的关系。
     */
    protected function actionAddAndDeleteProductCategory($category_id, $addCateProductIdArr, $deleteCateProductIdArr)
    {
        return $this->_product->addAndDeleteProductCategory($category_id, $addCateProductIdArr, $deleteCateProductIdArr);
    }

    /**
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
    protected function actionGetFrontCategoryProducts($filter)
    {
        return $this->_product->getFrontCategoryProducts($filter);
    }
    public function actionSync($arr)
    {
        return $this->_product->sync($arr);
    }
    

    /**
     * @param $filter_attr | String 需要进行统计的字段名称
     * @propertuy $where | Array  搜索条件。这个需要些mongodb的搜索条件。
     * 得到的是个属性，以及对应的个数。
     * 这个功能是用于前端分类侧栏进行属性过滤。
     */
    protected function actionGetFrontCategoryFilter($filter_attr, $where)
    {
        return $this->_product->getFrontCategoryFilter($filter_attr, $where);
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
    //protected function actionFullTearchText($filter){
    //	return $this->_product->fullTearchText($filter);
    //}

    /**
     * @param $ids | Array
     * 通过产品ids得到产品sku
     */
    public function getSkusByIds($ids)
    {
        return $this->_product->getSkusByIds($ids);
    }
    /**
     * @param $spu | String
     * @param $avag_rate | Int 产品的总平均得分
     * @param $count | Int 产品的总评论数
     * @param $avag_lang_rate | 当前语言的总平均得分
     * @param $lang_count | 当前语言的总评论数
     */
    protected function actionUpdateProductReviewInfo($spu, $avag_rate, $count, $lang_code, $avag_lang_rate, $lang_count, $rate_total_arr, $rate_lang_total_arr)
    {
        return $this->_product->updateProductReviewInfo($spu, $avag_rate, $count, $lang_code, $avag_lang_rate, $lang_count, $rate_total_arr, $rate_lang_total_arr);
    }

    public function updateAllScoreToZero()
    {
        return $this->_product->updateAllScoreToZero();
    }
    
    
    public function excelSave($productArr)
    {
        return $this->_product->excelSave($productArr);
    }
    
    
    
    
    
}
