<?php

namespace fecshop\app\apphtml5\modules\Cms\block\widgets;

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
        $lang = Yii::$service->store->currentLanguage;

        return self::BLOCK_CACHE_PREFIX.'_'.$lang;
    }
}
