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
class Theme extends ChildService
{
	/**
	 * user current theme dir. Highest priority
	 */
	public $localThemeDir;
	/**
	 * $thirdThemeDir | Array
	 * user current theme dir.Second priority.
	 * array[0] priority is higher than array[1],
	 */
	public $thirdThemeDir;
	/**
	 * fecshop theme dir. lower priority
	 */
	public $fecshopThemeDir ;
	/**
	 * current layout file path.
	 */
	public $layoutFile;
	/**
	 * array that contains mutil theme dir.
	 */
	protected $_themeDirArr;
	
	
	public function getThemeDirArr(){
		if(!$this->_themeDirArr){
			$arr = [];
			$arr[] = Yii::getAlias($this->localThemeDir);
			$thirdThemeDirArr = $this->thirdThemeDir;
			if(!empty($thirdThemeDirArr) && is_array($thirdThemeDirArr)){
				foreach($thirdThemeDirArr as $theme){
					$arr[] = Yii::getAlias($theme);
				}
			}
			$arr[] = Yii::getAlias($this->fecshopThemeDir);
			$this->_themeDirArr = $arr;
		}
		return $this->_themeDirArr;
	}
	
	/**
	 * find theme file by mutil theme ,if not find view file  and $throwError=true, it will throw InvalidValueException.
	 */ 
	public function getViewFile($view,$throwError=true){
		$view = trim($view);
		if(substr($view,0,1) == '@'){
			return Yii::getAlias($view);
		}
		$relativeFile = '';
		$module = Yii::$app->controller->module;
		if($module && $module->id){
			$relativeFile = $module->id.'/';
		}
		$relativeFile .= Yii::$app->controller->id.'/'.$view.'.php';
		$absoluteDir = Yii::$app->page->theme->getThemeDirArr();
		foreach($absoluteDir as $dir){
			if($dir){
				$file = $dir.'/'.$relativeFile;
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
					$file = $dir.'/'.$relativeFile;
					$notExistFile[] = $file;
				}
			}
			throw new InvalidValueException('view file is not exist in'.implode(',',$notExistFile));
		}else{
			return false;
		}
	}
	
	
	
	
	
	
	
	
	
}