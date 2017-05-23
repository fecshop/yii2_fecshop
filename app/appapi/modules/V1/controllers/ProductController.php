<?php

namespace fecshop\app\appapi\modules\V1\controllers;

use fecshop\app\appapi\modules\AppapiController;
use Yii;

class ProductController extends AppapiController
{
    public $modelClass = 'PRODUCT';

    public $numPerPage = 20;

    /**
     * 得到产品的数据list.
     */
    // http://fecshop.appapi.fancyecommerce.com/v1/products?page=2&access-token=1Gk6ZNn-QaBaKFI4uE2bSw0w3N7ej72q
    // http://fecshop.appapi.fancyecommerce.com/v1/products?page=2
    public function actionCustomindex()
    {
        $page = Yii::$app->request->get('page');
        $page = $page ? $page : 1;
        $filter = [
            'numPerPage'    => $this->numPerPage,
            'pageNum'        => $page,
            'asArray' => true,
        ];
        $data = Yii::$service->product->coll($filter);
        $coll = $data['coll'];
        $count = $data['count'];

        $pageCount = ceil($count / $this->numPerPage);
        $serializer = new \yii\rest\Serializer();
        Yii::$app->response->getHeaders()
            ->set($serializer->totalCountHeader, $count)
            ->set($serializer->pageCountHeader, $pageCount)
            ->set($serializer->currentPageHeader, $page)
            ->set($serializer->perPageHeader, $this->numPerPage);

        return [
            'status'    => 'success',
            'count'        => $count,
            'pageCount' => $pageCount,
            'page'        => $page,
            'numPerPage'=> $this->numPerPage,
            'coll'     => $coll,

        ];
    }

    /**
     * 得到单个产品
     */
    public function actionCustomview($product_id)
    {
        $product = Yii::$service->product->apiGetByPrimaryKey($primaryKey);

        return [
            'status' => 'success',
            'data'     => $product,
        ];
    }

    /**
     * 创建产品
     */
    public function actionCustomsave()
    {
        $product = Yii::$app->request->post('product');
        Yii::$service->product->apiSave($product);

        return [
            'status' => 'success',
        ];
    }

    /**
     * 删除产品
     */
    public function actionCustomdelete($product_id)
    {
        Yii::$service->product->ApiDelete($product_id);

        return [
            'status' => 'success',
        ];
    }
}
