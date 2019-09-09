<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license/
 */

namespace fecshop\app\appfront\modules\Cms\block\home;

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
        // change current layout File.
        //Yii::$service->page->theme->layoutFile = 'home.php';
        return [
            'bestFeaturedProducts'     => $this->getFeaturedProduct(),
            'bestSellerProducts'    => $this->getBestSellerProducts(),
        ];
    }

    public function getFeaturedProduct()
    {
        $appName = Yii::$service->helper->getAppName();
        $bestFeatureSkuConfig = Yii::$app->store->get($appName.'_home', 'best_feature_sku');
        $featured_skus = explode(',', $bestFeatureSkuConfig);

        return $this->getProductBySkus($featured_skus);
    }

    public function getBestSellerProducts()
    {
        $appName = Yii::$service->helper->getAppName();
        $bestSellSkusConfig = Yii::$app->store->get($appName.'_home', 'best_seller_sku');
        $bestSellSkus = explode(',', $bestSellSkusConfig);
        return $this->getProductBySkus($bestSellSkus);
    }

    public function getProductBySkus($skus)
    {
        if (is_array($skus) && !empty($skus)) {
            $filter['select'] = [
                'sku', 'spu', 'name', 'image',
                'price', 'special_price',
                'special_from', 'special_to',
                'url_key', 'score', 'reviw_rate_star_average', 'review_count'
            ];
            $filter['where'] = ['in', 'sku', $skus];
            $products = Yii::$service->product->getProducts($filter);
            $products = Yii::$service->category->product->convertToCategoryInfo($products);

            return $products;
        }
    }

    public function initHead()
    {
        $appName = Yii::$service->helper->getAppName();
        $home_title = Yii::$app->store->get($appName.'_home', 'meta_title');
        $appName = Yii::$service->helper->getAppName();
        $home_meta_keywords = Yii::$app->store->get($appName.'_home', 'meta_keywords');
        $appName = Yii::$service->helper->getAppName();
        $home_meta_description = Yii::$app->store->get($appName.'_home', 'meta_description');
        
        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => Yii::$service->store->getStoreAttrVal($home_meta_keywords, 'meta_keywords'),
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => Yii::$service->store->getStoreAttrVal($home_meta_description, 'meta_description'),
        ]);
        Yii::$app->view->title = Yii::$service->store->getStoreAttrVal($home_title, 'meta_title');
    }
}
