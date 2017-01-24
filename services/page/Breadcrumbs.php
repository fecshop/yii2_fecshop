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
/**
 * Breadcrumbs services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Breadcrumbs extends Service
{
	public 		$homeName = 'Home';
	public 		$ifAddHomeUrl = true;
	public 		$active 	= true;
	protected 	$_items;
	
	public function init(){
		if($this->homeName){
			$items['name'] = $this->homeName;
			if($this->ifAddHomeUrl)
				$items['url'] = CUrl::getHomeUrl();
			$this->addItems($items);
		}
	}
	
	/**
	 * property $items|Array. add $items to $this->_items.
	 * $items format example.
	 * $items = ['name'=>'fashion handbag','url'=>'http://www.xxx.com'];
	 */
	protected function actionAddItems($items){
		if($this->active){
			$this->_items[] = $items;
		}
	}
	
	
	protected function actionGetItems(){
		if($this->active){
			if(is_array($this->_items) && !empty($this->_items)){
				return $this->_items;
			}else{
				return [];
			}
		}
	}
	
	/**
	 * generate Breadcrumbs html ,before generate , you should use addItems function to add breadcrumbs items.
	 */
	/*
	protected function actionGenerateHtml(){
		$arr = [];
		if($this->_items){
			foreach($this->_items as $item){
				$name = isset($item['name']) ? $item['name'] : '';
				$url = isset($item['url']) ? $item['url'] : '';
				if($name){
					if($url){
						$arr[] = '<a href="'.$url.'">'.$name.'</a>';
					}else{
						$arr[] = '<span>'.$name.'</span>';
					}
				}
			}
		}
		return $arr;
		//if(!empty($arr))
		//	return implode($this->intervalSymbol,$arr);
	}
	*/
	
}