<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Catalog\block\product;

//use fecshop\app\apphtml5\modules\Catalog\helpers\Review as ReviewHelper;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
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
    /**
     * 为了可以使用rewriteMap，use 引入的文件统一采用下面的方式，通过Yii::mapGet()得到className和Object
     */
    protected $_reviewHelperName = '\fecshop\app\apphtml5\modules\Catalog\helpers\Review';
    protected $_reviewHelper;
    
    public function __construct()
    {
        /**
         * 通过Yii::mapGet() 得到重写后的class类名以及对象。Yii::mapGet是在文件@fecshop\yii\Yii.php中
         */
        list($this->_reviewHelperName,$this->_reviewHelper) = Yii::mapGet($this->_reviewHelperName);  
        
    }
    public function getLastData()
    {
        $productImgSize = Yii::$app->controller->module->params['productImgSize'];
        $productImgMagnifier = Yii::$app->controller->module->params['productImgMagnifier'];
        if(!$this->initProduct()){
            Yii::$service->url->redirect404();
            return;
        }
        $reviewHelper = $this->_reviewHelper;
        $reviewHelper::initReviewConfig();
        $ReviewAndStarCount = $reviewHelper::getReviewAndStarCount($this->_product);
        list($review_count, $reviw_rate_star_average) = $ReviewAndStarCount;
        $this->filterProductImg($this->_product['image']);
        $groupAttr = Yii::$service->product->getGroupAttr($this->_product['attr_group']);
        $groupAttrArr = $this->getGroupAttrArr($groupAttr);
        return [
            'groupAttrArr'              => $groupAttrArr,
            'name'                      => Yii::$service->store->getStoreAttrVal($this->_product['name'], 'name'),
            'image_thumbnails'          => $this->_image_thumbnails,
            'image_detail'              => $this->_image_detail,
            'sku'                       => $this->_product['sku'],
            'spu'                       => $this->_product['spu'],
            'attr_group'                => $this->_product['attr_group'],
            'review_count'              => $review_count,
            'reviw_rate_star_average'   => $reviw_rate_star_average,
            'price_info'                => $this->getProductPriceInfo(),
            'tier_price'                => $this->_product['tier_price'],
            'media_size' => [
                'small_img_width'       => $productImgSize['small_img_width'],
                'small_img_height'      => $productImgSize['small_img_height'],
                'middle_img_width'      => $productImgSize['middle_img_width'],
            ],
            'productImgMagnifier'       => $productImgMagnifier,
            'options'                   => $this->getSameSpuInfo(),
            'custom_option'             => $this->_product['custom_option'],
            'description'               => Yii::$service->store->getStoreAttrVal($this->_product['description'], 'description'),
            '_id'                       => $this->_product['_id'],
            'buy_also_buy'              => $this->getProductBySkus($skus),
        ];
    }
    public function getGroupAttrArr($groupAttr){
        $gArr = [];
        if(is_array($groupAttr)){
            foreach($groupAttr as $attr){
                if(isset($this->_product[$attr]) && $this->_product[$attr]){
                    $gArr[$attr] = $this->_product[$attr];
                }
            }
        }
        //var_dump($gArr);
        return $gArr;
    }
    /**
     * @property $product_images | Array ，产品的图片属性
     * 根据图片数组，得到橱窗图，和描述图
     * 橱窗图：在产品详细页面顶部，放大镜显示部分的产品列表
     * 描述图，在产品description文字描述后面显示的产品图片。
     */
    public function filterProductImg($product_images){
        $this->_image_thumbnails        = $product_images;
        //$this->_image_detail['gallery'] = $product_images['gallery'];
        if(isset($product_images['gallery']) && is_array($product_images['gallery'])){
            $thumbnails_arr = [];
            $detail_arr     = [];
            foreach($product_images['gallery'] as $one){
                $is_thumbnails  = $one['is_thumbnails'];
                $is_detail      = $one['is_detail'];
                if($is_thumbnails == 1){
                    $thumbnails_arr[]   = $one;
                }
                if($is_detail == 1){
                    $detail_arr[]       = $one;
                }
            }
            $this->_image_thumbnails['gallery'] = $thumbnails_arr;
            $this->_image_detail     = $detail_arr;
        }
        if(isset($product_images['main']['is_detail']) && $product_images['main']['is_detail'] == 1 ){
            $this->_image_detail[] = $product_images['main'];
        }
        
    }
    
    /**废弃
     * @property $data | Array 和当前产品的spu相同，但sku不同的产品  数组。
     * @property $current_size | String 当前产品的size值
     * @property $current_color | String 当前产品的颜色值
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

        return [$all_attr1, $all_attr2, $attr1_2_attr2, $attr2_2_attr1];
    }

    /**
     * @property $select | Array ， 需要查询的字段。
     * 得到当前spu下面的所有的sku的数组、
     * 这个是为了产品详细页面的spu下面的产品切换，譬如同一spu下的不同的颜色尺码切换。
     */
    protected function getSpuData($select)
    {
        $spu = $this->_product['spu'];
        $select = array_merge($select, $this->_productSpuAttrArr);

        $filter = [
            'select'    => $select,
            'where'            => [
                ['spu' => $spu],
            ],
            'asArray' => true,
        ];
        $coll = Yii::$service->product->coll($filter);

        return $coll['coll'];
    }

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
        //var_dump($this->_productSpuAttrArr);exit;
        $this->_spuAttrShowAsImg = Yii::$service->product->getSpuImgAttr($this->_product['attr_group']);
        if (!is_array($this->_productSpuAttrArr) || empty($this->_productSpuAttrArr)) {
            return;
        }
        // 当前的spu属性对应值数组 $['color'] = 'red'

        $this->_currentSpuAttrValArr = [];
        foreach ($this->_productSpuAttrArr as $spuAttr) {
            $spuAttrVal = $this->_product[$spuAttr];
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

                //[
                //    'attr_val' => $attr,
                //];
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

        //echo $reverse_key."<br/>";
        if (isset($reverse_val_spu[$reverse_key]) && is_array($reverse_val_spu[$reverse_key])) {
            $return['active'] = 'active';
            $arr = $reverse_val_spu[$reverse_key];
            foreach ($arr as $k=>$v) {
                $return[$k] = $v;
            }
            if ($spuAttr == $this->_spuAttrShowAsImg) {
                $return['show_as_img'] = $arr['main_img'];
            }
        } else {
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
        }
        if ($active) {
            $return['active'] = 'current';
        }

        return $return;
    }

    protected function generateSpuReverseKey($one)
    {
        $reverse_key = '';
        foreach ($this->_productSpuAttrArr as $spuAttr) {
            $spuValColl[$spuAttr][] = $one[$spuAttr];
            $reverse_key .= $one[$spuAttr];
        }

        return  $reverse_key;
    }

    /**
     *	@property $data | Array  各个尺码对应的产品数组
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
        //$this->breadcrumbs($name);
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
        if (Yii::$app->controller->module->params['category_breadcrumbs']) {
            $parent_info = Yii::$service->category->getAllParentInfo($this->_category['parent_id']);
            if (is_array($parent_info) && !empty($parent_info)) {
                foreach ($parent_info as $info) {
                    $parent_name = Yii::$service->store->getStoreAttrVal($info['name'], 'name');
                    $parent_url = Yii::$service->url->getUrl($info['url_key']);
                    Yii::$service->page->breadcrumbs->addItems(['name' => $parent_name, 'url' => $parent_url]);
                }
            }
            Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
        } else {
            Yii::$service->page->breadcrumbs->active = false;
        }
    }
    // 买了的人还买了什么，通过产品字段取出来sku，然后查询得到。
    protected function getProductBySkus($skus)
    {
        $buy_also_buy_sku = $this->_product['buy_also_buy_sku'];
        if ($buy_also_buy_sku) {
            $skus = explode(',', $buy_also_buy_sku);
            if (is_array($skus) && !empty($skus)) {
                $filter['select'] = [
                    'sku', 'spu', 'name', 'image',
                    'price', 'special_price',
                    'special_from', 'special_to',
                    'url_key', 'score',
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
