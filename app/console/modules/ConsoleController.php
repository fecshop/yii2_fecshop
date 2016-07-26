<?php
namespace fecshop\app\console\modules;
use Yii;
use fec\helpers\CConfig;
use fec\controllers\FecController;
use yii\base\InvalidValueException;
# use fecshop\app\appfront\modules\AppfrontController;
class ConsoleController extends FecController
{
	public $blockNamespace;
	
	/**
	 * get current block 
	 * you can change $this->blockNamespace
	 */
	public function getBlock($blockName=''){
		if(!$blockName){
			$blockName = $this->action->id;
		}
		if(!$this->blockNamespace){
			$this->blockNamespace = Yii::$app->controller->module->blockNamespace;
		}
		if(!$this->blockNamespace){
			throw new \yii\web\HttpException(406,'blockNamespace is empty , you should config it in module->blockNamespace or controller blockNamespace ');
		}
		
		$relativeFile = '\\'.$this->blockNamespace;
		$relativeFile .= '\\'.$this->id.'\\'.ucfirst($blockName);
		return new $relativeFile;
	}
	
	
}
