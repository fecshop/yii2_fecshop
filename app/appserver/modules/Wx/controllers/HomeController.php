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
        $homeData = [
            [
                'businessId' => 0,
                'dateAdd' => '2019-06-22 07:49:37',
                'id' => 18396,
                'linkUrl' => '',
                'paixu' => 0,
                'picUrl' => Yii::$service->image->getImgUrl('wx/6a202e5f215489f5082b5293476f301c.jpg'),
                'remark' => '',
                'status' => 0,
                'statusStr' => '显示',
                'title' => '女装',
                'type' => 'home',
                'userId' => 9436,
            ],
            
            [
                'businessId' => 0,
                'dateAdd' => '2019-06-22 07:49:37',
                'id' => 18395,
                'linkUrl' => '',
                'paixu' => 0,
                'picUrl' => Yii::$service->image->getImgUrl('wx/2829495b358a87480dcf0abf4b77c9b7.jpg'),
                'remark' => '',
                'status' => 0,
                'statusStr' => '显示',
                'title' => '首页幻灯',
                'type' => 'home',
                'userId' => 9436,
            ],
        ];
        
        $productData = $this->getProduct();
        /*
        $productData = [
            [
                'name' => 'xxx1',
                'pic'  => 'https://cdn.it120.cc/apifactory/2019/05/21/36b3f8c1-72cb-4bad-a89d-1412fa75669a.jpg',
                'special_price'  => '22.22',
                'price'  => '23.32',
            
            ],
            [
                'name' => 'xxx2',
                'pic'  => 'https://cdn.it120.cc/apifactory/2019/05/21/36b3f8c1-72cb-4bad-a89d-1412fa75669a.jpg',
                'special_price'  => '22.22',
                'price'  => '23.32',
            
            ],
            [
                'name' => 'xxx3',
                'pic'  => 'https://cdn.it120.cc/apifactory/2019/05/21/36b3f8c1-72cb-4bad-a89d-1412fa75669a.jpg',
                'special_price'  => '22.22',
                'price'  => '23.32',
            
            ],
            [
                'name' => 'xxx4',
                'pic'  => 'https://cdn.it120.cc/apifactory/2019/05/21/36b3f8c1-72cb-4bad-a89d-1412fa75669a.jpg',
                'special_price'  => '22.22',
                'price'  => '23.32',
            
            ],
            
        ];
        */
        
        $hotData = [
            [
                'businessId' => 0,
                'dateAdd' => '2019-06-22 07:49:37',
                'id' => 18388,
                'linkUrl' => '/pages/goods-detail/goods-detail?id=115781',
                'paixu' => 0,
                'picUrl' => Yii::$service->image->getImgUrl('wx/f9d34e8258cdef1dbcb5e1de65bdb404.jpg'), 
                'remark' => '',
                'status' => 0,
                'statusStr' => '显示',
                'title' => 'haowu',
                'type' => 'hot',
                'userId' => 9436,
            ],
            [
                'businessId' => 0,
                'dateAdd' => '2019-06-22 07:49:37',
                'id' => 18388,
                'linkUrl' => '/pages/goods-detail/goods-detail?id=115781',
                'paixu' => 0,
                'picUrl' => Yii::$service->image->getImgUrl('wx/a7658f2456e708e6be03d393cef0d368.jpg'), 
                'remark' => '',
                'status' => 0,
                'statusStr' => '显示',
                'title' => 'haowu',
                'type' => 'hot',
                'userId' => 9436,
            ],
            [
                'businessId' => 0,
                'dateAdd' => '2019-06-22 07:49:37',
                'id' => 18388,
                'linkUrl' => '/pages/goods-detail/goods-detail?id=115781',
                'paixu' => 0,
                'picUrl' => Yii::$service->image->getImgUrl('wx/868b8a0e1065f7123c949fb404049ce0.jpg'),  
                'remark' => '',
                'status' => 0,
                'statusStr' => '显示',
                'title' => 'haowu',
                'type' => 'hot',
                'userId' => 9436,
            ],
            [
                'businessId' => 0,
                'dateAdd' => '2019-06-22 07:49:37',
                'id' => 18388,
                'linkUrl' => '/pages/goods-detail/goods-detail?id=115781',
                'paixu' => 0,
                'picUrl' => Yii::$service->image->getImgUrl('wx/6480b6574ab76caf1d86a9fc327a62e8.jpg'), 
                'remark' => '',
                'status' => 0,
                'statusStr' => '显示',
                'title' => 'haowu',
                'type' => 'hot',
                'userId' => 9436,
            ],
        
        ];
        
        $banners = [
            [
                'businessId' => 0,
                'dateAdd' => '2019-06-22 07:49:37',
                'id' => 18396,
                'linkUrl' => '',
                'paixu' => 0,
                'picUrl' => "https://cdn.it120.cc/apifactory/2018/09/06/6a202e5f215489f5082b5293476f301c.jpg",
                'remark' => '',
                'status' => 0,
                'statusStr' => '显示',
                'title' => '女装',
                'type' => 'home',
                'userId' => 9436,
            ],
            
            [
                'businessId' => 0,
                'dateAdd' => '2019-06-22 07:49:37',
                'id' => 18395,
                'linkUrl' => '',
                'paixu' => 0,
                'picUrl' => "https://cdn.it120.cc/apifactory/2018/09/06/2829495b358a87480dcf0abf4b77c9b7.jpg",
                'remark' => '',
                'status' => 0,
                'statusStr' => '显示',
                'title' => '首页幻灯',
                'type' => 'home',
                'userId' => 9436,
            ],
        
        ];
        
        //{"barCode":"","categoryId":29582,"characteristic":"测试支付专用，不发货，不退款","commission":5.00,"commissionType":1,"dateAdd":"2018-09-14 00:00:00","dateStart":"2019-01-20 21:38:39","dateUpdate":"2019-06-24 21:54:58","gotScore":0,"gotScoreType":0,"id":115289,"kanjia":false,"kanjiaPrice":0.00,"logisticsId":3728,"miaosha":false,"minPrice":0.01,"minScore":0,"name":"支付测试专用","numberFav":3,"numberGoodReputation":6,"numberOrders":13,"numberSells":13,"originalPrice":1.00,"paixu":1,"pic":"https://cdn.it120.cc/apifactory/2019/05/21/36b3f8c1-72cb-4bad-a89d-1412fa75669a.jpg","pingtuan":false,"pingtuanPrice":0.00,"recommendStatus":1,"recommendStatusStr":"推荐","shopId":0,"status":0,"statusStr":"上架","stores":999986,"userId":9436,"videoId":"","views":330,"weight":0.00}
        
        
        $data = [
            'home' => $homeData,
            'banners'  => $banners,
            'products' => $productData,
            'topgoods'  => [
                'remark' => '备注',
                'value'  => '人气推荐',
            ],
            'hot'  => $hotData,
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
            $filter['select'] = [
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