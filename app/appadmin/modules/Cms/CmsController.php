<?php
namespace fecshop\app\appadmin\modules\Cms;

use Yii;
use yii\helpers\Url;
use fecadmin\FecadminbaseController;
class CmsController extends FecadminbaseController
{
	
	 public function getViewPath()
    {
		return Yii::getAlias('@fecshop/app/appadmin/modules/Cms/views') . DIRECTORY_SEPARATOR . $this->id;
    }
	
}


?>