<?php
namespace fecshop\app\appfront\modules\Cms\block\widget;
use Yii;
use fecshop\app\appfront\modules\AppfrontController;
class Test 
{
	public $terry;
	# 网站信息管理
    public function getLastData()
    {
		return [
			'i'   	=> $this->terry,
			'love' 	=> 'loves',
			'you' 	=> 'terry',
		];
	}
}



