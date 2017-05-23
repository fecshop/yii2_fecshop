<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\checkout\block\cart;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    public function getLastData()
    {
        $this->initHead();
        $currency_info = Yii::$service->page->currency->getCurrencyInfo();

        return [
            'cart_info' => $this->getCartInfo(),
            'currency_info' => $currency_info,
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
                    $cart_info['products'][$k]['image'] = $product_one['product_image']['main']['image'];
                }
                // 产品的url
                $cart_info['products'][$k]['url'] = Yii::$service->url->getUrl($product_one['product_url']);

                $custom_option = isset($product_one['custom_option']) ? $product_one['custom_option'] : '';
                $custom_option_sku = $product_one['custom_option_sku'];
                // 将在产品页面选择的颜色尺码等属性显示出来。
                $custom_option_info_arr = $this->getProductOptions($product_one);
                $cart_info['products'][$k]['custom_option_info'] = $custom_option_info_arr;
                // 设置相应的custom option 对应的图片
                $custom_option_image = isset($custom_option[$custom_option_sku]['image']) ? $custom_option[$custom_option_sku]['image'] : '';
                if ($custom_option_image) {
                    $cart_info['products'][$k]['image'] = $custom_option_image;
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

    public function initHead()
    {
        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => 'checkout cart',
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'checkout cart page',
        ]);
        $this->_title = 'checkout cart page';
        Yii::$app->view->title = $this->_title;
    }
}
