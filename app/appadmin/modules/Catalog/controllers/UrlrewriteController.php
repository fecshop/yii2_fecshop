<?php
namespace fecshop\app\appadmin\modules\Catalog\controllers;
use Yii;
use yii\helpers\Url;
use fecshop\app\appadmin\modules\Catalog\CatalogController;
/**
 * Site controller
 */
class UrlrewriteController extends CatalogController
{
	
    public function actionIndex()
    {
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	public function actionManageredit()
    {
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	public function actionManagereditsave()
    {
		$data = $this->getBlock("manageredit")->save();
	}
	
	public function actionManagerdelete()
    {
		$this->getBlock("manageredit")->delete();
	}


















	
	
}








