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
use yii\base\InvalidValueException;

/**
 * store service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Store extends Service
{
    /**
     * init by config file.
     * all stores config . include : domain,language,theme,themePackage.
     */
    public $stores;

    public $store;

    /**
     * current store language,for example: en_US,fr_FR.
     */
    public $currentLang;

    /**
     * current store language name.
     */
    public $currentLangName;

    /**
     * current store theme package.
     */
    //public $currentThemePackage = 'default';
    /**
     * current store theme.
     */
    //public $currentTheme = 'default';

    /**
     * 当前store的key，也就是当前的store.
     */
    public $currentStore;

    /**
     * current language code example : fr  es cn ru.
     */
    public $currentLangCode;

    public $thirdLogin;

    //public $https;
    
    public $serverLangs;
    
    public $apiAppNameArr = ['appserver','appapi'];
    
    public function init()
    {
        parent::init();
        
        $this->initCurrentStoreConfig();
    }
    
    // 是否是api入口
    public function isApiStore()
    {
        $appName = Yii::$app->params['appName'];
        if ($appName && in_array($appName, $this->apiAppNameArr)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isAppserver()
    {
        $appServerArr = ['appserver'];
        $currentAppName = $this->getCurrentAppName();
        if (in_array($currentAppName, $appServerArr)) {
            return true;
        }
        
        return false;
    }

    /**
     * 得到当前入口的名字
     * @return mixed
     */
    public function getCurrentAppName()
    {
        return Yii::$service->helper->getAppName();
    }
    // 初始化store配置
    public function initCurrentStoreConfig()
    {
        $currentAppName = $this->getCurrentAppName();
        if ($this->isAppserver()) {
            return $this->initAppserverCurrentStoreConfig(); 
        }
        $coll = Yii::$service->storeDomain->getCollByAppName($currentAppName);
        if (is_array($coll)) {
            foreach ($coll as $one) {
                $storeKey = $one['key'];
                $lang = $one['lang'];
                $lang_name = $one['lang_name'];
                $local_theme_dir = $one['local_theme_dir'];
                $third_theme_dir = $one['third_theme_dir'] ? explode(',',$one['third_theme_dir']) : '';
                $currency = $one['currency'];
                $mobile_enable = $one['mobile_enable'] == 1 ? true : false;
                $mobile_condition = $one['mobile_condition'] ? explode(',',$one['mobile_condition']) : '';
                $mobile_redirect_domain = $one['mobile_redirect_domain'];
                $mobile_https_enable = $one['mobile_https_enable'] == 1 ? true : false;
                $mobile_type = $one['mobile_type'];
                $facebook_login_app_id = $one['facebook_login_app_id'];
                $facebook_login_app_secret = $one['facebook_login_app_secret'];
                $google_login_client_id = $one['google_login_client_id'];
                $google_login_client_secret = $one['google_login_client_secret'];
                $https_enable = $one['https_enable'] == 1 ? true : false;
                $sitemap_dir = $one['sitemap_dir'];
                // set config stores
                $this->stores[$storeKey] = [
                    'language'         => $lang,        // 语言简码需要在@common/config/fecshop_local_services/FecshopLang.php 中定义。
                    'languageName'     => $lang_name,    // 语言简码对应的文字名称，将会出现在语言切换列表中显示。
                    'localThemeDir'    => $local_theme_dir, // 设置当前store对应的模板路径。关于多模板的方面的知识，您可以参看fecshop多模板的知识。
                    'thirdThemeDir'    => $third_theme_dir, // 第三方模板路径，数组，可以多个路径
                    'currency'         => $currency, // 当前store的默认货币,这个货币简码，必须在货币配置中配置
                    // 用于sitemap生成中域名。
                    'https'            => $https_enable,
                    // sitemap的路径。
                    'sitemapDir' => $sitemap_dir,
                ];
                if ($mobile_condition && $mobile_redirect_domain && $mobile_type) {
                    $this->stores[$storeKey]['mobile'] = [
                        'enable'             => $mobile_enable,
                        'condition'         => $mobile_condition, // phone 代表手机，tablet代表平板，当都填写，代表手机和平板都会进行跳转
                        'redirectDomain'    => $mobile_redirect_domain,    // 如果是移动设备访问进行域名跳转，这里填写的值为store key
                        'https'               => $mobile_https_enable,  // 手机端url是否支持https,如果支持，设置https为true，如果不支持，设置为false
                        'type'                => $mobile_type,  //  填写值选择：[apphtml5, appserver]，如果是 apphtml5 ， 则表示跳转到html5入口，如果是appserver，则表示跳转到vue这种appserver对应的入口
                    ];
                }
                // 第三方账号登录配置
                if ($facebook_login_app_id && $facebook_login_app_secret)  {
                    $this->stores[$storeKey]['thirdLogin']['facebook'] = [
                        'facebook_app_id'     => $facebook_login_app_id,
                        'facebook_app_secret' => $facebook_login_app_secret,
                    ];
                }
                // 第三方账号登录配置
                if ($google_login_client_id && $google_login_client_secret)  {
                    $this->stores[$storeKey]['thirdLogin']['google'] = [
                        'CLIENT_ID'     => $google_login_client_id,
                        'CLIENT_SECRET' => $google_login_client_secret,
                    ];
                }
            }
        }
        
        return true;
    }
    // 如果入口是appserver，那么通过这个函数初始化
    public function initAppserverCurrentStoreConfig()
    {
        $appserver_store_config = Yii::$app->store->get('appserver_store');
        $storeKey = $appserver_store_config['key'];
        $lang = $appserver_store_config['lang'];
        $lang_name = $appserver_store_config['lang_name'];
        $currency = $appserver_store_config['currency'];
        $facebook_login_app_id = $appserver_store_config['facebook_login_app_id'];
        $facebook_login_app_secret = $appserver_store_config['facebook_login_app_secret'];
        $google_login_client_id = $appserver_store_config['google_login_client_id'];
        $google_login_client_secret = $appserver_store_config['google_login_client_secret'];
        $https_enable = $appserver_store_config['https_enable'] == 1 ? true : false;
        // set config stores
        $this->stores[$storeKey] = [
            'language'         => $lang,        // 语言简码需要在@common/config/fecshop_local_services/FecshopLang.php 中定义。
            'languageName'     => $lang_name,    // 语言简码对应的文字名称，将会出现在语言切换列表中显示。
            'currency'         => $currency, // 当前store的默认货币,这个货币简码，必须在货币配置中配置
            // 用于sitemap生成中域名。
            'https'            => $https_enable,
        ];
        // 第三方账号登录配置
        if ($facebook_login_app_id && $facebook_login_app_secret)  {
            $this->stores[$storeKey]['thirdLogin']['facebook'] = [
                'facebook_app_id'     => $facebook_login_app_id,
                'facebook_app_secret' => $facebook_login_app_secret,
            ];
        }
        // 第三方账号登录配置
        if ($google_login_client_id && $google_login_client_secret)  {
            $this->stores[$storeKey]['thirdLogin']['google'] = [
                'CLIENT_ID'     => $google_login_client_id,
                'CLIENT_SECRET' => $google_login_client_secret,
            ];
        }
        // 初始化语言。
        $this->stores[$storeKey]['serverLangs'] = Yii::$app->store->get('appserver_store_lang');
        // 通过该方法，初始化货币services，直接从headers中取出来currency。进行set，这样currency就不会从session中读取（fecshop-2版本对于appserver已经抛弃session servcies）
        Yii::$service->page->currency->appserverSetCurrentCurrency();
        
        return true;
    }
    
    /**
     *	Bootstrap:init website,  class property $currentLang ,$currentTheme and $currentStore.
     *  if you not config this ,default class property will be set.
     *  if current store_code is not config , InvalidValueException will be throw.
     *	class property $currentStore will be set value $store_code.
     * @param $app
     */
    protected function actionBootstrap($app)
    {
        $host = explode('//', $app->getHomeUrl());
        $stores = $this->stores;
        $init_complete = 0;
        if (is_array($stores) && !empty($stores)) {
            foreach ($stores as $store_code => $store) {
                if ($host[1] == $store_code) {
                    $this->html5DeviceCheckAndRedirect($store_code, $store);
                    Yii::$service->store->currentStore = $store_code;
                    $this->store = $store;
                    if (isset($store['language']) && !empty($store['language'])) {
                        Yii::$service->store->currentLang = $store['language'];
                        Yii::$service->store->currentLangCode = Yii::$service->fecshoplang->getLangCodeByLanguage($store['language']);
                        Yii::$service->store->currentLangName = $store['languageName'];
                        Yii::$service->page->translate->setLanguage($store['language']);
                    }
                    //if (isset($store['theme']) && !empty($store['theme'])) {
                    //    Yii::$service->store->currentTheme = $store['theme'];
                    //}
                    // set local theme dir.
                    if (isset($store['localThemeDir']) && $store['localThemeDir']) {
                        //Yii::$service->page->theme->localThemeDir = $store['localThemeDir'];
                        Yii::$service->page->theme->setLocalThemeDir($store['localThemeDir']);
                    }
                    // set third theme dir.
                    if (isset($store['thirdThemeDir']) && $store['thirdThemeDir']) {
                        //Yii::$service->page->theme->thirdThemeDir = $store['thirdThemeDir'];
                        Yii::$service->page->theme->setThirdThemeDir($store['thirdThemeDir']);
                    }
                    // init store currency.
                    if (isset($store['currency']) && !empty($store['currency'])) {
                        $currency = $store['currency'];
                    } else {
                        $currency = '';
                    }
                    Yii::$service->page->currency->initCurrency($currency);
                    /**
                     * current domain is config is store config.
                     */
                    $init_complete = 1;
                    $this->thirdLogin = $store['thirdLogin'];
                    /**
                     * appserver 部分
                     */
                    if (isset($store['serverLangs']) && !empty($store['serverLangs'])) {
                        $this->serverLangs = $store['serverLangs'];
                    }
                    $headers = Yii::$app->request->getHeaders();
                    if (isset($headers['fecshop-lang']) && $headers['fecshop-lang']) {
                        $h_lang = $headers['fecshop-lang'];
                        if (is_array($this->serverLangs)) {
                            foreach ($this->serverLangs as $one) {
                                if ($one['code'] == $h_lang) {
                                    Yii::$service->store->currentLangCode = $h_lang;
                                    Yii::$service->store->currentLang = $one['language'];
                                    Yii::$service->store->currentLangName = $one['languageName'];
                                    Yii::$service->page->translate->setLanguage($one['language']);
                                    break;
                                }
                            }
                        }
                        
                    }
                    //if (isset($headers['fecshop-currency']) && $headers['fecshop-currency']) {
                    //    $currentC = Yii::$service->page->currency->getCurrentCurrency();
                    //    if ($currentC != $headers['fecshop-currency']) {
                    //        Yii::$service->page->currency->setCurrentCurrency($headers['fecshop-currency']);
                    ///    }
                    //}
                    break;
                }
            }
        }
        
        if (!$init_complete) {
            throw new InvalidValueException('this domain is not config in store service, you must config it in admin store config');
        }
    }

    /**
     * @param $store_code | String
     * @param $store | Array
     * mobile device url redirect.
     * pc端自动跳转到html5端的检测
     */
    protected function html5DeviceCheckAndRedirect($store_code, $store)
    {
        if (!isset($store['mobile'])) {
            return;
        }
        $enable = isset($store['mobile']['enable']) ? $store['mobile']['enable'] : false;
        if (!$enable) {
            return;
        }
        $condition = isset($store['mobile']['condition']) ? $store['mobile']['condition'] : false;
        $redirectDomain = isset($store['mobile']['redirectDomain']) ? $store['mobile']['redirectDomain'] : false;
        $redirectType = isset($store['mobile']['type']) ? $store['mobile']['type'] : false;
        if (is_array($condition) && !empty($condition) && !empty($redirectDomain) && $redirectType === 'apphtml5') {
            $mobileDetect = Yii::$service->helper->mobileDetect;
            $mobile_https = (isset($store['mobile']['https']) && $store['mobile']['https']) ? true : false;
            if (in_array('phone', $condition) && in_array('tablet', $condition)) {
                if ($mobileDetect->isMobile()) {
                    $this->redirectAppHtml5Mobile($store_code, $redirectDomain, $mobile_https);
                }
            } elseif (in_array('phone', $condition)) {
                if ($mobileDetect->isMobile() && !$mobileDetect->isTablet()) {
                    $this->redirectAppHtml5Mobile($store_code, $redirectDomain, $mobile_https);
                }
            } elseif (in_array('tablet', $condition)) {
                if ($mobileDetect->isTablet()) {
                    $this->redirectAppHtml5Mobile($store_code, $redirectDomain, $mobile_https);
                }
            }
        }
    }

    /**
     * @param $store_code | String
     * @param $redirectDomain | String
     * 检测，html5端跳转检测
     */
    protected function redirectAppHtml5Mobile($store_code, $redirectDomain, $mobile_https)
    {
        $currentUrl = Yii::$service->url->getCurrentUrl();
        $redirectUrl = str_replace($store_code, $redirectDomain, $currentUrl);
        // pc端跳转到html5，可能一个是https，一个是http，因此需要下面的代码进行转换。
        if ($mobile_https) {
            if (strstr($redirectUrl, 'https://') || strstr($redirectUrl, 'http://')) {
                $redirectUrl = str_replace('http://', 'https://', $redirectUrl);
            } else {
                $redirectUrl = 'https:'.$redirectUrl;
            }
        } else {
            if (strstr($redirectUrl, 'https://') || strstr($redirectUrl, 'http://')) {
                $redirectUrl = str_replace('https://', 'http://', $redirectUrl);
            } else {
                $redirectUrl = 'http:'.$redirectUrl;
            }
        }
        header('Location:'.$redirectUrl);
        exit;
    }

    /**
     * @return boolean, 检测是否属于满足跳转到appserver的条件
     */
    public function isAppServerMobile()
    {
        $store = $this->store;
        if (!isset($store['mobile'])) {
            return;
        }
        $enable = isset($store['mobile']['enable']) ? $store['mobile']['enable'] : false;
        if (!$enable) {
            return;
        }
        $condition = isset($store['mobile']['condition']) ? $store['mobile']['condition'] : false;
        $redirectDomain = isset($store['mobile']['redirectDomain']) ? $store['mobile']['redirectDomain'] : false;
        $redirectType = isset($store['mobile']['type']) ? $store['mobile']['type'] : false;
        if (is_array($condition) && !empty($condition) && !empty($redirectDomain) && $redirectType === 'appserver') {
            $mobileDetect = Yii::$service->helper->mobileDetect;
            if (in_array('phone', $condition) && in_array('tablet', $condition)) {
                if ($mobileDetect->isMobile()) {
                    return true;
                }
            } elseif (in_array('phone', $condition)) {
                if ($mobileDetect->isMobile() && !$mobileDetect->isTablet()) {
                    return true;
                }
            } elseif (in_array('tablet', $condition)) {
                if ($mobileDetect->isTablet()) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * @param $urlPath | String，跳转到vue端的url Path
     * @return boolean, 生成vue端的url，然后进行跳转。
     */
    public function redirectAppServerMobile($urlPath)
    {
        $store = $this->store;
        $redirectDomain = isset($store['mobile']['redirectDomain']) ? $store['mobile']['redirectDomain'] : false;
        $mobile_https = (isset($store['mobile']['https']) && $store['mobile']['https']) ? 'https://' : 'http://';
        $host = $mobile_https.$redirectDomain.'/#/';
        $urlParam = $_SERVER["QUERY_STRING"];
        // 得到当前的语言
        if ($urlParam) {
            $urlParam .= '&lang='.$this->currentLangCode;
        } else {
            $urlParam .= 'lang='.$this->currentLangCode;
        }
        $redirectUrl = $host.$urlPath.'?'.$urlParam;
        header('Location:'.$redirectUrl);
        exit;
    }

    /**
     * @param $attrVal|array , language attr array , like   ['title_en' => 'xxxx','title_fr' => 'yyyy']
     * @param $attrName|String, attribute name ,like: title ,description.
     * if  object or array  attribute is a language attribute, you can get current
     * language value by this function.
     * if lang attribute in current store language is empty , default language attribute will be return.
     * if attribute in default language value is empty, $attrVal will be return.
     */
    protected function actionGetStoreAttrVal($attrVal, $attrName)
    {
        $lang = $this->currentLangCode;

        return Yii::$service->fecshoplang->getLangAttrVal($attrVal, $attrName, $lang);
    }

    /**
     * @return array
     *               get all store info, one item in array format is: ['storeCode' => 'store language'].
     */
    protected function actionGetStoresLang()
    {
        $stores = $this->stores;
        $topLang = [];
        foreach ($stores as $storeCode=> $store) {
            $languageName = $store['languageName'];
            $topLang[$storeCode] = $languageName;
        }

        return $topLang;
    }
}

