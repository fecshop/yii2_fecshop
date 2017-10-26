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
class ReviewproductController extends AppserverController
{
    
    public $enableCsrfValidation = false ;
    
    // 增加评论
    public function actionAdd()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $reviewParam = Yii::$app->getModule('catalog')->params['review'];
        $addReviewOnlyLogin = isset($reviewParam['addReviewOnlyLogin']) ? $reviewParam['addReviewOnlyLogin'] : false;
        if ($addReviewOnlyLogin) {
            $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
            if(!$identity){
                return [
                    'code' => 400,
                    'content' => 'you must login your account'
                ];
            }
        }
        return  $this->getBlock()->getLastData();
    }

    public function actionLists()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $data = $this->getBlock()->getLastData($editForm);

        return $data;
    }
    
    
    public function actionSubmitreview()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $captcha = Yii::$app->request->post('captcha');
        $reviewParam = Yii::$app->getModule('catalog')->params['review'];
        $add_captcha = isset($reviewParam['add_captcha']) ? $reviewParam['add_captcha'] : false;
        
        // 检查验证码
        if($add_captcha){
            if(!\Yii::$service->helper->captcha->validateCaptcha($captcha)){
                return [
                    'code' => '401',
                    'content' => 'captcha is not correct',
                ];
            }
        }
        // 检查用户登录状态
        $addReviewOnlyLogin = isset($reviewParam['addReviewOnlyLogin']) ? $reviewParam['addReviewOnlyLogin'] : false;
        if ($addReviewOnlyLogin) {
            $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
            if(!$identity){
                return [
                    'code' => 400,
                    'content' => 'you must login your account'
                ];
            }
        }
        $product_id = Yii::$app->request->post('product_id');
        $customer_name = Yii::$app->request->post('customer_name');
        $summary = Yii::$app->request->post('summary');
        $review_content = Yii::$app->request->post('review_content');
        $selectStar = Yii::$app->request->post('selectStar');
        // 产品产品是否存在
        $product = Yii::$service->product->getByPrimaryKey($product_id);
        if (!$product['spu']) {
            return [
                'code'  => 401,
                'content' => 'product is not exist',
            ];
        }
        // 检查前台传递的信息
        if(!$customer_name){
            return [
                'code'  => 401,
                'content' => 'customer name is empty',
            ];
        }
        
        if(!$summary){
            return [
                'code'  => 401,
                'content' => 'summary is empty',
            ];
        }
        
        if(!$review_content){
            return [
                'code'  => 401,
                'content' => 'review content is empty',
            ];
        }
        
        if(!$selectStar){
            return [
                'code'  => 401,
                'content' => 'review Star is empty',
            ];
        }
        $editForm = [
            'product_id' => $product_id,
            'rate_star'  => $selectStar,
            'name'  => $customer_name,
            'summary'  => $summary,
            'review_content'  => $review_content,
            'product_spu'  => $product['spu'],
        ];
        // 保存评论
        return $this->getBlock('add')->saveReview($editForm);
        
        
       
        
    }
    
}
