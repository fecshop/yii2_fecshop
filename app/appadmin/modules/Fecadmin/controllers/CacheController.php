<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Fecadmin\controllers;
use Yii;
use fec\helpers\CRequest;
use fecadmin\FecadminbaseController;
use fecshop\app\appadmin\modules\AppadminController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CacheController extends AppadminController
{
	public $enableCsrfValidation = false;
	public $blockNamespace = 'fecshop\\app\\appadmin\\modules\\Fecadmin\\block';
	# 刷新缓存
    public function actionIndex()
    {
        $block = $this->getBlock();
        if(CRequest::param("method") == 'reflush'){
			$block->reflush();
		}
		$data = $block->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	
	
	
}








