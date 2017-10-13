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
    
    public function startExpress()
    {
        $checkStatus = $this->checkStockQty();
        if(!$checkStatus){
            return [
                'code'      => 401,
                'content'   => $this->_errors,
            ];
        }
        $methodName_ = 'SetExpressCheckout';
        $nvpStr_ = Yii::$service->payment->paypal->getExpressTokenNvpStr();
        //echo $nvpStr_;exit;
        $SetExpressCheckoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);
        //var_dump($SetExpressCheckoutReturn);
        if (strtolower($SetExpressCheckoutReturn['ACK']) == 'success') {
            $token = $SetExpressCheckoutReturn['TOKEN'];
            # 生成订单，订单中只有id,increment_id,token 三个字段有值。
            if($token){
                if(!Yii::$service->order->generatePPExpressOrder($token)){
                    return [
                        'code' => 402,
                        'content' => 'generate order fail',
                    ];
                }
                $redirectUrl = Yii::$service->payment->paypal->getSetExpressCheckoutUrl($token);
                return [
                    'code' => 200,
                    'content' => $redirectUrl,
                ];
            }
        } elseif (strtolower($SetExpressCheckoutReturn['ACK']) == 'failure') {
            return [
                'code' => 403,
                'content' => $SetExpressCheckoutReturn['L_LONGMESSAGE0'],
            ];
            echo $SetExpressCheckoutReturn['L_LONGMESSAGE0'];
        } else {
            return [
                'code' => 403,
                'content' => $SetExpressCheckoutReturn,
            ];
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
                            $this->_errors .= 'product: ['.$product_name.'] is stock out.';
                        }
                        
                        return false;
                    }
                }
            }
        }
        
        return true;
    }
}
