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
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'orderList'     => $order_list,
                'count'         => $count,
            ];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
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
                    $code = Yii::$service->helper->appserver->status_success;
                    $data = [
                        'order'=> $order_info,
                    ];
                    $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
                    
                    return $reponseData;
                    
                }
            }
        }
        
        
    }
    
    public function actionReorder()
    {
        $order_id = Yii::$app->request->get('order_id');
        $errorArr = [];
        if (!$order_id) {
            $errorArr[] = 'The order id is empty';
        }
        $order = Yii::$service->order->getByPrimaryKey($order_id);
        if (!$order['increment_id']) {
            $errorArr[] = 'The order is not exist';
        }
        $customer_id = Yii::$app->user->identity->id;
        if (!$order['customer_id'] || ($order['customer_id'] != $customer_id)) {
            $errorArr[] = 'The order does not belong to you';
        }
        if(!empty($errorArr)){
            $code = Yii::$service->helper->appserver->account_reorder_order_id_invalid;
            $data = [];
            $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
            
            return $reponseData;
        }
        $this->addOrderProductToCart($order_id);

        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $reponseData = Yii::$service->helper->appserver->getReponseData($code, $data);
        
        return $reponseData;
    }
    
    
    public function addOrderProductToCart($order_id)
    {
        $items = Yii::$service->order->item->getByOrderId($order_id);
        //var_dump($items);
        if (is_array($items) && !empty($items)) {
            foreach ($items as $one) {
                $item = [
                    'product_id'        => $one['product_id'],
                    'custom_option_sku' => $one['custom_option_sku'],
                    'qty'                => (int) $one['qty'],
                ];
                //var_dump($item);exit;
                Yii::$service->cart->addProductToCart($item);
            }
        }
    }
}