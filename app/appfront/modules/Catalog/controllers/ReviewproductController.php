<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalog\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ReviewproductController extends AppfrontController
{
    public function init(){
		parent::init();
		Yii::$service->page->theme->layoutFile = 'product_view.php';
	}
	# Ôö¼ÓÆÀÂÛ
    public function actionAdd()
    {
		$editForm = Yii::$app->request->post('editForm');
		if(!empty($editForm)){
			$this->getBlock()->saveReview($editForm);
		}
		//echo 1;exit;
		$data = $this->getBlock()->getLastData($editForm);
		return $this->render($this->action->id,$data);
	}
	
	public function actionLists()
    {
		$data = $this->getBlock()->getLastData($editForm);
		return $this->render($this->action->id,$data);
	}
	
}
















