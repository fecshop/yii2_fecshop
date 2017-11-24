<?php

namespace fecshop\app\appapi\modules\V1\controllers;

use fecshop\app\appapi\modules\AppapiTokenController;
use Yii;

class ArticleController extends AppapiTokenController
{
    public $numPerPage = 5;
    
    /**
     * 得到article 列表的api
     *
     */
    
    public function actionList(){
        
        $page = Yii::$app->request->get('page');
        $page = $page ? $page : 1;
        $filter = [
            'numPerPage'    => $this->numPerPage,
            'pageNum'       => $page,
            'asArray'       => true,
        ];
        $data = Yii::$service->cms->article->coll($filter);
        $coll = $data['coll'];
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
