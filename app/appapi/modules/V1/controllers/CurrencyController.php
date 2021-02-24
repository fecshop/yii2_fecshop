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
class CurrencyController extends AppapiTokenController
{
    
    /**
     * Get Lsit Api：得到article 列表的api
     */
    public function actionIndex()
    {
        $currencys = Yii::$service->page->currency->getCurrencys();
        
        return [
            'code'    => 200,
            'message' => 'fetch all currency success',
            'data'    => $currencys,
        ];
    }
    
    
    
    public function actionSetall()
    {
        $currencys = Yii::$app->request->post('currencys');
        if (!is_array($currencys) || empty($currencys)) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => 'currencys can not empty',
            ]);
            exit;
        }
        $hasDefaultCurrency = false;
        $defaultCurrency = false;
        foreach ($currencys as $k=>$currencyOne) {
            
            if ($currencyOne['is_default']) {
                if ($hasDefaultCurrency) {
                    // 配置中含有多个default lang，
                    echo  json_encode([
                        'statusCode' => '300',
                        'message'    => 'you can only set one currency is_default = true , other must set false ',
                    ]);
                    exit;
                }
                $hasDefaultCurrency = true;
                $defaultCurrency = $currencyOne['currency_code'];
            }
            unset($currencys[$k]['is_default']);
        }
        if (!$hasDefaultCurrency) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => 'you do not set default currency',
            ]);
            exit;
        }
        // set default lang;
        $this->setDefaultCurrency($defaultCurrency);
        $saveData = [
            'key' => 'currency',
            'value' => $currencys,
        ];
        
        Yii::$service->storeBaseConfig->saveConfig($saveData);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            
            return [
                'code'    => 200,
                'message' => 'set all currency success',
                'data'    => true,
            ];
        } 
        return [
            'code'    => 300,
            'message' => 'set all currency fail',
            'data'    => [
                'errors' => $errors,
            ],
        ];
    }
    /**
     * @param $defaultCurrency | string,  语言code
     */
    public function setDefaultCurrency($defaultCurrency)
    {
        $baseConfig = Yii::$service->storeBaseConfig->getByKey([
            'key' => 'base_info',
        ]);
        $saveData = ['key' => 'base_info'];
        if ($baseConfig['value']) {
            $saveData['value'] = unserialize($baseConfig['value']);
        }
        $saveData['value']['default_currency'] = $defaultCurrency;
        $saveData['value']['base_currency'] = $defaultCurrency;
        //var_dump($saveData['value']['default_lang'] );exit;
        Yii::$service->storeBaseConfig->saveConfig($saveData);
    }
}
