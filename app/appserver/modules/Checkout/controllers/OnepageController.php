<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Checkout\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class OnepageController extends AppserverController
{
    public $enableCsrfValidation = false;

    //public function init(){
    //	Yii::$service->page->theme->layoutFile = 'one_step_checkout.php';

    //}

    public function actionIndex()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $guestOrder = Yii::$app->store->get('order', 'guestOrder');
        if($guestOrder != Yii::$app->store->enable && Yii::$app->user->isGuest){
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        } 

        return $this->getBlock()->getLastData();
    }
    
    public function actionSubmitorder(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $guestOrder = Yii::$app->store->get('order', 'guestOrder');
        if($guestOrder != Yii::$app->store->enable && Yii::$app->user->isGuest){
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        } 
        
        return $this->getBlock('placeorder')->getLastData();
    }
    
    
    public function actionWxsubmitorder(){
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $guestOrder = Yii::$app->store->get('order', 'guestOrder');
        if($guestOrder != Yii::$app->store->enable && Yii::$app->user->isGuest){
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        } 
        
        return $this->getBlock('wxplaceorder')->getLastData();
        
    }

    public function actionChangecountry()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        return $this->getBlock('index')->ajaxChangecountry();
    }

    public function actionGetshippingandcartinfo()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        return $this->getBlock('index')->ajaxUpdateOrderAndShipping();
    }
    
    public function actionChangeshippingmethod()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $shipping_method = Yii::$app->request->post('shipping_method');
        //echo $shipping_method;
        //echo 2; exit;
        if (!Yii::$service->cart->quote->updateLoginCartShippingMethod($shipping_method)) {
            $code = Yii::$service->helper->appserver->cart_update_default_shipping_method_fail;
            $data = [
                // 'errors' => Yii::$service->helper->errors->get(),
            ];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        }
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
    // 得到customer address
    public function actionGetaddresslist()
    {
        //$identity = Yii::$app->user->identity;
        $addressList = Yii::$service->customer->address->currentAddressList();
        
        $code = Yii::$service->helper->appserver->status_success;
        $data = [
            'addressList' => $addressList,
        ];
        $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
        
        return $responseData;
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
