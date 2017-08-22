<?php

namespace fecshop\app\apphtml5\widgets;

use fecshop\interfaces\block\BlockCache;
use Yii;

class Menu implements BlockCache
{
    public function getLastData()
    {
        $categoryArr = Yii::$service->page->menu->getMenuData();
        //var_dump($categoryArr);
        return [
            'categoryArr' => $categoryArr,
        ];
    }

    public function getCacheKey()
    {
        $lang           = Yii::$service->store->currentLangCode;
        $appName        = Yii::$service->helper->getAppName();
        $cacheKeyName   = 'menu';
        $currentStore   = Yii::$service->store->currentStore;
        return self::BLOCK_CACHE_PREFIX.'_'.$currentStore.'_'.$lang.'_'.$appName.'_'.$cacheKeyName;
    }
}
