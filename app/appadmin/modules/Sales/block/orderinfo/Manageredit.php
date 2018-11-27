<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Sales\block\orderinfo;

use fec\helpers\CUrl;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit
{
    public $_saveUrl;

    public function init()
    {
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $order_id = Yii::$app->request->get('order_id');
        //$order = Yii::$service->order->getByPrimaryKey($order_id);
        $order_info = Yii::$service->order->getOrderInfoById($order_id);
        $order_info = $this->getViewOrderInfo($order_info);
        return [
            'order' => $order_info,
            //'editBar' 	=> $this->getEditBar(),
            //'textareas'	=> $this->_textareas,
            //'lang_attr'	=> $this->_lang_attr,
            'saveUrl' 	    => Yii::$service->url->getUrl('sales/orderinfo/managereditsave'),
        ];
    }
    
    public function getViewOrderInfo($order_info){
        // 订单状态部分
        $orderStatusArr = Yii::$service->order->getStatusArr();
        //var_dump($orderStatusArr);exit;
        $order_info['order_status_options'] = $this->getOptions($orderStatusArr,$order_info['order_status']);
    
        // 货币部分
        $currencys = Yii::$service->page->currency->getCurrencys();
        $currencyArr = [];
        if(is_array($currencys)){
            foreach( $currencys as $code => $v){
                $currencyArr[$code] = $code;
            }
        }
        $order_info['order_currency_code_options'] = $this->getOptions($currencyArr,$order_info['order_currency_code']);
        // 支付类型
        $checkTypeArr = Yii::$service->order->getCheckoutTypeArr();
        $order_info['checkout_method_options'] = $this->getOptions($checkTypeArr,$order_info['checkout_method']);
        // 游客下单
        $customerOrderArr = [ 
            1 => Yii::$service->page->translate->__('Yes'),
            2 => Yii::$service->page->translate->__('No'),
        ];
        $order_info['customer_is_guest_options'] = $this->getOptions($customerOrderArr,$order_info['customer_is_guest']);
        // 省
        $order_info['customer_address_country_options'] = Yii::$service->helper->country->getCountryOptionsHtml($order_info['customer_address_country']);
        // 市
        $order_info['customer_address_state_options'] = Yii::$service->helper->country->getStateOptionsByContryCode($order_info['customer_address_country'],$order_info['customer_address_state']);
        // 支付方式label 货运方式label
        $order_info['shipping_method_label'] = Yii::$service->shipping->getShippingLabelByMethod($order_info['shipping_method']);
        $order_info['payment_method_label'] = Yii::$service->payment->getPaymentLabelByMethod($order_info['payment_method']);
        
        return $order_info;
    }
    
    public function getOptions($orderStatusArr,$order_status){
        $str = '';
        if (is_array($orderStatusArr)) {
            foreach ($orderStatusArr as $k => $v) {
                if ($order_status == $k ) {
                    $str .= '<option selected="selected" value="'.$k.'">'.$v.'</option>';
                } else {
                    $str .= '<option value="'.$k.'">'.$v.'</option>';
                }
            }
        }
        
        return $str;
    }
    
    public function save(){
        $editForm = Yii::$app->request->post('editForm');
        $order_id = $editForm['order_id'];
        $orderModel = Yii::$service->order->getByPrimaryKey($order_id);
        if (is_array($editForm) && $orderModel['order_id']) {
            foreach ($editForm as $k => $v) {
                if ($orderModel->hasAttribute($k)) {
                    $orderModel[$k] = $v;
                }
            } 
            $orderModel->save();
        }
        echo  json_encode([
            'statusCode' => '200',
            'message' => Yii::$service->page->translate->__('Save Success'),
        ]);
        exit;
    }
    
    public function exportExcel(){
        $order_ids = Yii::$app->request->post('order_ids');
        $order_arr = explode(',', $order_ids);
        $excelArr[] = [
            Yii::$service->page->translate->__('Order Id'),
            Yii::$service->page->translate->__('Increment Id'),
            Yii::$service->page->translate->__('Order Status') ,
            Yii::$service->page->translate->__('Store'),
            Yii::$service->page->translate->__('Created At'),
            Yii::$service->page->translate->__('Update At'),
            Yii::$service->page->translate->__('Base Grand Total'),
            Yii::$service->page->translate->__('Customer Id'), 
            Yii::$service->page->translate->__('Order Email'),
            Yii::$service->page->translate->__('First Name') ,
            Yii::$service->page->translate->__('Last Name'),
            Yii::$service->page->translate->__('Is Guest'),
            Yii::$service->page->translate->__('Coupon Code'),
            Yii::$service->page->translate->__('Payment Method'),
            Yii::$service->page->translate->__('Shipping Method'),
            Yii::$service->page->translate->__('Base Shipping Total'),
            Yii::$service->page->translate->__('Telephone') ,
            Yii::$service->page->translate->__('Country'),
            Yii::$service->page->translate->__('State'),
            Yii::$service->page->translate->__('City'),
            Yii::$service->page->translate->__('Zip'),
            Yii::$service->page->translate->__('Street1'),
            Yii::$service->page->translate->__('Street2'),
            Yii::$service->page->translate->__('Remark'),
            Yii::$service->page->translate->__('Product Id'),
            Yii::$service->page->translate->__('Sku'),
            Yii::$service->page->translate->__('Product Name'),
            Yii::$service->page->translate->__('Custom Option Sku') ,
            Yii::$service->page->translate->__('Weight'),
            Yii::$service->page->translate->__('Qty'),
            Yii::$service->page->translate->__('Product Unit Price(base price)'),
        ];
        if (!empty($order_arr)) {
            $orderFilter = [
               'numPerPage' 	=> 1000,
               'pageNum'		=> 1,
               'where'			=> [
                   ['in', 'order_id', $order_arr],
                ],
                'asArray' => true,
            ];
            $orderData = Yii::$service->order->coll($orderFilter);
            $orderColl = $orderData['coll'];
            // 订单数据
            $orderCollArr = [];
            if (!empty($orderColl) && is_array($orderColl)) {
                foreach ($orderColl as $order) {
                    $orderCollArr[$order['order_id']] = $order;
                }
            }
            // 查找订单产品
            $orderFilter = [
               'numPerPage' 	=> 1000,
               'pageNum'		=> 1,
               'orderBy'	    => ['order_id' => SORT_DESC ],
               'where'			=> [
                   ['in', 'order_id', $order_arr],
                ],
                'asArray' => true,
            ];
            $orderItemData = Yii::$service->order->item->coll($orderFilter);
            $orderItemColl = $orderItemData['coll'];
            if (!empty($orderItemColl) && is_array($orderItemColl)) {
                foreach ($orderItemColl as $orderItem) {
                    $order_id = $orderItem['order_id'];
                    if (isset($orderCollArr[$order_id])) {
                        $order = $orderCollArr[$order_id];
                        $excelArr[] = [
                            $order['order_id'], $order['increment_id'], $order['order_status'], 
                            $order['store'], date('Y-m-d H:i:s', $order['created_at']), date('Y-m-d H:i:s', $order['updated_at']), 
                            $order['base_grand_total'], $order['customer_id'], $order['customer_email'], 
                            $order['customer_firstname'], $order['customer_lastname'], $order['customer_is_guest'] == 1 ? '是' : '否', 
                            $order['coupon_code'], $order['payment_method'], $order['shipping_method'], 
                            $order['base_shipping_total'], $order['customer_telephone'], $order['customer_address_country'], 
                            $order['customer_address_state'], $order['customer_address_city'], $order['customer_address_zip'], 
                            $order['customer_address_street1'], $order['customer_address_street2'], $order['order_remark'], 
                            
                            $orderItem['product_id'], $orderItem['sku'], $orderItem['name'], 
                            $orderItem['custom_option_sku'], $orderItem['weight'], 
                            $orderItem['qty'], $orderItem['base_price'],
                        ];
                    }
                }
            }
        }
        
        \fec\helpers\CExcel::downloadExcelFileByArray($excelArr);
    }
   
}
