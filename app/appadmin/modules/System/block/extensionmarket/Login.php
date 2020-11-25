<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\System\block\extensionmarket;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;

/**
 * block cms\staticblock.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Login extends \yii\base\BaseObject
{

    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        // parent::init();
    }

    public function getLastData()
    {

        
        return [
        ];
    }
    
    public function login($param)
    {
        // 进行远程登陆
        if (!Yii::$service->extension->remoteService->login($param)) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => 'login fail',
            ]);
            exit;
        }
        
        echo  json_encode([
            'statusCode' => '200',
            'message'    => Yii::$service->page->translate->__('Login Success'),
        ]);
        exit;        
        
        
    }

}
