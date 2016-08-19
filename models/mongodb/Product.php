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
class Product extends ActiveRecord
{
    public  static $_customProductAttrs;
    
	public static function collectionName()
    {
	   return 'product_flat';
    }
	
	/**
	 * get custom product attrs.
	 */
	public static function addCustomProductAttrs($attrs){
		
		self::$_customProductAttrs = $attrs;
	}

   
    public function attributes()
    {
		$origin =  [
			'_id', 
		    'name',
			'spu',
	        'sku', 
	        'weight', 
	        'status',
			'qty', 
			'is_in_stock', 
			'visibility', 
			'url_key', 
			'url_path',
			'category',
			'price', 
			'cost_price', 
			'special_price', 
			'special_from', 
			'special_to', 
			'tier_price',
			'now_price',   # 算出来的最终价格。这个通过脚本赋值。
			'new_product_from',
			'new_product_to',
			'freeshipping',
			'featured',
			'upc',
			'meta_title',
			'meta_keywords',
			'meta_description',
			'image',
			'sell_7_count',
			'sell_30_count',
			'sell_90_count',
			'description',
			'short_description',
			'custom_option',
			'remark', 
			'created_at',
			'updated_at',
			'created_user_id',
			'attr_group',
		];
		if(is_array(self::$_customProductAttrs) && !empty(self::$_customProductAttrs)){
			$origin = array_merge($origin,self::$_customProductAttrs);
		}
		return $origin;
    }
	
	
	
	
	
	
	
	
	
}