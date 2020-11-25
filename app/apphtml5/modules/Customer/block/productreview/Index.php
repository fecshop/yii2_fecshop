<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Customer\block\productreview;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends \yii\base\BaseObject
{
    public $pageNum;
    public $numPerPage = 20;
    public $_page = 'p';

    public function getLastData()
    {
        $this->pageNum = Yii::$app->request->get($this->_page);
        $this->pageNum = $this->pageNum ? $this->pageNum : 1;
        $identity = Yii::$app->user->identity;
        $user_id = $identity['id'];
        $filter = [
            'numPerPage'    => $this->numPerPage,
            'pageNum'        => $this->pageNum,
            'orderBy'    => ['review_date' => SORT_DESC],
            'where'            => [
                ['user_id'=> $user_id],
            ],
            'asArray' => true,
        ];
        $data = Yii::$service->product->review->getReviewsByUserId($filter);
        $count = $data['count'];
            //echo $count;exit;
        $pageToolBar = $this->getProductPage($count);

        $coll = $data['coll'];
        if (is_array($coll) && !empty($coll)) {
            foreach ($coll as $k=>$one) {
                $product_id = $one['product_id'];
                $productModel = Yii::$service->product->getByPrimaryKey($product_id);
                $coll[$k]['image'] = $productModel['image'];
                $coll[$k]['url_key'] = $productModel['url_key'];
            }
        }

        return [
            'pageToolBar'    => $pageToolBar,
            'coll'            => $coll,
            'noActiveStatus'=> Yii::$service->product->review->noActiveStatus(),
            'refuseStatus'  => Yii::$service->product->review->refuseStatus(),
            'activeStatus'  => Yii::$service->product->review->activeStatus(),
        ];
    }

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
}
