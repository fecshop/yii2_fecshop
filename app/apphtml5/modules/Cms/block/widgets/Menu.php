<?php

namespace fecshop\app\apphtml5\modules\Cms\block\widgets;

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
        $lang = Yii::$service->store->currentLanguage;

        return self::BLOCK_CACHE_PREFIX.'_'.$lang;
    }
}
