<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\product;
use Yii;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Image extends Service
{
	public $imagePath;
	# get Current Product info
	public function getProductImage()
	{
		return 'product Image info';
	}
	
	public function getImageBasePath(){
		return Yii::getAlias("@webroot").'/'. $this->imagePath;
	}
	
	
	public function getBase(){
		return $this->getChildService('base');
	}
	
	
 
}