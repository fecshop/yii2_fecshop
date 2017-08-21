<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\Catalogsearch;

use fecshop\app\appserver\modules\AppserverModule;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Module extends AppserverModule
{
    public $blockNamespace;

    public function init()
    {
        // 以下代码必须指定
        $nameSpace = __NAMESPACE__;
        // web controller
        if (Yii::$app instanceof \yii\web\Application) {
            $this->controllerNamespace = $nameSpace . '\\controllers';
            $this->blockNamespace = $nameSpace . '\\block';
        // console controller
        //} elseif (Yii::$app instanceof \yii\console\Application) {
        //	$this->controllerNamespace 	= 	$nameSpace . '\\console\\controllers';
        //	$this->blockNamespace 	= 	$nameSpace . '\\console\\block';
        }
        //$this->_currentDir			= 	__DIR__ ;
        //$this->_currentNameSpace	=   __NAMESPACE__;

        // 指定默认的man文件
        //$this->layout = "home.php";
        //Yii::$service->page->theme->layoutFile = 'main.php';
        parent::init();
    }
}
