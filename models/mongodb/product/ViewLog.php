<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\models\mongodb\product;
use Yii;
use yii\mongodb\ActiveRecord;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ViewLog extends ActiveRecord
{
    public static $_collectionName;
    
	public static function collectionName()
    {
       return self::$_collectionName;
    }
	
	public static function setCurrentCollectionName($name){
		self::$_collectionName = $name;
	}

   
    public function attributes()
    {
       return [
		'_id', 'date_time', 
		'product_id', 
		'sku', 'image' ,
		'name', 'user_id'
	   ];
    }
}