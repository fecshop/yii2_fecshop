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
class FavoriteproductController extends AppfrontController
{
   
	# 增加收藏
    public function actionAdd()
    {
		return $this->getBlock()->getLastData();
		//return $this->render($this->action->id,$data);
	}
	
	public function actionLists()
    {
		$data = $this->getBlock()->getLastData($editForm);
		return $this->render($this->action->id,$data);
	}
	
}
















