<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;
use \Firebase\JWT\JWT;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductreviewController extends AppserverTokenController
{
    public $pageNum;
    public $numPerPage = 20;
    public $_page = 'p';

    public function actionIndex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
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
        $coll = $data['coll'];
        if (is_array($coll) && !empty($coll)) {
            foreach ($coll as $k=>$one) {
                $product_id = $one['product_id'];
                $productModel = Yii::$service->product->getByPrimaryKey($product_id);
                $main_image = isset($productModel['image']['main']['image']) ? $productModel['image']['main']['image'] : '' ;
                $coll[$k]['imgUrl'] = Yii::$service->product->image->getResize($main_image,[80,80],false);
                $coll[$k]['review_date'] = date('Y-m-d H:i:s',$coll[$k]['review_date']);
                
            }
        }else{
            $coll = [];
        }
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'productList'   => $coll,
            'count'         => $count,
            'numPerPage'    => $this->numPerPage,
            'noActiveStatus'=> Yii::$service->product->review->noActiveStatus(),
            'refuseStatus'  => Yii::$service->product->review->refuseStatus(),
            'activeStatus'  => Yii::$service->product->review->activeStatus(),
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }

    
}