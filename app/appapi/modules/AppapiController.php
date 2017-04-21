<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appapi\modules;
use Yii;
use fec\helpers\CConfig;
use yii\web\Response;
use yii\rest\ActiveController;
use yii\base\InvalidValueException;
use yii\filters\auth\CompositeAuth;  
use yii\filters\auth\HttpBasicAuth;  
use yii\filters\auth\HttpBearerAuth;  
use yii\filters\auth\QueryParamAuth; 

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AppapiController extends ActiveController
{
	public $blockNamespace;
	
	public function init()
	{
		parent::init();
		\Yii::$app->user->enableSession = false;
	}
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		/*
		$behaviors['authenticator'] = [
				'class' => CompositeAuth::className(),
				'authMethods' => [
						# 下面是三种验证access_token方式
						//HttpBasicAuth::className(),
						//HttpBearerAuth::className(),
						# 这是GET参数验证的方式
						# http://10.10.10.252:600/user/index/index?access-token=xxxxxxxxxxxxxxxxxxxx
						QueryParamAuth::className(),
				],
		
		];
		*/
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
			'class' => HttpBasicAuth::className(),
		];
    
		#定义返回格式是：JSON
		$behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
		return $behaviors;
	}
	 
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
