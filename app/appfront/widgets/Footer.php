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
        $lang = Yii::$service->store->currentLangCode;

        return self::BLOCK_CACHE_PREFIX.'_'.$lang;
    }
}
