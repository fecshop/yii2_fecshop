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
    
    public function getCurrentLangCode(){
        if (!$this->_currentLangCode) {
            $currentLangCode = Yii::$service->session->get(self::ADMIN_CURRENT_LANG_CODE);
            if (!$currentLangCode) {
                $currentLangCode = Yii::$service->fecshoplang->defaultLangCode;
            } 
            if (!$currentLangCode) {
                throw new InvalidConfigException('default lang code must config');
            }
            $this->_currentLangCode = $currentLangCode;
        }
        
        return $this->_currentLangCode;
    }
    
    public function setCurrentLangCode($code){
        $allLangCode = Yii::$service->fecshoplang->getAllLangCode();
        if (in_array($code, $allLangCode)) {
            Yii::$service->session->set(self::ADMIN_CURRENT_LANG_CODE, $code);
            
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
