<?php

namespace fecshop\app\apphtml5\modules\Cms\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

class ArticleController extends AppfrontController
{
    public function init()
    {
        parent::init();
    }

    // 网站信息管理
    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionChangecurrency()
    {
        $currency = \fec\helpers\CRequest::param('currency');
        Yii::$service->page->currency->setCurrentCurrency($currency);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $primaryKey = Yii::$service->cms->article->getPrimaryKey();
        $article_id = Yii::$app->request->get($primaryKey);
        $cacheName = 'article';
        if (Yii::$service->cache->isEnable($cacheName)) {
            $timeout = Yii::$service->cache->timeout($cacheName);
            $disableUrlParam = Yii::$service->cache->disableUrlParam($cacheName);
            $cacheUrlParam = Yii::$service->cache->cacheUrlParam($cacheName);
            $get_str = '';
            $get = Yii::$app->request->get();
            // 存在无缓存参数，则关闭缓存
            if (isset($get[$disableUrlParam])) {
                $behaviors[] =  [
                    'enabled' => false,
                    'class' => 'yii\filters\PageCache',
                    'only' => ['index'],
                ];
                
                return $behaviors;
            }
            if (is_array($get) && !empty($get) && is_array($cacheUrlParam)) {
                foreach ($get as $k=>$v) {
                    if (in_array($k, $cacheUrlParam)) {
                        if ($k != 'p' || $v != 1) {
                            $get_str .= $k.'_'.$v.'_';
                        }
                    }
                }
            }
            $store = Yii::$service->store->currentStore;
            $currency = Yii::$service->page->currency->getCurrentCurrency();

            $behaviors[] =  [
                'enabled' => true,
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => $timeout,
                'variations' => [
                    $store, $currency, $get_str, $article_id,
                ],
                //'dependency' => [
                //	'class' => 'yii\caching\DbDependency',
                //	'sql' => 'SELECT COUNT(*) FROM post',
                //],
            ];
        }

        return $behaviors;
    }
}
