<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Catalog\block\product;

use fecshop\app\appfront\modules\Catalog\helpers\Review as ReviewHelper;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    protected $_product;
    protected $_title;
    protected $_primaryVal;
    protected $_productSpuAttrArr;
    protected $_spuAttrShowAsImg;

    public function getLastData()
    {
        $productImgSize = Yii::$app->controller->module->params['productImgSize'];
        $productImgMagnifier = Yii::$app->controller->module->params['productImgMagnifier'];
        $this->initProduct();
        ReviewHelper::initReviewConfig();
        $ReviewAndStarCount = ReviewHelper::getReviewAndStarCount($this->_product);
        list($review_count, $reviw_rate_star_average) = $ReviewAndStarCount;

        return [
            'name'                    => Yii::$service->store->getStoreAttrVal($this->_product['name'], 'name'),
            'image'                    => $this->_product['image'],
            'sku'                    => $this->_product['sku'],
            'spu'                    => $this->_product['spu'],
            'attr_group'            => $this->_product['attr_group'],
            'review_count'            => $review_count,
            'reviw_rate_star_average'=> $reviw_rate_star_average,
            'price_info'            => $this->getProductPriceInfo(),
            'tier_price'            => $this->_product['tier_price'],
            'media_size' => [
                'small_img_width'    => $productImgSize['small_img_width'],
                'small_img_height'    => $productImgSize['small_img_height'],
                'middle_img_width'    => $productImgSize['middle_img_width'],
            ],
            'productImgMagnifier'    => $productImgMagnifier,
            'options'                => $this->getSameSpuInfo(),
            'custom_option'            => $this->_product['custom_option'],
            'description'            => Yii::$service->store->getStoreAttrVal($this->_product['description'], 'description'),
            '_id'                    => $this->_product['_id'],
            'buy_also_buy'            => $this->getProductBySkus($skus),
        ];
    }

    /**
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
     * 得到当前spu下面的所有的sku的数组、
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
     * 显示这个的前提是，该产品所在属性组对应的spu_attr 只有color 和size 两种
     * 也就是说，这个只针对服装做的一种方式，其他的会按照大众化的那种方式显示出来过滤。（下拉条）
     * 这个有点像定制化的范畴，目前我只做了color和size这类
     * 衣服类产品需要用到的属性，对于其他的类似属性，就需要自己做定制修改了.
     * @return 返回和当前产品spu相同的其他产品，然后以颜色和尺码的方式罗列出来。
     */
    protected function getSameSpuInfo()
    {
        // 当前的产品对应的spu属性组的属性，譬如 ['color','size','myyy']
        $this->_productSpuAttrArr = Yii::$service->product->getSpuAttr($this->_product['attr_group']);

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
