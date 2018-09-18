<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appapi\modules\V1\controllers;

use fecshop\app\appapi\modules\AppapiTokenController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class OrderController extends AppapiTokenController
{
    public $numPerPage = 5;

    /**
     * Get Lsit Api：得到Category 列表的api
     */
    public function actionList(){

        $page = Yii::$app->request->get('page');
        $page = $page ? $page : 1;
        $filter = [
            'numPerPage'    => $this->numPerPage,
            'pageNum'       => $page,
            'asArray'       => true,
        ];
        $data  = Yii::$service->order->getorderinfocoll($filter);
        $coll  = $data['coll'];
        foreach ($coll as $k => $one) {
            // 处理mongodb类型
            if (isset($one['_id'])) {
                $coll[$k]['id'] = (string)$one['_id'];
                unset($coll[$k]['_id']);
            }
        }
        $count = $data['count'];
        $pageCount = ceil($count / $this->numPerPage);
        $serializer = new \yii\rest\Serializer();
        Yii::$app->response->getHeaders()
            ->set($serializer->totalCountHeader, $count)
            ->set($serializer->pageCountHeader, $pageCount)
            ->set($serializer->currentPageHeader, $page)
            ->set($serializer->perPageHeader, $this->numPerPage);
        if ($page <= $pageCount ) {
            return [
                'code'    => 200,
                'message' => 'fetch order success',
                'data'    => $coll,
            ];
        } else {
            return [
                'code'    => 400,
                'message' => 'fetch order fail , exceeded the maximum number of pages',
                'data'    => [],
            ];
        }
    }
    /**
     * Get One Api：根据url_key 和 id 得到Category 列表的api
     */
    public function actionFetchone(){
        $increment_id  = Yii::$app->request->get('increment_id');
        $data          = [];
        if (!$increment_id) {

            return [
                'code'    => 400,
                'message' => 'request param [increment_id] can not empty',
                'data'    => [],
            ];
        } else {
            $order = Yii::$service->order->getOrderInfoByIncrementId($increment_id);
            if(isset($order['increment_id']) && $order['increment_id']){
                $data = $order;
            }
        }
        if (empty($data)) {

            return [
                'code'    => 400,
                'message' => 'can not find order by id or url_key',
                'data'    => [],
            ];
        } else {
            // 处理mongodb类型
            if (isset($data['_id'])) {
                $data = $data->attributes;
                $data['id'] = (string)$data['_id'];
                unset($data['_id']);
            }
            return [
                'code'    => 200,
                'message' => 'fetch order success',
                'data'    => $data,
            ];
        }
    }

    /**
     * Update One Api：更新一条记录的api
     */
    public function actionUpdateone(){
        // 必填
        $increment_id   = Yii::$app->request->post('increment_id');
        $order_status   = Yii::$app->request->post('order_status');
        if (!$increment_id) {
            $error[] = '[increment_id] can not empty';
        }
        if (!$order_status) {
            $error[] = '[order_status] can not empty';
        }
        $orderService = Yii::$service->order;
        $orderStatusAllowArr = [
            $orderService->payment_status_pending,
            $orderService->payment_status_processing,
            $orderService->payment_status_confirmed,
            $orderService->payment_status_canceled,
            $orderService->status_holded,
            $orderService->payment_status_suspected_fraud,

            $orderService->status_processing,
            $orderService->status_dispatched,
            $orderService->status_refunded,
            $orderService->status_completed,
        ];


        if (!in_array($order_status, $orderStatusAllowArr)) {
            $error[] = '[order_status] value must be in array ['.implode(',',$orderStatusAllowArr).']';
        }

        if (!empty($error)) {
            return [
                'code'    => 400,
                'message' => 'data param format error',
                'data'    => [
                    'error' => $error,
                ],
            ];
        }

        $order = Yii::$service->order->getByIncrementId($increment_id);
        if (isset($order['increment_id']) && $order['increment_id']) {
            $order['order_status'] = $order_status;
            $order->save();
            return [
                'code'    => 200,
                'message' => 'update category success',
                'data'    => [
                    'updateData' => $order,
                ]
            ];
        } else {
            return [
                'code'    => 400,
                'message' => 'can not find order by increment_id:'.$increment_id,
                'data'    => [],
            ];
        }
    }
}
