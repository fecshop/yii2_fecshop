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
    
    public function actionMenu(){
        $arr = Yii::$service->category->getTreeArr('','',true);
        return $arr ;
    }
    // 语言
    public function actionLang(){
        
        $langs = Yii::$service->store->serverLangs;
        $currentLangCode = Yii::$service->store->currentLangCode;
        foreach($langs as $k => $one){
            $code = $one['code'];
            if($currentLangCode == $code){
                $langs[$k]['selected'] = true;
            }else{
                $langs[$k]['selected'] = false;
            }
        }
        return $langs ;
    }
    
    public function actionCurrency(){
        $currencys = Yii::$service->page->currency->getCurrencys();
        $currentCurrencyCode = Yii::$service->page->currency->getCurrentCurrency();
        foreach($currencys as $k => $one){
            $code = $one['code'];
            if($currentCurrencyCode == $code){
                $currencys[$k]['selected'] = true;
            }else{
                $currencys[$k]['selected'] = false;
            }
        }
        return $currencys;
    }
    
}