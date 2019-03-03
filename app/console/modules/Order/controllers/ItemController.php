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
class ItemController extends Controller
{
    public $pageNum = 10;

    public function actionCount()
    {
        $data = $this->getOrderData();
        if (isset($data['count']) && $data['count']) {
            echo $data['count'];
        }

        return;
    }

    public function actionPagenum()
    {
        $data = $this->getOrderData();
        if (isset($data['count']) && $data['count']) {
            echo ceil($data['count'] / $this->pageNum);
        }

        return;
    }

    public function actionComputesellercount($pageNum)
    {
        Yii::$service->product->updateAllScoreToZero();
        $orderData = $this->getOrderData($pageNum);
        $order_ids = [];
        if (isset($orderData['coll']) && is_array($orderData['coll'])) {
            foreach ($orderData['coll'] as $one) {
                $order_ids[] = $one['order_id'];
            }
        }
        if (empty($order_ids)) {
            return false;
        }
        $productSaleArr = [];
        $itemData = $this->getOrderItemsData($order_ids);
        if (isset($itemData['coll']) && is_array($itemData['coll']) ) {
            foreach ($itemData['coll'] as $item) {
                $sku = $item['sku'];
                $qty = $item['qty'];

                if (isset($productSaleArr[$sku])) {
                    $productSaleArr[$sku] += (int)$qty;
                } else {
                    $productSaleArr[$sku] = (int)$qty;
                }
            }
        }
        echo "Print Sku And Sales Qty List: \n";
        var_dump($productSaleArr);
        foreach ($productSaleArr as $sku => $qty) {
            $product = Yii::$service->product->getBySku((string)$sku, false);
            if ($product['sku']) {
                $product['score'] += (int)$qty;
                $product->save();
                //echo $sku."=>".$qty."\n";
            }
        }

        return ture;
    }


    public function getOrderItemsData($order_ids){
        if (!is_array($order_ids) || empty($order_ids)) {
            return;
        }
        $filter = [
            'where' => [
                ['in', 'order_id', $order_ids]
            ],
        ];

        return Yii::$service->order->item->coll($filter);
    }

    public function getOrderData($pageNum = 0){
        $paymentOrderStatus = Yii::$service->order->getOrderPaymentedStatusArr();
        $where = [];
        $orderProductSaleInMonths = Yii::$service->order->orderProductSaleInMonths;
        if ($orderProductSaleInMonths > 0) {
            $beginDate = strtotime('-'.$orderProductSaleInMonths.' months');
            $where[] = ['>', 'created_at', $beginDate];
        }
        $where[] = ['order_status' => $paymentOrderStatus];
        $filter = [
            'where' => $where,
        ];
        if ($pageNum) {
            $filter['numPerPage'] = $this->pageNum;
            $filter['pageNum'] = $pageNum;
        }

        return Yii::$service->order->coll($filter);
    }

}
