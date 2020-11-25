<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Customer\block\mailer\account\register;

use fecshop\app\apphtml5\helper\mailer\Email;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class EmailBody extends \yii\base\BaseObject
{
    public $params;

    public function getLastData()
    {
        $identity = $this->params;
        //echo Yii::$service->image->getImgUrl('mail/logo.png','apphtml5');exit;
        return [
            'name'        => $identity['firstname'].' '. $identity['lastname'],
            'email'        => $identity['email'],
            'password'    => $identity['password'],
            'storeName'            => Email::storeName(),
            'contactsEmailAddress'    => Email::contactsEmailAddress(),
            'contactsPhone'            => Email::contactsPhone(),
            'homeUrl'    => Yii::$service->url->homeUrl(),
            'logoImg'    => Yii::$service->image->getImgUrl('mail/logo.png', 'apphtml5'),

            'loginUrl'    => Yii::$service->url->getUrl('customer/account/index'),
            'accountUrl'=> Yii::$service->url->getUrl('customer/account/index'),

            'identity'  => $identity,
        ];
    }
}
