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
    
    public function actionMenu()
    {
        $arr = [];
        $displayHome = Yii::$service->page->menu->displayHome;
        if($displayHome['enable']){
            $home = $displayHome['display'] ? $displayHome['display'] : 'Home';
            $arr['home'] = [
                '_id'   => 'home',
                'level' => 1,
                'name'  => $home,
                'url'   => '/'
            ];
        }
        
        $treeArr = Yii::$service->category->getTreeArr('','',true);
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
        $langs = Yii::$service->store->serverLangs;
        $currentLangCode = Yii::$service->store->currentLangCode;
        
        return [
            'langList' => $langs,
            'currentLang' => $currentLangCode
        ];
    }
    
    public function actionCurrency()
    {
        $currencys = Yii::$service->page->currency->getCurrencys();
        $currentCurrencyCode = Yii::$service->page->currency->getCurrentCurrency();
        
        return [
            'currencyList' => $currencys,
            'currentCurrency' => $currentCurrencyCode
        ];
    }
    
}