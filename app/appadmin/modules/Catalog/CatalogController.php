<?php
namespace fecshop\app\appadmin\modules\Catalog;

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