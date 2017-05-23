<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block\order;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class View
{
    public function getLastData()
    {
        $order_id = Yii::$app->request->get('order_id');
        $order_info = $this->getCustomerOrderInfo($order_id);

        return $order_info;
    }

    public function getCustomerOrderInfo($order_id)
    {
        if ($order_id) {
            $order_info = Yii::$service->order->getOrderInfoById($order_id);
            if (isset($order_info['customer_id']) && !empty($order_info['customer_id'])) {
                $identity = Yii::$app->user->identity;
                $customer_id = $identity->id;
                if ($order_info['customer_id'] == $customer_id) {
                    return $order_info;
                }
            }
        }

        return [];
    }
}
