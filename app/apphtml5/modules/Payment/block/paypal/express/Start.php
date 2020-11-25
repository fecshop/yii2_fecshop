<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Payment\block\paypal\express;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Start extends \yii\base\BaseObject
{
    public function startPayment()
    {
        $checkStatus = $this->checkStockQty();
        if(!$checkStatus){
            return;
        }
        $methodName_ = 'SetExpressCheckout';
        $nvpStr_ = Yii::$service->payment->paypal->getExpressTokenNvpStr();
        //echo $nvpStr_;exit;
        $checkoutReturn = Yii::$service->payment->paypal->PPHttpPost5($methodName_, $nvpStr_);
        //var_dump($checkoutReturn);
        if (strtolower($checkoutReturn['ACK']) == 'success') {
            $token = $checkoutReturn['TOKEN'];
            # 生成订单，订单中只有id,increment_id,token 三个字段有值。
            if($token){
                if(!Yii::$service->order->generatePPExpressOrder($token)){
                    return false;
                }
                $redirectUrl = Yii::$service->payment->paypal->getExpressCheckoutUrl($token);
                return Yii::$service->url->redirect($redirectUrl);
            }
        } elseif (strtolower($checkoutReturn['ACK']) == 'failure') {
            echo $checkoutReturn['L_LONGMESSAGE0'];
        } else {
            var_dump($checkoutReturn);
        }
    }
    // 检查购物车中产品的库存。此步只是初步检查，在快捷支付完成返回网站的时候，生成订单的时候，还要进一步检查产品库存，
    // 因为在支付的过程中，产品可能被买走。
    public function checkStockQty(){
        $stockCheck = Yii::$service->product->stock->checkItemsQty();
        //var_dump($stockCheck);exit;
        if(!$stockCheck){
            Yii::$service->url->redirectByUrlKey('checkout/cart');
            return false;
        }else{
            if(isset($stockCheck['stockStatus'])){
                if($stockCheck['stockStatus'] == 2){
                    $outStockProducts = $stockCheck['outStockProducts'];
                    if(is_array($outStockProducts) && !empty($outStockProducts)){
                        foreach($outStockProducts as $outStockProduct){
                            $product_name = Yii::$service->store->getStoreAttrVal($outStockProduct['product_name'], 'name');
                            Yii::$service->helper->errors->add('product: [ {product_name} ] is stock out',['product_name' => $product_name]);
                        }
                        Yii::$service->page->message->addByHelperErrors();
                        Yii::$service->url->redirectByUrlKey('checkout/cart');
                        return false;
                    }
                }
            }
        }
        
        return true;
    }
}
