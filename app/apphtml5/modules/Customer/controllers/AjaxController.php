<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Customer\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AjaxController extends AppfrontController
{
    /**
     * ajax 请求 ，得到是否登录账户的信息.
     */
    public function actionIndex()
    {
        $result_arr = [];
        if (Yii::$app->request->isAjax) {
            $result_arr['loginStatus'] = false;
            $result_arr['favorite'] = false;
            $result_arr['favorite_product_count'] = 0;
            $product_id = Yii::$app->request->get('product_id');
            $customer_name = '';
            if (!Yii::$app->user->isGuest) {
                $identity = Yii::$app->user->identity;
                $customer_name = $identity['firstname'].' '.$identity['lastname'];
                $result_arr['customer_name'] = $customer_name;
                $result_arr['favorite_product_count'] = $identity['favorite_product_count'] ? $identity['favorite_product_count'] : 0;
                $result_arr['loginStatus'] = true;
                if ($product_id) {
                    $favorite = Yii::$service->product->favorite->getByProductIdAndUserId($product_id);
                    $favorite ? ($result_arr['favorite'] = true) : '';
                }
            }
            if ($product_id) {
                // 添加csrf数据
                $csrfName = \fec\helpers\CRequest::getCsrfName();
                $csrfVal = \fec\helpers\CRequest::getCsrfValue();
                $result_arr['csrfName'] = $csrfName;
                $result_arr['csrfVal'] = $csrfVal;
                $result_arr['product_id'] = $product_id;
            }
            $cartQty = Yii::$service->cart->getCartItemQty();
            $result_arr['cart_qty'] = $cartQty;
        }
        echo json_encode($result_arr);
        exit;
    }

    public function actionIsregister()
    {
        $email = Yii::$app->request->get('email');
        if (Yii::$service->customer->isRegistered($email)) {
            echo json_encode([
                'registered' => 1,
            ]);
        } else {
            echo json_encode([
                'registered' => 2,
            ]);
        }
    }
}
