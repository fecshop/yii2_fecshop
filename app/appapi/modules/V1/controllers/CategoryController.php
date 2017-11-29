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
class CategoryController extends AppapiTokenController
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
        $data  = Yii::$service->category->coll($filter);
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
                'message' => 'fetch category success',
                'data'    => $coll,
            ];
        } else {
            return [
                'code'    => 400,
                'message' => 'fetch category fail , exceeded the maximum number of pages',
                'data'    => [],
            ];
        }
    }
    /**
     * Get One Api：根据url_key 和 id 得到Category 列表的api
     */
    public function actionFetchone(){
        $url_key       = Yii::$app->request->get('url_key');
        $primaryKeyVal = Yii::$app->request->get('id');
        $data          = [];
        if ( !$url_key && !$primaryKeyVal ) {
            
            return [
                'code'    => 400,
                'message' => 'request param [url_key,id] can not all empty',
                'data'    => [],
            ];
        } else if ($primaryKeyVal) {
           
            $category = Yii::$service->category->getByPrimaryKey($primaryKeyVal);
            if(isset($category['url_key']) && $category['url_key']){
                $data = $category;
            }
        } else if ($url_key) {
            $category = Yii::$service->category->getByUrlKey($url_key);
            if(isset($category['url_key']) && $category['url_key']){
                $data = $category;
            }
        }
        if (empty($data)) {
            
            return [
                'code'    => 400,
                'message' => 'can not find category by id or url_key',
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
                'message' => 'fetch category success',
                'data'    => $data,
            ];
        } 
    }
    /**
     * Add One Api：新增一条记录的api
     */
    public function actionAddone(){
        //var_dump(Yii::$app->request->post());exit;
        // 必填
        $parent_id            = Yii::$app->request->post('parent_id');
        // 必填
        $name            = Yii::$app->request->post('name');
        // 选填
        $status            = Yii::$app->request->post('status');
        // 选填
        $url_key            = Yii::$app->request->post('url_key');
        // 选填
        $description            = Yii::$app->request->post('description');
        // 选填
        $menu_custom            = Yii::$app->request->post('menu_custom');
        // 选填
        $filter_product_attr_selected   = Yii::$app->request->post('filter_product_attr_selected');
        // 选填
        $filter_product_attr_unselected            = Yii::$app->request->post('filter_product_attr_unselected');
        // 选填
        $thumbnail_image            = Yii::$app->request->post('thumbnail_image');
        // 选填
        $image            = Yii::$app->request->post('image');
        // 选填 多语言
        $title              = Yii::$app->request->post('title');
        // 选填 多语言
        $meta_keywords      = Yii::$app->request->post('meta_keywords');
        // 选填 多语言
        $meta_description   = Yii::$app->request->post('meta_description');
       
        if (!$parent_id && $parent_id !== '0') {
            $error[] = '[parent_id] can not empty';
        }
        if (!$name) {
            $error[] = '[name] can not empty';
        }
        if(!Yii::$service->fecshoplang->getDefaultLangAttrVal($name, 'name')) {
            $defaultLangAttrName = Yii::$service->fecshoplang->getDefaultLangAttrName('name');
            $error[] = '[name.'.$defaultLangAttrName.'] can not empty';
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
        $identity = Yii::$app->user->identity;
        $param = [
            'parent_id'                     => $parent_id,
            'name'                          => $name,
            'status'                        => $status,
            'url_key'                       => $url_key,
            'description'                   => $description,
            'menu_custom'                   => $menu_custom,
            'filter_product_attr_selected'  => $filter_product_attr_selected,
            'filter_product_attr_unselected'=> $filter_product_attr_unselected,
            'thumbnail_image'               => $thumbnail_image,
            'image'                         => $image,
            'title'                         => $title,
            'meta_keywords'                 => $meta_keywords,
            'meta_description'              => $meta_description,
        ];
        $originUrlKey   = 'catalog/category/index';
        $saveData       = Yii::$service->category->save($param, $originUrlKey);
        $errors         = Yii::$service->helper->errors->get();
        if (!$errors) {
            $saveData = $saveData->attributes;
            if(isset($saveData['_id'])){
                $saveData['id'] = (string)$saveData['_id'];
                unset($saveData['_id']);
            }
            return [
                'code'    => 200,
                'message' => 'add article success',
                'data'    => [
                    'addData' => $saveData,
                ]
            ];
        } else {
            return [
                'code'    => 400,
                'message' => 'save category fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        }
    }
    /**
     * Update One Api：更新一条记录的api
     */
    public function actionUpdateone(){
        //var_dump(Yii::$app->request->post());exit;
        // 必填
        $id                 = Yii::$app->request->post('id');
        // 必填
        $parent_id          = Yii::$app->request->post('parent_id');
        // 必填
        $name               = Yii::$app->request->post('name');
        // 选填
        $menu_show             = Yii::$app->request->post('menu_show');
        
        $status             = Yii::$app->request->post('status');
        // 选填
        $url_key            = Yii::$app->request->post('url_key');
        // 选填
        $description        = Yii::$app->request->post('description');
        // 选填
        $menu_custom        = Yii::$app->request->post('menu_custom');
        // 选填
        $filter_product_attr_selected   = Yii::$app->request->post('filter_product_attr_selected');
        // 选填
        $filter_product_attr_unselected = Yii::$app->request->post('filter_product_attr_unselected');
        // 选填
        $thumbnail_image                = Yii::$app->request->post('thumbnail_image');
        // 选填
        $image              = Yii::$app->request->post('image');
        // 选填 多语言
        $title              = Yii::$app->request->post('title');
        // 选填 多语言
        $meta_keywords      = Yii::$app->request->post('meta_keywords');
        // 选填 多语言
        $meta_description   = Yii::$app->request->post('meta_description');
        if (!$id) {
            $error[] = '[id] can not empty';
        }
        //if (!$name) {
        //    $error[] = '[name] can not empty';
        //}
        //if (!$parent_id && $parent_id !== '0') {
        //    $error[] = '[parent_id] can not empty';
        //}
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
        $identity = Yii::$app->user->identity;
        $param['parent_id'] = $parent_id;
        $param['name']      = $name;
        $url_key            ? ($param['url_key'] = $url_key)                    : '';
        $title              ? ($param['title'] = $title)                        : '';
        $meta_keywords      ? ($param['meta_keywords'] = $meta_keywords)        : '';
        $meta_description   ? ($param['meta_description'] = $meta_description)  : '';
        $status             ? ($param['status'] = $status)                      : '';
        $menu_show          ? ($param['menu_show'] = $menu_show)                      : '';
        
        $description                ? ($param['description'] = $description)                    : '';
        $menu_custom                ? ($param['menu_custom'] = $menu_custom)                    : '';
        $filter_product_attr_selected               ? ($param['filter_product_attr_selected'] = $filter_product_attr_selected)     : '';
        $filter_product_attr_unselected             ? ($param['filter_product_attr_unselected'] = $filter_product_attr_unselected) : '';
        $thumbnail_image            ? ($param['thumbnail_image'] = $thumbnail_image)            : '';
        $image                      ? ($param['image'] = $image)               : '';
        $originUrlKey       = 'catalog/category/index';
        $primaryKey         = Yii::$service->category->getPrimaryKey();
        $param[$primaryKey] = $id;
        $saveData = Yii::$service->category->save($param, $originUrlKey);
        return [
            'code'    => 200,
            'message' => 'update category success',
            'data'    => [
                'updateData' => $saveData,
            ]
        ];
    }
    /**
     * Delete One Api：删除一条记录的api
     */
    public function actionDeleteone(){
        $ids = Yii::$app->request->post('ids');
        Yii::$service->category->remove($ids);
        $errors = Yii::$service->helper->errors->get();
        if (!empty($errors)) {
            return [
                'code'    => 400,
                'message' => 'remove Category by ids fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        } else {
            return [
                'code'    => 200,
                'message' => 'remove Category by ids success',
                'data'    => []
            ];
        }
    }
    
    
    /**
     * 用于测试的action
     */
    public function actionTest()
    {
        $post = Yii::$app->request->post();
        return $post;
        //var_dump();exit;
        //var_dump(get_class(Yii::$service->cms->article->getByPrimaryKey('')));
    }
    
}
