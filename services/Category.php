<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;
/**
 * Category service.
 *
 * @method coll($filters = [])
 * @see \fecshop\services\Category::actionColl()
 * @method getCategoryEnableStatus()
 * @see \fecshop\services\Category::actionGetCategoryEnableStatus()
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Category extends Service
{
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage; //     = 'CategoryMysqldb';   //  CategoryMongodb | CategoryMysqldb  当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';
    
    /**
     * 自定义分类过滤属性信息，数据例子如下，您可以在配置中注入
     * [
            'brand_id' => [
                'label' => 'Brand',
                'items' =>  [
                    1 => '华为',
                    3 => '小米',
                    4 => '大华',
                ],
            ],
        ]
      */
    public $customCategoryFilterAttr;
    
    /**
     * @var \fecshop\services\category\CategoryInterface
     */
    protected $_category;

    /**
     * init function , 初始化category，使用哪一个category service.
     * 目前只支持mongodb，不支持mysql
     */
    public function init()
    {
        parent::init();
        // 从数据库配置中得到值, 设置成当前service存储，是Mysqldb 还是 Mongodb
        $config = Yii::$app->store->get('service_db', 'category_and_product');
        $this->storage = 'CategoryMysqldb';
        if ($config == Yii::$app->store->serviceMongodbName) {
            $this->storage = 'CategoryMongodb';
        }
        $currentService = $this->getStorageService($this);
        $this->_category = new $currentService();
        
    }
    // 动态更改为mongodb model
    public function changeToMongoStorage()
    {
        $this->storage     = 'CategoryMongodb';
        $currentService = $this->getStorageService($this);
        $this->_category = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMysqlStorage()
    {
        $this->storage     = 'CategoryMysqldb';
        $currentService = $this->getStorageService($this);
        $this->_category = new $currentService();
    }
    
    public function getCategoryEnableStatus()
    {
        return $this->_category->getCategoryEnableStatus();
    }

    public function getCategoryMenuShowStatus()
    {
        return $this->_category->getCategoryMenuShowStatus();
    }

    /**
     * 得到当前的category service 对应的主键名称，譬如如果是mongo，返回的是 _id.
     */
    public function getPrimaryKey()
    {
        return $this->_category->getPrimaryKey();
    }

    /**
     * 得到category model的全名.
     */
    public function getModelName()
    {
        return get_class($this->_category->getByPrimaryKey());
    }

    /**
     * @param $primaryKey | String or Int , 主键
     * 通过主键，得到category info
     */
    public function getByPrimaryKey($primaryKey)
    {
        return $this->_category->getByPrimaryKey($primaryKey);
    }

    /**
     * @param $urlKey | String or Int , Url Key
     * 通过主键，得到category info
     */
    public function getByUrlKey($urlKey)
    {
        return $this->_category->getByUrlKey($urlKey);
    }

    public function collCount($filter = '')
    {
        return $this->_category->collCount($filter);
    }

    /**
     * Get category collection by $filter.
     * @param array $filter
     * example filter:
     * [
     *      'numPerPage'    => 20,
     *      'pageNum'       => 1,
     *      'orderBy'   => ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     *      'where'         => [
     *          ['>','price','1'],
     *          ['<','price','10'],
     *          ['sku' => 'uk10001'],
     *          ],
     *      'asArray' => true,
     * ]
     */
    public function coll($filter = [])
    {
        return $this->_category->coll($filter);
    }
    
    public function apiColl($filter = [])
    {
        return $this->_category->apiColl($filter);
    }
    
    public function findOne($where)
    {
        return $this->_category->findOne($where);
    }
    /**
     *  得到分类的树数组。
     *  数组中只有  id  name(default language), child(子分类) 等数据。
     *  目前此函数仅仅用于后台对分类的编辑使用。 appadmin.
     */
    public function getTreeArr($rootCategoryId = 0, $lang = '', $appserver = false, $level = 1)
    {
        return $this->_category->getTreeArr($rootCategoryId, $lang, $appserver);
    }

    /**
     * @param $one|array , save one data . 分类数组
     * @param $originUrlKey|string , 分类的在修改之前的url key.（在数据库中保存的url_key字段，如果没有则为空）
     * 保存分类，同时生成分类的伪静态url（自定义url），如果按照name生成的url或者自定义的urlkey存在，系统则会增加几个随机数字字符串，来增加唯一性。
     */
    public function save($one, $originUrlKey = 'catalog/category/index')
    {
        return $this->_category->save($one, $originUrlKey);
    }
    /**
     *
     */
    public function sync($arr)
    {
        return $this->_category->sync($arr);
    }

    /**
     * @param $id | String  主键值
     * 通过主键值找到分类，并且删除分类在url rewrite表中的记录
     * 查看这个分类是否存在子分类，如果存在子分类，则删除所有的子分类，以及子分类在url rewrite表中对应的数据。
     */
    public function remove($id)
    {
        return $this->_category->remove($id);
    }

    /**
     * @param $parent_id|string
     * 通过当前分类的parent_id字段（当前分类的上级分类id），得到所有的上级分类数组。
     * 里面包含的信息为：name，url_key。
     * 譬如一个分类为三级分类，将他的parent_id传递给这个函数，那么，他返回的数组信息为[一级分类的信息（name，url_key），二级分类的信息（name，url_key）].
     * 目前这个功能用于前端分类页面的面包屑导航。
     */
    public function getAllParentInfo($parent_id)
    {
        return $this->_category->getAllParentInfo($parent_id);
    }

    /**
     * @param $category_id|string  当前的分类_id
     * @param $parent_id|string  当前的分类上级id parent_id
     * 这个功能是点击分类后，在产品分类页面侧栏的子分类菜单导航，详细的逻辑如下：
     * 1.如果level为一级，那么title部分为当前的分类，子分类为一级分类下的二级分类
     * 2.如果level为二级，那么将所有的二级分类列出，当前的二级分类，会列出来当前二级分类对应的子分类
     * 3.如果level为三级，那么将所有的二级分类列出。当前三级分类的所有姊妹分类（同一个父类）列出，当前三级分类如果有子分类，则列出
     * 4.依次递归。
     * 具体的显示效果，请查看appfront 对应的分类页面。
     */
    public function getFilterCategory($category_id, $parent_id)
    {
        return $this->_category->getFilterCategory($category_id, $parent_id);
    }
    
    /**
     * 得到category model的全名.
     */
    public function getChildCategory($category_id)
    {
        return $this->_category->getChildCategory($category_id);
    }
    
    public function excelSave($categoryArr)
    {
        return $this->_category->excelSave($categoryArr);
    }
    
    protected $_filter_attr;
    /**
     * 得到分类侧栏属性过滤的产品属性数组
     */
    public function getFilterAttr($categoryM)
    {
        if (!$this->_filter_attr) {
            $appName = Yii::$service->helper->getAppName();
            $filter_default = Yii::$app->store->get($appName.'_catalog','category_filter_attr');
            $filter_default = explode(',',$filter_default);
            $current_fileter_select = $categoryM['filter_product_attr_selected'];
            $current_fileter_unselect = $categoryM['filter_product_attr_unselected'];
            $current_fileter_select_arr = $this->getFilterArr($current_fileter_select);
            $current_fileter_unselect_arr = $this->getFilterArr($current_fileter_unselect);
            $filter_attrs = array_merge($filter_default, $current_fileter_select_arr);
            $filter_attrs = array_diff($filter_attrs, $current_fileter_unselect_arr);
            $this->_filter_attr = $filter_attrs;
            $this->_filter_attr[] = 'brand_id';
        }
        //var_dump($this->_filter_attr);
        
        //echo 1;
        return $this->_filter_attr;
    }
    
    
    protected $_customCategoryFilterAttrInfo;
    /**
     * 分类页面-属性过滤-自定义属性以及属性值。
     */
    public function customCategoryFilterAttrInfo()
    {
        if (!$this->_customCategoryFilterAttrInfo) {
            $customCategoryFilterAttr = $this->customCategoryFilterAttr;
            // 加入品牌
            $customCategoryFilterAttr['brand_id'] = [
                'label' => 'Brand',
                'items' => Yii::$service->product->brand->getAllBrandIdAndNames(),
            ];
                
            $this->_customCategoryFilterAttrInfo = $customCategoryFilterAttr;
        }
        
        return $this->_customCategoryFilterAttrInfo;
    }
    /**
     * @param $attr | string, 属性名称
     * @param $attrVal | string, 属性值
     * 得到自定义的属性值。
     */
    public function getCustomCategoryFilterAttrItemLabel($attr, $attrVal)
    {
        $customAttrInfo = $this->customCategoryFilterAttrInfo();
        if (isset($customAttrInfo[$attr]['items'][$attrVal]) && $customAttrInfo[$attr]['items'][$attrVal]) {
            
            return $customAttrInfo[$attr]['items'][$attrVal];
        }
        
        return '';
    }
    
    public function getCustomCategoryFilterAttrLabel($attr)
    {
        $customAttrInfo = $this->customCategoryFilterAttrInfo();
        if (isset($customAttrInfo[$attr]['label']) && $customAttrInfo[$attr]['label']) {
            
            return $customAttrInfo[$attr]['label'];
        }
        
        return $attr;
    }
    
    /**
     * @param $categoryM | object, category model
     * @param $whereParam | array, 分类数据过滤的where数组
     * @param $chosenAttrs | array， appserver入口传递的数组，appfront，apphtml5忽视
     * @return 
        [
            'color' => [
                'name' => 'color',
                'label'  => 'Colour',
                'items' => [
                    ['name' => 'white', 'label' => 'White', 'count' => 3, 'url'=> 'http://www.xx.com/xxxx', 'selected' = true],
                    ['name' => 'multicolor', 'label' => 'White',  'count' => 6, 'url'=> 'http://www.xx.com/xxxx', 'selected' = false],
                    ['name' => 'black', 'label' => 'White', 'count' => 13, 'url'=> 'http://www.xx.com/xxxx', 'selected' = false],
                ],
            ],
            'size' => [
                'name' => 'size',
                'label' => 'Size',
                'items' => [
                    ['name' => 's', 'label' => 'S', 'count' => 3, 'url'=> 'http://www.xx.com/xxxx', 'selected' = true],
                    ['name' => 'm', 'label' => 'M',  'count' => 6, 'url'=> 'http://www.xx.com/xxxx', 'selected' = false],
                    ['name' => 'l', 'label' => 'L', 'count' => 13, 'url'=> 'http://www.xx.com/xxxx', 'selected' = false],
                ],
            ],
        ]
     */
    public function getFilterInfo($categoryM, $whereParam, $chosenAttrs = [])
    {
        $filter_info = [];
        $filter_attrs = $this->getFilterAttr($categoryM);
        $customAttrInfo = $this->customCategoryFilterAttrInfo();
        if (is_array($filter_attrs) && !empty($filter_attrs)) {
            foreach ($filter_attrs as $attr) {
                if ($attr != 'price') {
                    $attrFilterItem = [];
                    $attrFilterItem['name'] = $attr;
                    $attrLabel = '';
                    // filter
                    $attrFilter = Yii::$service->product->getFrontCategoryFilter($attr, $whereParam);
                    if (isset($customAttrInfo[$attr]) && $customAttrInfo[$attr]) {
                        $attrLabel = $customAttrInfo[$attr]['label'];
                    }
                    // 非api入口
                    if (!Yii::$service->helper->isApiApp()) {
                        if (!$attrLabel) {
                            $attrLabel = Yii::$service->page->translate->__($attr);
                        }
                        $attrFilterItem['label'] = $attrLabel;
                        $attrUrlStr = Yii::$service->url->category->attrValConvertUrlStr($attr);
                    } else {
                        if (!$attrLabel) {
                            $attrLabel = preg_replace_callback('/([-_]+([a-z]{1}))/i',function($matches){
                                return ' '.strtoupper($matches[2]);
                            },$attr);
                        }
                        $attrFilterItem['label'] = $attrLabel;
                    }
                    // 处理items
                    if (is_array($attrFilter) && !empty($attrFilter)) {
                        // var_dump($attrFilter);exit;
                        foreach ($attrFilter as $k=>$item) {
                            $itemName    = $item['_id'];
                            if (!$itemName) {
                                continue;
                            }
                            $itemLabel = $this->getCustomCategoryFilterAttrItemLabel($attr, $itemName);
                            // 非appapi入口
                            if (!Yii::$service->helper->isApiApp()) {
                                $count  = $item['count'];
                                if (!$itemLabel) {
                                    $itemLabel = Yii::$service->page->translate->__($itemName);
                                }
                                $urlInfo = Yii::$service->url->category->getFilterChooseAttrUrl($attrUrlStr, $itemName, 'p');
                                $url = $urlInfo['url'];
                                $selected = $urlInfo['selected'] ? true : false;
                                $attrFilterItem['items'][] = [
                                    '_id' => $itemName,
                                    'label' => $itemLabel, 
                                    'count' => $count, 
                                    'url'=> $url, 
                                    'selected' => $selected,
                                ];
                            } else { // appserver 入口
                                $chosenAttrArr = json_decode($chosenAttrs,true);
                                if(isset($chosenAttrArr[$attr]) && $chosenAttrArr[$attr] == $item['_id']){
                                    $item['selected'] = true;
                                } else {
                                    $item['selected'] = false;
                                }
                                if (!$itemLabel) {
                                    $itemLabel = Yii::$service->page->translate->__($itemName);
                                }
                                $item['label'] = $itemLabel;
                                
                                $attrFilterItem['items'][$k] = $item;
                            }
                        }
                    }
                    if (is_array($attrFilterItem['items']) && !empty($attrFilterItem['items'])) {
                        $filter_info[$attr] = $attrFilterItem;
                    }
                    
                }
            }
        }
        
        return $filter_info;
    }
    
    /**
     * @param $str | String
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
     * 是否在分类产品列表页面，进行价格过滤
     */
    public function isEnableFilterPrice()
    {
        $appName = Yii::$service->helper->getAppName();
        $category_filter_price = Yii::$app->store->get($appName.'_catalog','category_filter_price');
        if ($category_filter_price == Yii::$app->store->enable) {
            
            return true;
        }
        
        return false;
    }
    
    
    /**
     * 是否在分类产品列表页面，进行子分类显示
     */
    public function isEnableFilterSubCategory()
    {
        $appName = Yii::$service->helper->getAppName();
        $category_filter_category = Yii::$app->store->get($appName.'_catalog','category_filter_category');
        if ($category_filter_category == Yii::$app->store->enable) {
            
            return true;
        }
        
        return false;
    }
    
    
    
    
    
    
    
    
    
    
    
    
}
