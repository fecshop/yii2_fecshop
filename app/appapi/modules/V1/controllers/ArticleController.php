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
class ArticleController extends AppapiTokenController
{
    public $numPerPage = 5;
    
    /**
     * Get Lsit Api：得到article 列表的api
     */
    public function actionList(){
        
        $page = Yii::$app->request->get('page');
        $page = $page ? $page : 1;
        $filter = [
            'numPerPage'    => $this->numPerPage,
            'pageNum'       => $page,
            'asArray'       => true,
        ];
        $data  = Yii::$service->cms->article->coll($filter);
        $coll  = $data['coll'];
        $count = $data['count']; 
        foreach ($coll as $k => $one) {
            if (isset($one['_id'])) {
                $coll[$k]['id'] = (string)$one['_id'];
                unset($coll[$k]['_id']);
            }
        }
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
                'message' => 'fetch article success',
                'data'    => $coll,
            ];
        } else {
            return [
                'code'    => 400,
                'message' => 'fetch article fail , exceeded the maximum number of pages',
                'data'    => [],
            ];
        }
    }
    /**
     * Get One Api：根据url_key 和 id 得到article 列表的api
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
            $article = Yii::$service->cms->article->getByPrimaryKey($primaryKeyVal);
            if(isset($article['url_key']) && $article['url_key']){
                $data = $article;
            }
        } else if ($url_key) {
            $article = Yii::$service->cms->article->getByUrlKey($url_key);
            if(isset($article['url_key']) && $article['url_key']){
                $data = $article;
            }
        }
        if (empty($data)) {
            
            return [
                'code'    => 400,
                'message' => 'can not find article by id or url_key',
                'data'    => [],
            ];
        } else {
            
            return [
                'code'    => 200,
                'message' => 'fetch article success',
                'data'    => $data,
            ];
        } 
    }
    /**
     * Add One Api：新增一条记录的api
     */
    public function actionAddone(){
        //var_dump(Yii::$app->request->post());exit;
        // 选填
        $url_key            = Yii::$app->request->post('url_key');
        // 必填 多语言
        $title              = Yii::$app->request->post('title');
        // 选填 多语言
        $meta_keywords      = Yii::$app->request->post('meta_keywords');
        // 选填 多语言
        $meta_description   = Yii::$app->request->post('meta_description');
        // 必填 多语言
        $content            = Yii::$app->request->post('content');
        $status             = Yii::$app->request->post('status');
        if (!$title) {
            $error[] = '[title] can not empty';
        }
        if (!$content) {
            $error[] = '[content] can not empty';
        }
        if(!Yii::$service->fecshoplang->getDefaultLangAttrVal($title, 'title')) {
            $defaultLangAttrName = Yii::$service->fecshoplang->getDefaultLangAttrName('title');
            $error[] = '[title.'.$defaultLangAttrName.'] can not empty';
        }
        if (!Yii::$service->fecshoplang->getDefaultLangAttrVal($content, 'content')) {
            $defaultLangAttrName = Yii::$service->fecshoplang->getDefaultLangAttrName('content');
            $error[] = '[content.'.$defaultLangAttrName.'] can not empty';
        }
        if ($meta_keywords && !is_array($meta_keywords)) {
            $error[] = '[meta_keywords] must be array';
        }
        if ($meta_description && !is_array($meta_description)) {
            $error[] = '[meta_description] must be array';
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
            'url_key'           => $url_key,
            'title'             => $title,
            'meta_keywords'     => $meta_keywords,
            'meta_description'  => $meta_description,
            'content'           => $content,
            'status'            => $status,
        ];
        $saveData = Yii::$service->cms->article->save($param, 'cms/article/index');
        return [
            'code'    => 200,
            'message' => 'add article success',
            'data'    => [
                'addData' => $saveData,
            ]
        ];
    }
    /**
     * Update One Api：更新一条记录的api
     */
    public function actionUpdateone(){
        $id            = Yii::$app->request->post('id');
        // 选填
        $url_key            = Yii::$app->request->post('url_key');
        // 选填 多语言
        $title              = Yii::$app->request->post('title');
        // 选填 多语言
        $meta_keywords      = Yii::$app->request->post('meta_keywords');
        // 选填 多语言
        $meta_description   = Yii::$app->request->post('meta_description');
        // 选填 多语言
        $content            = Yii::$app->request->post('content');
        $status             = Yii::$app->request->post('status');
        if (!$id) {
            $error[] = '[id] can not empty';
        }
        if ($title && !Yii::$service->fecshoplang->getDefaultLangAttrVal($title, 'title')) {
            $defaultLangAttrName = Yii::$service->fecshoplang->getDefaultLangAttrName('title');
            $error[] = '[title.'.$defaultLangAttrName.'] can not empty';
        }
        if ($content && !Yii::$service->fecshoplang->getDefaultLangAttrVal($content, 'content')) {
            $defaultLangAttrName = Yii::$service->fecshoplang->getDefaultLangAttrName('content');
            $error[] = '[content.'.$defaultLangAttrName.'] can not empty';
        }
        if ($meta_keywords && !is_array($meta_keywords)) {
            $error[] = '[meta_keywords] must be array';
        }
        if ($meta_description && !is_array($meta_description)) {
            $error[] = '[meta_description] must be array';
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
        $url_key            ? ($param['url_key'] = $url_key)                    : '';
        $title              ? ($param['title'] = $title)                        : '';
        $meta_keywords      ? ($param['meta_keywords'] = $meta_keywords)        : '';
        $meta_description   ? ($param['meta_description'] = $meta_description)  : '';
        $content            ? ($param['content'] = $content)                    : '';
        $status             ? ($param['status'] = $status)                      : '';
        $primaryKey         = Yii::$service->cms->article->getPrimaryKey();
        $param[$primaryKey] = $id;
        $saveData = Yii::$service->cms->article->save($param, 'cms/article/index');
        return [
            'code'    => 200,
            'message' => 'add article success',
            'data'    => [
                'updateData' => $saveData,
            ]
        ];
    }
    /**
     * Delete One Api：删除一条记录的api
     */
    public function actionDeleteone(){
        $ids            = Yii::$app->request->post('ids');
        Yii::$service->cms->article->remove($ids);
        $errors = Yii::$service->helper->errors->get();
        if (!empty($errors)) {
            return [
                'code'    => 400,
                'message' => 'remove article by ids fail',
                'data'    => [
                    'error' => $errors,
                ],
            ];
        } else {
            return [
                'code'    => 200,
                'message' => 'delete article by ids success',
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
