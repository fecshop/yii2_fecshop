<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\models\mongodb\customer;
use Yii;
use yii\mongodb\ActiveRecord;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Newsletter extends ActiveRecord
{
    const ENABLE_STATUS = 1;
	const DISABLE_STATUS= 10;
	
	public static function collectionName()
    {
	   return 'newsletter';
    }
	
    public function attributes()
    {
		return [
			'_id', 
			'email',
			'created_at',
			'status',
			
	   ];
    }
	
	public static function primaryKey(){
		return '_id';
	}
	
	
	public static function create_index(){
		$indexs = [
			['email' 		=> -1],
			
		];
      
		$options = ['background' => true];
		foreach($indexs as $columns){
			self::getCollection()->createIndex($columns,$options);
		}
	}
	
	
	
}