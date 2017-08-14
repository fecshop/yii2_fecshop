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
    
    public function actionAdvertise(){
        
        $bigImg1 = Yii::$service->image->getImgUrl('custom/home_img_1.jpg','apphtml5');
        $bigImg2 = Yii::$service->image->getImgUrl('custom/home_img_2.jpg','apphtml5');
        $bigImg3 = Yii::$service->image->getImgUrl('custom/home_img_3.jpg','apphtml5');
        $smallImg1 = Yii::$service->image->getImgUrl('custom/home_small_1.jpg','apphtml5');
        $smallImg2 = Yii::$service->image->getImgUrl('custom/home_small_2.jpg','apphtml5');
        
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
    
    public function actionProduct(){
        $featured_skus = Yii::$app->controller->module->params['homeFeaturedSku'];
        Yii::$service->session->getUUID();
        return $this->getProductBySkus($featured_skus);
    }
    
    

    //public function getBestSellerProduct(){
    //	$best_skus = Yii::$app->controller->module->params['homeBestSellerSku'];
    //	return $this->getProductBySkus($best_skus);
    //}

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
   
    
}