<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalogsearch\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class IndexController extends AppfrontController
{
    public function init(){
		parent::init();
	}
	# 网站信息管理
    public function actionIndex()
    {
		/*
		Yii::$service->search->initFullSearchIndex();
		$filter['select'] = ['_id'];
		$count = Yii::$service->product->collCount($filter);
		//echo $count;
		$numPerPage = 10;
		$pageCount = ceil($count/10);
		for($i=1;$i<=$pageCount;$i++){
			$filter['numPerPage'] = $numPerPage;
			$filter['pageNum'] = $i;
			$products = Yii::$service->product->coll($filter);
			$product_ids = [];
			foreach($products['coll'] as $p){
				$product_ids[] = $p['_id'];
			}
			//var_dump($product_ids);exit;
			Yii::$service->search->syncProductInfo($product_ids);
			
		}
		*/
		
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	
}
















