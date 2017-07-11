<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Catalog\block\favoriteproduct;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Add
{
    /**
     * 用户添加收藏产品
     */
    public function getLastData()
    {
        $product_id = Yii::$app->request->get('product_id');
        //没有登录的用户跳转到登录页面
        if (Yii::$app->user->isGuest) {
            $url = Yii::$service->url->getCurrentUrl();
            Yii::$service->customer->setLoginSuccessRedirectUrl($url);

            return Yii::$service->url->redirectByUrlKey('customer/account/login');
        }

        $identity = Yii::$app->user->identity;
        $user_id = $identity->id;

        $addStatus = Yii::$service->product->favorite->add($product_id, $user_id);
        if (!$addStatus) {
            Yii::$service->page->message->addByHelperErrors();
        }
        $favoriteParam = Yii::$app->getModule('catalog')->params['favorite'];
        // 跳转。
        if (isset($favoriteParam['addSuccessRedirectFavoriteList']) && $favoriteParam['addSuccessRedirectFavoriteList']) {
            return Yii::$service->url->redirectByUrlKey('customer/productfavorite');
        } else {
            $product = Yii::$service->product->getByPrimaryKey($product_id);
            $urlKey = $product['url_key'];

            return Yii::$service->url->redirectByUrlKey($urlKey);
        }
    }
}
