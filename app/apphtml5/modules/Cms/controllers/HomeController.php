<?php

namespace fecshop\app\apphtml5\modules\Cms\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

class HomeController extends AppfrontController
{
    public function init()
    {
        //echo 1222;exit;
        parent::init();
    }

    // 网站信息管理
    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function behaviors()
    {
        $cacheName = 'home';
        if (Yii::$service->cache->isEnable($cacheName)) {
            $timeout = Yii::$service->cache->timeout($cacheName);
            $disableUrlParam = Yii::$service->cache->timeout($cacheName);
            $get = Yii::$app->request->get();
            // 存在无缓存参数，则关闭缓存
            if (isset($get[$disableUrlParam])) {
                return [
                    [
                        'enabled' => false,
                        'class' => 'yii\filters\PageCache',
                        'only' => ['index'],

                    ],
                ];
            }
            $store = Yii::$service->store->currentStore;
            $currency = Yii::$service->page->currency->getCurrentCurrency();

            return [
                [
                    'enabled' => true,
                    'class' => 'yii\filters\PageCache',
                    'only' => ['index'],
                    'duration' => $timeout,
                    'variations' => [
                        $store, $currency,
                    ],
                ],
            ];
        }

        return [];
    }

    public function actionChangecurrency()
    {
        $currency = \fec\helpers\CRequest::param('currency');
        Yii::$service->page->currency->setCurrentCurrency($currency);
    }
}
