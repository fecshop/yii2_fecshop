<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block\account;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Resetpasswordsuccess
{
    public function getLastData()
    {
        $this->breadcrumbs(Yii::$service->page->translate->__('Reset Password Success'));
        return [
            'loginUrl' => Yii::$service->url->getUrl('customer/account/login'),
        ];
    }
    
     // 面包屑导航
    protected function breadcrumbs($name)
    {
        if (Yii::$app->controller->module->params['forgot_reset_password_success_breadcrumbs']) {
            Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
        } else {
            Yii::$service->page->breadcrumbs->active = false;
        }
    }
}
