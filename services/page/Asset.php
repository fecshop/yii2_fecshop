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
use fecshop\services\Service;
use yii\web\AssetBundle;

use yii\base\Object;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * page asset services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 extends AssetBundle
 */
class Asset extends Service
{
	public $cssOptions;
	public $jsOptions; 
	
	public $jsVersion 	= 1;   //?v=115
	public $cssVersion	= 1;   //?v=115
	# js 和 css 如果想用独立的域名，可以在这里设置相应的域名。
	public $jsCssDomain;
	/**
	 * 在模板路径下的相对文件夹。
	 * 譬如模板路径为@fecshop/app/theme/base/front
	 * 那么js,css路径默认为@fecshop/app/theme/base/front/assets
	 */
	public $defaultDir = 'assets';
    /**
	 * 文件路径默认放到模板路径下面的assets里面
	 */
	protected function actionRegister($view){
		$assetArr = [];
		$themeDir = Yii::$service->page->theme->getThemeDirArr();
		if( is_array($themeDir) && !empty($themeDir)){
			if( is_array($this->jsOptions) && !empty($this->jsOptions)){
				foreach($this->jsOptions as $jsOption){
					if( isset($jsOption['js']) && is_array($jsOption['js']) && !empty($jsOption['js'])){
			
						foreach($jsOption['js'] as $jsPath){
							foreach($themeDir as $dir){
								$dir = $dir.'/'.$this->defaultDir.'/';
								$jsAbsoluteDir = $dir.$jsPath;
								if(file_exists($jsAbsoluteDir)){
										$assetArr[$dir]['jsOptions'][] = [
										'js' 		=>  $jsPath,
										'options' 	=>  $this->initOptions($jsOption['options']),
									];
									break;
								}
							}
						}
					}
				}	
			}
			
			if( is_array($this->cssOptions) && !empty($this->cssOptions)){
				foreach($this->cssOptions as $cssOption){
					if( isset($cssOption['css']) && is_array($cssOption['css']) && !empty($cssOption['css'])){
						foreach($cssOption['css'] as $cssPath){		
							foreach($themeDir as $dir){
								$dir = $dir.'/'.$this->defaultDir.'/';
								$cssAbsoluteDir = $dir.$cssPath;
								if(file_exists($cssAbsoluteDir)){
									$assetArr[$dir]['cssOptions'][] = [
										'css' 		=>  $cssPath,
										'options' 	=>  $this->initOptions($cssOption['options']),
									];
									break;
								}
							}
						}
					}
				}	
			}
		}
		if(!empty($assetArr)){
			$jsV 	= '?v='.$this->jsVersion;
			$cssV 	= '?v='.$this->cssVersion;
			foreach($assetArr as $fileDir=>$as){
				$cssConfig = $as['cssOptions'];
				$jsConfig = $as['jsOptions'];
				$publishDir = $view->assetManager->publish($fileDir);
				if(!empty($jsConfig) && is_array($jsConfig)){
					foreach($jsConfig as $c){
						$view->registerJsFile($this->jsCssDomain.$publishDir[1].'/'.$c['js'].$jsV,$c['options']);
					}
				}
				if(!empty($cssConfig) && is_array($cssConfig)){
					foreach($cssConfig as $c){
						$view->registerCssFile($this->jsCssDomain.$publishDir[1].'/'.$c['css'].$cssV,$c['options']);
					}
				}
			}
		}
	}
	
	
	protected function initOptions($options){
		if(isset($options['position'])){
			if($options['position'] == 'POS_HEAD'){
				$options['position'] =  \yii\web\View::POS_HEAD;
			}else if($options['position'] == 'POS_END'){
				$options['position'] =  \yii\web\View::POS_END;
			}
		}
		return $options;
	}
}












