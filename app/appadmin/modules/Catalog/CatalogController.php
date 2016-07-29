<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */  
use Yii;
use yii\helpers\Url;
use fecadmin\FecadminbaseController;
class CatalogController extends FecadminbaseController
{
	
	 public function getViewPath()
    {
		return Yii::getAlias('@fecshop/app/appadmin/modules/Catalog/views') . DIRECTORY_SEPARATOR . $this->id;
    }
	
}


?>