<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appserver\modules\Checkout\controllers;
use fecshop\app\appserver\modules\AppserverController;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CartController extends AppserverController
{
    public $enableCsrfValidation = false;
    
    public function actionIndex()
    {
        $currency_info = Yii::$service->page->currency->getCurrencyInfo();

        return [
            'code' => 200,
            'cart_info' => $this->getCartInfo(),
            'currency' => $currency_info,
        ];
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
        $cart_info = Yii::$service->cart->getCartInfo();

        if (isset($cart_info['products']) && is_array($cart_info['products'])) {
            foreach ($cart_info['products'] as $k=>$product_one) {
                // 设置名字，得到当前store的语言名字。
                $cart_info['products'][$k]['name'] = Yii::$service->store->getStoreAttrVal($product_one['product_name'], 'name');
                // 设置图片
                if (isset($product_one['product_image']['main']['image'])) {
                    $productImg = $product_one['product_image']['main']['image'];
                    $cart_info['products'][$k]['img_url'] = Yii::$service->product->image->getResize($productImg,[150,150],false);
                }
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
        //echo 1;exit;
        $custom_option = Yii::$app->request->post('custom_option');
        $product_id = Yii::$app->request->post('product_id');
        $qty = Yii::$app->request->post('qty');
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
                $addToCart = Yii::$service->cart->addProductToCart($item);
                if ($addToCart) {
                    $return = [
                        'status' => 'success',
                        'items_count' => Yii::$service->cart->quote->getCartItemCount(),
                    ];
                    $code = 200;
                    $innerTransaction->commit();
                } else {
                    $errors = Yii::$service->helper->errors->get(',');
                    $return = [
                        'status' => 'fail',
                        'content'=> $errors,
                        //'items_count' => Yii::$service->cart->quote->getCartItemCount(),
                    ];
                    $code = 400;
                    $innerTransaction->rollBack();
                }
            } catch (Exception $e) {
                $innerTransaction->rollBack();
            }
        }
        
        return [
            'code'      => $code ,
            'content'   => $return ,
        ];
    }
    /**
     * 购物车中添加优惠券.
     */
    public function actionAddcoupon()
    {
        if (Yii::$app->user->isGuest) {
            return [
                'code' => 400,
                'content' => 'you must login your account',
            ];
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
            } catch (Exception $e) {
                $innerTransaction->rollBack();
            }
            $error_arr = Yii::$service->helper->errors->get(true);
            if (!empty($error_arr)) {
                $error_str = implode(',', $error_arr);
                return [
                    'code' => '401',
                    'content'=> $error_str,
                ];
            } else {
                return [
                    'code' => '200',
                    'content'=> 'add coupon success',
                ];
            }
        } else {
            return [
                'status' => '402',
                'content'=> 'coupon is empty',
            ];
        }
    }
    /**
     * 购物车中取消优惠券.
     */
    public function actionCancelcoupon()
    {
        if (Yii::$app->user->isGuest) {
            return [
                'code' => 400,
                'content' => 'you must login your account',
            ];
        }
        $coupon_code = trim(Yii::$app->request->post('coupon_code'));
        if ($coupon_code) {
            $innerTransaction = Yii::$app->db->beginTransaction();
            try {
                $cancelStatus = Yii::$service->cart->coupon->cancelCoupon($coupon_code);
                if (!$cancelStatus) {
                    $innerTransaction->rollBack();
                    return [
                        'code' => 401,
                        'content'=> 'cancel coupon fail',
                    ];
                    
                }
                $error_arr = Yii::$service->helper->errors->get(true);
                if (!empty($error_arr)) {
                    $error_str = implode(',', $error_arr);
                    $innerTransaction->rollBack();
                    return [
                        'code' => '401',
                        'content'=> $error_str,
                    ];
                    
                } else {
                    $innerTransaction->commit();
                    return [
                        'code' => '200',
                        'content'=> 'add coupon success',
                    ];
                }
            } catch (Exception $e) {
                $innerTransaction->rollBack();
                return [
                    'code' => '401',
                    'content'=> 'fail',
                ];
            }
        } else {
            return [
                'status' => '402',
                'content'=> 'coupon is empty',
            ];
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
                
                $innerTransaction->commit();
                return [
                    'code' => 200,
                    'content' => 'success'
                ];
            } else {
                $innerTransaction->rollBack();
            }
        } catch (Exception $e) {
            $innerTransaction->rollBack();
        }
        return [
            'code' => 401,
            'content' => 'update cart info  fail'
        ];
    }
}