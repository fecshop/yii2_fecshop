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
use fecshop\queue\job\SendEmailJob;
use fecshop\elasticsearch\models\elasticSearch\Product;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class TestController extends AppfrontController
{
    //protected $_registerSuccessRedirectUrlKey = 'customer/account';

    public $enableCsrfValidation = false;

    /**
     * 
     */
    public function actionIndex()
    {
        Product::initLang('en');
        //Product::updateMapping();
        //Product::initLang('zh');
        //Product::updateMapping();
        /*
        $one = Product::findOne(5);
        var_dump($one->attributes);
        $one->_id = 'yyyy';
        $one->save(); 
        var_dump($one->getPrimaryKey());exit;
        */
        $p = new Product;
        $p->_id = 5;
        $p->sku = 'xxxx';
        $p->save(); 
        
        $dd = Product::find()->all();
        var_dump($dd);
        
    }

    public function actionTest(){
        echo 12;exit;
    }

}
