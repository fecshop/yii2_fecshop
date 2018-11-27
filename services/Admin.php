<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use yii\base\InvalidConfigException;
use Yii;
/**
 * Cms services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Admin extends Service
{
    protected $_currentLangCode;
    const ADMIN_CURRENT_LANG_CODE = 'admin_current_lang_code';

    /**
     * @param $app
     * 在Yii2框架的初始化过程过程过程中执行的函数，将被组件（Yii2 components）Yii::$app->store->bootstrap() 调用
     *  Yii::$app->store 组件, 就是文件：fecshop\components\Store.php
     * @throws InvalidConfigException
     */
    public function bootstrap($app){
        $this->initLangCode();
    }

    /**
     * 初始化后台多语言
     * @throws InvalidConfigException
     */
    protected function initLangCode(){
        if (!$this->_currentLangCode) {
            $currentLangCode = Yii::$service->session->get(self::ADMIN_CURRENT_LANG_CODE);
            if (!$currentLangCode) {
                $currentLangCode = Yii::$service->fecshoplang->defaultLangCode;
            }
            if (!$currentLangCode) {
                throw new InvalidConfigException('default lang code must config');
            }
            if ($this->setTranslateLang($currentLangCode)) {
                $this->_currentLangCode = $currentLangCode;
            } else {
                throw new InvalidConfigException('lang code: '.$currentLangCode.' can not find in fecshoplang service config, you should add this language config');
            }
        }
    }

    public function getCurrentLangCode(){
        if (!$this->_currentLangCode) {
            $this->initLangCode();
        }
        return $this->_currentLangCode;
    }
    
    public function setCurrentLangCode($code){
        $allLangCode = Yii::$service->fecshoplang->getAllLangCode();
        if (in_array($code, $allLangCode)) {
            Yii::$service->session->set(self::ADMIN_CURRENT_LANG_CODE, $code);
            $this->_currentLangCode = $code;
            if ($this->setTranslateLang($code)) {

                return true;
            }
        }
        
        return false;
    }

    public function setTranslateLang($code){
        $langCodeArr = Yii::$service->fecshoplang->getLangAndCodeArr();
        if (isset($langCodeArr[$code]) && $langCodeArr[$code]) {
            Yii::$service->page->translate->setLanguage($langCodeArr[$code]);

            return true;
        }

        return false;
    }
    
    public function getLangArr(){
        $arr = [];
        $allLangCode = Yii::$service->fecshoplang->allLangCode;
        
        if (is_array($allLangCode)) {
            foreach ($allLangCode as $one) {
                $arr[$one['code']] = $one['name'];
            }
        }
        
        return $arr;
    }


    
}
