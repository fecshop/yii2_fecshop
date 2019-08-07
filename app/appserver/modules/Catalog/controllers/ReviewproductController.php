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
        //$reviewParam = Yii::$app->getModule('catalog')->params['review'];
        $appName = Yii::$service->helper->getAppName();
        $addReviewOnlyLogin = Yii::$app->store->get($appName.'_catalog','review_addReviewOnlyLogin');
        //$addReviewOnlyLogin = ($addReviewOnlyLogin ==  Yii::$app->store->enable)  ? true : false;
        if ($addReviewOnlyLogin ==  Yii::$app->store->enable && Yii::$app->user->isGuest) {
            $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
            if(!$identity){
                
                $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
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
        //$reviewParam = Yii::$app->getModule('catalog')->params['review'];
        //$add_captcha = isset($reviewParam['add_captcha']) ? $reviewParam['add_captcha'] : false;
        $appName = Yii::$service->helper->getAppName();
        $addCaptcha = Yii::$app->store->get($appName.'_catalog','review_add_captcha');
        // 检查验证码
        if($addCaptcha == Yii::$app->store->enable){
            if(!\Yii::$service->helper->captcha->validateCaptcha($captcha)){
                $code = Yii::$service->helper->appserver->status_invalid_captcha;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
        }
        // 检查用户登录状态
        $addReviewOnlyLogin = isset($reviewParam['addReviewOnlyLogin']) ? $reviewParam['addReviewOnlyLogin'] : false;
        if ($addReviewOnlyLogin) {
            $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
            if(!$identity){
                $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
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
            $code = Yii::$service->helper->appserver->product_not_active;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        // 检查前台传递的信息
        if(!$customer_name){
            
            $code = Yii::$service->helper->appserver->status_miss_param;
            $data = [];
            $message = 'customer name is empty';
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data, $message);
            
            return $responseData;
        }
        
        if(!$summary){
            
            $code = Yii::$service->helper->appserver->status_miss_param;
            $data = [];
            $message = 'summary is empty';
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data, $message);
            
            return $responseData;
        }
        
        if(!$review_content){
            
            $code = Yii::$service->helper->appserver->status_miss_param;
            $data = [];
            $message = 'review content is empty';
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data, $message);
            
            return $responseData;
        }
        
        if(!$selectStar){
            
            $code = Yii::$service->helper->appserver->status_miss_param;
            $data = [];
            $message = 'review Star is empty';
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data, $message);
            
            return $responseData;
        }
        // 用户是否有添加这个产品的权限
        if (!Yii::$service->product->review->isReviewRole($product_id)) {
            $code = Yii::$service->helper->appserver->status_miss_param;
            $data = [];
            $message = 'product _id:'.$product_id.'  , you review this product only after ordered it';
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data, $message);
            
            return $responseData;
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
