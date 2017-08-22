<?php

namespace fecshop\app\apphtml5\widgets;

use fecshop\interfaces\block\BlockCache;
use Yii;

class Headers implements BlockCache
{
    public function getLastData()
    {
        $currentUrl = Yii::$service->url->getCurrentUrl();
        $logoutUrl = Yii::$service->url->getUrl('customer/account/logout', ['rt'=>base64_encode($currentUrl)]);

        //$currentLang =
        //$currency = Yii::$service->page->currency->getCurrentCurrency();
        return [
            'logoutUrl'            => $logoutUrl,
            'homeUrl'            => Yii::$service->url->homeUrl(),
            'currentBaseUrl'    => Yii::$service->url->getCurrentBaseUrl(),
            'currentStore'        => Yii::$service->store->currentStore,
            'currentStoreLang'    => Yii::$service->store->currentLangName,
            'stores'            => Yii::$service->store->getStoresLang(),
            'currency'            => Yii::$service->page->currency->getCurrencyInfo(),
            'currencys'            => Yii::$service->page->currency->getCurrencys(),
        ];
    }

    public function getCacheKey()
    {
        $lang = Yii::$service->store->currentLangCode;
        $currency = Yii::$service->page->currency->getCurrentCurrency();
        $appName        = Yii::$service->helper->getAppName();
        $cacheKeyName   = 'footer';
        $currentStore   = Yii::$service->store->currentStore;
        return self::BLOCK_CACHE_PREFIX.'_'.$currentStore.'_'.$lang.'_'.$currency.'_'.$appName.'_'.$cacheKeyName;
    }
}
