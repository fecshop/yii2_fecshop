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
use fec\helpers\CCache;
use fecshop\services\Service;
use fecshop\interfaces\block\BlockCache;
/**
 * widget services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Widget extends Service
{
	public $defaultObMethod = 'getLastData';
	public $widgetConfig;
	/*
	@property configKey   String or Array
	
	Array example:
	[
		# class 选填
		'class' => 'fec\block\TestMenu',
		# view 为 必填 ， view可以用两种方式
		#  view 1 使用绝对地址的方式
		'view'  => '@fec/views/testmenu/index.php',
			OR
		#  view 2 使用相对地址，通过当前模板进行查找
		'view'  => 'cms/home/index.php',
		
		# 下面为选填
		'method'=> 'getLastData',
		'terry1'=> 'My1',
		'terry2'=> 'My2',
	]
	*/
	protected function actionRender($configKey,$parentThis=''){
		$config = '';
		if(is_array($configKey)){
			$config = $configKey;
			$configKey = '';
		}else{
			if(isset($this->widgetConfig[$configKey])){
				$config = $this->widgetConfig[$configKey];
			}else{
				throw new InvalidValueException(" config key: '$configKey', can not find in  ".'Yii::$service->page->widget->widgetConfig'.", you must config it before use it.");
			}
		}
		
		return $this->renderContent($configKey,$config,$parentThis);
	}
	
	protected function actionRenderContentHtml($configKey,$config,$parentThis=''){
		if( !isset($config['view']) || empty($config['view'])
		){
			throw new InvalidConfigException('view and class must exist in array config!');
		}
		$params = [];
		$view = $config['view'];
		unset($config['view']);
		$viewFile = $this->getViewFile($view);
		if( !isset($config['class']) || empty($config['class'])){
			if($parentThis){
				$params['parentThis'] = $parentThis;
			}
			return Yii::$app->view->renderFile($viewFile, $params);
		}
		if(isset($config['method']) && !empty($config['method'])){
			$method = $config['method'];
			unset($config['method']);
		}else{
			$method = $this->defaultObMethod;
		}
		$ob = Yii::createObject($config);
		$params = $ob->$method();
		if($parentThis){
			$params['parentThis'] = $parentThis;
		}
		return Yii::$app->view->renderFile($viewFile, $params);
	}
	
	protected function actionRenderContent($configKey,$config,$parentThis=''){
		if(isset($config['cache']['enable']) && $config['cache']['enable']){
			if(!isset($config['class']) || !$config['class']){
				throw new InvalidConfigException('in widget ['.$configKey.'],you enable cache ,you must config widget class .');
			}else if($ob = new $config['class']){
				if($ob instanceof BlockCache){
					$cacheKey = $ob->getCacheKey();
					if(!($content = CCache::get($cacheKey))){
						$cache = $config['cache'];
						$timeout = isset($cache['timeout']) ? $cache['timeout'] : 0;
						unset($config['cache']);
						$content = $this->renderContentHtml($configKey,$config,$parentThis);
						CCache::set($cacheKey,$content,$timeout);
					}
					return $content;
				}else{
					throw new InvalidConfigException($config['class'].' must implete fecshop\interfaces\block\BlockCache  when you use block cache .');
				}
			}
		}
		$content = $this->renderContentHtml($configKey,$config,$parentThis);
		return $content;
		
	}
	
	
	
	
	/**
	 * find theme file by mutil theme ,if not find view file  and $throwError=true, it will throw InvalidValueException.
	 */ 
	protected function getViewFile($view,$throwError=true){
		$view = trim($view);
		if(substr($view,0,1) == '@'){
			return Yii::getAlias($view);
		}
		$absoluteDir = Yii::$service->page->theme->getThemeDirArr();
		
		foreach($absoluteDir as $dir){
			if($dir){
				$file = $dir.'/'.$view;
				//echo $file."<br/>";
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