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
class Index
{
    public function getLastData()
    {
        $identity = Yii::$app->user->identity;
        $this->breadcrumbs(Yii::$service->page->translate->__('Account Center'));
        return [
            'accountEditUrl' => Yii::$service->url->getUrl('customer/editaccount'),
            'email'            => $identity['email'],
            'accountAddressUrl' => Yii::$service->url->getUrl('customer/address'),
            'accountOrderUrl' => Yii::$service->url->getUrl('customer/order'),
        ];
    }
    
    // 面包屑导航
    protected function breadcrumbs($name)
    {
        if (Yii::$app->controller->module->params['account_center_breadcrumbs']) {
            Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
        } else {
            Yii::$service->page->breadcrumbs->active = false;
        }
    }
}
