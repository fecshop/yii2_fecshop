<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Wx\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0 
 */
class HomeController extends AppserverController
{
    //   general/start/first
    public function actionIndex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        
        $homeBigImgConfig = Yii::$app->controller->module->params['homeBigImg'];
        $homeData = [];
        foreach ($homeBigImgConfig as $one) {
            $picUrl = Yii::$service->image->getImgUrl($one['picUrl']);
            $linkUrl = $one['linkUrl'];
            $homeData[] = [
                'businessId' => 0,
                'dateAdd' => '2019-06-22 07:49:37',
                'id' => 18396,
                'linkUrl' => $linkUrl,
                'paixu' => 0,
                'picUrl' => $picUrl,
                'remark' => '',
                'status' => 0,
                'statusStr' => '显示',
                'title' => '女装',
                'type' => 'home',
                'userId' => 9436,
            ];
        }
        
        
        $home4BannerImg = Yii::$app->controller->module->params['home4BannerImg'];
        $hotData = [];
        foreach ($home4BannerImg as $one) {
            $picUrl = Yii::$service->image->getImgUrl($one['picUrl']);
            $linkUrl = $one['linkUrl'];
            $hotData[] = [
                'businessId' => 0,
                'dateAdd' => '2019-06-22 07:49:37',
                'id' => 18396,
                'linkUrl' => $linkUrl,
                'paixu' => 0,
                'picUrl' => $picUrl,
                'remark' => '',
                'status' => 0,
                'statusStr' => '显示',
                'title' => '女装',
                'type' => 'home',
                'userId' => 9436,
            ];
        }
        
        
        
        $productData = $this->getProduct();
        
        
        //{"barCode":"","categoryId":29582,"characteristic":"测试支付专用，不发货，不退款","commission":5.00,"commissionType":1,"dateAdd":"2018-09-14 00:00:00","dateStart":"2019-01-20 21:38:39","dateUpdate":"2019-06-24 21:54:58","gotScore":0,"gotScoreType":0,"id":115289,"kanjia":false,"kanjiaPrice":0.00,"logisticsId":3728,"miaosha":false,"minPrice":0.01,"minScore":0,"name":"支付测试专用","numberFav":3,"numberGoodReputation":6,"numberOrders":13,"numberSells":13,"originalPrice":1.00,"paixu":1,"pic":"https://cdn.it120.cc/apifactory/2019/05/21/36b3f8c1-72cb-4bad-a89d-1412fa75669a.jpg","pingtuan":false,"pingtuanPrice":0.00,"recommendStatus":1,"recommendStatusStr":"推荐","shopId":0,"status":0,"statusStr":"上架","stores":999986,"userId":9436,"videoId":"","views":330,"weight":0.00}
        
        $currencys = Yii::$service->page->currency->getCurrencys();
        $currentCurrencyCode = Yii::$service->page->currency->getCurrentCurrency();
        $currencyList = [];
        $currencyCodeList = [];
        foreach ($currencys as $currency) {
            $currencyList[] = $currency['symbol']. '' . $currency['code'];
            $currencyCodeList[] = $currency['code'];
        }
        $currency = [
            'currencyList' => $currencyList,
            'currencyCodeList' => $currencyCodeList,
            'currentCurrency' => $currentCurrencyCode
        ];
        
        
        $data = [
            //'home' => $homeData,
            'banners'  => $homeData,
            'products' => $productData,
            'topgoods'  => [
                'remark' => '备注',
                'value'  => Yii::$service->page->translate->__('Feature Product'),  //'人气推荐',
            ],
            'hot'  => $hotData,
            'currency'  => $currency,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
    }
    
    
    public function getProduct(){
        $featured_skus = Yii::$app->controller->module->params['homeFeaturedSku'];
        //echo $featured_skus ;exit;
        //Yii::$service->session->getUUID();
        return $this->getProductBySkus($featured_skus);
    }
    
    public function getProductBySkus($skus)
    {
        if (is_array($skus) && !empty($skus)) {
            $productPrimaryKey = Yii::$service->product->getPrimaryKey();
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
            $product_return = [];
            if(is_array($products) && !empty($products)){
                foreach($products as $k=>$v){
                    
                    $priceInfo = Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($v['price'], $v['special_price'],$v['special_from'],$v['special_to']);
                    $price = isset($priceInfo['price']) ? $priceInfo['price'] : '';
                    $special_price = isset($priceInfo['special_price']) ? $priceInfo['special_price'] : '';
                    
                    
                    $product_return[] = [
                        'name' => $v['name'],
                        'pic'  => Yii::$service->product->image->getResize($v['image'],296,false),
                        'special_price'  => $special_price,
                        'price'  => $price,
                        'id'  => $v['product_id'],
                    ];
                }
                
            }
            return $product_return;
        }
    }
    
}