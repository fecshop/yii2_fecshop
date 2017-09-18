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
class AccountController extends AppserverTokenController
{
    
    
    public function actionIndex(){
        if (Yii::$app->user->isGuest) {
            return [
                'code' => 400,
                'content' => 'no login'
            ];
        }
        $identity = Yii::$app->user->identity;

        return [
            'email'            => $identity['email'],
        ];
    
    }
    
    
    
    /**
     * 登录.
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return [
                'code' => 400,
                'content' => 'has login'
            ];
        }
        
        $data = $this->getBlock()->getLastData($param);

        return $this->render($this->action->id, $data);
    }
    
    public function actionLoginpost(){
        if (!Yii::$app->user->isGuest) {
            return [
                'code' => 400,
                'content' => 'has login'
            ];
        }
        $param = Yii::$app->request->post('editForm');
        if (!empty($param) && is_array($param)) {
            $this->getBlock()->login($param);
            if (!Yii::$app->user->isGuest) {
                return Yii::$service->customer->loginSuccessRedirect('customer/account');
            }
        }
        
    }

    
}