<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Catalog\block\reviewproduct;

//use fecshop\app\apphtml5\modules\Catalog\helpers\Review as ReviewHelper;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Lists extends \yii\base\BaseObject
{
    public $product_id;
    public $spu;
    public $filterBySpu = true;
    public $filterOrderBy = 'review_date';
    public $_page = 'p';
    public $numPerPage = 20;
    public $pageNum;
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
        // 初始化服务
        $reviewHelper = $this->_reviewHelper;
        $reviewHelper::initReviewConfig();
    }
    /**
     * @param $countTotal | Int
     * 得到toolbar的分页部分
     */
    protected function getProductPage($countTotal)
    {
        if ($countTotal <= $this->numPerPage) {
            return '';
        }
        $config = [
            'class'        => 'fecshop\app\apphtml5\widgets\Page',
            'view'        => 'widgets/page.php',
            'pageNum'        => $this->pageNum,
            'numPerPage'    => $this->numPerPage,
            'countTotal'    => $countTotal,
            'page'            => $this->_page,
        ];

        return Yii::$service->page->widget->renderContent('category_product_page', $config);
    }
    // 初始化参数
    public function initParam()
    {
        $this->pageNum = Yii::$app->request->get($this->_page);
        $this->pageNum = $this->pageNum ? $this->pageNum : 1;
        //$this->spu = Yii::$app->request->get('spu');
        $this->product_id = Yii::$app->request->get('product_id');
        // $review = Yii::$app->getModule('catalog')->params['review'];
        $appName = Yii::$service->helper->getAppName();
        $reviewPageReviewCount = Yii::$app->store->get($appName.'_catalog','review_reviewPageReviewCount');
            
        //$productPageReviewCount = $reviewPageReviewCount ? $reviewPageReviewCount : 10;
        $this->numPerPage = $reviewPageReviewCount ? $reviewPageReviewCount : $this->numPerPage;
    }

    public function getLastData()
    {
        $this->initParam();
        if (!$this->product_id) {
            
            $code = Yii::$service->helper->appserver->product_id_not_exist;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $product = Yii::$service->product->getByPrimaryKey($this->product_id);
        if (!$product['spu']) {
            $code = Yii::$service->helper->appserver->product_not_active;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $this->spu = $product['spu'];
        $price_info = $this->getProductPriceInfo($product);
        $spu = $product['spu'];
        $image = $product['image'];
        $main_img = isset($image['main']['image']) ? $image['main']['image'] : '';
        $imgUrl = Yii::$service->product->image->getResize($main_img,[150,150],false);
        
        $name = Yii::$service->store->getStoreAttrVal($product['name'], 'name');
        if ($this->filterBySpu) {
            $data = $this->getReviewsBySpu($this->spu);
            $count = $data['count'];

            $coll = $data['coll'];
            $reviewHelper = $this->_reviewHelper;
            $ReviewAndStarCount = $reviewHelper::getReviewAndStarCount($product);
            list($review_count, $reviw_rate_star_average, $reviw_rate_star_info) = $ReviewAndStarCount;
            $product = [
                'product_id' => $this->product_id,
                'spu' => $this->spu,
                'price_info' => $price_info,
                'imgUrl' => $imgUrl,
                'name' => $name,
            ];
            
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'product'       => $product,
                'reviewList'    => $coll,
                'review_count'              => $review_count,
                'reviw_rate_star_average'   => $reviw_rate_star_average,
                'reviw_rate_star_info'      => $reviw_rate_star_info,
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
    }
    /**
     * @param $spu  | String
     * 通过spu得到产品评论
     */
    public function getReviewsBySpu($spu)
    {
        $currentIp = \fec\helpers\CFunc::get_real_ip();
        $filter = [
            'numPerPage'    => $this->numPerPage,
            'pageNum'        => $this->pageNum,
            'orderBy'    => [$this->filterOrderBy => SORT_DESC],
            'where'            => [
                [
                    '$or' => [
                        [
                            'status' => Yii::$service->product->review->activeStatus(),
                            'product_spu' => $spu,
                        ],
                        [
                            'status' => Yii::$service->product->review->noActiveStatus(),
                            'product_spu' => $spu,
                            'ip' => $currentIp,
                        ],
                    ],
                ],
            ],
        ];

        return Yii::$service->product->review->getListBySpu($filter);
    }
    // 产品价格信息
    protected function getProductPriceInfo($product)
    {
        $price = $product['price'];
        $special_price = $product['special_price'];
        $special_from = $product['special_from'];
        $special_to = $product['special_to'];

        return Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($price, $special_price, $special_from, $special_to);
    }
}
