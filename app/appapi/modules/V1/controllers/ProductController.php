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
class ProductController extends AppapiTokenController
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
        $data  = Yii::$service->product->coll($filter);
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
                'message' => 'fetch product success',
                'data'    => $coll,
            ];
        } else {
            return [
                'code'    => 400,
                'message' => 'fetch product fail , exceeded the maximum number of pages',
                'data'    => [],
            ];
        }
    }
    /**
     * Get One Api：根据url_key 和 id 得到Category 列表的api
     */
    public function actionFetchone(){
        $primaryKeyVal = Yii::$app->request->get('id');
        $data          = [];
        if (!$primaryKeyVal ) {
            return [
                'code'    => 400,
                'message' => 'request param [url_key,id] can not all empty',
                'data'    => [],
            ];
        } else if ($primaryKeyVal) {
            $product = Yii::$service->product->apiGetByPrimaryKey($primaryKeyVal);
            if(!empty($product)){
                $data = $product;
            }
        }
        if (empty($data)) {
            return [
                'code'    => 400,
                'message' => 'can not find product by id ',
                'data'    => [],
            ];
        } else {
            // 处理mongodb类型
            if (isset($data['_id'])) {
                $data['id'] = (string)$data['_id'];
                unset($data['_id']);
            }
            return [
                'code'    => 200,
                'message' => 'fetch product success',
                'data'    => $data,
            ];
        } 
    }
    /**
     * Add One Api：新增一条记录的api
     */
    public function actionAddone(){
        //var_dump(Yii::$app->request->post());exit;
        $data = Yii::$service->product->productapi->insertByPost();
        return $data;
    }
    /**
     * Update One Api：更新一条记录的api
     */
    public function actionUpdateone(){
        //var_dump(Yii::$app->request->post());exit;
        // 必填
        $id                 = Yii::$app->request->post('id');
        $name               = Yii::$app->request->post('name');
        $weight             = Yii::$app->request->post('weight');
        $status             = Yii::$app->request->post('status');
        $qty                = Yii::$app->request->post('qty');
        $is_in_stock        = Yii::$app->request->post('is_in_stock');
        $category           = Yii::$app->request->post('category');
        $price              = Yii::$app->request->post('price');
        $special_price      = Yii::$app->request->post('special_price');
        $special_from       = Yii::$app->request->post('special_from');
        $special_to         = Yii::$app->request->post('special_to');
        $cost_price         = Yii::$app->request->post('cost_price');
        $tier_price         = Yii::$app->request->post('tier_price');
        $new_product_from   = Yii::$app->request->post('new_product_from');
        $new_product_to     = Yii::$app->request->post('new_product_to');
        $short_description  = Yii::$app->request->post('short_description');
        $remark             = Yii::$app->request->post('remark');
        $relation_sku       = Yii::$app->request->post('relation_sku');
        $buy_also_buy_sku   = Yii::$app->request->post('buy_also_buy_sku');
        $see_also_see_sku   = Yii::$app->request->post('see_also_see_sku');
        $title              = Yii::$app->request->post('title');
        $meta_keywords      = Yii::$app->request->post('meta_keywords');
        $meta_description   = Yii::$app->request->post('meta_description');
        $description        = Yii::$app->request->post('description');
        if (!$id) {
            $error[] = '[id] can not empty';
        }
        if ($name && !Yii::$service->fecshoplang->getDefaultLangAttrVal($name, 'name')) {
            $defaultLangAttrName = Yii::$service->fecshoplang->getDefaultLangAttrName('name');
            $error[] = '[name.'.$defaultLangAttrName.'] can not empty';
        }
        if ($meta_keywords && !is_array($meta_keywords)) {
            $error[] = '[meta_keywords] must be array';
        }
        if ($meta_description && !is_array($meta_description)) {
            $error[] = '[meta_description] must be array';
        }
        if ($description && !is_array($description)) {
            $error[] = '[description] must be array';
        }
        if ($short_description && !is_array($short_description)) {
            $error[] = '[short_description] must be array';
        }
        if ($title && !is_array($title)) {
            $error[] = '[title] must be array';
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
        $param = [];
        
        $name               ? ($param['name'] = $name)                    : '';
        $title              ? ($param['title'] = $title)                        : '';
        $meta_keywords      ? ($param['meta_keywords'] = $meta_keywords)        : '';
        $meta_description   ? ($param['meta_description'] = $meta_description)  : '';
        $status             ? ($param['status'] = $status)                      : '';
        
        $weight             ? ($param['weight'] = $weight)                      : '';
        $qty                ? ($param['qty'] = $qty)                      : '';
        $is_in_stock        ? ($param['is_in_stock'] = $is_in_stock)                      : '';
        $category           ? ($param['category'] = $category)                      : '';
        $price              ? ($param['price'] = $price)                      : '';
        $special_price      ? ($param['special_price'] = $special_price)                      : '';
        $special_from       ? ($param['special_from'] = $special_from)                      : '';
        $special_to         ? ($param['special_to'] = $special_to)                      : '';
        $cost_price         ? ($param['cost_price'] = $cost_price)                      : '';
        
        $tier_price         ? ($param['tier_price'] = $tier_price)                      : '';
        $new_product_from   ? ($param['new_product_from'] = $new_product_from)                      : '';
        $new_product_to     ? ($param['new_product_to'] = $new_product_to)                      : '';
        $short_description  ? ($param['short_description'] = $short_description)                      : '';
        $remark             ? ($param['remark'] = $remark)                      : '';
        $relation_sku       ? ($param['relation_sku'] = $relation_sku)                      : '';
        $buy_also_buy_sku   ? ($param['buy_also_buy_sku'] = $buy_also_buy_sku)                      : '';
        $see_also_see_sku   ? ($param['see_also_see_sku'] = $see_also_see_sku)                      : '';
        $cost_price         ? ($param['cost_price'] = $cost_price)                      : '';
        $description        ? ($param['description'] = $description)                    : '';
       
        $primaryKey         = Yii::$service->product->getPrimaryKey();
        $param[$primaryKey] = $id;
        
        $saveData = Yii::$service->product->save($param);
        if (!$saveData) {
            $errors = Yii::$service->helper->errors->get();
            return [
                'code'    => 400,
                'message' => 'update product fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        } else {
            return [
                'code'    => 200,
                'message' => 'update product success',
                'data'    => [
                    'updateData' => $saveData,
                ]
            ];
        }
    }
    /**
     * Delete One Api：删除一条记录的api
     */
    public function actionDeleteone(){
        $ids = Yii::$app->request->post('ids');
        Yii::$service->product->remove($ids);
        $errors = Yii::$service->helper->errors->get();
        if (!empty($errors)) {
            return [
                'code'    => 400,
                'message' => 'remove product by ids fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        } else {
            return [
                'code'    => 200,
                'message' => 'remove product by ids success',
                'data'    => []
            ];
        }
    }
    
    
}
