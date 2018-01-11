<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class TestController extends AppfrontController
{
    //protected $_registerSuccessRedirectUrlKey = 'customer/account';

    public $enableCsrfValidation = false;

    /**
     * Н╦╗Долл─.
     */
    public function actionIndex()
    {
        echo json_encode([
            'data' => 
            [
                ['terry','1','2','3'],
                ['water','11','22','33'],
                ['xxx','111','222','333'],
            ]
        ]);
        
        
        exit;
    }

}
