<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mysqldb\adminUser;

use Yii; 
use fecshop\models\mysqldb\AdminUser;
use yii\base\NotSupportedException;  
use yii\behaviors\TimestampBehavior;   
use yii\web\IdentityInterface;  
use yii\filters\RateLimitInterface;  

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AdminUserAccessToken extends AdminUser implements IdentityInterface ,RateLimitInterface  
{
    
     # 速度控制  6秒内访问3次，注意，数组的第一个不要设置1，设置1会出问题，一定要  
    #大于2，譬如下面  6秒内只能访问三次  
    # 文档标注：返回允许的请求的最大数目及时间，例如，[100, 600] 表示在600秒内最多100次的API调用。  
    public  function getRateLimit($request, $action){  
        $rateLimit = Yii::$app->params['rateLimit'];
        if(is_array($rateLimit['limit']) && !empty($rateLimit['limit'])){
            return $rateLimit['limit']; 
        }else{
            return [120, 60]; 
        }
         
    }  
    # 文档标注： 返回剩余的允许的请求和相应的UNIX时间戳数 当最后一次速率限制检查时。  
    public  function loadAllowance($request, $action){  
        //return [1,strtotime(date("Y-m-d H:i:s"))];  
        //echo $this->allowance;exit;  
         return [$this->allowance, $this->allowance_updated_at];  
    }  
    # allowance 对应user 表的allowance字段  int类型  
    # allowance_updated_at 对应user allowance_updated_at  int类型  
    # 文档标注：保存允许剩余的请求数和当前的UNIX时间戳。  
    public  function saveAllowance($request, $action, $allowance, $timestamp){  
        $this->allowance = $allowance;  
        $this->allowance_updated_at = $timestamp;  
        $this->save();  
    }  
    
     /**  
     * @inheritdoc  
     */  
    public function behaviors()  
    {  
        return [  
            TimestampBehavior::className(),  
        ];  
    }  
    
    
    
}

