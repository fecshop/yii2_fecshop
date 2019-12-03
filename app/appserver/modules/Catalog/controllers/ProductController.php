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
use fecshop\app\appserver\modules\Catalog\helpers\Review as ReviewHelper;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductController extends AppserverController
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
    protected $_currentSpuAttrValArr;
    protected $_spuAttrShowAsImgArr;


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $product_id = Yii::$app->request->get('product_id');
        $cacheName = 'product';
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
                        if ($k != 'p' && $v != 1) {
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
                    $store, $currency, $get_str, $product_id, $langCode
                ],
                //'dependency' => [
                //	'class' => 'yii\caching\DbDependency',
                //	'sql' => 'SELECT COUNT(*) FROM post',
                //],
            ];
        }

        return $behaviors;
    }
    
    public function actionGetfav()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if(Yii::$app->user->isGuest){
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $product_id = Yii::$app->request->get('product_id');
        $customer_id = Yii::$app->user->identity->id;
        if ($product_id && $customer_id) {
            $one = Yii::$service->product->favorite->getByProductIdAndUserId($product_id, $customer_id);
            $fav = 1;
            if (!$one['product_id']) {
                $fav = 2;
            }
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'fav' => $fav,
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
    }
    
    public function actionFavorite(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if(Yii::$app->user->isGuest){
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $product_id = Yii::$app->request->get('product_id');
        $favType = Yii::$app->request->get('type');
        $identity   = Yii::$app->user->identity;
        $user_id    = $identity->id;
        if ($favType == 'del') {
            $delStatus = Yii::$service->product->favorite->removeByProductIdAndUserId($product_id, $user_id);
            if (!$delStatus) {
                $code = Yii::$service->helper->appserver->product_favorite_fail;
                $data = [];
                $message = Yii::$service->helper->errors->get(true);
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data,$message);
                
                return $responseData;
            }else{
                $code = Yii::$service->helper->appserver->status_success;
                $data = [
                    'content' => 'success',
                ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
        } else {
            $addStatus = Yii::$service->product->favorite->add($product_id, $user_id);
            if (!$addStatus) {
                $code = Yii::$service->helper->appserver->product_favorite_fail;
                $data = [];
                $message = Yii::$service->helper->errors->get(true);
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data,$message);
                
                return $responseData;
            }else{
                $code = Yii::$service->helper->appserver->status_success;
                $data = [
                    'content' => 'success',
                ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
        }
        
        
        // 收藏失败，需要登录
        
        
    }
    
    
    // 网站信息管理
    public function actionIndex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        /**
         * 通过Yii::mapGet() 得到重写后的class类名以及对象。Yii::mapGet是在文件@fecshop\yii\Yii.php中
         */
        list($this->_reviewHelperName,$this->_reviewHelper) = Yii::mapGet($this->_reviewHelperName);  
        
        $appName = Yii::$service->helper->getAppName();
        $middle_img_width = Yii::$app->store->get($appName.'_catalog','product_middle_img_width');
        
        if(!$this->initProduct()){
            $code = Yii::$service->helper->appserver->product_not_active;
            $data = '';
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data,$message);
            
            return $responseData;
        }
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        $reviewHelper = $this->_reviewHelper;
        $reviewHelper::initReviewConfig();
        $ReviewAndStarCount = $reviewHelper::getReviewAndStarCount($this->_product);
        list($review_count, $reviw_rate_star_average, $reviw_rate_star_info) = $ReviewAndStarCount;
        //list($review_count, $reviw_rate_star_average, $reviw_rate_star_info) = $reviewHelper::getReviewAndStarCount($this->_product);
        $this->filterProductImg($this->_product['image']);
        $groupAttrInfo = Yii::$service->product->getGroupAttrInfo($this->_product['attr_group']);
        $groupAttrArr = $this->getGroupAttrArr($groupAttrInfo);
        $custom_option_attr_info = Yii::$service->product->getCustomOptionAttrInfo($this->_product['attr_group']);
        //var_dump($custom_option_attr_info);exit;
        $custom_option_showImg_attr = $this->getCustomOptionShowImgAttr($custom_option_attr_info);
        //var_dump($custom_option_showImg_attr );exit;
        $thumbnail_img = [];
        $image = $this->_image_thumbnails;
        if(isset($image['gallery']) && is_array($image['gallery']) && !empty($image['gallery'])){
            $gallerys = $image['gallery'];
            $gallerys = \fec\helpers\CFunc::array_sort($gallerys,'sort_order',$dir='asc');
            if(is_array($image['main']) && !empty($image['main'])){
                $main_arr[] = $image['main'];
                $gallerys = array_merge($main_arr,$gallerys);
            }	
        }else if(is_array($image['main']) && !empty($image['main'])){
            $main_arr[] = $image['main'];
            $gallerys = $main_arr;
        }
        if(is_array($gallerys) && !empty($gallerys)){
            foreach($gallerys as $gallery){
                $image = $gallery['image'];
                $thumbnail_img[] = Yii::$service->product->image->getResize($image,$middle_img_width,false);
            }
        }
        $custom_option = $this->getCustomOption($this->_product['custom_option'],$middle_img_width); 
        $reviewHelper = new ReviewHelper;
        $reviewHelper->product_id = $this->_product[$productPrimaryKey];
        $reviewHelper->spu = $this->_product['spu'];
        $productReview = $reviewHelper->getLastData();
        $code = Yii::$service->helper->appserver->status_success;
        $productImage = isset($this->_product['image']['main']['image']) ? $this->_product['image']['main']['image'] : '' ;
                
        $data = [
            'product' => [
                'groupAttrArr'              => $groupAttrArr,
                'name'                      => Yii::$service->store->getStoreAttrVal($this->_product['name'], 'name'),
                'sku'                       => $this->_product['sku'],
                'main_image' => Yii::$service->product->image->getResize($productImage,[120, 120],false),
                'package_number'            => $this->_product['package_number'],
                'spu'                       => $this->_product['spu'],
                'thumbnail_img'             => $thumbnail_img,
                'productReview'             => $productReview,
                'custom_option_showImg_attr'=> $custom_option_showImg_attr,
                'image_detail'              => $this->_image_detail,
                'attr_group'                => $this->_product['attr_group'],
                'review_count'              => $review_count,
                'reviw_rate_star_average'   => $reviw_rate_star_average,
                'reviw_rate_star_info'      => $reviw_rate_star_info,
                'price_info'                => $this->getProductPriceInfo(),
                'tier_price'                => $this->getTierPrice(),
                'options'                   => $this->getSameSpuInfo(),
                'custom_option'             => $custom_option,
                'description'               => Yii::$service->store->getStoreAttrVal($this->_product['description'], 'description'),
                '_id'                       => (string)$this->_product[$productPrimaryKey],
                'buy_also_buy'              => $this->getProductBySkus(),
            ]
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data,$message);
        
        return $responseData;
    }
    
    public function getTierPrice(){
        $i = 1;
        $pre_qty = 1;
        $t_arr = [];
        $tier_price = $this->_product['tier_price'];
        if(is_array($tier_price) && !empty($tier_price)){
            $tier_price_arr = \fec\helpers\CFunc::array_sort($tier_price,'qty');
            $arr = [Yii::$service->page->translate->__('Qty').':'];
            foreach($tier_price_arr as $one){
                if($i != 1){
                    $end_qty = $one['qty'] - 1;
                    if ($end_qty > $pre_qty) {
                        $arr[] = $pre_qty.'-'.$end_qty;
                    } else {
                        $arr[] = $pre_qty;
                    } 
                }
                $i++;
                $pre_qty = $one['qty'];
            }
            $arr[] = '>='.$pre_qty;
            $t_arr[] = $arr;
            
            $arr = [Yii::$service->page->translate->__('Price').':'];
            foreach($tier_price as $one){
                $arr[] = Yii::$service->product->price->formatSamplePrice($one['price']);
            }
            $t_arr[] = $arr;
        }
        return $t_arr;
    }
    
    public function getCustomOptionShowImgAttr($custom_option_attr_info){
        if (is_array($custom_option_attr_info) && !empty($custom_option_attr_info)) {
            foreach($custom_option_attr_info as $attr => $one){
                if($one['showAsImg']){
                    return $attr;
                }
            }
        }
    }
    
    public function getCustomOption($custom_option,$middle_img_width){
        $arr = [];
        if(is_array($custom_option)){
            foreach($custom_option as $attr => $one){
                if($attr && isset($one['image']) && $one['image']){
                    $one['image'] = Yii::$service->product->image->getResize($one['image'],[40,45],false);
                    if($price = $one['price']){
                        $one['price'] = Yii::$service->page->currency->getCurrentCurrencyPrice($price);
                    }
                    $arr[$attr] = $one;
                }
            }
        }
        return $arr;
    }
    
    public function getGroupAttrArr($groupAttrInfo){
        $gArr = [];
        // 增加重量，长宽高，体积重等信息
        if ($this->_product['weight']) {
            $weightName = Yii::$service->page->translate->__('weight');
            $gArr[$weightName] = $this->_product['weight'].' Kg';
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
            $gArr[$volumeWeightName] = $this->_product['volume_weight'].' Kg';
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
                    $detail_arr[]       = Yii::$service->product->image->getUrl($one['image']);
                }
                
            }
            $this->_image_thumbnails['gallery'] = $thumbnails_arr;
            $this->_image_detail     = $detail_arr;
        }

        if(isset($product_images['main']['is_detail']) && $product_images['main']['is_detail'] == 1 ){
            $this->_image_detail[] = Yii::$service->product->image->getUrl($product_images['main']['image']);
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
     * @param $select | Array ， 需要查询的字段。
     * 得到当前spu下面的所有的sku的数组、
     * 这个是为了产品详细页面的spu下面的产品切换，譬如同一spu下的不同的颜色尺码切换。
     */
    protected function getSpuData($select)
    {
        $spu = $this->_product['spu'];
        return Yii::$service->product->spuCollData($select, $this->_productSpuAttrArr, $spu);
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
            return [];
        }
        // 当前的spu属性对应值数组 $['color'] = 'red'

        $this->_currentSpuAttrValArr = [];
        foreach ($this->_productSpuAttrArr as $spuAttr) {
            if (isset($this->_product['attr_group_info']) && $this->_product['attr_group_info']) {  // mysql
                $attr_group_info = $this->_product['attr_group_info'];
                $spuAttrVal = $attr_group_info[$spuAttr];
            } else {
                //$spuAttrVal = $this->_product[$spuAttr];
                $spuAttrVal = isset($this->_product[$spuAttr]) ? $this->_product[$spuAttr] : '';
            }
            if ($spuAttrVal) {
                $this->_currentSpuAttrValArr[$spuAttr] = $spuAttrVal;
            } else {
                // 如果某个spuAttr的值为空，则退出，这个说明产品数据有问题。
                return [];
            }
        }
        // 得到当前的spu下面的所有的值
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        $select = [$productPrimaryKey, 'name', 'image', 'url_key'];
        $data = $this->getSpuData($select);
        //var_dump($data);
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
                $one['url'] = '/catalog/product/'.(string)$one[$productPrimaryKey];
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
                
                
                $attr_info['attr_val'] = Yii::$service->page->translate->__($attr_info['attr_val']);
                $attr_coll[] = $attr_info;
                //[
                //    'attr_val' => $attr,
                //];
            }
            $spuShowArr[] = [
                'label' =>  Yii::$service->page->translate->__($spuAttr),
                'value' => $attr_coll,
            ];
        }
        //var_dump($spuShowArr);exit;
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
                $return['show_as_img'] = Yii::$service->product->image->getResize($arr['main_img'],[50,55],false);
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
                $return['show_as_img'] = Yii::$service->product->image->getResize($arr['main_img'],[50,55],false);
              
            }
        }
        if ($active) {
            $return['active'] = 'current';
        }
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        $return['_id'] = (string)$return[$productPrimaryKey];
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
        //$primaryKey = Yii::$service->product->getPrimaryKey();
        $primaryVal = Yii::$app->request->get('product_id');
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
        $appName = Yii::$service->helper->getAppName();
        $category_breadcrumbs = Yii::$app->store->get($appName.'_catalog','product_breadcrumbs');
        if ($category_breadcrumbs == Yii::$app->store->enable) {
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
    public function getProductBySkus()
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
                $i = 1;
                $product_return = [];
                if(is_array($products) && !empty($products)){
                    
                    foreach($products as $k=>$v){
                        $i++;
                        $products[$k]['url'] = '/catalog/product/'.$v['product_id'];
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
                return $product_return;
            }
        }
    }
    

    // ajax 得到产品加入购物车的价格。
    public function actionGetcoprice()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $custom_option_sku = Yii::$app->request->get('custom_option_sku');
        $product_id = Yii::$app->request->get('product_id');
        $qty = Yii::$app->request->get('qty');
        $cart_price = 0;
        $custom_option_price = 0;
        $product = Yii::$service->product->getByPrimaryKey($product_id);
        $cart_price = Yii::$service->product->price->getCartPriceByProductId($product_id, $qty, $custom_option_sku);
        if (!$cart_price) {
            return;
        }
        $price_info = [
            'price' => $cart_price,
        ];

        $priceView = [
            'view'    => 'catalog/product/index/price.php',
        ];
        $priceParam = [
            'price_info' => $price_info,
        ];

        echo json_encode([
            'price' =>Yii::$service->page->widget->render($priceView, $priceParam),
        ]);
        exit;
    }
}
