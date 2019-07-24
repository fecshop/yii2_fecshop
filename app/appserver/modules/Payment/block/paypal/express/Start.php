<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Payment\block\paypal\express;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Start
{
    
    public $_errors;
    
    public function startPayment()
    {
        $checkStatus = $this->checkStockQty();
        if(!$checkStatus){
            $code = Yii::$service->helper->appserver->order_generate_product_stock_out;
            $data = [
                'error' => $this->_errors,
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        $methodName_ = 'SetExpressCheckout';
        $return_url = Yii::$app->request->post('return_url');
        $cancel_url = Yii::$app->request->post('cancel_url');
        $nvpStr_ = Yii::$service->payment->paypal->getExpressTokenNvpStr('Login',$return_url,$cancel_url);
        //echo $nvpStr_;exit;
        $checkoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);
        //var_dump($checkoutReturn);
        if (strtolower($checkoutReturn['ACK']) == 'success') {
            $token = $checkoutReturn['TOKEN'];
            # 生成订单，订单中只有id,increment_id,token 三个字段有值。
            if($token){
                if(!Yii::$service->order->generatePPExpressOrder($token)){
                    $code = Yii::$service->helper->appserver->order_generate_fail;
                    $data = [];
                    $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                    
                    return $responseData;
                }
                $redirectUrl = Yii::$service->payment->paypal->getExpressCheckoutUrl($token);
                $createdOrder = Yii::$service->order->createdOrder;
                $code = Yii::$service->helper->appserver->status_success;
                $data = [
                    'redirectUrl' => $redirectUrl,
                    'increment_id' => $createdOrder['increment_id'],
                ];
                $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
                
                return $responseData;
            }
        } else {
            $code = Yii::$service->helper->appserver->order_paypal_express_get_token_fail;
            $data = [
                 'error' => isset($checkoutReturn['L_LONGMESSAGE0']) ? $checkoutReturn['L_LONGMESSAGE0'] : '',
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
    }

    // 检查购物车中产品的库存。此步只是初步检查，在快捷支付完成返回网站的时候，生成订单的时候，还要进一步检查产品库存，
    // 因为在支付的过程中，产品可能被买走。
    public function checkStockQty(){
        $stockCheck = Yii::$service->product->stock->checkItemsQty();
        
        //var_dump($stockCheck);exit;
        if(!$stockCheck){
            //Yii::$service->url->redirectByUrlKey('checkout/cart');
            $this->_errors .= 'cart products is empty';
            return false;
        }else{
            if(isset($stockCheck['stockStatus'])){
                if($stockCheck['stockStatus'] == 2){
                    $outStockProducts = $stockCheck['outStockProducts'];
                    if(is_array($outStockProducts) && !empty($outStockProducts)){
                        foreach($outStockProducts as $outStockProduct){
                            $product_name = Yii::$service->store->getStoreAttrVal($outStockProduct['product_name'], 'name');
                            $this->_errors .= Yii::$service->page->translate->__('product: [ {product_name} ] is stock out',['product_name' => $product_name]);
                        }
                        
                        return false;
                    }
                }
            }
        }
        
        return true;
    }
}
