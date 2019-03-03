<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appserver\modules\General\controllers;

use fecshop\app\appserver\modules\AppserverController;
use Yii;
 
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0 
 */
class BaseController extends AppserverController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $cacheName = 'appserver_left_menu';
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
                    'only' => ['menu'],
                ];
            }
            if (is_array($get) && !empty($get) && is_array($cacheUrlParam)) {
                foreach ($get as $k=>$v) {
                    if (in_array($k, $cacheUrlParam)) {
                        if ($k != 'p' && $v != 1) {
                            $get_str .= $k.'_'.$v.'_';
                        }
                    }
                }
            }
            $currentLangCode = Yii::$service->store->currentLangCode;
            $behaviors[] =  [
                'enabled' => true,
                'class' => 'yii\filters\PageCache',
                'only' => ['menu'],
                'duration' => $timeout,
                'variations' => [
                    $currentLangCode,'F-E-C-S-h-o-p'
                ],
            ];
        }
        return $behaviors;
    }

    public function actionMenu()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $arr = [];
        $displayHome = Yii::$service->page->menu->displayHome;
        if($displayHome['enable']){
            $home = $displayHome['display'] ? $displayHome['display'] : 'Home';
            $home = Yii::$service->page->translate->__($home);
            $arr['home'] = [
                '_id'   => 'home',
                'level' => 1,
                'name'  => $home,
                'url'   => '/'
            ];
        }
        $currentLangCode = Yii::$service->store->currentLangCode;
        $treeArr = Yii::$service->category->getTreeArr('',$currentLangCode,true);
        if (is_array($treeArr)) {
            foreach ($treeArr as $k=>$v) {
                $arr[$k] = $v ;
            }
        }
        return $arr ;
    }
    // 语言
    public function actionLang()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $langs = Yii::$service->store->serverLangs;
        $currentLangCode = Yii::$service->store->currentLangCode;
        
        return [
            'langList' => $langs,
            'currentLang' => $currentLangCode
        ];
    }
    
    public function actionCurrency()
    {
        if(Yii::$app->request->getMethod() === 'OPTIONS'){
            return [];
        }
        $currencys = Yii::$service->page->currency->getCurrencys();
        $currentCurrencyCode = Yii::$service->page->currency->getCurrentCurrency();
        
        return [
            'currencyList' => $currencys,
            'currentCurrency' => $currentCurrencyCode
        ];
    }
    
}