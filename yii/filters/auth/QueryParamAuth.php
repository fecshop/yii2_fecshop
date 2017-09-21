<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\yii\filters\auth;

use Yii; 
use yii\filters\RateLimiter;  
use yii\filters\auth\QueryParamAuth as YiiQueryParamAuth;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class QueryParamAuth extends YiiQueryParamAuth
{
    
    /**
     * 重写该方法。该方法从request header中读取access-token。
     */
    public function authenticate($user, $request, $response)
    {   
        $identity = Yii::$service->customer->loginByAccessToken(get_class($this));
        if($identity){
            return $identity;
        }else{
            $fecshop_uuid = Yii::$service->session->fecshop_uuid;
            $cors_allow_headers = [$fecshop_uuid,'fecshop-lang','fecshop-currency','access-token'];
            
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, ".implode(', ',$cors_allow_headers));
            header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');
            
            $result = ['status' => 'access token error', 'code' => 400,'message' => 'token is time out'];
            Yii::$app->response->data = $result;
            Yii::$app->response->send();
            Yii::$app->end();
            
            
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
}