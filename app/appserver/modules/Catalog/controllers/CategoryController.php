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
    
    
    public function actionIndex(){
        // 每页显示的产品个数，进行安全验证，如果个数不在预先设置的值内，则会报错。
        // 这样是为了防止恶意攻击，也就是发送很多不同的页面个数的链接，绕开缓存。
        $this->getNumPerPage();
        //echo Yii::$service->page->translate->__('fecshop,{username}', ['username' => 'terry']);
        if(!$this->initCategory()){
            return [
                'code' => 300,
                'content' => 'category is disable',
            ];
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
        //echo $this->_productCount;
        return  [
            'code' => 200,
            'content' => [
                'name'              => $this->category_name ,
                'title'             => $this->_title,
                'image'             => $this->_category['image'] ? Yii::$service->category->image->getUrl($this->_category['image']) : '',
                'products'          => $products,
                'query_item'        => $query_item,
                'refine_by_info'    => $this->getRefineByInfo(),
                'filter_info'       => $this->getFilterInfo(),
                'filter_price'      => $this->getFilterPrice(),
                'filter_category'   => $this->getFilterCategory(),
                'page_count'        => $page_count,
            ],
            //'content' => Yii::$service->store->getStoreAttrVal($this->_category['content'],'content'),
            //'created_at' => $this->_category['created_at'],
        ];
        
    }
    
    public function actionProduct()
    {
        // 每页显示的产品个数，进行安全验证，如果个数不在预先设置的值内，则会报错。
        // 这样是为了防止恶意攻击，也就是发送很多不同的页面个数的链接，绕开缓存。
        $this->getNumPerPage();
        if(!$this->initCategory()){
            return [
                'code' => 300,
                'content' => 'category is disable',
            ];
        }
        $productCollInfo = $this->getCategoryProductColl();
        $products = $productCollInfo['coll'];
        $this->_productCount = $productCollInfo['count'];
        $p = Yii::$app->request->get('p');
        $p = (int)$p;
        return [
            'code' => 200,
            'content' => [
                'products' => $products
            ]
        ];
        
    }
    /**
     * @property $products | Array 产品的数组。
     * ajax方式访问，得到产品的数据
     * 这个是wap端手机页面访问，下拉自动加载下一页的数据的加载实现。
     */
    protected function getAjaxProductHtml($products)
    {
        $parentThis['products'] = $products;
        $config = [
            'view'        => 'cms/home/index/product.php',
        ];
        $html = Yii::$service->page->widget->renderContent('category_product_price', $config, $parentThis);
        echo json_encode([
            'html' => $html,
        ]);
        exit;
    }

    /**
     * 得到子分类，如果子分类不存在，则返回同级分类。
     */
    protected function getFilterCategory()
    {
        $arr = [];
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
     * @property $filter_category | Array
     * 通过递归的方式，得到分类以及子分类的html。
     */
    protected function getFilterCategoryHtml($filter_category = '')
    {
        $str = '';
        if (!$filter_category) {
            $filter_category = $this->getFilterCategory();
        }
        if (is_array($filter_category) && !empty($filter_category)) {
            $str .= '<ul>';
            foreach ($filter_category as $cate) {
                $name = Yii::$service->store->getStoreAttrVal($cate['name'], 'name');
                $url = Yii::$service->url->getUrl($cate['url_key']);
                $current = '';
                if (isset($cate['current']) && $cate['current']) {
                    $current = 'class="current"';
                }
                $str .= '<li '.$current.'><a external href="'.$url.'">'.$name.'</a>';
                if (isset($cate['child']) && is_array($cate['child']) && !empty($cate['child'])) {
                    $str .= $this->getFilterCategoryHtml($cate['child']);
                }
                $str .= '</li>';
            }
            $str .= '</ul>';
        }
        //exit;
        return $str;
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
        $category_query  = Yii::$app->controller->module->params['category_query'];
        $numPerPage      = $category_query['numPerPage'];
        $sort            = $category_query['sort'];
        $current_sort    = Yii::$app->request->get($this->_sort);
        $frontNumPerPage = [];
        
        $frontSort = [];
        if (is_array($sort) && !empty($sort)) {
            $attrUrlStr = $this->_sort;
            $dirUrlStr  = $this->_direction;
            foreach ($sort as $np=>$info) {
                $label      = $info['label'];
                $direction  = $info['direction'];
                
                if($current_sort == $np){
                    $selected = true;
                }else{
                    $selected = false;
                }
                $frontSort[] = [
                    'label'     => $label,
                    'value'     => $np,
                    'selected'  => $selected,
                ];
            }
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
        if (!$this->_filter_attr) {
            $filter_default               = Yii::$app->controller->module->params['category_filter_attr'];
            $current_fileter_select       = $this->_category['filter_product_attr_selected'];
            $current_fileter_unselect     = $this->_category['filter_product_attr_unselected'];
            $current_fileter_select_arr   = $this->getFilterArr($current_fileter_select);
            $current_fileter_unselect_arr = $this->getFilterArr($current_fileter_unselect);
            //var_dump($current_fileter_select_arr);
            $filter_attrs                 = array_merge($filter_default, $current_fileter_select_arr);
            $filter_attrs                 = array_diff($filter_attrs, $current_fileter_unselect_arr);
            $filter_attrs                 = array_unique($filter_attrs);
            $this->_filter_attr           = $filter_attrs;
        }

        return $this->_filter_attr;
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
                $refineInfo[] = [
                    'attr' =>  $attr,
                    'val'  =>  $val,
                ];
            }
        }
        $currenctPriceFilter = Yii::$app->request->get($this->_filterPrice); 
        if($currenctPriceFilter){
            $refineInfo[] = [
                'attr' =>  $this->_filterPrice,
                'val'  =>  $currenctPriceFilter,
            ];
        }
        
        if (!empty($refineInfo)) {
            $arr[] = [
                'attr'   => 'clearAll',
                'val'    => 'clear all',
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
                        
                    }
                }
                
                $filter_info[$attr] = [
                    'label' => $label,
                    'items' => $items,
                ];
            }
        }

        return $filter_info;
    }
    /**
     * 侧栏价格过滤部分
     */
    protected function getFilterPrice()
    {
        $symbol = Yii::$service->page->currency->getCurrentSymbol();
        
        $currenctPriceFilter = Yii::$app->request->get($this->_filterPrice);
        $filter = [];
        $priceInfo = Yii::$app->controller->module->params['category_query'];
        if (isset($priceInfo['price_range']) && !empty($priceInfo['price_range']) && is_array($priceInfo['price_range'])) {
            foreach ($priceInfo['price_range'] as $price_item) {
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
     * @property $str | String
     * 字符串转换成数组。
     */
    protected function getFilterArr($str)
    {
        $arr = [];
        if ($str) {
            $str = str_replace('，', ',', $str);
            $str_arr = explode(',', $str);
            foreach ($str_arr as $a) {
                $a = trim($a);
                if ($a) {
                    $arr[] = trim($a);
                }
            }
        }

        return $arr;
    }
    /**
     * 用于搜索条件的排序部分
     */
    protected function getOrderBy()
    {
        $primaryKey = Yii::$service->category->getPrimaryKey();
        $sort       = Yii::$app->request->get($this->_sort);
        $direction  = Yii::$app->request->get($this->_direction);

        $category_query_config = Yii::$app->controller->module->params['category_query'];
        if (isset($category_query_config['sort'])) {
            $sortConfig = $category_query_config['sort'];
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
                if ($direction == 'desc') {
                    $direction = -1;
                } else {
                    $direction = 1;
                }
                //var_dump([$db_columns => $direction]);
                //exit;
                return [$db_columns => $direction];
            }
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
            $category_query_config = Yii::$app->getModule('catalog')->params['category_query'];
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
        $select = [
                'sku', 'spu', 'name', 'image',
                'price', 'special_price',
                'special_from', 'special_to',
                'url_key', 'score',
            ];
        $category_query = Yii::$app->getModule('catalog')->params['category_query'];
        if (is_array($category_query['sort'])) {
            foreach ($category_query['sort'] as $sort_item) {
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
                $i++;
                $products[$k]['url'] = '/catalog/product/'.$one['_id']; 
                $products[$k]['image'] = Yii::$service->product->image->getResize($v['image'],296,false);
                $priceInfo = Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($v['price'], $v['special_price'],$v['special_from'],$v['special_to']);
                $products[$k]['price'] = isset($priceInfo['price']) ? $priceInfo['price'] : '';
                $products[$k]['special_price'] = isset($priceInfo['special_price']) ? $priceInfo['special_price'] : '';
                
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
        $filter_price = Yii::$app->request->get($this->_filterPrice);
        //echo $filter_price;
        list($f_price, $l_price) = explode('-', $filter_price);
        if ($f_price == '0' || $f_price) {
            $where[$this->_filterPriceAttr]['$gte'] = (float) $f_price;
        }
        if ($l_price) {
            $where[$this->_filterPriceAttr]['$lte'] = (float) $l_price;
        }
        $where['category'] = $this->_primaryVal;
        //var_dump($where);exit;
        return $where;
    }
    /**
     * 分类部分的初始化
     * 对一些属性进行赋值。
     */
    protected function initCategory()
    {
        $primaryKey = 'category_id';
        $primaryVal = Yii::$app->request->get($primaryKey);
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