<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Customer\controllers;

use fecshop\app\appserver\modules\AppserverTokenController;
use Yii;
use \Firebase\JWT\JWT;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class OrderController extends AppserverTokenController
{
    public $enableCsrfValidation = false ;
    protected $numPerPage = 10;
    protected $pageNum;
    protected $orderBy;
    protected $customer_id;
    protected $_page = 'p';
    
    public function actionIndex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $identity = Yii::$app->user->identity;
        $this->customer_id = $identity['id'];
        $this->pageNum = (int) Yii::$app->request->get('p');
        $this->pageNum = ($this->pageNum >= 1) ? $this->pageNum : 1;
        $this->orderBy = ['created_at' => SORT_DESC];
        $return_arr = [];
        if ($this->customer_id) {
            $filter = [
                'numPerPage'    => $this->numPerPage,
                'pageNum'        => $this->pageNum,
                'orderBy'        => $this->orderBy,
                'where'            => [
                    ['customer_id' => $this->customer_id],
                ],
                'asArray' => true,
            ];

            $customer_order_list = Yii::$service->order->coll($filter);
            $order_list = $customer_order_list['coll'];
            $count = $customer_order_list['count'];
            if(is_array($order_list)){
                foreach($order_list as $k=>$order){
                    $created_at = $order_list[$k]['created_at'];
                    $order_list[$k]['created_at'] = date('Y-m-d H:i:s',$created_at);
                }
            }
            return [
                'code'          => 200,
                'orderList'     => $order_list,
                'count'         => $count,
            ];
        }
    }
    
    
    public function actionView(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $order_id = Yii::$app->request->get('order_id');
        if ($order_id) {
            $order_info = Yii::$service->order->getOrderInfoById($order_id);
            if (isset($order_info['customer_id']) && !empty($order_info['customer_id'])) {
                $identity = Yii::$app->user->identity;
                $customer_id = $identity->id;
                if ($order_info['customer_id'] == $customer_id) {
                    $order_info['created_at'] = date('Y-m-d H:i:s',$order_info['created_at']);
                    $productArr = [];
                    if(is_array($order_info['products'])){
                        foreach($order_info['products'] as $product){
                            $productArr[] = [
                                'imgUrl' => Yii::$service->product->image->getResize($product['image'],[100,100],false),
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'qty' => $product['qty'],
                                'row_total' => $product['row_total'],
                                'product_id' => $product['product_id'],
                                'custom_option_info' => $product['custom_option_info'],
                            ];
                            
                        }
                    }
                    $order_info['products'] = $productArr;
                    return [
                        'code' => 200,
                        'order'=> $order_info,
                    ];
                    
                }
            }
        }
        
        
    }
    
    
    
}