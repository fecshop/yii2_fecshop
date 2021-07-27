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
use fecshop\queue\job\SendEmailJob;
use fecshop\elasticsearch\models\elasticSearch\Product;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class TestController extends AppfrontController
{
    //protected $_registerSuccessRedirectUrlKey = 'customer/account';

    public $enableCsrfValidation = false;
    
    public function actionIndex()
    {
        //$filePath = Yii::getAlias('@fecshop/app/appfront/languages/zh-CN/appfront.php');

        //$token = Yii::$service->tongtool->getProducts();
        
        //echo $token;
    }
    
    /*
    public function actionIndex()
    {
        //$filePath = Yii::getAlias('@fecshop/app/appfront/languages/zh-CN/appfront.php');

        $config = $this->getOrigin();
        //var_dump($config);
        foreach ($config as $k=>$v) {
            echo $v.'<br/>';
        }
        exit;
    }

    public function actionIndex2()
    {
        //$filePath = Yii::getAlias('@fecshop/app/appfront/languages/zh-CN/appfront.php');

        $config = $this->getOrigin();
        //var_dump($config);
        foreach ($config as $k=>$v) {
            echo $k.'<br/>';
        }
        exit;
    }

    public function getOrigin()
    {
        $filePath = Yii::getAlias('@fecshop/app/appserver/languages/zh-CN/appserver.php');

        $config = require('/www/web/demo/fecwbbc/addons/fecmall/fecwbbc/app/appfront/languages/zh-CN/appfront.php');

        return $config;
    }

    public function actionGetarr()
    {

        $arr1 = $this->getOrigin();
        $arr2 = $this->getArr1();
        echo count($arr1).'<br/>';
        echo count($arr2).'<br/>';
        //var_dump($arr2);

        $i = 0;
        foreach ($arr1 as $k=>$v) {
            $arr1[$k] = $arr2[$i];
            $i++;
        }
        $this->echoArr($arr1);exit;
        // var_dump($arr1);exit;
    }

    public function echoArr($arr)
    {
        echo ' return [<br>';
        foreach ($arr as $k=>$v) {
            if (strstr($k, "'")) {
                $k = str_replace("'", "\'", $k);
            }
            if (strstr($v, "'")) {
                $v = str_replace("'", "\'", $v);
            }
            echo "&nbsp;&nbsp;&nbsp;&nbsp;'".$k."' => '".$v."',<br>";
        }
        echo '
            <br>
        ];
        ';
    }

    protected function getArr1()
    {
        $str = "
        ";
        $arr = explode(PHP_EOL, $str);
        $arr1 = [];
        foreach ($arr as $k => $v) {
            $k1 = trim($k);
            $v1 = trim($v);

            if (!$k1 || !$v1) {

                continue;
            }
            $arr1[] = $v1;
        }

        return $arr1;
    }

    //public function actionTest(){
    //    $src_file = Yii::getAlias('@addons/fecshop_theme_furnilife.zip');
    //    $dest_dir = Yii::getAlias('@addons/');
    //    Yii::$service->helper->zipFile->unzip($src_file, $dest_dir, true, false);
    //}


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



    */






}
