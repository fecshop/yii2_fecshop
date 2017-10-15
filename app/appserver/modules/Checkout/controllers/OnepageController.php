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
        $guestOrder = Yii::$app->controller->module->params['guestOrder'];
        if(!$guestOrder && Yii::$app->user->isGuest){
            return [
                'code' => 400,
                'content' => 'you must login your account'
            ];
        } 

        return $this->getBlock()->getLastData();
    }
    
    public function actionSubmitorder(){
        $guestOrder = Yii::$app->controller->module->params['guestOrder'];
        if(!$guestOrder && Yii::$app->user->isGuest){
            return [
                'code' => 400,
                'content' => 'you must login your account'
            ];
        } 
        
        return $this->getBlock('placeorder')->getLastData();
        
        
        
    }

    public function actionChangecountry()
    {
        return $this->getBlock('index')->ajaxChangecountry();
    }

    public function actionAjaxupdateorder()
    {
        $this->getBlock('index')->ajaxUpdateOrderAndShipping();
    }
}
