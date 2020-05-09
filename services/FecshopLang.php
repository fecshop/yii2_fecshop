<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;

/**
 * language services 语言部分
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Fecshoplang extends Service
{
    /**
     * all languages.
     */
    public $allLangCode;
    public $adminLangCode;
    /**
     * default language.
     */
    public $defaultLangCode;

    protected $_allLangCode;
    protected $_adminLangCode;
    
    public function init()
    {
        parent::init();
        // init default lang
        $this->defaultLangCode = Yii::$app->store->get('base_info', 'default_lang');
        // init all langs
        $mutil_langs = Yii::$app->store->get('mutil_lang');
        if (is_array($mutil_langs)) {
            foreach ($mutil_langs as $lang) {
                $lang_name = $lang['lang_name'];
                $lang_code = $lang['lang_code'];
                $this->allLangCode[$lang_name] = ['code' => $lang_code];
            }
        }
    }
    /**
     * @param $attrName|string  , attr name ,like  : tilte , description ,name etc..
     * @param $langCode|string , language 2 code, like :en ,fr ,es,
     *  get language child language attr, like: title_fr
     */
    public function getLangAttrName($attrName, $langCode)
    {
        return $attrName.'_'.$langCode;
    }

    /**
     * @param $attrName | String 属性名称
     * 得到默认语言的属性名称
     */
    public function getDefaultLangAttrName($attrName)
    {
        return $attrName.'_'.$this->defaultLangCode;
    }

    public function getAdminLangCode()
    {
        if (!$this->_adminLangCode) {
            if (empty($this->adminLangCode) || !is_array($this->adminLangCode)) {
                
                return [];
            }
            if ($this->defaultLangCode) {
                $this->_adminLangCode[] = $this->defaultLangCode;
                foreach ($this->adminLangCode as $codeInfo) {
                    $code = $codeInfo['code'];
                    if ($this->defaultLangCode != $code) {
                        $this->_adminLangCode[] = $code;
                    }
                }
            }
        }

        return $this->_adminLangCode;
    }

    /**
     * 得到所有的语言简码，譬如：en,es,fr,zh,de等
     */
    public function getAllLangCode()
    {
        if (!$this->_allLangCode) {
            if (empty($this->allLangCode) || !is_array($this->allLangCode)) {
                
                return [];
            }
            if ($this->defaultLangCode) {
                $this->_allLangCode[] = $this->defaultLangCode;
                foreach ($this->allLangCode as $codeInfo) {
                    $code = $codeInfo['code'];
                    if ($this->defaultLangCode != $code) {
                        $this->_allLangCode[] = $code;
                    }
                }
            }
        }

        return $this->_allLangCode;
    }
    
    public function getAllLangName()
    {
        $arr = [];
        if (empty($this->allLangCode) || !is_array($this->allLangCode)) {
            
            return [];
        }
        foreach ($this->allLangCode as  $langName =>$codeInfo) {
            $arr[] = $langName;
        }
        
        return $arr;
    }
    
    /**
     * @param $attrVal|array , language attr array , like   ['title_en' => 'xxxx','title_fr' => 'yyyy']
     * @param $attrName|String, attribute name ,like: title ,description.
     * get default language attr value.
     * example getDefaultLangAttrVal(['title_en'=>'xx','title_fr'=>'yy'],'title');
     * 得到属性默认语言对应的值。上面是title属性默认语言的值。
     */
    public function getDefaultLangAttrVal($attrVal, $attrName)
    {
        $defaultLangAttrName = $this->getDefaultLangAttrName($attrName);
        if (isset($attrVal[$defaultLangAttrName]) && !empty($attrVal[$defaultLangAttrName])) {
            
            return $attrVal[$defaultLangAttrName];
        }

        return '';
    }

    /**
     * @param $attrVal|array , language attr array , like   ['title_en' => 'xxxx','title_fr' => 'yyyy']
     * @param $attrName|String, attribute name ,like: title ,description.
     * @param $lang | String , language.
     * if  object or array  attribute is a language attribute, you can get current
     * language value by this function.
     * if lang attribute in current store language is empty , default language attribute will be return.
     * if attribute in default language value is empty, '' will be return.
     * example getLangAttrVal(['title_en'=>'xx','title_fr'=>'yy'],'title','fr');
     */
    public function getLangAttrVal($attrVal, $attrName, $langCode)
    {
        $langAttrName = $this->getLangAttrName($attrName, $langCode);
        if (isset($attrVal[$langAttrName]) && !empty($attrVal[$langAttrName])) {
            
            return $attrVal[$langAttrName];
        } else {
            $defaultLangAttrName = $this->getDefaultLangAttrName($attrName);
            if (isset($attrVal[$defaultLangAttrName]) && !empty($attrVal[$defaultLangAttrName])) {
                
                return $attrVal[$defaultLangAttrName];
            }
        }

        return '';
    }

    /**
     * @param $attrVal|string  属性对应的值 一般是一个数组，里面包含各个语言的的属性值
     * @param $attrName|string 属性名称，譬如:  name   title
     * @return 当前store 语言对应的值。
     */
    /*
    public function getCurrentStoreAttrVal($attrVal,$attrName){
        $langCode = Yii::$service->store->currentLangCode ;
        if($langCode){
            return $this->getLangAttrVal($attrVal,$attrName,$langCode);
        }
    }
    */

    /**
     * @param $language|string  like: en_US ,fr_FR,zh_CN
     * @return string , like  en ,fr ,es ,  if  $language is not exist in $this->allLangCode
     *                empty will be return.
     */
    public function getLangCodeByLanguage($language)
    {
        if (isset($this->allLangCode[$language])) {
            
            return $this->allLangCode[$language]['code'];
        } else {
            
            return '';
        }
    }
    /**
     * @return  array , like
     *  ['en' => 'en_US' , 'zh' => 'zh_CN']
     */
    public function getLangAndCodeArr(){
        $arr = [];
        if (is_array($this->allLangCode)) {
            foreach ($this->allLangCode as $lang => $one) {
                if (isset($one['code']) && $one['code'] && $lang) {
                    $arr[$one['code']] = $lang;
                }
            }
        }

        return $arr;
    }
}
