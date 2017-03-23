<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\models\mongodb\url;
use Yii;
use yii\mongodb\ActiveRecord;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
 
class UrlRewrite extends ActiveRecord
{
    
    
	public static function collectionName()
    {
	   return 'url_rewrite';
    }
	
	
	public function attributes()
    {
       return [
		'_id',
		'type', 
		'custom_url_key', 
		'origin_url', 
		'status',
		'updated_at',
		'created_at',
		];
    }
	
	public static function create_index(){
		$indexs = [
			['custom_url_key' 		=> -1],
			
		];
      
		$options = ['background' => true];
		foreach($indexs as $columns){
			self::getCollection()->createIndex($columns,$options);
		}
	}
}