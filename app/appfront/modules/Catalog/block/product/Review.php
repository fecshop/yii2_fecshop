<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Catalog\block\product;

//use fecshop\app\appfront\modules\Catalog\helpers\Review as ReviewHelper;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Review
{
    public $product_id;
    public $spu;
    public $filterBySpu = true;
    public $filterOrderBy = 'review_date';
    
    /**
     * 为了可以使用rewriteMap，use 引入的文件统一采用下面的方式，通过Yii::mapGet()得到className和Object
     */
    protected $_reviewHelperName = '\fecshop\app\appfront\modules\Catalog\helpers\Review';
    protected $_reviewHelper;

    public function __construct()
    {
        /**
         * 通过Yii::mapGet() 得到重写后的class类名以及对象。Yii::mapGet是在文件@fecshop\yii\Yii.php中
         */
        list($this->_reviewHelperName,$this->_reviewHelper) = Yii::mapGet($this->_reviewHelperName);  
        $reviewHelper = $this->_reviewHelper;
        // 初始化当前appfront的设置，覆盖service的初始设置。
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
                'review_count'    => $count,
                'coll'            => $coll,
                'noActiveStatus'=> Yii::$service->product->review->noActiveStatus(),
            ];
        }
    }
    /**
     * 得到当前spu下面的所有评论信息。
     */
    public function getReviewsBySpu($spu)
    {
        $review = Yii::$app->getModule('catalog')->params['review'];
        $productPageReviewCount = isset($review['productPageReviewCount']) ? $review['productPageReviewCount'] : 10;
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
