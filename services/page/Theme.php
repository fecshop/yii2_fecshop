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
	public $currentThemeDir;
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
	
	protected $_themeDirArr;
	
	
	public function getThemeDirArr(){
		if(!$this->_themeDirArr){
			$arr = [];
			$arr[] = Yii::getAlias($this->currentThemeDir);
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
	
	
	
	
	
	
	
	
	
	
}