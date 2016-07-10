<?php
namespace fecshop\app\appfront\modules\Cms\block\widgets;
use Yii;
use fecshop\interfaces\block\BlockCache;
class Menu implements BlockCache
{
	
    public function getLastData()
    {
		return [
			
		];
	}
	
	public function getCacheKey(){
		$lang = Yii::$app->store->currentLanguage;
		
		return self::BLOCK_CACHE_PREFIX.'_'.$lang;
	
	}
}



