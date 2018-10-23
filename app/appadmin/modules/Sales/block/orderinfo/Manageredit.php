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
        $customerOrderArr = [ 1 => '是',2 => '否',];
        $order_info['customer_is_guest_options'] = $this->getOptions($customerOrderArr,$order_info['customer_is_guest']);
        // 省
        $order_info['customer_address_country_options'] = Yii::$service->helper->country->getCountryOptionsHtml($order_info['customer_address_country']);
        // 市
        $order_info['customer_address_state_options'] = Yii::$service->helper->country->getStateOptionsByContryCode($order_info['customer_address_country'],$order_info['customer_address_state']);
        // 支付方式label
        
        // 货运方式label
        $order_info['shipping_method_label'] = Yii::$service->shipping->getShippingLabelByMethod($order_info['shipping_method']);
        $order_info['payment_method_label'] = Yii::$service->payment->getPaymentLabelByMethod($order_info['payment_method']);
        
        return $order_info;
    }
    
    
    
    
    public function getOptions($orderStatusArr,$order_status){
        $str = '';
        if(is_array($orderStatusArr)){
            foreach($orderStatusArr as $k => $v){
                if($order_status == $k ){
                    $str .= '<option selected="selected" value="'.$k.'">'.$v.'</option>';
                }else{
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
        if(is_array($editForm) && $orderModel['order_id']){
            foreach($editForm as $k => $v){
                if ($orderModel->hasAttribute($k)) {
                    $orderModel[$k] = $v;
                }
            } 
            $orderModel->save();
        }
        echo  json_encode([
            'statusCode'=>'200',
            'message'=>'save success',
        ]);
        exit;
    }
    
    public function exportExcel(){
        $order_ids = Yii::$app->request->get('order_ids');
        $order_arr = explode(',', $order_ids);
        $excelArr[] = [
            '订单Id(order_id)', '订单编号(increment_id)', '订单状态(order_status)',
            'Store(store)', '创建时间(created_at)', '更新时间(updated_at)',
            '订单总金额(base_grand_total)', '用户Id(customer_id)', '订单Email(customer_email)',
            '订单用户-名(customer_firstname)', '订单用户-姓(customer_lastname)', '是否游客(customer_is_guest)',
            '优惠券(coupon_code)', '支付方式(payment_method)', '货运方式(shipping_method)',
            '订单运费(base_shipping_total)', '订单电话(customer_telephone)', '订单国家(customer_address_country)',
            '订单省/市(customer_address_state)', '订单城市(customer_address_city)', '订单邮编(customer_address_zip)',
            '订单街道地址1(customer_address_street1)', '订单街道地址2(customer_address_street2)', '订单备注(order_remark)',
            
            '产品Id(product_id)', '产品Sku(sku)', '产品名称(name)',
            '产品自定义选项Sku(custom_option_sku)',
            '产品单重(weight)', '产品数量(qty)', '产品单价(base_price)',
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
                            $orderItem['qty'], $orderItem['base_price']
                            
                        ];
                        
                    }
                }
            }
            
        }
        
        
        
        \fec\helpers\CExcel::downloadExcelFileByArray($excelArr);
        
    }
    
    
        
    
    
    
}
