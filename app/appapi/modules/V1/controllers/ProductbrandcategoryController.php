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
class ProductbrandcategoryController extends AppapiTokenController
{
    public $numPerPage = 5;
    
    /**
     * Get Lsit Api：得到article 列表的api
     */
    public function actionList()
    {
        
        $page = Yii::$app->request->get('page');
        $numPerPage = Yii::$app->request->get('numPerPage');
        $page = $page ? $page : 1;
        $numPerPage = $numPerPage > 0 ? $numPerPage : $this->numPerPage;
        $filter = [
            'numPerPage'    => $numPerPage,
            'pageNum'       => $page,
            'asArray'       => true,
        ];
        $data  = Yii::$service->product->brandcategory->coll($filter);
        $coll  = $data['coll'];
        $count = $data['count']; 
        
        $pageCount = ceil($count / $numPerPage);
        $serializer = new \yii\rest\Serializer();
        Yii::$app->response->getHeaders()
            ->set($serializer->totalCountHeader, $count)
            ->set($serializer->pageCountHeader, $pageCount)
            ->set($serializer->currentPageHeader, $page)
            ->set($serializer->perPageHeader, $numPerPage);
        if ($page <= $pageCount ) {
            return [
                'code'    => 200,
                'message' => 'fetch product brand category success',
                'data'    => $coll,
            ];
        } else {
            return [
                'code'    => 400,
                'message' => 'fetch product brand category fail , exceeded the maximum number of pages',
                'data'    => [],
            ];
        }
    }
    
    /**
     * Update One Api：更新一条记录的api
     */
    public function actionUpsertone()
    {
        $id = Yii::$app->request->post('id');
        $sort_order = Yii::$app->request->post('sort_order');
        $name = Yii::$app->request->post('name');
        $status = Yii::$app->request->post('status');
        
        $primaryKey = Yii::$service->product->brand->getPrimaryKey();
        if (!$id) {
            return [
                'code'    => 400,
                'message' => 'upsert product brand category id can not empty',
                'data'    => [
                    'error' => 'upsert product brand category id can not empty',
                ],
            ];
        }
        $param = [
            'sort_order'           => $sort_order,
            'name'             => $name,
            'status'     => $status,
            'id'   => $id,
        ];
        
        $saveData = Yii::$service->product->brandcategory->upsert($param);
        if (!$saveData) {
            $errors = Yii::$service->helper->errors->get(', ');
            return [
                'code'    => 400,
                'message' => 'update product brandcategory fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        }
        return [
            'code'    => 200,
            'message' => 'update product brandcategory success',
            'data'    => [
                'addData' => $saveData,
            ]
        ];
    }
    
    /**
     * Delete One Api：删除一条记录的api
     */
    public function actionDeleteone()
    {
       //  这个暂不实现
    }
    
    /**
     * Get One Api：根据url_key 和 id 得到article 列表的api
     */
    public function actionFetchone()
    {
        $primaryKeyVal = Yii::$app->request->get('id');
        $data          = [];
        if ( !$primaryKeyVal ) {
            
            return [
                'code'    => 400,
                'message' => 'request param [id] can not all empty',
                'data'    => [],
            ];
        }
            
        $attrM = Yii::$service->product->brandcategory->getByPrimaryKey($primaryKeyVal);
        if(isset($attrM['name']) && $attrM['name']){
            $data = $attrM;
        }
         
        if (empty($data)) {
            
            return [
                'code'    => 400,
                'message' => 'can not find product brandcategory by id ',
                'data'    => [],
            ];
        } else {
            if (isset($data['display_data']) && $data['display_data']) {
                $data['display_data'] = unserialize($data['display_data']);
            }
            return [
                'code'    => 200,
                'message' => 'fetch product brandcategory success',
                'data'    => $data,
            ];
        } 
    }
    
    
}
