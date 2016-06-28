<?php
namespace fecshop\app\appfront\modules\Cms;
use Yii;
use fecshop\app\appfront\modules\AppfrontModule;
class Module extends AppfrontModule
{
   
    public function init()
    {
		# 以下代码必须指定
		
		# web controller
		if (Yii::$app instanceof \yii\web\Application) {
			$this->controllerNamespace 	= 	__NAMESPACE__ . '\\controllers';
		# console controller
		} elseif (Yii::$app instanceof \yii\console\Application) {
			$this->controllerNamespace 	= 	__NAMESPACE__ . '\\console';
		}
		//$this->_currentDir			= 	__DIR__ ;
		//$this->_currentNameSpace	=   __NAMESPACE__;
		
		# 指定默认的man文件
		//$this->layout = "/main_ajax.php";
		parent::init();  
		
    }
}
