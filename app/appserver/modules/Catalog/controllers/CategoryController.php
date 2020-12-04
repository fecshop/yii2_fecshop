<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Catalog\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CategoryController extends AppserverController
{
    
    // 当前分类对象
    protected $_category;
    // 页面标题
    protected $_title;
    // 当前分类主键对应的值
    protected $_primaryVal;
    // 默认的排序字段
    protected $_defautOrder;
    // 默认的排序方向，升序还是降序
    protected $_defautOrderDirection = SORT_DESC;
    // 当前的where条件
    protected $_where;
    // url的参数，每页产品个数
    protected $_numPerPage = 'numPerPage';
    // url的参数，排序方向
    protected $_direction = 'dir';
    // url的参数，排序字段
    protected $_sort = 'sortColumn';
    // url的参数，页数
    protected $_page = 'p';
    // url的参数，价格
    protected $_filterPrice = 'price';
    // url的参数，价格
    protected $_filterPriceAttr = 'price';
    // 产品总数
    protected $_productCount;
    protected $_filter_attr;
    protected $_numPerPageVal;
    protected $_page_count;
    protected $category_name;
    protected $sp = '---';
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //$primaryKey = Yii::$service->category->getPrimaryKey();
        $category_id = Yii::$app->request->get('categoryId');
        $cacheName = 'category';
        if (Yii::$service->cache->isEnable($cacheName)) {
            $timeout = Yii::$service->cache->timeout($cacheName);
            $disableUrlParam = Yii::$service->cache->disableUrlParam($cacheName);
            $cacheUrlParam = Yii::$service->cache->cacheUrlParam($cacheName);
            $get_str = '';
            $get = Yii::$app->request->get();
            // 存在无缓存参数，则关闭缓存
            if (isset($get[$disableUrlParam])) {
                $behaviors[] =  [
                    'enabled' => false,
                    'class' => 'yii\filters\PageCache',
                    'only' => ['index'],
                ];
                
                return $behaviors;
            }
            if (is_array($get) && !empty($get) && is_array($cacheUrlParam)) {
                foreach ($get as $k=>$v) {
                    if (in_array($k, $cacheUrlParam)) {
                        if ($k != 'p' || $v != 1) {
                            $get_str .= $k.'_'.$v.'_';
                        }
                    }
                }
            }
            $store = Yii::$service->store->currentStore;
            $currency = Yii::$service->page->currency->getCurrentCurrency();
            $langCode = Yii::$service->store->currentLangCode;
            $behaviors[] =  [
                'enabled' => true,
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => $timeout,
                'variations' => [
                    $store, $currency, $get_str, $category_id,$langCode
                ],
                //'dependency' => [
                //	'class' => 'yii\caching\DbDependency',
                //	'sql' => 'SELECT COUNT(*) FROM post',
                //],
            ];
        }

        return $behaviors;
    }
    public function init()
    {
        parent::init();
        $this->getQuerySort();
    }
    protected $_sort_items;
    public function getQuerySort()
    {
        if (!$this->_sort_items) {
            $category_sorts = Yii::$app->store->get('category_sort');
            if (is_array($category_sorts)) {
                foreach ($category_sorts as $one) {
                    $sort_key = $one['sort_key'];
                    $sort_label = $one['sort_label'];
                    $sort_db_columns = $one['sort_db_columns'];
                    $sort_direction = $one['sort_direction'];
                    $this->_sort_items[$sort_key] = [
                        'label'        => $sort_label,
                        'db_columns'   => $sort_db_columns,
                        'direction'    => $sort_direction,
                    ];
                }
            }
        }
    }
    
    public function actionIndex(){
        
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        // 每页显示的产品个数，进行安全验证，如果个数不在预先设置的值内，则会报错。
        // 这样是为了防止恶意攻击，也就是发送很多不同的页面个数的链接，绕开缓存。
        $this->getNumPerPage();
        //echo Yii::$service->page->translate->__('fecshop,{username}', ['username' => 'terry']);
        if(!$this->initCategory()){
            $code = Yii::$service->helper->appserver->category_not_exist;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        // change current layout File.
        //Yii::$service->page->theme->layoutFile = 'home.php';

        $productCollInfo = $this->getCategoryProductColl();
        $products = $productCollInfo['coll'];
        $this->_productCount = $productCollInfo['count'];
        $p = Yii::$app->request->get('p');
        $p = (int)$p;
        $query_item = $this->getQueryItem();
        $page_count = $this->getProductPageCount();
        $this->category_name = Yii::$service->store->getStoreAttrVal($this->_category['name'], 'name');
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'name'              => $this->category_name ,
            'name_default_lang' => Yii::$service->fecshoplang->getDefaultLangAttrVal($this->_category['name'], 'name'),
            'title'             => $this->_title,
            'image'             => $this->_category['image'] ? Yii::$service->category->image->getUrl($this->_category['image']) : '',
            'products'          => $products,
            'query_item'        => $query_item,
            'refine_by_info'    => $this->getRefineByInfo(),
            'filter_info'       => $this->getFilterInfo(),
            'filter_price'      => $this->getFilterPrice(),
            'filter_category'   => $this->getFilterCategory(),
            'page_count'        => $page_count,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    // 微信分类部分数据
    public function actionWxindex(){
        
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        // 每页显示的产品个数，进行安全验证，如果个数不在预先设置的值内，则会报错。
        // 这样是为了防止恶意攻击，也就是发送很多不同的页面个数的链接，绕开缓存。
        $this->getNumPerPage();
        //echo Yii::$service->page->translate->__('fecshop,{username}', ['username' => 'terry']);
        if(!$this->initCategory()){
            $code = Yii::$service->helper->appserver->category_not_exist;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        // change current layout File.
        //Yii::$service->page->theme->layoutFile = 'home.php';

        $productCollInfo = $this->getWxCategoryProductColl();
        $products = $productCollInfo['coll'];
        $this->_productCount = $productCollInfo['count'];
        $p = Yii::$app->request->get('p');
        $p = (int)$p;
        $query_item = $this->getQueryItem();
        $page_count = $this->getProductPageCount();
        $this->category_name = Yii::$service->store->getStoreAttrVal($this->_category['name'], 'name');
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'name'              => $this->category_name ,
            'name_default_lang' => Yii::$service->fecshoplang->getDefaultLangAttrVal($this->_category['name'], 'name'),
            'title'             => $this->_title,
            'image'             => $this->_category['image'] ? Yii::$service->category->image->getUrl($this->_category['image']) : '',
            'products'          => $products,
            'query_item'        => $query_item,
            'refine_by_info'    => $this->getRefineByInfo(),
            'filter_info'       => $this->getFilterInfo(),
            'filter_price'      => $this->getFilterPrice(),
            'filter_category'   => $this->getFilterCategory(),
            'page_count'        => $page_count,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    public function actionProduct()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        // 每页显示的产品个数，进行安全验证，如果个数不在预先设置的值内，则会报错。
        // 这样是为了防止恶意攻击，也就是发送很多不同的页面个数的链接，绕开缓存。
        $this->getNumPerPage();
        if(!$this->initCategory()){
            $code = Yii::$service->helper->appserver->category_not_exist;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $productCollInfo = $this->getCategoryProductColl();
        $products = $productCollInfo['coll'];
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'products' => $products
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    /**
     * 得到子分类，如果子分类不存在，则返回同级分类。
     */
    protected function getFilterCategory()
    {
        $arr = [];
        if (!Yii::$service->category->isEnableFilterSubCategory()) {
            
            return $arr;
        }
        $category_id = $this->_primaryVal;
        $parent_id = $this->_category['parent_id'];
        $filter_category = Yii::$service->category->getFilterCategory($category_id, $parent_id);
        return $this->getAppServerFilterCategory($filter_category);
    }
    
    protected function getAppServerFilterCategory($filter_category){
        if((is_array($filter_category) || is_object($filter_category)) && !empty($filter_category)){
            foreach($filter_category as $category_id => $v){
                $filter_category[$category_id]['name'] = Yii::$service->store->getStoreAttrVal($v['name'],'name');
                if($filter_category[$category_id]['name'] == $this->category_name){
                    $filter_category[$category_id]['current'] = true;
                }else{
                    $filter_category[$category_id]['current'] = false;
                }
                $filter_category[$category_id]['url'] = 'catalog/category/'.$category_id;
                if(isset($v['child'])){
                    $filter_category[$category_id]['child'] = $this->getAppServerFilterCategory($v['child']);
                }
            }
        }
        return $filter_category;
    }
    
    
    /**
     * 得到产品页面的toolbar部分
     * 也就是分类页面的分页工具条部分。
     */
    protected function getProductPageCount()
    {
        $productNumPerPage  = $this->getNumPerPage();
        $productCount       = $this->_productCount;
        $pageNum            = $this->getPageNum();
        return $this->_page_count = ceil($productCount / $productNumPerPage);
    }
    /**
     * 分类页面toolbar部分：
     * 产品排序，产品每页的产品个数等，为这些部分提供数据。
     */
    protected function getQueryItem()
    {
        //$category_query  = Yii::$app->controller->module->params['category_query'];
        //$numPerPage      = $category_query['numPerPage'];
        
        $appName = Yii::$service->helper->getAppName();
        $numPerPage = Yii::$app->store->get($appName.'_catalog','category_query_numPerPage');
        $numPerPage = explode(',', $numPerPage);
        $sort                   = $this->_sort_items;
        $current_sort    = Yii::$app->request->get($this->_sort);
        $frontNumPerPage = [];
        
        $frontSort = [];
        $hasSelect = false;
        if (is_array($sort) && !empty($sort)) {
            $attrUrlStr = $this->_sort;
            $dirUrlStr  = $this->_direction;
            foreach ($sort as $np=>$info) {
                $label      = $info['label'];
                $direction  = $info['direction'];
                
                if($current_sort == $np){
                    $selected = true;
                    $hasSelect = true;
                }else{
                    $selected = false;
                }
                $label = Yii::$service->page->translate->__($label);
                $frontSort[] = [
                    'label'     => $label,
                    'value'     => $np,
                    'selected'  => $selected,
                ];
            }
        }
        if (!$hasSelect ){ // 默认第一个为选中的排序方式
            $frontSort[0]['selected'] = true;
        }
        $data = [
            'frontNumPerPage' => $frontNumPerPage,
            'frontSort'       => $frontSort,
        ];

        return $data;
    }
    /**
     * @return Array
     * 得到当前分类，侧栏用于过滤的属性数组，由三部分计算得出
     * 1.全局默认属性过滤（catalog module 配置文件中配置 category_filter_attr），
     * 2.当前分类属性过滤，也就是分类表的 filter_product_attr_selected 字段
     * 3.当前分类去除的属性过滤，也就是分类表的 filter_product_attr_unselected
     * 最终出来一个当前分类，用于过滤的属性数组。
     */
    protected function getFilterAttr()
    {
        
        return Yii::$service->category->getFilterAttr($this->_category);
    }
    /**
     * 得到分类侧栏用于属性过滤的部分数据
     */
    protected function getRefineByInfo()
    {
        $refineInfo     = [];
        $chosenAttrs = Yii::$app->request->get('filterAttrs');
        $chosenAttrArr = json_decode($chosenAttrs,true);
        if(!empty($chosenAttrArr)){
            foreach ($chosenAttrArr as $attr=>$val) {
                $refine_attr_str = Yii::$service->category->getCustomCategoryFilterAttrItemLabel($attr, $val);
                if (!$refine_attr_str) {
                    $refine_attr_str = Yii::$service->page->translate->__($val);
                }
                $attrLabel = Yii::$service->category->getCustomCategoryFilterAttrLabel($attr);
                $refineInfo[] = [
                    'attr' =>  $attr,
                    'val'  =>  $refine_attr_str,
                    'attrLabel' => $attrLabel,
                ];
            }
        }
        $currenctPriceFilter = Yii::$app->request->get('filterPrice'); 
        if($currenctPriceFilter){
            $refineInfo[] = [
                'attr' =>  $this->_filterPrice,
                'attrLabel' => $this->_filterPrice,
                'val'  =>  $currenctPriceFilter,
            ];
        }
        
        if (!empty($refineInfo)) {
            $arr[] = [
                'attr'   => 'clear All',
                'attrLabel' =>'clear All',
                'val'    => Yii::$service->page->translate->__('clear all'),
            ];
            $refineInfo = array_merge($arr, $refineInfo);
        }

        return $refineInfo;
    }
    /**
     * 侧栏除价格外的其他属性过滤部分
     */
    protected function getFilterInfo()
    {
        $chosenAttrs = Yii::$app->request->get('filterAttrs');
        
        return Yii::$service->category->getFilterInfo($this->_category, $this->_where, $chosenAttrs);
        /*
        
        $filter_info  = [];
        $filter_attrs = $this->getFilterAttr();
        $chosenAttrs = Yii::$app->request->get('filterAttrs');
        $chosenAttrArr = json_decode($chosenAttrs,true);
        foreach ($filter_attrs as $attr) {
            if ($attr != 'price') {
                $label = preg_replace_callback('/([-_]+([a-z]{1}))/i',function($matches){
                    return ' '.strtoupper($matches[2]);
                },$attr);
                $items = Yii::$service->product->getFrontCategoryFilter($attr, $this->_where);
                if(is_array($items) && !empty($items)){
                    foreach($items as $k=>$one){
                        if(isset($chosenAttrArr[$attr]) && $chosenAttrArr[$attr] == $one['_id']){
                            $items[$k]['selected'] = true;
                        } else {
                            $items[$k]['selected'] = false;
                        }
                        if (isset($items[$k]['_id'])) {
                            $items[$k]['label'] = Yii::$service->page->translate->__($items[$k]['_id']);
                        }
                    }
                }
                $label = Yii::$service->page->translate->__($label);
                $filter_info[$attr] = [
                    'label' => $label,
                    'items' => $items,
                ];
            }
        }

        return $filter_info;
        */
    }
    /**
     * 侧栏价格过滤部分
     */
    protected function getFilterPrice()
    {
        
        $filter = [];
        if (!Yii::$service->category->isEnableFilterPrice()) {
            
            return $filter;
        }
        $symbol = Yii::$service->page->currency->getCurrentSymbol();
        $currenctPriceFilter = Yii::$app->request->get('filterPrice');
        //$priceInfo = Yii::$app->controller->module->params['category_query'];
        $appName = Yii::$service->helper->getAppName();
        $category_query_priceRange = Yii::$app->store->get($appName.'_catalog','category_query_priceRange');
        $category_query_priceRange = explode(',',$category_query_priceRange);
        if ( !empty($category_query_priceRange) && is_array($category_query_priceRange)) {
            foreach ($category_query_priceRange as $price_item) {
                $price_item = trim($price_item);
                list($b_price,$e_price) = explode('-',$price_item);
                $b_price = $b_price ? $symbol.$b_price : '';
                $e_price = $e_price ? $symbol.$e_price : '';
                $label = $b_price.$this->sp.$e_price;
                if($currenctPriceFilter && ($currenctPriceFilter == $price_item)){
                    $selected = true;
                }else{
                    $selected = false;
                }
                $info = [
                    'selected'  => $selected,
                    'label'     => $label,
                    'val'       => $price_item
                ];
                
                $filter[$this->_filterPrice][] = $info;
            }
        }

        return $filter;
    }
    /**
     * 格式化价格格式，侧栏价格过滤部分
     */
    protected function getFormatFilterPrice($price_item)
    {
        list($f_price, $l_price) = explode('-', $price_item);
        $str = '';
        if ($f_price == '0' || $f_price) {
            $f_price = Yii::$service->product->price->formatPrice($f_price);
            $str .= $f_price['symbol'].$f_price['value'].'---';
        }
        if ($l_price) {
            $l_price = Yii::$service->product->price->formatPrice($l_price);
            $str .= $l_price['symbol'].$l_price['value'];
        }

        return $str;
    }
    
    /**
     * 用于搜索条件的排序部分
     */
    protected function getOrderBy()
    {
        $primaryKey = Yii::$service->category->getPrimaryKey();
        $sort       = Yii::$app->request->get($this->_sort);
        $direction  = Yii::$app->request->get($this->_direction);

        //$category_query_config = Yii::$app->controller->module->params['category_query'];
       
        $sortConfig = $this->_sort_items;
        if (is_array($sortConfig)) {
            
            //return $category_query_config['numPerPage'][0];
            if ($sort && isset($sortConfig[$sort])) {
                $orderInfo = $sortConfig[$sort];
                //var_dump($orderInfo);
                if (!$direction) {
                    $direction = $orderInfo['direction'];
                }
            } else {
                foreach ($sortConfig as $k => $v) {
                    $orderInfo = $v;
                    if (!$direction) {
                        $direction = $v['direction'];
                    }
                    break;
                }
            }
            
            $db_columns = $orderInfo['db_columns'];
           $storageName = Yii::$service->product->serviceStorageName();
            if ($direction == 'desc') {
                $direction =  $storageName == 'mongodb' ? -1 :  SORT_DESC;
            } else {
                $direction = $storageName == 'mongodb' ? 1 :SORT_ASC;
            }
            //var_dump([$db_columns => $direction]);
            //exit;
            return [$db_columns => $direction];
        }
    }
    /**
     * 分类页面的产品，每页显示的产品个数。
     * 对于前端传递的个数参数，在后台验证一下是否是合法的个数（配置里面有一个分类产品个数列表）
     * 如果不合法，则报异常
     * 这个功能是为了防止分页攻击，伪造大量的不同个数的url，绕过缓存。
     */
    protected function getNumPerPage()
    {
        if (!$this->_numPerPageVal) {
            $numPerPage = Yii::$app->request->get($this->_numPerPage);
            //$category_query_config = Yii::$app->getModule('catalog')->params['category_query'];
            $appName = Yii::$service->helper->getAppName();
            $categoryConfigNumPerPage = Yii::$app->store->get($appName.'_catalog','category_query_numPerPage');
            $category_query_config['numPerPage'] = explode(',',$categoryConfigNumPerPage);
            if (!$numPerPage) {
                if (isset($category_query_config['numPerPage'])) {
                    if (is_array($category_query_config['numPerPage'])) {
                        $this->_numPerPageVal = $category_query_config['numPerPage'][0];
                    }
                }
            } elseif (!$this->_numPerPageVal) {
                if (isset($category_query_config['numPerPage']) && is_array($category_query_config['numPerPage'])) {
                    $numPerPageArr = $category_query_config['numPerPage'];
                    if (in_array((int) $numPerPage, $numPerPageArr)) {
                        $this->_numPerPageVal = $numPerPage;
                    } else {
                        throw new InvalidValueException('Incorrect numPerPage value:'.$numPerPage);
                    }
                }
            }
        }

        return $this->_numPerPageVal;
    }
    /**
     * 得到当前第几页
     */
    protected function getPageNum()
    {
        $numPerPage = Yii::$app->request->get($this->_page);

        return $numPerPage ? (int) $numPerPage : 1;
    }
    /**
     * 得到当前分类的产品
     */
    protected function getCategoryProductColl()
    {
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        $select = [
            'sku', 'spu', 'name', 'image',
                'price', 'special_price',
                'special_from', 'special_to',
                'url_key', 'score', 'reviw_rate_star_average', 'review_count'
        ];
        if ($productPrimaryKey == 'id') {
            $select[] = 'id';
        }
        if (is_array($this->_sort_items)) {
            foreach ($this->_sort_items as $sort_item) {
                $select[] = $sort_item['db_columns'];
            }
        }
        $filter = [
            'pageNum'      => $this->getPageNum(),
            'numPerPage'  => $this->getNumPerPage(),
            'orderBy'      => $this->getOrderBy(),
            'where'          => $this->_where,
            'select'      => $select,
        ];
        //var_dump($filter);
        $productList = Yii::$service->category->product->getFrontList($filter);
        // var_dump($productList );
        $i = 1;
        $product_return = [];
        $products = $productList['coll'];
        if(is_array($products) && !empty($products)){
            
            foreach($products as $k=>$v){
                $i++;
                $products[$k]['url'] = '/catalog/product/'.$v['_id']; 
                $products[$k]['image'] = Yii::$service->product->image->getResize($v['image'],296,false);
                $priceInfo = Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($v['price'], $v['special_price'],$v['special_from'],$v['special_to']);
                $products[$k]['price'] = isset($priceInfo['price']) ? $priceInfo['price'] : '';
                $products[$k]['special_price'] = isset($priceInfo['special_price']) ? $priceInfo['special_price'] : '';
                if (isset($products[$k]['special_price']['value'])) {
                    $products[$k]['special_price']['value'] = Yii::$service->helper->format->numberFormat($products[$k]['special_price']['value']);
                }
                if (isset($products[$k]['price']['value'])) {
                    $products[$k]['price']['value'] = Yii::$service->helper->format->numberFormat($products[$k]['price']['value']);
                }
                if($i%2 === 0){
                    $arr = $products[$k];
                }else{
                    $product_return[] = [
                        'one' => $arr,
                        'two' => $products[$k],
                    ];
                }
            }
            if($i%2 === 0){
                $product_return[] = [
                    'one' => $arr,
                    'two' => [],
                ];
            }
        }
        $productList['coll'] = $product_return;
        return $productList;
    }
    
     /**
     * 得到当前分类的产品
     */
    protected function getWxCategoryProductColl()
    {
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        $select = [
            $productPrimaryKey ,
            'sku', 'spu', 'name', 'image',
            'price', 'special_price',
            'special_from', 'special_to',
            'url_key', 'score',
        ];
        //$category_query = Yii::$app->getModule('catalog')->params['category_query'];
        if (is_array($this->_sort_items)) {
            foreach ($this->_sort_items as $sort_item) {
                $select[] = $sort_item['db_columns'];
            }
        }
        $filter = [
            'pageNum'      => $this->getPageNum(),
            'numPerPage'  => $this->getNumPerPage(),
            'orderBy'      => $this->getOrderBy(),
            'where'          => $this->_where,
            'select'      => $select,
        ];
        
        $productList = Yii::$service->category->product->getFrontList($filter);
        
        $i = 1;
        $product_return = [];
        $products = $productList['coll'];
        if(is_array($products) && !empty($products)){
            
            foreach($products as $k=>$v){
                $priceInfo = Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($v['price'], $v['special_price'],$v['special_from'],$v['special_to']);
                $price = isset($priceInfo['price']) ? $priceInfo['price'] : '';
                $special_price = isset($priceInfo['special_price']) ? $priceInfo['special_price'] : '';
                
                
                $product_return[] = [
                    'name' => $v['name'],
                    'pic'  => Yii::$service->product->image->getResize($v['image'],296,false),
                    'special_price'  => $special_price,
                    'price'  => $price,
                    'id'  => $v['product_id'],
                ];
            }
            
        }
        $productList['coll'] = $product_return;
        return $productList;
    }
    
    /**
     * 得到用于查询的where数组。
     */
    protected function initWhere()
    {
        
        $chosenAttrs = Yii::$app->request->get('filterAttrs');
        $chosenAttrArr = json_decode($chosenAttrs,true);
        //var_dump($chosenAttrArr);
        
        if(is_array($chosenAttrArr) && !empty($chosenAttrArr)){
            $filterAttr = $this->getFilterAttr();
            //var_dump($filterAttr);
            foreach ($filterAttr as $attr) {
                if(isset($chosenAttrArr[$attr]) && $chosenAttrArr[$attr]){
                    $where[$attr] = $chosenAttrArr[$attr];
                }
            }
        }
        $filter_price = Yii::$app->request->get('filterPrice');
        //echo $filter_price;
        list($f_price, $l_price) = explode('-', $filter_price);
        if ($f_price == '0' || $f_price) {
            $where[$this->_filterPriceAttr]['$gte'] = (float) $f_price;
        }
        if ($l_price) {
            $where[$this->_filterPriceAttr]['$lte'] = (float) $l_price;
        }
        $where['category'] = $this->_primaryVal;
        //var_dump($where);
        return $where;
    }
    /**
     * 分类部分的初始化
     * 对一些属性进行赋值。
     */
    protected function initCategory()
    {
        //$primaryKey = 'category_id';
        $primaryVal = Yii::$app->request->get('categoryId');
        $this->_primaryVal = $primaryVal;
        $category = Yii::$service->category->getByPrimaryKey($primaryVal);
        if ($category) {
            $enableStatus = Yii::$service->category->getCategoryEnableStatus();
            if ($category['status'] != $enableStatus){
                
                return false;
            }
        } else {
            
            return false;
        }
        $this->_category = $category;
        
        $this->_where = $this->initWhere();
        return true;
    }

    
    
    
   
    
}