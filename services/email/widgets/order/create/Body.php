<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\email\widgets\order\create;

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
        $order          = $this->params;
        $countryCode    = $order['customer_address_country'];
        $stateCode      = $order['customer_address_state'];
        $countryName    = Yii::$service->helper->country->getCountryNameByKey($countryCode);
        $stateName      = Yii::$service->helper->country->getStateByContryCode($countryCode, $stateCode);

        return [
            'name'                  => $order['customer_firstname'].' '. $order['customer_lastname'],
            'customer_email'        => $order['customer_email'],
            'increment_id'          => $order['increment_id'],
            'storeName'             => Yii::$service->email->storeName(),
            'contactsEmailAddress'  => Yii::$service->email->contactsEmailAddress(),
            'contactsPhone'         => Yii::$service->email->contactsPhone(),
            'homeUrl'               => Yii::$service->url->homeUrl(),
            'logoImg'               => Yii::$service->image->getImgUrl('mail/logo.png', 'appfront'),
            'countryName'           => $countryName,
            'stateName'             => $stateName,
            'order'                 => $order,
        ];
    }
}
