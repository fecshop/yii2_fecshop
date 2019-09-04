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
        
    }
    /*
    public function actionTest(){
        $src_file = Yii::getAlias('@addons/fecshop_theme_furnilife.zip');
        $dest_dir = Yii::getAlias('@addons/');
        Yii::$service->helper->zipFile->unzip($src_file, $dest_dir, true, false);
    }
    */
    
    public function actionTest(){
        //$remoteUrl = 'http://addons.server.fecmall.com/';
        //$url = $remoteUrl . 'customer/addons/downloada?namespace=fectfurnilife';
        
        //$this->downFile($url,$path)
    }
    
    function downFile($url,$path){
        $arr=parse_url($url);
        $fileName=basename($arr['path']);
        $file=file_get_contents($url);
        file_put_contents($path.$fileName,$file);
    }
    
    
    
    
    
    
    
    
    
    
}
