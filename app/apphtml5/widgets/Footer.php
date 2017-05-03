<?php
namespace fecshop\app\apphtml5\widgets;
use Yii;
use fecshop\interfaces\block\BlockCache;
class Footer implements BlockCache
{
	
    public function getLastData()
    {
		return [
			
		];
	}
	
	public function getCacheKey(){
		$lang = Yii::$service->store->currentLanguage;
		return self::BLOCK_CACHE_PREFIX.'_'.$lang;
	
	}
}



