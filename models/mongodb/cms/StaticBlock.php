<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\models\mongodb\cms;
use Yii;
use yii\mongodb\ActiveRecord;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StaticBlock extends ActiveRecord
{
    
    
	public static function collectionName()
    {
	   return 'static_block';
    }
	
	
	public function attributes()
    {
       return [
			'_id',
			'title', 
			'identify', 
			'status',
			'content',
			'created_at',
			'updated_at',
			'created_user_id',
		];
    }
	
	public static function create_index(){
		$indexs = [
			['identify' 		=> -1],
			
		];
      
		$options = ['background' => true];
		foreach($indexs as $columns){
			self::getCollection()->createIndex($columns,$options);
		}
	}
	
}