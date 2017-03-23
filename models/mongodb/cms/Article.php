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
class Article extends ActiveRecord
{
    
    
	public static function collectionName()
    {
	   return 'article';
    }
	
	
	public function attributes()
    {
       return [
			'_id',
			'url_key', 
			'title', 
			'meta_keywords', 
			'meta_description',
			'content',
			'status',
			'created_at',
			'updated_at',
			'created_user_id',
			
		];
    }
	
	public static function create_index(){
		$indexs = [
			['url_key' 		=> -1],
			
		];
      
		$options = ['background' => true];
		foreach($indexs as $columns){
			self::getCollection()->createIndex($columns,$options);
		}
	}
	
}