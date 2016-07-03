<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\page;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\services\ChildService;
/**
 * Breadcrumbs services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Widget extends ChildService
{
	public $defaultObMethod = 'getLastData';
	public $widgetConfig;
	/*
	[
		
		'widgetConfig' =>[
			'menu' =>[
				# 必填
				'class' => 'fec\block\TestMenu',
				'view'  => '@fec/views/testmenu/index.php',
				OR
				'view'  => 'cms/home/index.php',
				
				# 下面为选填
				'method'=> 'getLastData',
				'terry1'=> 'My1',
				'terry2'=> 'My2',
			],
		]
	]
	*/
	public function render($configKey){
		$config = '';
		if(is_array($configKey)){
			$config = $configKey;
		}else{
			if(isset($this->widgetConfig[$configKey])){
				$config = $this->widgetConfig[$configKey];
			}else{
				throw new InvalidValueException(" config key: '$configKey', can not find in  ".'Yii::$app->page->widget->widgetConfig'.", you must config it before use it.");
			}
		}
		return $this->renderContent($config);
	}
	
	
	protected function renderContent($config){
		if( !isset($config['view']) || empty($config['view'])
		){
			throw new InvalidConfigException('view and class must exist in array config!');
		}
		$view = $config['view'];
		unset($config['view']);
		$viewFile = $this->getViewFile($view);
		if( !isset($config['class']) || empty($config['class']))
			return Yii::$app->view->render($viewFile, []);
		
		if(isset($config['method']) && !empty($config['method'])){
			$method = $config['method'];
			unset($config['method']);
		}else{
			$method = $this->defaultObMethod;
		}
		$ob = Yii::createObject($config);
		$params = $ob->$method();
		return Yii::$app->view->renderFile($viewFile, $params);
		
	}
	
	
	/**
	 * find theme file by mutil theme ,if not find view file  and $throwError=true, it will throw InvalidValueException.
	 */ 
	protected function getViewFile($view,$throwError=true){
		$view = trim($view);
		if(substr($view,0,1) == '@'){
			return Yii::getAlias($view);
		}
		$absoluteDir = Yii::$app->page->theme->getThemeDirArr();
		
		foreach($absoluteDir as $dir){
			if($dir){
				$file = $dir.'/'.$view;
				if(file_exists($file)){
					
					return $file;	
				}
			}
		}
		/* not find view file */
		if($throwError){
			$notExistFile = [];
			foreach($absoluteDir as $dir){
				if($dir){
					$file = $dir.'/'.$view;
					$notExistFile[] = $file;
				}
			}
			throw new InvalidValueException('view file is not exist in'.implode(',',$notExistFile));
		}else{
			return false;
		}
	}
	
	
}