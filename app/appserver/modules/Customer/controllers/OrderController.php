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
    
    public function initWxWhere($where)
    {
        $wxRequestOrderStatus = Yii::$app->request->get("wxRequestOrderStatus");
        $this->numPerPage = 100;
        
        if (!$wxRequestOrderStatus || $wxRequestOrderStatus == 'all') {
            return $where;
        }
        if ($wxRequestOrderStatus == 0) {
            $where[] = ['in', 'order_status', [
                Yii::$service->order->payment_status_pending,
                Yii::$service->order->payment_status_processing,
            ]]; 
        } else if ($wxRequestOrderStatus == 1) {
            $where[] = ['in', 'order_status', [
                Yii::$service->order->payment_status_confirmed,
                Yii::$service->order->status_processing,
            ]]; 
        } else if ($wxRequestOrderStatus == 2) {
            $where[] = ['order_status' => Yii::$service->order->status_dispatched];
            
        } else if ($wxRequestOrderStatus == 3) {
            $where[] = ['in', 'order_status', [
                Yii::$service->order->status_refunded,
                Yii::$service->order->status_completed,
            ]]; 
            
        }
        
        return $where;
    }
    
    public function actionDelivery()
    {
        $increment_id = Yii::$app->request->get('orderId');
        $identity = Yii::$app->user->identity;
        $customer_id = $identity->id;
        if (Yii::$service->order->delivery($increment_id, $customer_id)) {
            $code = Yii::$service->helper->appserver->status_success;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

            return $responseData;
        }
        
    }
    
    public function actionCancel()
    {
        $increment_id = Yii::$app->request->get('orderId');
        $identity = Yii::$app->user->identity;
        $customer_id = $identity->id;
        if (!$increment_id) {
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [
                'errors' => 'request orderId is empty',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

            return $responseData;
        }
        if (!$customer_id){
            $code = Yii::$service->helper->appserver->status_invalid_param;
            $data = [
                'errors' => 'not login account',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

            return $responseData;
        }
        if (Yii::$service->order->cancel($increment_id, $customer_id)) {
            $code = Yii::$service->helper->appserver->status_success;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

            return $responseData;
        }
        
    }
    
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
        // 订单信息，是否包含产品信息
        $orderWithItems = Yii::$app->request->get('withItems');
        // 微信订单过滤
        $where[] = ['customer_id' => $this->customer_id];
        $where = $this->initWxWhere($where);
        //var_dump($where);
        if ($this->customer_id) {
            $filter = [
                'numPerPage'    => $this->numPerPage,
                'pageNum'        => $this->pageNum,
                'orderBy'        => $this->orderBy,
                'where'            => $where,
                'asArray' => true,
            ];

            $customer_order_list = Yii::$service->order->coll($filter);
            $order_list = $customer_order_list['coll'];
            $count = $customer_order_list['count'];
            $orderArr = [];
            $orderIds = [];
            if(is_array($order_list)){
                foreach($order_list as $k=>$order){
                    $currencyCode = $order['order_currency_code'];
                    $order['currency_symbol'] = Yii::$service->page->currency->getSymbol($currencyCode);
                    $orderArr[] = $this->getOrderArr($order);
                    $orderIds[] = $order['order_id'];
                }
            }
            // 包含订单产品信息
            $orderItems = [];
            if ($orderWithItems == 1) {
                $items = Yii::$service->order->item->getByOrderIds($orderIds, true);
                if (is_array($items) && !empty($items)) {
                    foreach ($items as $item) {
                        $item['pic'] = Yii::$service->product->image->getUrl($item['image']);
                        $orderItems[$item['order_id']][] = $item;
                    }
                }
                foreach($orderArr as $k=>$order){
                    $orderArr[$k]['item_products'] = $orderItems[$order['order_id']];
                }
            }
            $code = Yii::$service->helper->appserver->status_success;
            $data = [
                'orderList'     => $orderArr,
                'count'         => $count,
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

            return $responseData;
        }
    }

    public function getOrderArr($order){
        $orderInfo = [];
        $orderInfo['created_at'] = date('Y-m-d H:i:s',$order['created_at']);
        $orderInfo['updated_at'] = date('Y-m-d H:i:s',$order['updated_at']);
        $orderInfo['increment_id'] = $order['increment_id'];
        $orderInfo['order_id'] = $order['order_id'];
        $orderInfo['order_status'] = $order['order_status'];
        $orderInfo['items_count'] = $order['items_count'];
        $orderInfo['total_weight'] = $order['total_weight'];
        $orderInfo['order_currency_code'] = $order['order_currency_code'];
        $orderInfo['order_to_base_rate'] = $order['order_to_base_rate'];
        $orderInfo['grand_total'] = $order['grand_total'];
        $orderInfo['base_grand_total'] = $order['base_grand_total'];
        $orderInfo['subtotal'] = $order['subtotal'];
        $orderInfo['base_subtotal'] = $order['base_subtotal'];
        $orderInfo['subtotal_with_discount'] = $order['subtotal_with_discount'];
        $orderInfo['base_subtotal_with_discount'] = $order['base_subtotal_with_discount'];
        $orderInfo['checkout_method'] = $order['checkout_method'];
        $orderInfo['customer_id'] = $order['customer_id'];
        $orderInfo['customer_group'] = $order['customer_group'];
        $orderInfo['customer_email'] = $order['customer_email'];
        $orderInfo['customer_firstname'] = $order['customer_firstname'];
        $orderInfo['customer_lastname'] = $order['customer_lastname'];
        $orderInfo['customer_is_guest'] = $order['customer_is_guest'];
        $orderInfo['coupon_code'] = $order['coupon_code'];
        $orderInfo['payment_method'] = $order['payment_method'];
        $orderInfo['shipping_method'] = $order['shipping_method'];
        $orderInfo['tracking_number'] = $order['tracking_number'];
        $orderInfo['tracking_company'] = $order['tracking_company'];

        $orderInfo['shipping_total'] = $order['shipping_total'];
        $orderInfo['base_shipping_total'] = $order['base_shipping_total'];
        $orderInfo['customer_telephone'] = $order['customer_telephone'];
        $orderInfo['customer_address_country'] = $order['customer_address_country'];
        $orderInfo['customer_address_state'] = $order['customer_address_state'];
        $orderInfo['customer_address_city'] = $order['customer_address_city'];
        $orderInfo['customer_address_zip'] = $order['customer_address_zip'];
        $orderInfo['customer_address_street1'] = $order['customer_address_street1'];
        $orderInfo['customer_address_street2'] = $order['customer_address_street2'];
        $orderInfo['customer_address_state_name'] = $order['customer_address_state_name'];
        $orderInfo['customer_address_country_name'] = $order['customer_address_country_name'];
        $orderInfo['currency_symbol'] = $order['currency_symbol'];
        $orderInfo['products'] = $order['products'];



        return $orderInfo;
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
                    $order_info = $this->getOrderArr($order_info);
                    $productArr = [];
                    //var_dump($order_info);exit;
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
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

                    return $responseData;

                }
            }
        }


    }
    
    public function actionWxview(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $increment_id = Yii::$app->request->get('increment_id');
        if ($increment_id) {
            $order_info = Yii::$service->order->getOrderInfoByIncrementId($increment_id);
            if (isset($order_info['customer_id']) && !empty($order_info['customer_id'])) {
                $identity = Yii::$app->user->identity;
                $customer_id = $identity->id;
                if ($order_info['customer_id'] == $customer_id) {
                    $order_info = $this->getOrderArr($order_info);
                    $productArr = [];
                    //var_dump($order_info);exit;
                    if(is_array($order_info['products'])){
                        foreach($order_info['products'] as $product){
                            $custom_option_info_arr = [];
                            if (is_array($product['custom_option_info'])) {
                                foreach ($product['custom_option_info'] as $attr => $val) {
                                    $custom_option_info_arr[] = Yii::$service->page->translate->__($attr) .':'. Yii::$service->page->translate->__($val);
                                }
                            }
                            $custom_option_info_str = implode(',', $custom_option_info_arr);
                            $productArr[] = [
                                'imgUrl' => Yii::$service->product->image->getResize($product['image'],[100,100],false),
                                'name' => $product['name'],
                                'sku' => $product['sku'],
                                'qty' => $product['qty'],
                                'row_total' => $product['row_total'],
                                'price' => $product['price'],
                                'product_id' => $product['product_id'],
                                'custom_option_info' => $product['custom_option_info'],
                                'custom_option_info_str' =>$custom_option_info_str,
                            ];

                        }
                    }
                    $order_info['products'] = $productArr;
                    $code = Yii::$service->helper->appserver->status_success;
                    $data = [
                        'order'=> $order_info,
                    ];
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

                    return $responseData;

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
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

            return $responseData;
        }
        $this->addOrderProductToCart($order_id);

        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);

        return $responseData;
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
