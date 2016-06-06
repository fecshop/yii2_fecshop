<?php
namespace fecshop\services\product\image;
use Yii;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
class Base extends Service
{
	public $imagePath;
	# get Current Product info
	public function getBaseProductImage()
	{
		return 'product Image info';
	}
	
	public function getImageBasePath(){
		return Yii::getAlias("@webroot").'/'. $this->imagePath;
	}
	
	
	
 
}