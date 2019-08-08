<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Checkout\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CartController extends AppfrontController
{
    public $enableCsrfValidation = true;
    public $noCsrfActions = ['add'];

    public function beforeAction($action)
    {
        if(in_array($action->id, $this->noCsrfActions)) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    /**
     * 把产品加入到购物车.
     */
    public function actionAdd()
    {
        $custom_option = Yii::$app->request->post('custom_option');
        $product_id = Yii::$app->request->post('product_id');
        $qty = Yii::$app->request->post('qty');
        //$custom_option  = \Yii::$service->helper->htmlEncode($custom_option);
        $product_id = \Yii::$service->helper->htmlEncode($product_id);
        $qty = \Yii::$service->helper->htmlEncode($qty);
        $qty = abs(ceil((int) $qty));
        if ($qty && $product_id) {
            if ($custom_option) {
                $custom_option_sku = json_decode($custom_option, true);
            }
            if (empty($custom_option_sku)) {
                $custom_option_sku = null;
            }
            $item = [
                'product_id' => $product_id,
                'qty'        =>  $qty,
                'custom_option_sku' => $custom_option_sku,
            ];
            $innerTransaction = Yii::$app->db->beginTransaction();
            try {
                $addToCart = Yii::$service->cart->addProductToCart($item);
                if ($addToCart) {
                    echo json_encode([
                        'status' => 'success',
                        'items_count' => Yii::$service->cart->quote->getCartItemCount(),
                    ]);
                    $innerTransaction->commit();
                    exit;
                } else {
                    $errors = Yii::$service->helper->errors->get(',');
                    echo json_encode([
                        'status' => 'fail',
                        'content'=> Yii::$service->page->translate->__($errors),
                        //'items_count' => Yii::$service->cart->quote->getCartItemCount(),
                    ]);
                    $innerTransaction->rollBack();
                    exit;
                }
            } catch (\Exception $e) {
                $innerTransaction->rollBack();
            }
        }
    }

    /**
     * 购物车中添加优惠券.
     */
    public function actionAddcoupon()
    {
        if (Yii::$app->user->isGuest) {
            // 记忆一下登录成功返回购物车页面
            $cartUrl = Yii::$service->url->getUrl('checkout/cart');
            Yii::$service->customer->setLoginSuccessRedirectUrl($cartUrl);
            echo json_encode([
                'status' => 'fail',
                'content'=> 'nologin',
            ]);
            exit;
        }
        
        $coupon_code = trim(Yii::$app->request->post('coupon_code'));
        $coupon_code = \Yii::$service->helper->htmlEncode($coupon_code);
        if ($coupon_code) {
            $innerTransaction = Yii::$app->db->beginTransaction();
            try {
                if (Yii::$service->cart->coupon->addCoupon($coupon_code)) {
                    $innerTransaction->commit();
                } else {
                    $innerTransaction->rollBack();
                }
            } catch (\Exception $e) {
                $innerTransaction->rollBack();
            }
            
            $error_arr = Yii::$service->helper->errors->get();
            if (!empty($error_arr)) {
                $error_str = implode(',', $error_arr);
                echo json_encode([
                    'status' => 'fail',
                    'content'=> Yii::$service->page->translate->__($error_str),
                ]);
                exit;
            } else {
                echo json_encode([
                    'status' => 'success',
                    'content'=> Yii::$service->page->translate->__('add coupon success'),
                ]);
                exit;
            }
        } else {
            echo json_encode([
                'status' => 'fail',
                'content'=> Yii::$service->page->translate->__('coupon is empty'),
            ]);
            exit;
        }
    }

    /**
     * 购物车中取消优惠券.
     */
    public function actionCancelcoupon()
    {
        if (Yii::$app->user->isGuest) {
            // 记忆一下登录成功返回购物车页面
            $cartUrl = Yii::$service->url->getUrl('checkout/cart');
            Yii::$service->customer->setLoginSuccessRedirectUrl($cartUrl);
            echo json_encode([
                'status' => 'fail',
                'content'=> 'nologin',
            ]);
            exit;
        }
        $coupon_code = trim(Yii::$app->request->post('coupon_code'));
        if ($coupon_code) {
            $innerTransaction = Yii::$app->db->beginTransaction();
            try {
                $cancelStatus = Yii::$service->cart->coupon->cancelCoupon($coupon_code);
                if (!$cancelStatus) {
                    echo json_encode([
                        'status' => 'fail',
                        'content'=> Yii::$service->page->translate->__('coupon is not exist'),
                    ]);
                    $innerTransaction->rollBack();
                    exit;
                }
                $error_arr = Yii::$service->helper->errors->get(true);
                if (!empty($error_arr)) {
                    if (is_array($error_arr)) {
                        $error_str = implode(',', $error_arr);
                    } else {
                        $error_str = $error_arr;
                    }
                    echo json_encode([
                        'status' => 'fail',
                        'content'=> Yii::$service->page->translate->__($error_str),
                    ]);
                    $innerTransaction->rollBack();
                    exit;
                } else {
                    echo json_encode([
                        'status' => 'success',
                        'content'=> Yii::$service->page->translate->__('cacle coupon success'),
                    ]);
                    $innerTransaction->commit();
                    exit;
                }
            } catch (\Exception $e) {
                $innerTransaction->rollBack();
            }
        } else {
            echo json_encode([
                'status' => 'fail',
                'content'=> Yii::$service->page->translate->__('coupon is empty'),
            ]);
            exit;
        }
    }

    public function actionUpdateinfo()
    {
        $item_id = Yii::$app->request->post('item_id');
        $up_type = Yii::$app->request->post('up_type');
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            if ($up_type == 'add_one') {
                $status = Yii::$service->cart->addOneItem($item_id);
            } elseif ($up_type == 'less_one') {
                $status = Yii::$service->cart->lessOneItem($item_id);
            } elseif ($up_type == 'remove') {
                $status = Yii::$service->cart->removeItem($item_id);
            }
            if ($status) {
                echo json_encode([
                    'status' => 'success',
                ]);
                $innerTransaction->commit();
            } else {
                $errors = Yii::$service->helper->errors->get(',');
                echo json_encode([
                    'status' => 'fail',
                    'content'=> Yii::$service->page->translate->__($errors),
                    //'items_count' => Yii::$service->cart->quote->getCartItemCount(),
                ]);
                $innerTransaction->rollBack();
            }
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
        }
        exit;
    }
    
    public function actionSelectone()
    {
        $item_id = Yii::$app->request->post('item_id');
        $checked = Yii::$app->request->post('checked');
        $checked = $checked == 1 ? true : false; 
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            $status = Yii::$service->cart->selectOneItem($item_id, $checked);
            if ($status) {
                echo json_encode([
                    'status' => 'success',
                ]);
                $innerTransaction->commit();
            } else {
                echo json_encode([
                    'status' => 'fail',
                    'content' => Yii::$service->helper->errors->get(',')
                ]);
                $innerTransaction->rollBack();
            }
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
        }
        exit;
    }
    
    public function actionSelectall()
    {
        $checked = Yii::$app->request->post('checked');
        $checked = $checked == 1 ? true : false; 
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            $status = Yii::$service->cart->selectAllItem($checked);
            if ($status) {
                echo json_encode([
                    'status' => 'success',
                ]);
                $innerTransaction->commit();
            } else {
                echo json_encode([
                    'status' => 'fail',
                    'content' => Yii::$service->helper->errors->get(',')
                ]);
                $innerTransaction->rollBack();
            }
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
        }
        exit;
    }
}
