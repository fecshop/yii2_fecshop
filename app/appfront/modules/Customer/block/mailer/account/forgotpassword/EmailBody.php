<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block\mailer\account\forgotpassword;

use fecshop\app\appfront\helper\mailer\Email;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class EmailBody
{
    public $params;

    public function getLastData()
    {
        $identity = $this->params;
        $resetUrl = Yii::$service->url->getUrl('customer/account/resetpassword', ['resetToken'=>$identity['password_reset_token']]);
        //echo $resetUrl;
        //exit;
        //echo Yii::$service->image->getImgUrl('mail/logo.png','appfront');exit;
        return [
            'name'        => $identity['firstname'].' '. $identity['lastname'],
            'email'        => $identity['email'],
            'resetUrl'    => $resetUrl,
            'storeName'            => Email::storeName(),
            'contactsEmailAddress'    => Email::contactsEmailAddress(),
            'contactsPhone'            => Email::contactsPhone(),
            'homeUrl'    => Yii::$service->url->homeUrl(),
            'logoImg'    => Yii::$service->image->getImgUrl('mail/logo.png', 'appfront'),

            //'loginUrl'	=> Yii::$service->url->getUrl("customer/account/index"),
            //'accountUrl'=> Yii::$service->url->getUrl("customer/account/index"),

            'identity'  => $identity,
        ];
    }
}
