<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\helper;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
# use fecshop\app\appadmin\modules\Catalog\helper\Product as ProductHelper;
class Product 
{
	public static function getStatusArr(){
		return [		
			1=>'激活',
			2=>'关闭',
		];
	}
	
	public static function getInStockArr(){
		return [		
			1=>'有货',
			2=>'缺货',
		];
	}
	
}








