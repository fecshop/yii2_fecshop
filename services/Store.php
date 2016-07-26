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
	 * current store language,for example: en_US,fr_FR 
	 */
	public $currentLang;
	/**
	 * current store language name
	 */
	public $currentLangName;
	/**
	 * current store theme package
	 */
	//public $currentThemePackage = 'default';
	/**
	 * current store theme
	 */
	//public $currentTheme = 'default';
	/**
	 * current store code , this property will  init value with store code.
	 */
	public $currentStore;
	
	/**
	 * current language code example : fr  es cn ru.
	 */
	protected $currentLangCode;
	/**
	 *	Bootstrap:init website,  class property $currentLang ,$currentTheme and $currentStore.
	 *  if you not config this ,default class property will be set.
	 *  if current store_code is not config , InvalidValueException will be throw. 
	 *	class property $currentStore will be set value $store_code.
	 */
	public function bootstrap($app){
		
		//Yii::$service = new \fecshop\services\Service;
		//Yii::createObject($service);
		$host = explode('://' ,$app->getHomeUrl());
		$stores = $this->stores;
		
		$init_compelte = 0;
		if(is_array($stores) && !empty($stores)){
			foreach($stores as $store_code => $store){
				if($host[1] == $store_code){
					Yii::$service->store->currentStore = $store_code;
					if(isset($store['language']) && !empty($store['language'])){
						Yii::$service->store->currentLang = $store['language'];
						Yii::$service->store->currentLangCode = Yii::$service->fecshoplang->getLangCodeByLanguage($store['language']);
						Yii::$service->store->currentLangName = $store['languageName'];
						Yii::$service->page->translate->setLanguage($store['language']);
					}
					if(isset($store['theme']) && !empty($store['theme'])){
						Yii::$service->store->currentTheme = $store['theme'];
					}
					/**
					 * set local theme dir.
					 */ 
					if(isset($store['localThemeDir']) && $store['localThemeDir']){
						Yii::$service->page->theme->localThemeDir = $store['localThemeDir'];
					}
					/**
					 * set third theme dir.
					 */ 
					if(isset($store['thirdThemeDir']) && $store['thirdThemeDir']){
						Yii::$service->page->theme->thirdThemeDir = $store['thirdThemeDir'];
					}
					/**
					 * init store currency.
					 */
					if(isset($store['currency']) && !empty($store['currency'])){
						$currency = $store['currency'];
					}else{
						$currency = '';
					}
					
					Yii::$service->page->currency->initCurrency($currency);
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
	 * @property $attrVal|Array , language attr array , like   ['title_en' => 'xxxx','title_fr' => 'yyyy']
	 * @property $attrName|String, attribute name ,like: title ,description. 
	 * if  object or array  attribute is a language attribute, you can get current 
	 * language value by this function.
	 * if lang attribute in current store language is empty , default language attribute will be return. 
	 * if attribute in default language value is empty, $attrVal will be return. 
	 */
	public function getStoreAttrVal($attrVal,$attrName){
		$lang = $this->currentLangCode;
		return Yii::$service->fecshoplang->getLangAttrVal($attrVal,$attrName,$lang);
	}
	
	/**
	 * @return Array
	 * get all store info, one item in array format is: ['storeCode' => 'store language'].
	 */
	public function getStoresLang(){
		$stores = $this->stores;
		$topLang = [];
		foreach($stores as $storeCode=> $store){
			$languageName = $store['languageName'];
			$topLang[$storeCode] = $languageName;
		}
		return $topLang;
	}
	
	
	
	
}