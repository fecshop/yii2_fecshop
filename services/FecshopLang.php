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

    /**
     * default language.
     */
    public $defaultLangCode;

    protected $_allLangCode;

    /**
     * @property $attrName|string  , attr name ,like  : tilte , description ,name etc..
     * @property $langCode|string , language 2 code, like :en ,fr ,es,
     *  get language child language attr, like: title_fr
     */
    protected function actionGetLangAttrName($attrName, $langCode)
    {
        return $attrName.'_'.$langCode;
    }

    /**
     * @property $attrName | String 属性名称
     * 得到默认语言的属性名称
     */
    protected function actionGetDefaultLangAttrName($attrName)
    {
        return $attrName.'_'.$this->defaultLangCode;
    }

    /**
     * 得到所有的语言简码，譬如：en,es,fr,zh,de等
     */
    protected function actionGetAllLangCode()
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

    /**
     * @property $attrVal|array , language attr array , like   ['title_en' => 'xxxx','title_fr' => 'yyyy']
     * @property $attrName|String, attribute name ,like: title ,description.
     * get default language attr value.
     * example getDefaultLangAttrVal(['title_en'=>'xx','title_fr'=>'yy'],'title');
     * 得到属性默认语言对应的值。上面是title属性默认语言的值。
     */
    protected function actionGetDefaultLangAttrVal($attrVal, $attrName)
    {
        $defaultLangAttrName = $this->getDefaultLangAttrName($attrName);
        if (isset($attrVal[$defaultLangAttrName]) && !empty($attrVal[$defaultLangAttrName])) {
            return $attrVal[$defaultLangAttrName];
        }

        return '';
    }

    /**
     * @property $attrVal|array , language attr array , like   ['title_en' => 'xxxx','title_fr' => 'yyyy']
     * @property $attrName|String, attribute name ,like: title ,description.
     * @property $lang | String , language.
     * if  object or array  attribute is a language attribute, you can get current
     * language value by this function.
     * if lang attribute in current store language is empty , default language attribute will be return.
     * if attribute in default language value is empty, '' will be return.
     * example getLangAttrVal(['title_en'=>'xx','title_fr'=>'yy'],'title','fr');
     */
    protected function actionGetLangAttrVal($attrVal, $attrName, $langCode)
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
     * @property $attrVal|string  属性对应的值 一般是一个数组，里面包含各个语言的的属性值
     * @property $attrName|string 属性名称，譬如:  name   title
     * @return 当前store 语言对应的值。
     */
    /*
    protected function actionGetCurrentStoreAttrVal($attrVal,$attrName){
        $langCode = Yii::$service->store->currentLangCode ;
        if($langCode){
            return $this->getLangAttrVal($attrVal,$attrName,$langCode);
        }
    }
    */

    /**
     * @property $language|string  like: en_US ,fr_FR,zh_CN
     * @return string , like  en ,fr ,es ,  if  $language is not exist in $this->allLangCode
     *                empty will be return.
     */
    protected function actionGetLangCodeByLanguage($language)
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
