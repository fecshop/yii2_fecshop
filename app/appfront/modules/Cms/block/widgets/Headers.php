<?php
namespace fecshop\app\appfront\modules\Cms\block\widgets;
use Yii;
use fecshop\interfaces\block\BlockCache;
class Headers implements BlockCache
{
	
    public function getLastData()
    {
		$currentLang = 
		$currency = Yii::$app->page->currency->getCurrentCurrency();
		return [
			'baseurl'			=> Yii::$app->url->getBaseUrl(),
			'currentStore'		=> Yii::$app->store->currentStore,
			'currentStoreLang' 	=> Yii::$app->store->currentLangName,
			'stores'			=> Yii::$app->store->getStoresLang(),
			'currency'			=> Yii::$app->page->currency->getCurrencyInfo(),
			'currencys'			=> Yii::$app->page->currency->getCurrencys(),
		];
	}
	
	
	
	
	public function getCacheKey(){
		$lang = Yii::$app->store->currentStore;
		$currency = Yii::$app->page->currency->getCurrentCurrency();
		return self::BLOCK_CACHE_PREFIX.'_'.$lang.'_'.$currency;
	
	}
}



