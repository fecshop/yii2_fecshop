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
class IndexController extends AppadminController
{
	public $enableCsrfValidation = true;
    
    public function init()
    {
        Yii::$service->page->theme->layoutFile = 'dashboard.php';
        parent::init(); 
    }
    
    public function actionIndex()
    {
		return $this->render('index');
	}
	
	
	
	
}








