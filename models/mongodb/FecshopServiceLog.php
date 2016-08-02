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
class FecshopServiceLog extends ActiveRecord
{
    
    
	public static function collectionName()
    {
	   return 'fecshop_service_log';
    }
	
	

   
    public function attributes()
    {
		return [
			'_id', 
		    'service_file',
	        'service_method', 
	        'service_method_argument', 
	        'begin_microtime',
			'end_microtime', 
			'used_time', 
			'process_date_time', 
	   ];
    }
	
}