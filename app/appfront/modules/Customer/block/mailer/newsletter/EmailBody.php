<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block\mailer\newsletter;

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
        //echo Yii::$service->image->getImgUrl('mail/logo.png','appfront');exit;
        return [
            'email'        => $identity['email'],
            //'name'		=> $identity['name'],
            'logoImg'    => Yii::$service->image->getImgUrl('mail/logo.png', 'appfront'),
            'homeUrl'    => Yii::$service->url->homeUrl(),
            'storeName'    => Yii::$service->store->currentStore,
            'identity'  => $identity,
        ];
    }
}
