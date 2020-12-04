<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Cms\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class HomeController extends AppserverController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $cacheName = 'home';
        if (Yii::$service->cache->isEnable($cacheName)) {
            $timeout = Yii::$service->cache->timeout($cacheName);
            $disableUrlParam = Yii::$service->cache->disableUrlParam($cacheName);
            $get = Yii::$app->request->get();
            // 存在无缓存参数，则关闭缓存
            if (isset($get[$disableUrlParam])) {
                $behaviors[] =  [
                    'enabled' => false,
                    'class' => 'yii\filters\PageCache',
                    'only' => ['index'],
                ];
                
                return $behaviors;
            }
            $store = Yii::$service->store->currentStore;
            $currency = Yii::$service->page->currency->getCurrentCurrency();
            $langCode = Yii::$service->store->currentLangCode;
            $behaviors[] =  [
                'enabled' => true,
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => $timeout,
                'variations' => [
                    $store, $currency,$langCode
                ],
            ];
        }

        return $behaviors;
    }
    public function actionIndex(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $advertiseImg = $this->getAdvertise();
        $productList  = $this->getProduct();
        $language = $this->getLang();
        $currency = $this->getCurrency();
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
                'productList' => $productList,
                'advertiseImg'=> $advertiseImg,
                'language'    => $language,
                'currency'    => $currency,
            ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    public function getAdvertise(){
        
        $bigImg1 = Yii::$service->image->getImgUrl('apphtml5/custom/home_img_1.jpg');
        $bigImg2 = Yii::$service->image->getImgUrl('apphtml5/custom/home_img_2.jpg');
        $bigImg3 = Yii::$service->image->getImgUrl('apphtml5/custom/home_img_3.jpg');
        $smallImg1 = Yii::$service->image->getImgUrl('apphtml5/custom/home_small_1.jpg');
        $smallImg2 = Yii::$service->image->getImgUrl('apphtml5/custom/home_small_2.jpg');
        
        return [
            'bigImgList' => [
                ['imgUrl' => $bigImg1],
                ['imgUrl' => $bigImg2],
                ['imgUrl' => $bigImg3],
            ],
            'smallImgList' => [
                ['imgUrl' => $smallImg1],
                ['imgUrl' => $smallImg2],
            ],
        ];
    }
    
    public function getProduct(){
        $appName = Yii::$service->helper->getAppName();
        $bestFeatureSkuConfig = Yii::$app->store->get($appName.'_home', 'best_feature_sku');
        $featured_skus = explode(',', $bestFeatureSkuConfig);

        return $this->getProductBySkus($featured_skus);
    }
    
    public function getProductBySkus($skus)
    {
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        if (is_array($skus) && !empty($skus)) {
            $filter['select'] = [
                $productPrimaryKey,
                'sku', 'spu', 'name', 'image',
                'price', 'special_price',
                'special_from', 'special_to',
                'url_key', 'score',
            ];
            $filter['where'] = ['in', 'sku', $skus];
            $products = Yii::$service->product->getProducts($filter);
            //var_dump($products);
            $products = Yii::$service->category->product->convertToCategoryInfo($products);
            $i = 1;
            $product_return = [];
            if(is_array($products) && !empty($products)){
                
                foreach($products as $k=>$v){
                    $i++;
                    $products[$k]['url'] = '/catalog/product/'.$v['product_id'];
                    $products[$k]['image'] = Yii::$service->product->image->getResize($v['image'],296,false);
                    $priceInfo = Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($v['price'], $v['special_price'],$v['special_from'],$v['special_to']);
                    $products[$k]['price'] = isset($priceInfo['price']) ? $priceInfo['price'] : '';
                    $products[$k]['special_price'] = isset($priceInfo['special_price']) ? $priceInfo['special_price'] : '';
                    if (isset($products[$k]['special_price']['value'])) {
                        $products[$k]['special_price']['value'] = Yii::$service->helper->format->numberFormat($products[$k]['special_price']['value']);
                    }
                    if (isset($products[$k]['price']['value'])) {
                        $products[$k]['price']['value'] = Yii::$service->helper->format->numberFormat($products[$k]['price']['value']);
                    }
                    if($i%2 === 0){
                        $arr = $products[$k];
                    }else{
                        $product_return[] = [
                            'one' => $arr,
                            'two' => $products[$k],
                        ];
                    }
                }
                if($i%2 === 0){
                    $product_return[] = [
                        'one' => $arr,
                        'two' => [],
                    ];
                }
            }
            return $product_return;
        }
    }
    
    // 语言
    public function getLang()
    {
        $langs = Yii::$service->store->serverLangs;
        $currentLangCode = Yii::$service->store->currentLangCode;
        
        return [
            'langList' => $langs,
            'currentLang' => $currentLangCode
        ];
    }
    // 货币
    public function getCurrency()
    {
        $currencys = Yii::$service->page->currency->getCurrencys();
        $currentCurrencyCode = Yii::$service->page->currency->getCurrentCurrency();
        
        return [
            'currencyList' => $currencys,
            'currentCurrency' => $currentCurrencyCode
        ];
    }
   
    
}