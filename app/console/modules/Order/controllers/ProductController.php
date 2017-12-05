<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\console\modules\Order\controllers;

use Yii;
use yii\console\Controller;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductController extends Controller
{
    public function actionReturnpendingstock()
    {
        $logMessage = Yii::$service->order->returnPendingStock();
        foreach ($logMessage as $msg) {
            echo $msg."\n";
        }
    }
}
