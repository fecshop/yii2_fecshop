<?php
namespace fecshop\app\appadmin\modules\Cms\controllers;
use Yii;
use yii\helpers\Url;
use fecshop\app\appadmin\modules\Cms\CmsController;
/**
 * Site controller
 */
class ArticleController extends CmsController
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








