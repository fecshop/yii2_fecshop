<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\apphtml5\modules\Catalog\controllers;

use fecshop\app\apphtml5\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CategoryController extends AppfrontController
{
    public function init()
    {
        parent::init();
        Yii::$service->page->theme->layoutFile = 'category_view.php';
    }

    // 分类页面。
    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();
        if(is_array($data)){
            return $this->render($this->action->id, $data);
        }
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $primaryKey = Yii::$service->category->getPrimaryKey();
        $category_id = Yii::$app->request->get($primaryKey);
        $cacheName = 'category';
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
                    $store, $currency, $get_str, $category_id,
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
