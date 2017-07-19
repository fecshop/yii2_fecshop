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
class ProductController extends AppfrontController
{
    public function init()
    {
        parent::init();
        Yii::$service->page->theme->layoutFile = 'product_view.php';
    }

    // 产品详细页面
    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();
        if(is_array($data)){
            return $this->render($this->action->id, $data);
        }
    }
    /**
     * Yii2 behaviors 可以参看地址：http://www.yiichina.com/doc/guide/2.0/concept-behaviors
     * 这里的行为的作用为添加page cache（整页缓存）。
     */
    public function behaviors()
    {
        $primaryKey = Yii::$service->product->getPrimaryKey();
        $product_id = Yii::$app->request->get($primaryKey);
        $cacheName = 'product';
        if (Yii::$service->cache->isEnable($cacheName)) {
            $timeout = Yii::$service->cache->timeout($cacheName);
            $disableUrlParam = Yii::$service->cache->timeout($cacheName);
            $cacheUrlParam = Yii::$service->cache->cacheUrlParam($cacheName);
            $get_str = '';
            $get = Yii::$app->request->get();
            // 存在无缓存参数，则关闭缓存
            if (isset($get[$disableUrlParam])) {
                return [
                    [
                        'enabled' => false,
                        'class' => 'yii\filters\PageCache',
                        'only' => ['index'],

                    ],
                ];
            }
            if (is_array($get) && !empty($get) && is_array($cacheUrlParam)) {
                foreach ($get as $k=>$v) {
                    if (in_array($k, $cacheUrlParam)) {
                        if ($k != 'p' && $v != 1) {
                            $get_str .= $k.'_'.$v.'_';
                        }
                    }
                }
            }
            $store = Yii::$service->store->currentStore;
            $currency = Yii::$service->page->currency->getCurrentCurrency();

            return [
                [
                    'enabled' => true,
                    'class' => 'yii\filters\PageCache',
                    'only' => ['index'],
                    'duration' => $timeout,
                    'variations' => [
                        $store, $currency, $get_str, $product_id,
                    ],
                    //'dependency' => [
                    //	'class' => 'yii\caching\DbDependency',
                    //	'sql' => 'SELECT COUNT(*) FROM post',
                    //],
                ],
            ];
        }

        return [];
    }

    // ajax 得到产品加入购物车的价格。
    public function actionGetcoprice()
    {
        $custom_option_sku = Yii::$app->request->get('custom_option_sku');
        $product_id = Yii::$app->request->get('product_id');
        $qty = Yii::$app->request->get('qty');
        $cart_price = 0;
        $custom_option_price = 0;
        $product = Yii::$service->product->getByPrimaryKey($product_id);
        $cart_price = Yii::$service->product->price->getCartPriceByProductId($product_id, $qty, $custom_option_sku);
        if (!$cart_price) {
            return;
        }
        $price_info = [
            'price' => $cart_price,
        ];

        $priceView = [
            'view'    => 'catalog/product/index/price.php',
        ];
        $priceParam = [
            'price_info' => $price_info,
        ];

        echo  json_encode([
            'price' =>Yii::$service->page->widget->render($priceView, $priceParam),
        ]);
        exit;
    }
}
