<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;
/**
 * Systemhelper services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Systemhelper extends Service
{
    public $controllerNameSpace;
    
    /**
     * 后台用户登陆后，看到的的统计信息
     * 1.总量：产品Sku总数，用户总量，订单总量，评价总量
     * 2.昨日，今日：支付订单销售额，支付订单数，新增用户数，下单用户数
     */
    public function getRecentStatisticsInfo()
    {
        // 1.1总量：产品Sku总数
        $productFilter = [
            'select' => ['sku'],
            'asArray' => true,
            'where'  => [],
        ];
        $productData = Yii::$service->product->coll($productFilter);
        $productTotalCount = isset($productData['count']) ? $productData['count'] : 0;
        // 1.2总量：用户总量
        $filter = [
            'select' => ['firstname'],
            'where'			=> [],
            'asArray' => true,
        ];
        $customerData = Yii::$service->customer->coll($filter);
        $customerTotalCount = 0;
        if (isset($customerData['count']) && $customerData['count']) {
            $customerTotalCount = $customerData['count'];
        }
        // 1.3总量：支付订单总数
        $filter = [
            'select' => ['increment_id'],
            'where'			=> [
                ['in', 'order_status', Yii::$service->order->getOrderPaymentedStatusArr()]
            ],
            'asArray' => true,
        ];
        $orderData = Yii::$service->order->coll($filter);
        $orderTotalCount = 0;
        if (isset($orderData['count']) && $orderData['count']) {
            $orderTotalCount = $orderData['count'];
        }
        // 1.4总量：评价
        $filter = [
            'select' => ['product_spu'],
            'where'			=> [],
            'asArray' => true,
        ];
        $reviewData = Yii::$service->product->review->coll($filter);
        $reviewTotalCount = 0;
        if (isset($reviewData['count']) && $reviewData['count']) {
            $reviewTotalCount = $reviewData['count'];
        }
        // 2.1昨日/今日 订单总销售额，总数，下单用户数
        $yest0Time = strtotime(date("Y-m-d",strtotime("-1 day")));   // 昨日0点
        $today0Time = strtotime(date("Y-m-d",time()));   // 昨日0点
        $filter = [
            'select' => ['created_at', 'customer_id', 'order_status', 'base_grand_total' ],
            'fetchAll' => true,
            'where'			=> [
                ['>=', 'created_at', $yest0Time],
                ['in', 'order_status', Yii::$service->order->getOrderPaymentedStatusArr()]
            ],
            'asArray' => true,
        ];
        $orderData = Yii::$service->order->coll($filter);
        $orderYestTotalCount= 0;
        $orderYestTotalBaseSale = 0;
        $orderTodayTotalCount= 0;
        $orderTodayTotalBaseSale = 0;
        $orderYestCustomer= [];
        $orderTodayCustomer =[];
        if (is_array($orderData['coll']) && !empty($orderData['coll'])) {
            foreach ($orderData['coll'] as $one) {
                $orderTime = $one['created_at'];
                $baseGrandTotal = $one['base_grand_total'];
                $customer_id = $one['customer_id'];
                if ($orderTime >= $today0Time) {
                    $orderTodayTotalCount +=1;
                    $orderTodayTotalBaseSale += $baseGrandTotal;
                    $orderTodayCustomer[$customer_id] = $customer_id;
                } else {
                    $orderYestTotalCount +=1;
                    $orderYestTotalBaseSale += $baseGrandTotal;
                    $orderYestCustomer[$customer_id] = $customer_id;
                }
            }
        }
        $orderYestCustomerCount= count($orderYestCustomer);
        $orderTodayCustomerCount = count($orderTodayCustomer);
        //2.2 新增用户数
        // 1.2总量：用户总量
        $filter = [
            'select' => ['firstname'],
            'where'			=> [
                ['>=', 'created_at', $yest0Time],
                ['<', 'created_at', $today0Time],
            ],
            'asArray' => true,
        ];
        $yestCustomerData = Yii::$service->customer->coll($filter);
        $yestCustomerCount = isset($yestCustomerData['count']) ? $yestCustomerData['count'] : 0;
        $filter = [
            'select' => ['firstname'],
            'where'			=> [
                ['>=', 'created_at', $today0Time],
            ],
            'asArray' => true,
        ];
        $todayCustomerData = Yii::$service->customer->coll($filter);
        $todayCustomerCount = isset($todayCustomerData['count']) ? $todayCustomerData['count'] : 0;
        
        return [
            'all' => [
                'product_count' => $productTotalCount,
                'customer_count' => $customerTotalCount,
                'order_count' => $orderTotalCount,
                'review_count' => $reviewTotalCount,
            ],
            'yestday' => [
                'order_count' => $orderYestTotalCount,
                'order_base_sale' => Yii::$service->helper->format->numberFormat($orderYestTotalBaseSale),
                'order_customer_count' => $orderYestCustomerCount,
                'register_customer_count' => $yestCustomerCount,
            ],
            'today' => [
                'order_count' => $orderTodayTotalCount,
                'order_base_sale' => Yii::$service->helper->format->numberFormat($orderTodayTotalBaseSale),
                'order_customer_count' => $orderTodayCustomerCount,
                'register_customer_count' => $todayCustomerCount,
            ],
        ];
    }
    
    
    
}
