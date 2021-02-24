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
class LanguagesController extends AppapiTokenController
{
    
    /**
     * Get Lsit Api：得到article 列表的api
     */
    public function actionIndex()
    {
        $langs = Yii::$service->fecshoplang->getAllLanguages();
        
        return [
            'code'    => 200,
            'message' => 'fetch all languages success',
            'data'    => $langs,
        ];
    }
    
    
    
    public function actionSetall()
    {
        $languages = Yii::$app->request->post('languages');
        if (!is_array($languages) || empty($languages)) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => 'languages can not empty',
            ]);
            exit;
        }
        $hasDefaultLang = false;
        $defaultLang = false;
        foreach ($languages as $k=>$languageOne) {
            $languages[$k]['search_engine'] = 'mysqlSearch';
            if ($languageOne['is_default']) {
                if ($hasDefaultLang) {
                    // 配置中含有多个default lang，
                    echo  json_encode([
                        'statusCode' => '300',
                        'message'    => 'you can only set one lang is_default = true , other must set false ',
                    ]);
                    exit;
                }
                $hasDefaultLang = true;
                $defaultLang = $languageOne['lang_code'];
            }
            unset($languages[$k]['is_default']);
        }
        if (!$hasDefaultLang) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => 'you do not set default language',
            ]);
            exit;
        }
        // set default lang;
        $this->setDefaultlang($defaultLang);
        $saveData = [
            'key' => 'mutil_lang',
            'value' => $languages,
        ];
        
        Yii::$service->storeBaseConfig->saveConfig($saveData);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            
            return [
                'code'    => 200,
                'message' => 'set all languages success',
                'data'    => true,
            ];
        } 
        return [
            'code'    => 300,
            'message' => 'set all languages fail',
            'data'    => [
                'errors' => $errors,
            ],
        ];
    }
    /**
     * @param $defaultLangCode | string,  语言code
     */
    public function setDefaultlang($defaultLangCode)
    {
        $baseConfig = Yii::$service->storeBaseConfig->getByKey([
            'key' => 'base_info',
        ]);
        $saveData = ['key' => 'base_info'];
        if ($baseConfig['value']) {
            $saveData['value'] = unserialize($baseConfig['value']);
        }
        $saveData['value']['default_lang'] = $defaultLangCode;
        //var_dump($saveData['value']['default_lang'] );exit;
        Yii::$service->storeBaseConfig->saveConfig($saveData);
    }
}
