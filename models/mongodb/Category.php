<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\models\mongodb;
use Yii;
use yii\mongodb\ActiveRecord;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Category extends ActiveRecord
{
    
	public static function collectionName()
    {
	   return 'category';
    }
	
    public function attributes()
    {
		return [
			'_id', 
			'parent_id',
		    'name',
	        'status', 
	        'url_key', 
			'level',
			'thumbnail_image',
			'image',
			'filter_product_attr_selected',
			'filter_product_attr_unselected',
	        'description',
			'menu_custom',
			'title', 
			'meta_description', 
			'meta_keywords', 
			'include_in_menu', 
			'is_feature', 
			'available_sort_by', 
			'default_sort_by', 
			'theme', 
			'active_from',
			'active_to', 
			'created_at',
			'updated_at',
			'created_user_id',
			//other
			/*
				category filter
				category product
			
			
			*/
	   ];
    }
	
	
	
	
	
	
	
	
	
}