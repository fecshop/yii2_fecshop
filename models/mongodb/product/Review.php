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
class Review extends ActiveRecord
{
    public static $_customAttrs;
	
	
	
	public static function collectionName()
    {
	   return 'review';
    }
	# 动态增加字段。
	public static function addCustomAttrs($attrs){
		self::$_customAttrs = $attrs;
	}
	
    public function attributes($origin=false)
    {
		$origin = [
			'_id', 
			'product_spu',
			'product_id',
			'rate_star',
		    'name',
	        'summary', 
	        'review_content', 
			'review_date',			# 
			'store',			# store
			'lang_code',		# 语言
			'status',			# 审核状态
			'audit_user',		# 审核账号
			'audit_date',		# 审核时间
	    ];
		if($origin){ # 取原始的数据
			return $origin;
		}
	    if(is_array(self::$_customAttrs) && !empty(self::$_customAttrs)){
			$origin = array_merge($origin,self::$_customAttrs);
		}
		return $origin;
    }
	
	
	
	
	
	
	
	
	
}