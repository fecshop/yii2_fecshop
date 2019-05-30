<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\email\widgets\customer\account\register;

use fecshop\services\email\widgets\BodyBase;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Body extends BodyBase
{
    public function getLastData()
    {
        $identity = $this->params;
        $registerEnableUrl = '';
        if (Yii::$service->email->customer->registerAccountIsNeedEnableByEmail) {
            $registerEnableUrl = Yii::$service->url->getUrl('customer/account/registerenable', ['enableToken'=>$identity['register_enable_token']]);
            if (Yii::$service->store->isApiStore()) {
                if ($homeUrl = Yii::$service->helper->getAppServiceDomain()) {
                } else {
                    $homeUrl = Yii::$service->url->getUrl('/');
                }
                $registerEnableUrl = $homeUrl.'#/customer/account/registerenable/'.$identity['register_enable_token'];
            }
        }
        
        return [
            'name'                  => $identity['firstname'].' '. $identity['lastname'],
            'email'                 => $identity['email'],
            'password'              => $identity['password'],
            'storeName'             => Yii::$service->email->storeName(),
            'contactsEmailAddress'  => Yii::$service->email->contactsEmailAddress(),
            'contactsPhone'         => Yii::$service->email->contactsPhone(),
            'homeUrl'               => Yii::$service->url->homeUrl(),
            'logoImg'               => Yii::$service->image->getImgUrl('mail/logo.png', 'appfront'),
            'loginUrl'              => Yii::$service->url->getUrl('customer/account/index'),
            'accountUrl'            => Yii::$service->url->getUrl('customer/account/index'),
            'identity'              => $identity,
            'registerEnableUrl' => $registerEnableUrl,
        ];
    }
}
