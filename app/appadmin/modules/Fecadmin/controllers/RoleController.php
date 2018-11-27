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
class RoleController extends AppadminController
{
    public $enableCsrfValidation = true;
    public $blockNamespace = 'fecshop\\app\\appadmin\\modules\\Fecadmin\\block';
    # 权限
    public function actionManager()
    {
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id,$data);
    }

    # 权限
    public function actionManageredit()
    {
        $data = $this->getBlock()->getLastData();
        return $this->render($this->action->id,$data);
    }

    # 权限
    public function actionManagereditsave()
    {
        $this->getBlock("manageredit")->save();

    }

    # 权限
    public function actionManagerdelete()
    {
        $this->getBlock("manageredit")->delete();

    }
}








