<?php

namespace fecshop\app\appserver\modules\Cms\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;

class ArticleController extends AppserverController
{
    
    protected $_artile;
    protected $_title;

    
    public function init()
    {
        parent::init();
    }

    // 网站信息管理
    public function actionIndex()
    {
        $url_key = Yii::$app->request->get('url_key');
        $article = Yii::$service->cms->article->getByUrlKey($url_key);
        if ($article) {
            
            $data = [
                'content' => Yii::$service->store->getStoreAttrVal($article['content'], 'content'),
                'created_at' => $article['created_at'],
                'title' => Yii::$service->store->getStoreAttrVal($article['title'], 'title'),
            ];
            
            $code = Yii::$service->helper->appserver->status_success;
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
        } else {
            $code = Yii::$service->helper->appserver->cms_article_not_exist;
            $data = [];
            $responseData = Yii::$service->helper->appserver->getResponseData($code, $data);
            
            return $responseData;
            
        }
        
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
            $langCode = Yii::$service->store->currentLangCode;
            $behaviors[] =  [
                'enabled' => true,
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => $timeout,
                'variations' => [
                    $store, $currency, $get_str, $article_id,$langCode
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
