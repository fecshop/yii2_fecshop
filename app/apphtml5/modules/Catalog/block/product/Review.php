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
class Review  extends \yii\base\BaseObject
{
    public $product_id;
    public $spu;
    public $filterBySpu = true;
    public $filterOrderBy = 'review_date';
    public $reviw_rate_star_info;
    public $review_count;
    public $reviw_rate_star_average;
    /**
     * 为了可以使用rewriteMap，use 引入的文件统一采用下面的方式，通过Yii::mapGet()得到className和Object
     */
    protected $_reviewHelperName = '\fecshop\app\apphtml5\modules\Catalog\helpers\Review';
    protected $_reviewHelper;
    
    public function init()
    {
        parent::init();
        /**
         * 通过Yii::mapGet() 得到重写后的class类名以及对象。Yii::mapGet是在文件@fecshop\yii\Yii.php中
         */
        list($this->_reviewHelperName,$this->_reviewHelper) = Yii::mapGet($this->_reviewHelperName);  
        // 初始化当前apphtml5的设置，覆盖service的初始设置。
        $reviewHelper = $this->_reviewHelper;
        $reviewHelper::initReviewConfig();
    }
    
    /**
     * 得到当前spu下面的所有评论信息。
     */
    public function getLastData()
    {
        if (!$this->spu || !$this->product_id) {
            return;
        }
        if ($this->filterBySpu) {
            $data = $this->getReviewsBySpu($this->spu);
            $count = $data['count'];
            $coll = $data['coll'];

            return [
                '_id' => $this->product_id,
                'spu' => $this->spu,
                'coll'            => $coll,
                'noActiveStatus'=> Yii::$service->product->review->noActiveStatus(),
                'reviw_rate_star_info' => $this->reviw_rate_star_info,
                'review_count' => $this->review_count,
                'reviw_rate_star_average' => $this->reviw_rate_star_average,
            ];
        }
    }
    /**
     * 得到当前spu下面的所有评论信息。
     */
    public function getReviewsBySpu($spu)
    {
        // $review = Yii::$app->getModule('catalog')->params['review'];
        $appName = Yii::$service->helper->getAppName();
        $productPageReviewCount = Yii::$app->store->get($appName.'_catalog','review_productPageReviewCount');
        
        $productPageReviewCount = $productPageReviewCount ? $productPageReviewCount: 10;
        $currentIp = \fec\helpers\CFunc::get_real_ip();
        $filter = [
            'numPerPage'    => $productPageReviewCount,
            'pageNum'        => 1,
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

        // 调出来 review 信息。
        return Yii::$service->product->review->getListBySpu($filter);
    }
}
