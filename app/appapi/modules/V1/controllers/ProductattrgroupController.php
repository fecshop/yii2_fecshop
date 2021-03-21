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
class ProductattrgroupController extends AppapiTokenController
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
        $data  = Yii::$service->product->attrGroup->coll($filter);
        $coll  = $data['coll'];
        $count = $data['count']; 
        foreach ($coll as $k => $one) {
            if (isset($one['_id'])) {
                $coll[$k]['id'] = (string)$one['_id'];
                unset($coll[$k]['_id']);
            }
            if (isset($one['attr_ids']) &&  !empty($one['attr_ids'])) {
                $coll[$k]['attr_ids'] = unserialize($one['attr_ids']);
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
                'message' => 'fetch product attr group success',
                'data'    => $coll,
            ];
        } else {
            return [
                'code'    => 400,
                'message' => 'fetch product attr group fail , exceeded the maximum number of pages',
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
        $name = Yii::$app->request->get('name');
        $data          = [];
        
        if ($primaryKeyVal) {
            $attrM = Yii::$service->product->attrGroup->getByPrimaryKey($primaryKeyVal);
        } else if ($name) {
            $attrM = Yii::$service->product->attrGroup->getByName($name);
        } else {
            
            return [
                'code'    => 400,
                'message' => 'request param [id or name] can not all empty',
                'data'    => [],
            ];
        }   
        
        if(isset($attrM['name']) && $attrM['name']){
            $data = $attrM;
        }
         
        if (empty($data)) {
            
            return [
                'code'    => 400,
                'message' => 'can not find product attr group by id ',
                'data'    => [],
            ];
        } else {
            if (isset($data['attr_ids']) && $data['attr_ids']) {
                $data['attr_ids'] = unserialize($data['attr_ids']);
            }
            return [
                'code'    => 200,
                'message' => 'fetch product attr group success',
                'data'    => $data,
            ];
        } 
    }
    
    /**
     * Update One Api：更新一条记录的api
     */
    public function actionUpsertone()
    {
        $remote_id = Yii::$app->request->post('remote_id');
        $name = Yii::$app->request->post('name');
        $status = Yii::$app->request->post('status');
        $attr_ids = Yii::$app->request->post('attr_ids');
        if (!$remote_id) {
            return [
                'code'    => 400,
                'message' => 'update product attr group id can not empty',
                'data'    => [
                    'error' => 'update product attr group id can not empty',
                ],
            ];
        }
        if (!is_array($attr_ids) || empty($attr_ids)) {
            return [
                'code'    => 400,
                'message' => 'attr_ids must be array and can not empty',
                'data'    => [
                    'error' => 'attr_ids must be array and can not empty',
                ],
            ];
        }
        $primaryKey = Yii::$service->product->attrGroup->getPrimaryKey();
        $param = [
            'remote_id'           => $remote_id,
            'name'             => $name,
            'status'     => $status,
            'attr_ids'  => $attr_ids,
        ];
        // 转换 $attr_ids, 将远程的id转换成fecmall本地的id
        foreach ($attr_ids as $k=>$one) {
            $remote_attr_id = $one['attr_id'];
            $attrM = Yii::$service->product->attr->getByRemoteId($remote_attr_id);
            $local_attr_id = '';
            if ($attrM && isset($attrM['id'])) {
                $local_attr_id = $attrM['id'];
            }
            $attr_ids[$k]['attr_id'] = $local_attr_id;
        }
        $saveData = Yii::$service->product->attrGroup->save($param);
        if (isset($saveData['attr_ids']) && $saveData['attr_ids']) {
            $saveData['attr_ids'] = unserialize($saveData['attr_ids']);
        }
        if (!$saveData) {
            $errors = Yii::$service->helper->errors->get(', ');
            return [
                'code'    => 400,
                'message' => 'update product attr group fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        }
        return [
            'code'    => 200,
            'message' => 'update product attr group success',
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
        $name = Yii::$app->request->post('name');
        $status = Yii::$app->request->post('status');
        $attr_ids = Yii::$app->request->post('attr_ids');
        
        $param = [
            'name'           => $name,
            'status'             => $status,
            'attr_ids'     => $attr_ids,
        ];
       
        $saveData = Yii::$service->product->attrGroup->save($param);
        if (!$saveData) {
            $errors = Yii::$service->helper->errors->get(', ');
            return [
                'code'    => 400,
                'message' => 'add product attr group fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        }
        //$saveData['attr_ids'] = unserialize($saveData['attr_ids']);
        if (isset($saveData['attr_ids']) && $saveData['attr_ids']) {
            $saveData['attr_ids'] = unserialize($saveData['attr_ids']);
        }
        return [
            'code'    => 200,
            'message' => 'add product attr group success',
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
        $name = Yii::$app->request->post('name');
        $status = Yii::$app->request->post('status');
        $attr_ids = Yii::$app->request->post('attr_ids');
        if (!$id) {
            return [
                'code'    => 400,
                'message' => 'update product attr group id can not empty',
                'data'    => [
                    'error' => 'update product attr group id can not empty',
                ],
            ];
        }
        $primaryKey = Yii::$service->product->attrGroup->getPrimaryKey();
        $param = [
            $primaryKey           => $id,
            'name'             => $name,
            'status'     => $status,
            'attr_ids'  => $attr_ids,
        ];
        $saveData = Yii::$service->product->attrGroup->save($param);
        if (isset($saveData['attr_ids']) && $saveData['attr_ids']) {
            $saveData['attr_ids'] = unserialize($saveData['attr_ids']);
        }
        if (!$saveData) {
            $errors = Yii::$service->helper->errors->get(', ');
            return [
                'code'    => 400,
                'message' => 'update product attr group fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        }
        return [
            'code'    => 200,
            'message' => 'update product attr group success',
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
