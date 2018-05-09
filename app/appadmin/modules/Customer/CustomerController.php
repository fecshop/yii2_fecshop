<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Customer;

/*
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
use fecadmin\FecadminbaseController;
use Yii;
use fecshop\app\appadmin\modules\AppadminController;

class CustomerController extends AppadminController
{
    public $enableCsrfValidation = false;
    
    //public function getViewPath()
    //{
    //    return Yii::getAlias('@fecshop/app/appadmin/modules/Customer/views') . DIRECTORY_SEPARATOR . $this->id;
    //}
}
