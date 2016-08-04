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
use fecshop\models\mongodb\Category;
/**
 * Menu services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Menu extends Service
{
	/**
	 * whether display HOME in the menu.
	 */
	public $displayHome;
	/**
	 * custom menu that display in the front of product category.
	 */
	public $frontCustomMenu;
	/**
	 * custom menu that display in the behind of product category.
	 */
	public $behindCustomMenu;
	
	protected $_homeUrl;
	
	
	/**
	 * return menu array data, contains: 
	 * home,frontCustomMenu,productCategory,behindCustomMenu.
	 * example:
		[
			[
				'name' 		=> 'my custom menu',
				'urlPath'	=> '/my-custom-menu.html',
				'childMenu' => [
						'name' 		=> 'my custom menu',
						'urlPath'	=> '/my-custom-menu.html',
					],
			],
			[
				...,
			]
		]
	 */
	protected function actionGetMenuData(){
		$this->_homeUrl = CUrl::getHomeUrl();
		$arr = [];
		if($displayHome = $this->displayHome){
			$enable =  isset($displayHome['enable']) ?$displayHome['enable'] : '',
			$display =  isset($displayHome['display']) ?$displayHome['display'] : '',
			if($enable && $display){
				$arr[] = [
					'name' => $display,
					'urlPath'	=> '',
				];
			}
		}
		if($this->frontCustomMenu){
			$arr[] = $this->frontCustomMenu,
		}
		$arr[] = $this->getProductCategoryMenu();
		if($this->behindCustomMenu){
			$arr[] = $this->behindCustomMenu,
		}
		return $arr;
	} 
	
	/**
	 * get product category array as menu.
	 */
	protected function getProductCategoryMenu(){
		return Yii::$service->category->menu->getCategoryMenuArr();
	}
	
}




