<?php
namespace fecshop\app\appfront\modules;
use Yii;
use fec\controllers\FecController;
use yii\base\InvalidValueException;
class AppfrontController extends FecController
{
	protected $_currentLayoutFile = 'main.php';
	protected $_themeDir = '@fecshop/app/appfront/theme/base/default';
	
	public function beforeAction($action){
		if(parent::beforeAction($action)){
			Yii::$app->page->theme->fecshopThemeDir = Yii::getAlias($this->_themeDir);
			return true;
		}
	}
	
	 public function render($view, $params = []){
		$viewFile = '';
		$relativeFile = $this->module->id.'/'.$this->id.'/'.$view.'.php';
		$absoluteDir = Yii::$app->page->theme->getThemeDirArr();
		foreach($absoluteDir as $dir){
			if($dir){
				$file = $dir.'/'.$relativeFile;
				if(file_exists($file)){
					$viewFile = $file;
					break;	
				}
			}
		}
		if(!$viewFile){
			$notExistFile = [];
			foreach($absoluteDir as $dir){
				if($dir){
					$file = $dir.'/'.$relativeFile;
					$notExistFile[] = $file;
				}
			}
			throw new InvalidValueException('view file is not exist in'.implode(',',$notExistFile));
		}
		$content = $this->getView()->renderFile($viewFile, $params, $this);
        return $this->renderContent($content);
    }
	
	
	public function findLayoutFile($view){
		$layoutFile = '';
		$relativeFile = '/layouts/'.$this->_currentLayoutFile;
		$absoluteDir = Yii::$app->page->theme->getThemeDirArr();
		foreach($absoluteDir as $dir){
			if($dir){
				$file = $dir.$relativeFile;
				if(file_exists($file)){
					$layoutFile = $file;
					return $layoutFile;
				}
			}
		}
		throw new InvalidValueException('layout file is not exist!');
	}
}
