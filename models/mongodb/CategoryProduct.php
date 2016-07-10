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
class CategoryProduct extends ActiveRecord
{
    
    
	public static function collectionName()
    {
	   return '{{%category_product}}';
    }
	
	

   
    public function attributes()
    {
		return [
			'_id', 
		    'product_id',
	        'category_id', 
	   ];
    }
	
	
	
	
	
	
	
	
	
}