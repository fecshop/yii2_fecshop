<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\page;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
use fecshop\services\ChildService;
/**
 * Breadcrumbs services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StaticBlock extends ChildService
{
	/**
	 * @property  $key|Array
	 * 
	 */
	public function getByKey($key,$lang=''){
		if(!$lang)
			$lang = Yii::$app->store->currentLanguage;
		if(!$lang)
			throw new InvalidValueException('language is empty');
		
	}
	
	/**
	 *	@property $_id | Int
	 *  get StaticBlock one data by $_id.
	 */
	public function getById($_id){
		
		
	}
	
	/**
	 *	@property $filter | Array
	 *  get StaticBlock collections by $filter .
	 */
	public function getStaticBlockList($filter){
		
		
	}
	
}