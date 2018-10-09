<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product\viewLog;

use fec\helpers\CDate;
use fec\helpers\CUser;
use fecshop\services\Service;
use Yii;

/**
 * **注意**：该方法不能在接口类型里面使用
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Session extends Service
{
    public $type;

    public $_defaultType = 'session';

    public $_sessionKey = 'services_product_viewlog_history';

    public $_maxProductCount = 10;

    /**
     *	get product history log.
     */
    public function getHistory()
    {
        $history = Yii::$service->session->get($this->_sessionKey);

        return $history ? $history : '';
    }

    /**
     *	save product  history log.
     */
    public function setHistory($productOb)
    {
        $logArr = [
            'date_time' => CDate::getCurrentDateTime(),
            'product_id'=> $productOb['id'],
            'sku'        => $productOb['sku'],
            'image'        => $productOb['image'],
            'name'        => is_array($productOb['name']) ? serialize($productOb['name']) : $productOb['name'],
        ];
        if (isset($productOb['user_id']) && $productOb['user_id']) {
            $logArr['user_id'] = $productOb['user_id'];
        } else {
            $logArr['user_id'] = CUser::getCurrentUserId();
        }

        if (!($session_history = Yii::$service->session->get($this->_sessionKey))) {
            $session_history = [];
        } elseif (($count = count($session_history)) >= $this->_maxProductCount) {
            $unsetMaxKey = $count - $this->_maxProductCount;
            for ($i = 0; $i <= $unsetMaxKey; $i++) {
                array_shift($session_history);
            }
        }
        $session_history[] = $logArr;
        Yii::$service->session->set($this->_sessionKey, $session_history);
    }
}
