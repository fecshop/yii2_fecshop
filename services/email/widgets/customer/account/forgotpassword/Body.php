<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\email\widgets\customer\account\forgotpassword;

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
        $resetUrl = Yii::$service->url->getUrl('customer/account/resetpassword', ['resetToken'=>$identity['password_reset_token']]);
        return [
            'name'                  => $identity['firstname'].' '. $identity['lastname'],
            'email'                 => $identity['email'],
            'resetUrl'              => $resetUrl,
            'storeName'             => Yii::$service->email->storeName(),
            'contactsEmailAddress'  => Yii::$service->email->contactsEmailAddress(),
            'contactsPhone'         => Yii::$service->email->contactsPhone(),
            'homeUrl'               => Yii::$service->url->homeUrl(),
            'logoImg'               => Yii::$service->image->getImgUrl('mail/logo.png', 'appfront'),
            'identity'              => $identity,
        ];
    }
}
