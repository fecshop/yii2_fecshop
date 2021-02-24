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
class ProductattrController extends AppapiTokenController
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
        $data  = Yii::$service->product->attr->coll($filter);
        $coll  = $data['coll'];
        $count = $data['count']; 
        foreach ($coll as $k => $one) {
            if (isset($one['_id'])) {
                $coll[$k]['id'] = (string)$one['_id'];
                unset($coll[$k]['_id']);
            }
            if (isset($one['display_data']) &&  !empty($one['display_data'])) {
                $coll[$k]['display_data'] = unserialize($one['display_data']);
            }
        }
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
                'message' => 'fetch product attr success',
                'data'    => $coll,
            ];
        } else {
            return [
                'code'    => 400,
                'message' => 'fetch product attr fail , exceeded the maximum number of pages',
                'data'    => [],
            ];
        }
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
            
        $attrM = Yii::$service->product->attr->getByPrimaryKey($primaryKeyVal);
        if(isset($attrM['name']) && $attrM['name']){
            $data = $attrM;
        }
         
        if (empty($data)) {
            
            return [
                'code'    => 400,
                'message' => 'can not find product attr by id ',
                'data'    => [],
            ];
        } else {
            if (isset($data['display_data']) && $data['display_data']) {
                $data['display_data'] = unserialize($data['display_data']);
            }
            return [
                'code'    => 200,
                'message' => 'fetch product attr success',
                'data'    => $data,
            ];
        } 
    }
    
    /**
     * Upsert One Api：更新一条记录的api
     */
    public function actionUpsertone()
    {
        $remote_id = Yii::$app->request->post('remote_id');
        $attr_type = Yii::$app->request->post('attr_type');
        $name = Yii::$app->request->post('name');
        $status = Yii::$app->request->post('status');
        $db_type = Yii::$app->request->post('db_type');
        $show_as_img = Yii::$app->request->post('show_as_img');
        $display_type = Yii::$app->request->post('display_type');
        $display_data = Yii::$app->request->post('display_data');
        $is_require = Yii::$app->request->post('is_require');
        $primaryKey = Yii::$service->product->attr->getPrimaryKey();
        $param = [
            'remote_id'           => $remote_id,
            'attr_type'           => $attr_type,
            'name'             => $name,
            'status'     => $status,
            'db_type'  => $db_type,
            'show_as_img'           => $show_as_img,
            'display_type'            => $display_type,
            'display_data'            => $display_data,
            'is_require'            => $is_require,
            'db_type'   => 'String',
        ];
        if (!$remote_id) {
            return [
                'code'    => 400,
                'message' => 'update product attr group remote_id can not empty',
                'data'    => [
                    'error' => 'update product attr group remote_id can not empty',
                ],
            ];
        }
        $saveData = Yii::$service->product->attr->save($param);
        if (!$saveData) {
            $errors = Yii::$service->helper->errors->get(', ');
            return [
                'code'    => 400,
                'message' => 'update product attr fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        }
        return [
            'code'    => 200,
            'message' => 'update product attr success',
            'data'    => [
                'addData' => $saveData,
            ]
        ];
    }
    
    /**
     * Add One Api：新增一条记录的api
     */
    public function actionAddone()
    {
        
        $attr_type = Yii::$app->request->post('attr_type'); 
        $name = Yii::$app->request->post('name');
        $status = Yii::$app->request->post('status');
        $db_type = Yii::$app->request->post('db_type');
        $show_as_img = Yii::$app->request->post('show_as_img');
        $display_type = Yii::$app->request->post('display_type');
        $display_data = Yii::$app->request->post('display_data');
        $is_require = Yii::$app->request->post('is_require');
       
        $param = [
            'attr_type'           => $attr_type,
            'name'             => $name,
            'status'     => $status,
            'db_type'  => $db_type,
            'show_as_img'           => $show_as_img,
            'display_type'            => $display_type,
            'display_data'            => $display_data,
            'is_require'            => $is_require,
            'db_type'   => 'String',
        ];
       
        $saveData = Yii::$service->product->attr->save($param);
        if (!$saveData) {
            $errors = Yii::$service->helper->errors->get(', ');
            return [
                'code'    => 400,
                'message' => 'add product attr fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        }
        return [
            'code'    => 200,
            'message' => 'add product attr success',
            'data'    => [
                'addData' => $saveData,
            ]
        ];
    }
    /**
     * Update One Api：更新一条记录的api
     */
    public function actionUpdateone()
    {
        $id = Yii::$app->request->post('id');
        $attr_type = Yii::$app->request->post('attr_type');
        $name = Yii::$app->request->post('name');
        $status = Yii::$app->request->post('status');
        $db_type = Yii::$app->request->post('db_type');
        $show_as_img = Yii::$app->request->post('show_as_img');
        $display_type = Yii::$app->request->post('display_type');
        $display_data = Yii::$app->request->post('display_data');
        $is_require = Yii::$app->request->post('is_require');
        $primaryKey = Yii::$service->product->attr->getPrimaryKey();
        $param = [
            $primaryKey           => $id,
            'attr_type'           => $attr_type,
            'name'             => $name,
            'status'     => $status,
            'db_type'  => $db_type,
            'show_as_img'           => $show_as_img,
            'display_type'            => $display_type,
            'display_data'            => $display_data,
            'is_require'            => $is_require,
            'db_type'   => 'String',
        ];
        if (!$id) {
            return [
                'code'    => 400,
                'message' => 'update product attr group id can not empty',
                'data'    => [
                    'error' => 'update product attr group id can not empty',
                ],
            ];
        }
        $saveData = Yii::$service->product->attr->save($param);
        if (!$saveData) {
            $errors = Yii::$service->helper->errors->get(', ');
            return [
                'code'    => 400,
                'message' => 'update product attr fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        }
        return [
            'code'    => 200,
            'message' => 'update product attr success',
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
    
    
    
}
