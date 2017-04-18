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
class Store extends Service 
{
	/**
	 * init by config file.
	 * all stores config . include : domain,language,theme,themePackage
	 */
	public $stores; 
	
	public $store;
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
	 * 当前store的key，也就是当前的store
	 */
	public $currentStore;
	/**
	 * current language code example : fr  es cn ru.
	 */
	public $currentLangCode;
	
	public $thirdLogin;
	//public $https;
	
	/**
	 *	Bootstrap:init website,  class property $currentLang ,$currentTheme and $currentStore.
	 *  if you not config this ,default class property will be set.
	 *  if current store_code is not config , InvalidValueException will be throw. 
	 *	class property $currentStore will be set value $store_code.
	 */
	protected function actionBootstrap($app){
		$host = explode('//' ,$app->getHomeUrl());
		$stores = $this->stores;
		$init_compelte = 0;
		if(is_array($stores) && !empty($stores)){
			foreach($stores as $store_code => $store){
				if($host[1] == $store_code){
					$this->html5DevideCheckAndRedirect($store_code,$store);
					Yii::$service->store->currentStore = $store_code;
					Yii::$service->store->store = $store;
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
						//Yii::$service->page->theme->localThemeDir = $store['localThemeDir'];
						Yii::$service->page->theme->setLocalThemeDir($store['localThemeDir']);
					}
					/**
					 * set third theme dir.
					 */ 
					if(isset($store['thirdThemeDir']) && $store['thirdThemeDir']){
						//Yii::$service->page->theme->thirdThemeDir = $store['thirdThemeDir'];
						Yii::$service->page->theme->setThirdThemeDir($store['thirdThemeDir']);
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
					$this->thirdLogin = $store['thirdLogin'];
					break;
				}
			}
		}
		if(!$init_compelte){
			throw new InvalidValueException('this domain is not config in store component');
		}
		
    }
	
	/**
	 * mobile devide url redirect.
	 */
	protected function html5DevideCheckAndRedirect($store_code,$store){
		
		if(!isset($store['mobile'])){
			return;
		}
		$mobileDetect = Yii::$service->helper->mobileDetect;
		$enable = isset($store['mobile']['enable']) ? $store['mobile']['enable'] : false ;
		if(!$enable){
			return;
		}
		$condition = isset($store['mobile']['condition']) ? $store['mobile']['condition'] : false ;
		$redirectDomain = isset($store['mobile']['redirectDomain']) ? $store['mobile']['redirectDomain'] : false ;
		if(is_array($condition) && !empty($condition) && !empty($redirectDomain)){
			if(in_array('phone',$condition) && in_array('tablet',$condition)){
				if($mobileDetect->isMobile()){
					$this->redirectMobile($store_code,$redirectDomain);
				}
			}else if(in_array('phone',$condition)){
				if( $mobileDetect->isMobile() && !$mobileDetect->isTablet() ){
					$this->redirectMobile($store_code,$redirectDomain);
				}
			}else if(in_array('tablet',$condition)){
				if( $mobileDetect->isTablet() ){
					$this->redirectMobile($store_code,$redirectDomain);
				}
			}
		}
	}
	/**
	 * 设备满足什么条件的时候进行跳转。
	 */
	protected function redirectMobile($store_code,$redirectDomain){
		$currentUrl = Yii::$service->url->getCurrentUrl();
		$redirectUrl = str_replace($store_code,$redirectDomain,$currentUrl);
		header("Location:".$redirectUrl);
	}
	
	
	
	
	/**
	 * @property $attrVal|Array , language attr array , like   ['title_en' => 'xxxx','title_fr' => 'yyyy']
	 * @property $attrName|String, attribute name ,like: title ,description. 
	 * if  object or array  attribute is a language attribute, you can get current 
	 * language value by this function.
	 * if lang attribute in current store language is empty , default language attribute will be return. 
	 * if attribute in default language value is empty, $attrVal will be return. 
	 */
	protected function actionGetStoreAttrVal($attrVal,$attrName){
		$lang = $this->currentLangCode;
		return Yii::$service->fecshoplang->getLangAttrVal($attrVal,$attrName,$lang);
	}
	
	/**
	 * @return Array
	 * get all store info, one item in array format is: ['storeCode' => 'store language'].
	 */
	protected function actionGetStoresLang(){
		$stores = $this->stores;
		$topLang = [];
		foreach($stores as $storeCode=> $store){
			$languageName = $store['languageName'];
			$topLang[$storeCode] = $languageName;
		}
		return $topLang;
	}
	
	
	
	
}