<?php
namespace fecshop\app\appfront\modules;
use Yii;
use fec\helpers\CConfig;
use fec\controllers\FecController;
use yii\base\InvalidValueException;
class AppfrontController extends FecController
{
	
	/**
	 * init theme component property : $fecshopThemeDir and $layoutFile
	 * $fecshopThemeDir is appfront base theme directory.
	 * layoutFile is current layout relative path.
	 */
	public function init(){
		if(!Yii::$app->page->theme->fecshopThemeDir){
			Yii::$app->page->theme->fecshopThemeDir = Yii::getAlias(CConfig::param('appfrontBaseTheme'));
		}
		if(!Yii::$app->page->theme->layoutFile){
			Yii::$app->page->theme->layoutFile = CConfig::param('appfrontBaseLayoutName');
		}
	}
	
	/**
	 * @property $view|String , (only) view file name ,by this module id, this controller id , generate view relative path.
	 * @property $params|Array,
	 * 1.get exist view file from mutil theme by theme protity.
	 * 2.get content by yii view compontent  function renderFile()  , 
	 */
	public function render($view, $params = []){
		$viewFile = Yii::$app->page->theme->getViewFile($view);
		$content = Yii::$app->view->renderFile($viewFile, $params, $this);
        return $this->renderContent($content);
    }
	
	/**
	 * Get current layoutFile absolute path from mutil theme dir by protity
	 */
	public function findLayoutFile($view){
		$layoutFile = '';
		$relativeFile = 'layouts/'.Yii::$app->page->theme->layoutFile;
		$absoluteDir = Yii::$app->page->theme->getThemeDirArr();
		foreach($absoluteDir as $dir){
			if($dir){
				$file = $dir.'/'.$relativeFile;
				if(file_exists($file)){
					$layoutFile = $file;
					return $layoutFile;
				}
			}
		}
		throw new InvalidValueException('layout file is not exist!');
	}
}
