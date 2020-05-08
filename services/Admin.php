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
 * Admin services.
 *
 * @property \fecshop\services\customer\UrlKey $urlKey
 * @property \fecshop\services\customer\RoleUrlKey $roleUrlKey
 * @property \fecshop\services\customer\Role $role
 * @property \fecshop\services\customer\Config $config
 * @property \fecshop\services\customer\UserRole $userRole
 * @property \fecshop\services\customer\SystemLog $systemLog
 * @property \fecshop\services\customer\Menu $menu
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Admin extends Service
{
    public $xhEditorUploadImgUrl = 'cms/xeditor/imageupload';
    public $xhEditorUploadImgForamt = 'jpg,jpeg,gif,png';

    public $xhEditorUploadFlashUrl = 'cms/xeditor/flashupload';
    public $xhEditorUploadFlashFormat = 'swf';

    public $xhEditorUploadLinkUrl = 'cms/xeditor/linkupload';
    public $xhEditorUploadLinkFormat = 'zip,rar,txt';

    public $xhEditorUploadMediaUrl = 'cms/xeditor/mediaupload';
    public $xhEditorUploadMediaFormat = 'avi';

    protected $_currentLangCode;
    const ADMIN_CURRENT_LANG_CODE = 'admin_current_lang_code';

    /**
     * @return string 得到编辑器上传图片upload url
     */
    public function getXhEditorUploadImgUrl(){
        
        return $this->xhEditorUploadImgUrl;
    }
    /**
     * @return string 得到编辑器上传图片允许的文件格式
     */
    public function getXhEditorUploadImgForamt(){
        
        return $this->xhEditorUploadImgForamt;
    }
    /**
     * @return string 得到编辑器上传Flash upload url
     */
    public function getXhEditorUploadFlashUrl(){
        
        return $this->xhEditorUploadFlashUrl;
    }
    /**
     * @return string 得到编辑器上传Flash允许的文件格式
     */
    public function getXhEditorUploadFlashFormat(){
        
        return $this->xhEditorUploadFlashFormat;
    }
    /**
     * @return string 得到编辑器上传Link upload url
     */
    public function getXhEditorUploadLinkUrl(){
        
        return $this->xhEditorUploadLinkUrl;
    }
    /**
     * @return string 得到编辑器上传Link允许的文件格式
     */
    public function getXhEditorUploadLinkFormat(){
        
        return $this->xhEditorUploadLinkFormat;
    }
    /**
     * @return string 得到编辑器上传Media upload url
     */
    public function getXhEditorUploadMediaUrl(){
        
        return $this->xhEditorUploadMediaUrl;
    }
    /**
     * @return string 得到编辑器上传Media允许的文件格式
     */
    public function getXhEditorUploadMediaFormat(){
        
        return $this->xhEditorUploadMediaFormat;
    }
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
        $adminLangCode = Yii::$service->fecshoplang->getAdminLangCode();
        if (in_array($code, $adminLangCode)) {
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
        $adminLangCode = Yii::$service->fecshoplang->adminLangCode;
        
        if (is_array($adminLangCode)) {
            foreach ($adminLangCode as $one) {
                $arr[$one['code']] = $one['name'];
            }
        }
        
        return $arr;
    }

    
}
