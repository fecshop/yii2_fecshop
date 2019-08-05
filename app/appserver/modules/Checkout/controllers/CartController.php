<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appserver\modules\Checkout\controllers;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CartController extends AppserverTokenController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $currency_info = Yii::$service->page->currency->getCurrencyInfo();
        $code = Yii::$service->helper->appserver->status_success;
        $cart_info = $this->getCartInfo();
        // check if is enable paypal express
        $enablePaypalExpress = false;
        $appName = Yii::$service->helper->getAppName();
        $paypalExpressConfig = Yii::$app->store->get($appName.'_payment', 'paypal_express');
        if ($paypalExpressConfig == Yii::$app->store->enable) {
            $enablePaypalExpress = true;
        }
        $data = [
            'cart_info' => $cart_info,
            'currency'  => $currency_info,
            'enablePaypalExpress' => $enablePaypalExpress,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

        return $responseData;
    }

    /** @return data example
     *	[
     *				'coupon_code' 	=> $coupon_code,
     *				'grand_total' 	=> $grand_total,
     *				'shipping_cost' => $shippingCost,
     *				'coupon_cost' 	=> $couponCost,
     *				'product_total' => $product_total,
     *				'products' 		=> $products,
     *	]
     *			上面的products数组的个数如下：
     *			$products[] = [
     *					    'item_id' => $one['item_id'],
     *						'product_id' 		=> $product_id ,
     *						'qty' 				=> $qty ,
     *						'custom_option_sku' => $custom_option_sku ,
     *						'product_price' 	=> $product_price ,
     *						'product_row_price' => $product_row_price ,
     *						'product_name'		=> $product_one['name'],
     *						'product_url'		=> $product_one['url_key'],
     *						'product_image'		=> $product_one['image'],
     *						'custom_option'		=> $product_one['custom_option'],
     *						'spu_options' 		=> $productSpuOptions,
     *				];
     */
    public function getCartInfo()
    {
        $cart_info = Yii::$service->cart->getCartInfo(false);

        if (isset($cart_info['products']) && is_array($cart_info['products'])) {
            foreach ($cart_info['products'] as $k=>$product_one) {
                // 设置名字，得到当前store的语言名字。
                $cart_info['products'][$k]['name'] = Yii::$service->store->getStoreAttrVal($product_one['product_name'], 'name');
                unset($cart_info['products'][$k]['product_name']);
                // 设置图片
                if (isset($product_one['product_image']['main']['image'])) {
                    $productImg = $product_one['product_image']['main']['image'];
                    $cart_info['products'][$k]['img_url'] = Yii::$service->product->image->getResize($productImg,[150,150],false);
                }
                unset($cart_info['products'][$k]['product_image']);
                // 产品的url
                $cart_info['products'][$k]['url'] = '/catalog/product/'.$product_one['product_id'];

                $custom_option = isset($product_one['custom_option']) ? $product_one['custom_option'] : '';
                $custom_option_sku = $product_one['custom_option_sku'];
                // 将在产品页面选择的颜色尺码等属性显示出来。
                $custom_option_info_arr = $this->getProductOptions($product_one);
                $cart_info['products'][$k]['custom_option_info'] = $custom_option_info_arr;
                // 设置相应的custom option 对应的图片
                $custom_option_image = isset($custom_option[$custom_option_sku]['image']) ? $custom_option[$custom_option_sku]['image'] : '';
                if ($custom_option_image) {
                    $cart_info['products'][$k]['img_url'] = Yii::$service->product->image->getResize($custom_option_image,[150,150],false);
                }
                $activeStatus = Yii::$service->cart->quoteItem->activeStatus;
                $cart_info['products'][$k]['active'] = ($product_one['active'] == $activeStatus) ? 1 : 0;
                if ( isset($cart_info['products'][$k]['spu_options']) && is_array($cart_info['products'][$k]['spu_options']) ) {
                    $spu_options_arr = [];
                    foreach ($cart_info['products'][$k]['spu_options'] as $op_k => $op_v) {
                        $spu_options_arr[] = $op_k . ': ' . $op_v;
                    }
                    $cart_info['products'][$k]['spu_options_str'] = implode(',', $spu_options_arr);
                }
            }
        }

        return $cart_info;
    }

    /**
     * 将产品页面选择的颜色尺码等显示出来，包括custom option 和spu options部分的数据.
     */
    public function getProductOptions($product_one)
    {
        $custom_option_info_arr = [];
        $custom_option = isset($product_one['custom_option']) ? $product_one['custom_option'] : '';
        $custom_option_sku = $product_one['custom_option_sku'];
        if (isset($custom_option[$custom_option_sku]) && !empty($custom_option[$custom_option_sku])) {
            $custom_option_info = $custom_option[$custom_option_sku];
            foreach ($custom_option_info as $attr=>$val) {
                if (!in_array($attr, ['qty', 'sku', 'price', 'image'])) {
                    $attr = str_replace('_', ' ', $attr);
                    $attr = ucfirst($attr);
                    $custom_option_info_arr[$attr] = $val;
                }
            }
        }

        $spu_options = $product_one['spu_options'];
        if (is_array($spu_options) && !empty($spu_options)) {
            foreach ($spu_options as $label => $val) {
                $custom_option_info_arr[$label] = $val;
            }
        }

        return $custom_option_info_arr;
    }



    /**
     * 把产品加入到购物车.
     */
    public function actionAdd()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        //echo 1;exit;
        $custom_option = Yii::$app->request->post('custom_option');
        $product_id = Yii::$app->request->post('product_id');
        $qty = Yii::$app->request->post('qty');
        $buy_now = Yii::$app->request->post('buy_now');
        //$custom_option  = \Yii::$service->helper->htmlEncode($custom_option);
        $product_id = \Yii::$service->helper->htmlEncode($product_id);
        $qty = \Yii::$service->helper->htmlEncode($qty);
        $qty = abs(ceil((int) $qty));
        $return = [];
        $code = 400;
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
                if ($buy_now == 1) { // 如果是立即购买，则先将购物车中的产品disable掉
                    if (!Yii::$service->cart->selectAllItem(false)) {
                        $innerTransaction->rollBack();
                        $code = Yii::$service->helper->appserver->cart_product_select_fail;
                        $data = [];
                        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                    }
                }
                $addToCart = Yii::$service->cart->addProductToCart($item);
                if ($addToCart) {
                    $innerTransaction->commit();
                    $code = Yii::$service->helper->appserver->status_success;
                    $data = [
                        'items_count' => Yii::$service->cart->quote->getCartItemCount(),
                    ];
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

                    return $responseData;
                } else {
                    $innerTransaction->rollBack();
                    $code = Yii::$service->helper->appserver->cart_product_add_fail;
                    $data = Yii::$service->helper->errors->get(',');
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

                    return $responseData;
                }
            } catch (\Exception $e) {
                $innerTransaction->rollBack();
            }
        } else {
            $code = Yii::$service->helper->appserver->cart_product_add_param_invaild;
            $data = '';
            $message = 'request post param: \'product_id\' and \'qty\' can not empty';
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data,$message);

            return $responseData;

        }

    }
    /**
     * 购物车中添加优惠券.
     */
    public function actionAddcoupon()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

            return $responseData;

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
            $error_arr = Yii::$service->helper->errors->get(true);
            if (!empty($error_arr)) {
                if(is_array($error_arr)){
                    $error_str = implode(',', $error_arr);
                }else{
                    $error_str = $error_arr;
                }
                $code = Yii::$service->helper->appserver->cart_coupon_invalid;
                $data = [
                    'error' => $error_str,
                ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

                return $responseData;
            } else {
                $code = Yii::$service->helper->appserver->status_success;
                $data = [];
                $message = 'add coupon success';
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data, $message);

                return $responseData;
            }
        } else {
            $code = Yii::$service->helper->appserver->cart_coupon_invalid;
            $data = [
                'error' => 'coupon code is empty',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

            return $responseData;
        }
    }
    /**
     * 购物车中取消优惠券.
     */
    public function actionCancelcoupon()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        if (Yii::$app->user->isGuest) {
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

            return $responseData;
        }
        $coupon_code = trim(Yii::$app->request->post('coupon_code'));
        if ($coupon_code) {
            $innerTransaction = Yii::$app->db->beginTransaction();
            try {
                $cancelStatus = Yii::$service->cart->coupon->cancelCoupon($coupon_code);
                if (!$cancelStatus) {
                    $innerTransaction->rollBack();
                    $code = Yii::$service->helper->appserver->cart_coupon_invalid;
                    $data = [
                        'error' => 'cancel coupon code fail',
                    ];
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

                    return $responseData;

                }
                $error_arr = Yii::$service->helper->errors->get(true);
                if (!empty($error_arr)) {
                    if (is_array($error_arr)) {
                        $error_str = implode(',', $error_arr);
                    } else {
                        $error_str = $error_arr;
                    }
                    $innerTransaction->rollBack();
                    $code = Yii::$service->helper->appserver->cart_coupon_invalid;
                    $data = [
                        'error' => $error_str,
                    ];
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

                    return $responseData;
                } else {
                    $innerTransaction->commit();
                    $code = Yii::$service->helper->appserver->status_success;
                    $data = [];
                    $message = 'cancel coupon success';
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data, $message);

                    return $responseData;
                }
            } catch (\Exception $e) {
                $innerTransaction->rollBack();
                $code = Yii::$service->helper->appserver->cart_coupon_invalid;
                $data = [
                    'error' => 'cancel coupon fail',
                ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

                return $responseData;
            }
        } else {
            $code = Yii::$service->helper->appserver->cart_coupon_invalid;
            $data = [
                'error' => 'coupon code is empty',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

            return $responseData;
        }
    }

    public function actionUpdateinfo()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
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
                $innerTransaction->commit();
                $code = Yii::$service->helper->appserver->status_success;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

                return $responseData;
            } else {
                $innerTransaction->rollBack();
            }
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
        }
        $code = Yii::$service->helper->appserver->cart_product_update_qty_fail;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

        return $responseData;
    }

    public function actionSelectone()
    {
        $item_id = Yii::$app->request->get('item_id');
        $checked = Yii::$app->request->get('checked');
        $checked = $checked == 1 ? true : false;
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            $status = Yii::$service->cart->selectOneItem($item_id, $checked);
            if ($status) {
                $innerTransaction->commit();
                $code = Yii::$service->helper->appserver->status_success;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            } else {
                $innerTransaction->rollBack();
                $code = Yii::$service->helper->appserver->cart_product_select_fail;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            }
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
        }

        return $responseData;
    }

    public function actionSelectall()
    {
        $checked = Yii::$app->request->get('checked');
        $checked = $checked == 1 ? true : false;
        $innerTransaction = Yii::$app->db->beginTransaction();
        try {
            $status = Yii::$service->cart->selectAllItem($checked);
            if ($status) {
                $innerTransaction->commit();
                $code = Yii::$service->helper->appserver->status_success;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            } else {
                $innerTransaction->rollBack();
                $code = Yii::$service->helper->appserver->cart_product_select_fail;
                $data = [];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            }
        } catch (\Exception $e) {
            $innerTransaction->rollBack();
        }

        return $responseData;
    }

}
