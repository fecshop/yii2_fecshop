<?php

namespace fecshop\app\apphtml5\modules\Cms\block\widgets;

use fecshop\interfaces\block\BlockCache;
use Yii;

class Head implements BlockCache
{
    public function getLastData()
    {
        return [

        ];
    }

    public function getCacheKey()
    {
        $store = Yii::$service->store->currentStore;
        $moduleId = Yii::$app->controller->module->id;
        $controllerId = Yii::$app->controller->id;
        $actionId = Yii::$app->controller->action->id;
        $urlPathKey = $moduleId.'_'.$controllerId.'_'.$actionId;

        return self::BLOCK_CACHE_PREFIX.'_'.$store.'_'.$urlPathKey;
    }
}
