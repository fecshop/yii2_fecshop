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
            $cors = Yii::$service->helper->appserver->getYiiAuthCors();
            if (is_array($cors)) {
                foreach ($cors as $c) {
                    header($c);
                }
            }
            $code = Yii::$service->helper->appserver->account_no_login_or_login_token_timeout;
            $result = [ 'code' => $code,'message' => 'token is time out'];
            Yii::$app->response->data = $result;
            Yii::$app->response->send();
            Yii::$app->end();
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
}