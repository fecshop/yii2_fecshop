<?php

namespace fecshop\app\appfront\widgets;

use fecshop\interfaces\block\BlockCache;
use Yii;

class Footer implements BlockCache
{
    public function getLastData()
    {
        return [

        ];
    }

    public function getCacheKey()
    {
        $lang           = Yii::$service->store->currentLangCode;
        $appName        = Yii::$service->helper->getAppName();
        $cacheKeyName   = 'footer';
        $currentStore   = Yii::$service->store->currentStore;
        return self::BLOCK_CACHE_PREFIX.'_'.$currentStore.'_'.$appName.'_'.$lang.'_'.$cacheKeyName;
    }
}
