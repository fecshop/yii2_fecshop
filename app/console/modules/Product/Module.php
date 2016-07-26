<?php
namespace fecshop\app\console\modules\Product;
use Yii;
use fecshop\app\console\modules\ConsoleModule;
class Module extends ConsoleModule
{
   public $blockNamespace;
    public function init()
    {
		# 以下代码必须指定
		$nameSpace = __NAMESPACE__;
		$this->controllerNamespace 	= 	$nameSpace . '\\controllers';
		$this->blockNamespace 	= 	$nameSpace . '\\block';
		parent::init();  
		
    }
}
