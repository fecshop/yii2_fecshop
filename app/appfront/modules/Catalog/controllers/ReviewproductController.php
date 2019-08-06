<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Catalog\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ReviewproductController extends AppfrontController
{
    public $enableCsrfValidation = true;
    
    public function init()
    {
        parent::init();
        Yii::$service->page->theme->layoutFile = 'product_view.php';
    }

    // 增加评论
    public function actionAdd()
    {
        //$reviewParam = Yii::$app->getModule('catalog')->params['review'];
        $appName = Yii::$service->helper->getAppName();
        $addReviewOnlyLogin = Yii::$app->store->get($appName.'_catalog','review_addReviewOnlyLogin');
        //$addReviewOnlyLogin = ($addReviewOnlyLogin ==  Yii::$app->store->enable)  ? true : false;
        if ($addReviewOnlyLogin ==  Yii::$app->store->enable && Yii::$app->user->isGuest) {
            $currentUrl = Yii::$service->url->getCurrentUrl();
            Yii::$service->customer->setLoginSuccessRedirectUrl($currentUrl);

            // 如果评论产品必须登录用户，则跳转到用户登录页面
            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }
        $editForm = Yii::$app->request->post('editForm');
        $editForm = Yii::$service->helper->htmlEncode($editForm);
        if (!empty($editForm) && is_array($editForm)) {
            $saveStatus = $this->getBlock()->saveReview($editForm);
            if ($saveStatus) {
                $spu = Yii::$app->request->get('spu');
                $_id = Yii::$app->request->get('_id');
                $spu = Yii::$service->helper->htmlEncode($spu);
                $_id = Yii::$service->helper->htmlEncode($_id);
                if ($spu && $_id) {
                    $url = Yii::$service->url->getUrl('catalog/reviewproduct/lists', ['spu' => $spu, '_id'=>$_id]);
                    return $this->redirect($url);
                    
                }
            }
        }
        $data = $this->getBlock()->getLastData($editForm);

        return $this->render($this->action->id, $data);
    }

    public function actionLists()
    {
        if (Yii::$service->store->isAppServerMobile()) {
            $product_id = Yii::$app->request->get('_id');
            $urlPath = 'product/review/lists/'.$product_id;
            Yii::$service->store->redirectAppServerMobile($urlPath);
        }
        $data = $this->getBlock()->getLastData($editForm);

        return $this->render($this->action->id, $data);
    }
}
