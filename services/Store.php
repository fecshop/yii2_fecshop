<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use yii\base\BootstrapInterface;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Store extends Service implements BootstrapInterface
{
	/**
	 * init by config file.
	 * all stores config . include : domain,language,theme,themePackage
	 */
	public $stores; 
	/**
	 * init by config file.
	 * all store Language.
	 */	
	public $languages;
	
	/**
	 * current store language
	 */
	public $currentLanguage = 'en';
	
	/**
	 * current store theme package
	 */
	public $currentThemePackage = 'default';
	/**
	 * current store theme
	 */
	public $currentTheme = 'default';
	/**
	 * current store name , this property will  init value with domain.
	 */
	public $currentStore;
	
	
	/**
	 *	Bootstrap:init website,  class property $currentLanguage ,$currentTheme and $currentStore.
	 *  if you not config this ,default class property will be set.
	 *  if current domain is not config , InvalidValueException will be throw. 
	 *	class property $currentStore will be set value $domain.
	 */
	public function bootstrap($app){
		$host = explode('://' ,$app->getHomeUrl());
		$stores = $this->stores;
		$init_compelte = 0;
		if(is_array($stores) && !empty($stores)){
			foreach($stores as $domain => $lang){
				if($host[1] == $domain){
					Yii::$app->store->currentStore = $domain;
					if(isset($lang['language']) && !empty($lang['language'])){
						Yii::$app->store->currentLanguage = $lang['language'];
					}
					if(isset($lang['theme']) && !empty($lang['theme'])){
						Yii::$app->store->currentTheme = $lang['theme'];
					}
					if(isset($lang['themePackage']) && !empty($lang['themePackage'])){
						Yii::$app->store->currentThemePackage = $lang['themePackage'];
					}
					/**
					 * init store currency.
					 */
					if(isset($lang['currency']) && !empty($lang['currency'])){
						$currency = $lang['currency'];
					}else{
						$currency = '';
					}
					
					Yii::$app->page->currency->initCurrency($currency);
					/**
					 * current domian is config is store config.
					 */
					$init_compelte = 1;
				}
			}
		}
		if(!$init_compelte){
			throw new InvalidValueException('this domain is not config in store component');
		}
		
    }
	
	/**
	 * if a object or array  attribute is a store attribute, you can get current 
	 * language value by this function.
	 */
	public function getLangVal($attr,$attrName){
		return $attr[$this->currentLanguage."_".$attrName];
	}
	
	
	public function getAllLanguage(){
		
		
	}
	
	
}