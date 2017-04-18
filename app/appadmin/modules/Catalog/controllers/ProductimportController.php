<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\controllers;
use Yii;
use yii\helpers\Url;
use fecshop\app\appadmin\modules\Catalog\CatalogController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductimportController extends CatalogController
{
	//public $enableCsrfValidation = false;
	protected $_postParam;
    public function actionIndex()
    {
		$this->_postParam = Yii::$app->request->post(); 
		
		if($this->_postParam['_csrf']){
            $this->getBlock()->importProductByExcel();
		}else{
			$data = $this->getBlock()->getLastData();
			return $this->render($this->action->id,$data);
		}
	}
	
	
	
}