<?php

namespace fecshop\app\apphtml5\widgets;

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
        return self::BLOCK_CACHE_PREFIX.'_'.$appName.'_'.$lang.'_'.$cacheKeyName;
    }
}
