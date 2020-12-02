<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Catalog\block\product;

// use fecshop\app\appfront\modules\Catalog\helpers\Review as ReviewHelper;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends \yii\base\BaseObject
{
    // 当前产品
    protected $_product;
    // 产品页面的title
    protected $_title;
    // 产品的主键对应的值
    protected $_primaryVal;
    // 产品的spu属性数组。
    protected $_productSpuAttrArr;
    // 以图片方式显示的spu属性
    protected $_spuAttrShowAsImg;
    // 在产品详细页面，在橱窗图部分，以放大镜的方式显示的产品图片列表
    protected $_image_thumbnails;
    // 在产品详细页面，在产品描述部分显示的产品图片列表
    protected $_image_detail;
    protected $_brandName;
    /**
     * 为了可以使用rewriteMap，use 引入的文件统一采用下面的方式，通过Yii::mapGet()得到className和Object
     */
    protected $_reviewHelperName = '\fecshop\app\appfront\modules\Catalog\helpers\Review';
    protected $_reviewHelper;
    protected $_currentSpuAttrValArr;
    protected $_spuAttrShowAsImgArr;
    
    public function init()
    {
        /**
         * 通过Yii::mapGet() 得到重写后的class类名以及对象。Yii::mapGet是在文件@fecshop\yii\Yii.php中
         */
        list($this->_reviewHelperName,$this->_reviewHelper) = Yii::mapGet($this->_reviewHelperName);  
        parent::init();
    }
    
    public function getLastData()
    {
        $reviewHelper = $this->_reviewHelper;
        //$productImgSize = Yii::$app->controller->module->params['productImgSize'];
        //$productImgMagnifier = Yii::$app->controller->module->params['productImgMagnifier'];
        
        $appName = Yii::$service->helper->getAppName();
        $product_small_img_width = Yii::$app->store->get($appName.'_catalog','product_small_img_width');
        $product_small_img_height = Yii::$app->store->get($appName.'_catalog','product_small_img_height');
        $product_middle_img_width = Yii::$app->store->get($appName.'_catalog','product_middle_img_width');
        $productImgMagnifier = Yii::$app->store->get($appName.'_catalog','productImgMagnifier');
        
        if (!$this->initProduct()) {
            Yii::$service->url->redirect404();
            return;
        }
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        $reviewHelper::initReviewConfig();
        list($review_count, $reviw_rate_star_average, $reviw_rate_star_info) = $reviewHelper::getReviewAndStarCount($this->_product);
        
        $this->filterProductImg($this->_product['image']);
        // var_dump($this->_product['attr_group']);
        $groupAttrInfo = Yii::$service->product->getGroupAttrInfo($this->_product['attr_group']);
        //var_dump($groupAttrInfo);
        $groupAttrArr = $this->getGroupAttrArr($groupAttrInfo);
        //var_dump($groupAttrArr );
        return [
            'groupAttrArr'              => $groupAttrArr,
            'name'                      => Yii::$service->store->getStoreAttrVal($this->_product['name'], 'name'),
            'image_thumbnails'          => $this->_image_thumbnails,
            'image_detail'              => $this->_image_detail,
            'sku'                       => $this->_product['sku'],
            'is_in_stock'                     => $this->_product['is_in_stock'],
            'package_number'            => $this->_product['package_number'],
            'spu'                       => $this->_product['spu'],
            'attr_group'                => $this->_product['attr_group'],
            'review_count'              => $review_count,
            'reviw_rate_star_average'   => $reviw_rate_star_average,
            'reviw_rate_star_info'      => $reviw_rate_star_info,
            'price_info'                => $this->getProductPriceInfo(),
            'tier_price'                => $this->_product['tier_price'],
            'media_size' => [
                'small_img_width'       => $product_small_img_width,
                'small_img_height'      => $product_small_img_height,
                'middle_img_width'      => $product_middle_img_width,
            ],
            'productImgMagnifier'       => $productImgMagnifier,
            'options'                   => $this->getSameSpuInfo(),
            'custom_option'             => $this->_product['custom_option'],
            'short_description'         => Yii::$service->store->getStoreAttrVal($this->_product['short_description'], 'short_description'),
            'description'               => Yii::$service->store->getStoreAttrVal($this->_product['description'], 'description'),
            '_id'                       => $this->_product[$productPrimaryKey],
            'buy_also_buy'              => $this->getProductBuyAlsoBuy(),
            'brand_name' => $this->_brandName,
        ];
    }
    public function getGroupAttrArr($groupAttrInfo){
        $gArr = [];
        if ($this->_product['brand_id']) {
            $brandName = Yii::$service->page->translate->__('brand');
            $this->_brandName = $gArr[$brandName] = Yii::$service->product->brand->getBrandNameById($this->_product['brand_id']);
        }
        // 增加重量，长宽高，体积重等信息
        if ($this->_product['weight']) {
            $weightName = Yii::$service->page->translate->__('weight');
            $gArr[$weightName] = $this->_product['weight'].' g';
        }
        if ($this->_product['long']) {
            $longName = Yii::$service->page->translate->__('long');
            $gArr[$longName] = $this->_product['long'].' Cm';
        }
        if ($this->_product['width']) {
            $widthName = Yii::$service->page->translate->__('width');
            $gArr[$widthName] = $this->_product['width'].' Cm';
        }
        if ($this->_product['high']) {
            $highName = Yii::$service->page->translate->__('high');
            $gArr[$highName] = $this->_product['high'].' Cm';
        }
        if ($this->_product['volume_weight']) {
            $volumeWeightName = Yii::$service->page->translate->__('volume weight');
            $gArr[$volumeWeightName] = $this->_product['volume_weight'].' g';
        }
        if (is_array($groupAttrInfo)) {
            foreach ($groupAttrInfo as $attr => $info) {
                //var_dump($attr);
                //var_dump($info);
                if (isset($this->_product[$attr]) && $this->_product[$attr]) {
                    $attrVal = $this->_product[$attr];
                    // get translate 
                    if (isset($info['display']['lang']) && $info['display']['lang'] && is_array($attrVal)) {
                        $attrVal = Yii::$service->store->getStoreAttrVal($attrVal, $attr);
                    } else if ($attrVal && !is_array($attrVal)) {
                        $attrVal = Yii::$service->page->translate->__($attrVal);
                    }
                    $attr = Yii::$service->page->translate->__($attr);
                    $gArr[$attr] = $attrVal;
                } else if (isset($this->_product['attr_group_info']) && is_array($this->_product['attr_group_info'])) {
                    $attr_group_info = $this->_product['attr_group_info'];
                    if (isset($attr_group_info[$attr]) && $attr_group_info[$attr]) {
                        $attrVal = $attr_group_info[$attr];
                        // get translate 
                        if (isset($info['display']['lang']) && $info['display']['lang'] && is_array($attrVal)) {
                            $attrVal = Yii::$service->store->getStoreAttrVal($attrVal, $attr);
                        } else if ($attrVal && !is_array($attrVal)) {
                            $attrVal = Yii::$service->page->translate->__($attrVal);
                        }
                        $attr = Yii::$service->page->translate->__($attr);
                        $gArr[$attr] = $attrVal;
                    }
                }
            }
        }
        
        return $gArr;
    }
    /**
     * @param $product_images | Array ，产品的图片属性
     * 根据图片数组，得到橱窗图，和描述图
     * 橱窗图：在产品详细页面顶部，放大镜显示部分的产品列表
     * 描述图，在产品description文字描述后面显示的产品图片。
     */
    public function filterProductImg($product_images){
        $this->_image_thumbnails        = $product_images;
        //$this->_image_detail['gallery'] = $product_images['gallery'];
        if (isset($product_images['main']['is_detail']) && $product_images['main']['is_detail'] == 1 ) {
            $this->_image_detail[] = $product_images['main'];
        }
        if (isset($product_images['gallery']) && is_array($product_images['gallery'])) {
            $thumbnails_arr = [];
            //$detail_arr     = [];
            foreach ($product_images['gallery'] as $one) {
                $is_thumbnails  = $one['is_thumbnails'];
                $is_detail      = $one['is_detail'];
                if($is_thumbnails == 1){
                    $thumbnails_arr[]   = $one;
                }
                if($is_detail == 1){
                    $this->_image_detail[]       = $one;
                }
            }
            $this->_image_thumbnails['gallery'] = $thumbnails_arr;
            //$this->_image_detail     = $detail_arr;
        }
    }
    
    /**废弃
     * @param $data | Array 和当前产品的spu相同，但sku不同的产品  数组。
     * @param $current_size | String 当前产品的size值
     * @param $current_color | String 当前产品的颜色值
     * @return array 分别为
     *               $all_attr1 所有的颜色数组
     *               $all_attr2  所有的尺码数组
     *               $attr1_2_attr2 当前尺码下的所有颜色数组。
     *               $attr2_2_attr1 当前颜色下的所有尺码数组。
     */
    protected function getAttr1AndAttr2Info($data, $current_attr1, $current_attr2, $attr1, $attr2)
    {
        $all_attr1 = [];
        $all_attr2 = [];
        $attr1_2_attr2 = [];
        $attr2_2_attr1 = [];
        //var_dump($data);
        if (is_array($data)) {
            foreach ($data as $one) {
                $attr1_val = isset($one[$attr1]) ? $one[$attr1] : '';
                $attr2_val = isset($one[$attr2]) ? $one[$attr2] : '';
                //echo $attr1_val;
                //echo $size;
                //echo '##';
                $image = $one['image'];
                $name = $one['name'];
                $url_key = $one['url_key'];
                if ($attr1_val || $attr2_val) {
                    if ($attr1_val) {
                        $all_attr1[$attr1_val] = [
                            'name'        => $name,
                            'image'    => $image,
                            'url_key'    => $url_key,
                        ];
                    }
                    if ($attr2_val) {
                        $all_attr2[$attr2_val] = [
                            'name'        => $name,
                            'image'    => $image,
                            'url_key'    => $url_key,
                        ];
                    }
                    //echo $attr2_val.'#'.$current_attr2;
                    //echo '<br/>';
                    if ($attr2_val && $current_attr2 == $attr2_val) {
                        $attr1_2_attr2[$attr1_val] = [
                            'name'        => $name,
                            'image'    => $image,
                            'url_key'    => $url_key,
                        ];
                    }
                    if ($attr1_val && $current_attr1 == $attr1_val) {
                        $attr2_2_attr1[$attr2_val] = [
                            'name'        => $name,
                            'image'    => $image,
                            'url_key'    => $url_key,
                        ];
                    }
                }
            }
        }
        return [$all_attr1, $all_attr2, $attr1_2_attr2, $attr2_2_attr1];
    }

    /**
     * @param $select | Array ， 需要查询的字段。
     * 得到当前spu下面的所有的sku的数组、
     * 这个是为了产品详细页面的spu下面的产品切换，譬如同一spu下的不同的颜色尺码切换。
     */
    protected function getSpuData($select)
    {
        $spu = $this->_product['spu'];
        
        return Yii::$service->product->spuCollData($select, $this->_productSpuAttrArr, $spu);
    }

    // 商城产品页面spu属性，显示在第一排的属性
    protected $_spuAttrShowAsTop;
    protected $_spuAttrShowAsTopArr;
    /**
     * @return Array得到spu下面的sku的spu属性的数据。用于在产品
     * 得到spu下面的sku的spu属性的数据。用于在产品详细页面显示其他的sku
     * 譬如页面：https://fecshop.appfront.fancyecommerce.com/raglan-sleeves-letter-printed-crew-neck-sweatshirt-53386451-77774122
     * 该spu的其他sku的颜色尺码也在这里显示出来。
     */
    protected function getSameSpuInfo()
    {
        $groupAttr = Yii::$service->product->getGroupAttr($this->_product['attr_group']);
        // 当前的产品对应的spu属性组的属性，譬如 ['color','size','myyy']
        $this->_productSpuAttrArr = Yii::$service->product->getSpuAttr($this->_product['attr_group']);
        if (!is_array($this->_productSpuAttrArr) || empty($this->_productSpuAttrArr)) {
            
            return;
        }
        $this->_spuAttrShowAsTop = $this->_productSpuAttrArr[0];
        //var_dump($this->_productSpuAttrArr);exit;
        $this->_spuAttrShowAsImg = Yii::$service->product->getSpuImgAttr($this->_product['attr_group']);
        $this->_currentSpuAttrValArr = [];
        foreach ($this->_productSpuAttrArr as $spuAttr) {
            if (isset($this->_product['attr_group_info']) && $this->_product['attr_group_info']) {  // mysql
                $attr_group_info = $this->_product['attr_group_info'];
                $spuAttrVal = $attr_group_info[$spuAttr];
            } else {
                $spuAttrVal = isset($this->_product[$spuAttr]) ? $this->_product[$spuAttr] : '';
            }
            
            if ($spuAttrVal) {
                $this->_currentSpuAttrValArr[$spuAttr] = $spuAttrVal;
            } else {
                // 如果某个spuAttr的值为空，则退出，这个说明产品数据有问题。
                return;
            }
        }
        // 得到当前的spu下面的所有的值
        $select = ['name', 'image', 'url_key'];
        $data = $this->getSpuData($select);
        $spuValColl = [];
        // 通过值，找到spu。
        $reverse_val_spu = [];
        if (is_array($data) && !empty($data)) {
            foreach ($data as $one) {
                $reverse_key = '';
                foreach ($this->_productSpuAttrArr as $spuAttr) {
                    $spuValColl[$spuAttr][$one[$spuAttr]] = $one[$spuAttr];
                    $reverse_key .= $one[$spuAttr];
                }

                //$active = 'class="active"';
                $one['main_img'] = isset($one['image']['main']['image']) ? $one['image']['main']['image'] : '';
                $one['url'] = Yii::$service->url->getUrl($one['url_key']);
                $reverse_val_spu[$reverse_key] = $one;
                $showAsImgVal = $one[$this->_spuAttrShowAsImg];
                if ($showAsImgVal) {
                    if (!isset($this->_spuAttrShowAsImgArr[$this->_spuAttrShowAsImg])) {
                        $this->_spuAttrShowAsImgArr[$showAsImgVal] = $one;
                    }
                }
                // 显示在顶部的spu属性（当没有图片属性的时候使用）
                $showAsTopVal = $one[$this->_spuAttrShowAsTop];
                if ($showAsTopVal) {
                    if (!isset($this->_spuAttrShowAsTopArr[$this->_spuAttrShowAsTop])) {
                        $this->_spuAttrShowAsTopArr[$showAsTopVal] = $one;
                    }
                }
            }
        }
        
        // 得到各个spu属性对应的值的集合。
        foreach ($spuValColl as $spuAttr => $attrValArr) {
            $spuValColl[$spuAttr] = array_unique($attrValArr);
            $spuValColl[$spuAttr] = $this->sortSpuAttr($spuAttr, $spuValColl[$spuAttr]);
        }

        $spuShowArr = [];
        foreach ($spuValColl as $spuAttr => $attrValArr) {
            $attr_coll = [];
            foreach ($attrValArr as $attrVal) {
                $attr_info = $this->getSpuAttrInfo($spuAttr, $attrVal, $reverse_val_spu);
                $attr_coll[] = $attr_info;
            }
            $spuShowArr[] = [
                'label' => $spuAttr,
                'value' => $attr_coll,
            ];
        }

        return $spuShowArr;
    }
    // spu属性部分
    protected function getSpuAttrInfo($spuAttr, $attrVal, $reverse_val_spu)
    {
        $current = $this->_currentSpuAttrValArr;
        $active = false;
        if (isset($this->_currentSpuAttrValArr[$spuAttr])) {
            if ($attrVal != $this->_currentSpuAttrValArr[$spuAttr]) {
                $current[$spuAttr] = $attrVal;
            } else {
                $active = true;
            }
        }
        $reverse_key = $this->generateSpuReverseKey($current);
        $return = [];
        $return['attr_val'] = $attrVal;
        $return['active'] = 'noactive';
        if (isset($reverse_val_spu[$reverse_key]) && is_array($reverse_val_spu[$reverse_key])) {
            $return['active'] = 'active';
            $arr = $reverse_val_spu[$reverse_key];
            foreach ($arr as $k=>$v) {
                $return[$k] = $v;
            }
            if ($spuAttr == $this->_spuAttrShowAsImg) {
                $return['show_as_img'] = $arr['main_img'];
            }
        } else if ($this->_spuAttrShowAsImg){
            // 如果是图片，不存在，则使用备用的。
            if ($spuAttr == $this->_spuAttrShowAsImg) {
                $return['active'] = 'active';
                $arr = $this->_spuAttrShowAsImgArr[$attrVal];
                if (is_array($arr) && !empty($arr)) {
                    foreach ($arr as $k=>$v) {
                        $return[$k] = $v;
                    }
                }
                $return['show_as_img'] = $arr['main_img'];
            }
        } else if ($this->_spuAttrShowAsTop){
            if ($spuAttr == $this->_spuAttrShowAsTop) {
                $return['active'] = 'active';
                $arr = $this->_spuAttrShowAsTopArr[$attrVal];
                if (is_array($arr) && !empty($arr)) {
                    foreach ($arr as $k=>$v) {
                        $return[$k] = $v;
                    }
                }
            }
        }
        if ($active) {
            $return['active'] = 'current';
        }

        return $return;
    }

    protected function generateSpuReverseKey($one)
    {
        $reverse_key = '';
        if (is_array($this->_productSpuAttrArr)) {
            foreach ($this->_productSpuAttrArr as $spuAttr) {
                $spuValColl[$spuAttr][] = $one[$spuAttr];
                $reverse_key .= $one[$spuAttr];
            }
        }

        return  $reverse_key;
    }

    /**
     *	@param $data | Array  各个尺码对应的产品数组
     *  @return array 排序后的数组
     *		该函数，按照在配置中的size的顺序，将$data中的数据进行排序，让其按照尺码的由小到大的顺序
     * 		排列，譬如 ：s,m,l,xl,xxl,xxxl等
     */
    protected function sortSpuAttr($spuAttr, $data)
    {
        // 对size排序一下
        $size = [];
        $attr_group = $this->_product['attr_group'];
        $attrInfo = Yii::$service->product->getGroupAttrInfo($attr_group);
        $size_arr = isset($attrInfo[$spuAttr]['display']['data']) ? $attrInfo[$spuAttr]['display']['data'] : '';
        $d_arr = [];
        if (is_array($size_arr) && !empty($size_arr)) {
            foreach ($size_arr as $size) {
                if (isset($data[$size])) {
                    $d_arr[$size] = $data[$size];
                }
            }
            if (!empty($d_arr)) {
                // 不在里面的规格属性（新建产品添加的规格属性），添加进去
                foreach ($data as $size=>$d) {
                    if (!isset($d_arr[$size])) {
                        $d_arr[$size] = $data[$size];
                    }
                }
                return $d_arr;
            }
        }

        return $data;
    }

    /**
     * @return array
     *               得到当前货币状态下的产品的价格和特价信息。
     */
    protected function getProductPriceInfo()
    {
        $price = $this->_product['price'];
        $special_price = $this->_product['special_price'];
        $special_from = $this->_product['special_from'];
        $special_to = $this->_product['special_to'];

        return Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($price, $special_price, $special_from, $special_to);
    }

    /**
     * 初始化数据包括
     * 主键值：$this->_primaryVal
     * 当前产品对象：$this->_product
     * Meta keywords ， Meta Description等信息的设置。
     * Title的设置。
     * 根据当前产品的attr_group(属性组信息)重新给Product Model增加相应的属性组信息
     * 然后，重新获取当前产品对象：$this->_product，此时加入了配置中属性组对应的属性。
     */
    protected function initProduct()
    {
        $primaryKey = Yii::$service->product->getPrimaryKey();
        $primaryVal = Yii::$app->request->get($primaryKey);
        $this->_primaryVal = $primaryVal;
        $product = Yii::$service->product->getByPrimaryKey($primaryVal);
        if ($product) {
            $enableStatus = Yii::$service->product->getEnableStatus();
            if ($product['status'] != $enableStatus){
                
                return false;
            }
        } else {
            
            return false;
        }
        $this->_product = $product;
        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => Yii::$service->store->getStoreAttrVal($product['meta_keywords'], 'meta_keywords'),
        ]);
        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => Yii::$service->store->getStoreAttrVal($product['meta_description'], 'meta_description'),
        ]);
        $this->_title = Yii::$service->store->getStoreAttrVal($product['meta_title'], 'meta_title');
        $name = Yii::$service->store->getStoreAttrVal($product['name'], 'name');
        $this->breadcrumbs($name);
        $this->_title = $this->_title ? $this->_title : $name;
        Yii::$app->view->title = $this->_title;
        //$this->_where = $this->initWhere();

        // 通过上面查询的属性组，得到属性组对应的属性列表
        // 然后重新查询产品
        $attr_group = $this->_product['attr_group'];

        Yii::$service->product->addGroupAttrs($attr_group);

        // 重新查询产品信息。
        $product = Yii::$service->product->getByPrimaryKey($primaryVal);
        $this->_product = $product;
        return true;
    }

    // 面包屑导航
    protected function breadcrumbs($name)
    {
        $appName = Yii::$service->helper->getAppName();
        $category_breadcrumbs = Yii::$app->store->get($appName.'_catalog','product_breadcrumbs');
        if ($category_breadcrumbs == Yii::$app->store->enable) {
            Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
        } else {
            Yii::$service->page->breadcrumbs->active = false;
        }
    }
    // 买了的人还买了什么，通过产品字段取出来sku，然后查询得到。
    protected function getProductBuyAlsoBuy()
    {
        $buy_also_buy_sku = $this->_product['buy_also_buy_sku'];
        if ($buy_also_buy_sku) {
            $skus = explode(',', $buy_also_buy_sku);
            if (is_array($skus) && !empty($skus)) {
                $filter['select'] = [
                    'sku', 'spu', 'name', 'image',
                    'price', 'special_price',
                    'special_from', 'special_to',
                    'url_key', 'score', 'reviw_rate_star_average', 'review_count'
                ];
                $filter['where'] = ['in', 'sku', $skus];
                $products = Yii::$service->product->getProducts($filter);
                //var_dump($products);
                $products = Yii::$service->category->product->convertToCategoryInfo($products);

                return $products;
            }
        }
    }
}
