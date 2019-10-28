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
 * Url Services
 *
 * @property \fecshop\services\url\Category $category category sub-service of url
 * @property \fecshop\services\url\Rewrite $rewrite rewrite sub-service of url
 *
 * Url Service
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Url extends Service
{
    public $randomCount = 9;

    public $showScriptName;

    protected $_secure;

    protected $_currentBaseUrl;

    protected $_origin_url;

    protected $_httpType;

    protected $_baseUrl;

    protected $_currentUrl;

    /**
     * About: 对于 \yii\helpers\CUrl 已经 封装了一些对url的操作，也就是基于yii2的url机制进行的
     * 但是对于前端并不适用，对于域名当首页http://xx.com这类url是没有问题，但是，
     * 对于子目录当首页的时候就会出问题：  譬如：http://xx.com/zh , http://xx.com/es , http://xx.com/fr 这类得有一定目录的url，则不能满足要求
     * 另外前端页面为了seo要求，还会加入url自定义等要求，作为Yii2的url，已经不能满足要求，
     * 因此这里重新封装，对于前端页面，请使用 Yii::$service->url.
     * 对于admin部分，不会涉及到重写url和域名子目录作为htmlUrl的情况，因此admin部分还是可以用\yii\helpers\CUrl的。
     */

    /**
     * save custom url to mongodb collection url_rewrite.
     * @param $str|String, example:  fashion handbag women
     * @param $originUrl|string , origin url ,example: /cms/home/index?id=5
     * @param $originUrlKey|String,origin url key, it can be empty ,or generate by system , or custom url key.
     * @param $type|String, url rewrite type.
     * @return rewrite Key.
     */
    protected function actionSaveRewriteUrlKeyByStr($str, $originUrl, $originUrlKey, $type = 'system')
    {
        $str = trim($str);
        $originUrl = $originUrl ? '/'.trim($originUrl, '/') : '';
        $originUrlKey = $originUrlKey ? '/'.trim($originUrlKey, '/') : '';
        if ($originUrlKey && $originUrl) {
            /**
             * if originUrlKey and  originUrl is exist in url rewrite collectons.
             */ 
            $model = $this->find();
            // 如果已经存在，那么直接返回
            $data_one = $model->where([
                'custom_url_key'    => $originUrlKey,
                'origin_url'        => $originUrl,
            ])->one();
            if (isset($data_one['custom_url_key'])) {

                return $originUrlKey;
            }
            /*
            $data_one = $model->where([
                'custom_url_key'    => $originUrlKey,
                'origin_url'        => $originUrl,
            ])->one();
            if (isset($data_one['custom_url_key'])) {
                // 只要进行了查询，就要更新一下rewrite url表的updated_at.
                $data_one->updated_at = time();
                $data_one->origin_url = $originUrl;
                $data_one->save();

                return $originUrlKey;
            }
            */
        }
        if ($originUrlKey) {
            $urlKey = $this->generateUrlByName($originUrlKey);
        } else {
            $urlKey = $this->generateUrlByName($str);
        }
        if (strlen($urlKey) <= 1) {
            $urlKey .= $this->getRandom();
        }
        if (strlen($urlKey) <= 2) {
            $urlKey .= '-'.$this->getRandom();
        }
        $urlKey = $this->getRewriteUrlKey($urlKey, $originUrl);
        $UrlRewrite = $this->findOne([
            'origin_url' => $originUrl,
        ]);
        if (!isset($UrlRewrite['origin_url'])) {
            $UrlRewrite = $this->newModel();
            $UrlRewrite->created_at = time();
        }
        $UrlRewrite->updated_at = time();
        $UrlRewrite->type = $type;
        $UrlRewrite->custom_url_key = $urlKey;
        $UrlRewrite->origin_url = $originUrl;
        $UrlRewrite->save();

        return $urlKey;
    }

    /**
     * @param $url_key|string
     * remove url rewrite data by $url_key,which is custom url key that saved in custom url modules,like articcle , product, category ,etc..
     */
    protected function actionRemoveRewriteUrlKey($url_key)
    {
        $model = $this->findOne([
            'custom_url_key' => $url_key,
        ]);
        if ($model['custom_url_key']) {
            $model->delete();
        }
    }

    /**
     * 得到当前的url，使用的是php的方式，而不是Yii2的函数
     * 对于Yii框架得到当前的url使用：\yii\helpers\BaseUrl::current([],true)
     * 这里没有使用的原因是，因为fecshop存在url rewrite，在初始化的时候，会将当前的url转换成yii2框架的url
     * 当前函数返回的url，是浏览器地址栏中的当前url，而\yii\helpers\BaseUrl::current([],true) 返回的yii2框架中的url
     * 这个要分清楚使用
     * 譬如分类页面的url，进行了url rewrite：http://fecshop.appfront.fancyecommerce.com/men
     * 1.函数`\yii\helpers\BaseUrl::current([],true)`的输出为：http://fecshop.appfront.fancyecommerce.com/catalog/category/index?_id=57b6ac42f656f246653bf576
     * 2.而当前函数`getCurrentUrl()`的输出为：http://fecshop.appfront.fancyecommerce.com/men
     * 3.关于fecshop url rewrite，详细参看：http://www.fecshop.com/doc/fecshop-guide/instructions/cn-1.0/guide-fecshop_url_rewrite.html
     */
    public function getCurrentUrl()
    {
        if (!$this->_currentUrl) {
            $secure = Yii::$app->getRequest()->getIsSecureConnection();
            $http = $secure ? 'https' : 'http';
            $this->_currentUrl = $http . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
        
        return $this->_currentUrl;
    }

    protected function actionGetCurrentUrlNoParam()
    {
        $currentUrl = $this->getCurrentUrl();
        if (strstr($currentUrl, '?')) {
            $currentUrl = substr($currentUrl, 0, strpos($currentUrl, '?'));
        }

        return $currentUrl;
    }

    /**
     *  @param $urlKey|string
     *  get $origin_url by $custom_url_key ,it is used for yii2 init,
     *  in (new fecshop\services\Request)->resolveRequestUri(),  ## fecshop\services\Request is extend  yii\web\Request
     */
    protected function actionGetOriginUrl($urlKey)
    {
        return Yii::$service->url->rewrite->getOriginUrl($urlKey);
    }

    /**
     * @param $url_key | String  urlKey的值
     * @param $params | Array 。url里面个各个参数
     * @param https | boolean 是否使用https的方式
     * @param $domain | String ， 相应的域名，譬如www.fecshop.com
     * @proeprty $showScriptName | boolean，是否在url中包含index.php/部分
     * @param $useHttpForUrl | boolean ，是否在url中加入http部分、
     * 通过传入domain的方式得到相应的url
     * 该功能一般是在脚本中通过各个域名的传入得到相应的url，譬如sitemap的生成就是应用了这个方法得到
     * 产品和分类的url。
     */
    protected function actionGetUrlByDomain($url_key, $params = [], $https = false, $domain, $showScriptName = false, $useHttpForUrl = false)
    {
        $first_str = substr($url_key, 0, 1);
        if ($first_str == '/') {
            $jg = '';
        } else {
            $jg = '/';
        }
        if ($useHttpForUrl) {
            if ($https) {
                $baseUrl = 'https://'.$domain;
            } else {
                $baseUrl = 'http://'.$domain;
            }
        } else {
            $baseUrl = '//'.$domain;
        }

        if ($showScriptName) {
            $baseUrl .= '/index.php';
        }
        if (is_array($params) && !empty($params)) {
            $arr = [];
            foreach ($params as $k => $v) {
                $arr[] = $k.'='.$v;
            }

            return $baseUrl.$jg.$url_key.'?'.implode('&', $arr);
        }

        return $url_key ? $baseUrl.$jg.$url_key : $baseUrl;
    }

    /**
     * @param $path|String, for example about-us.html,  fashion-handbag/women.html
     * genarate current store url by path.
     * example:
     * Yii::$service->url->getUrlByPath('cms/article/index?id=33');
     * Yii::$service->url->getUrlByPath('cms/article/index',['id'=>33]);
     * Yii::$service->url->getUrlByPath('cms/article/index',['id'=>33],true);
     */
    protected function actionGetUrl($path, $params = [], $https = false)
    {
        $first_str = substr($path, 0, 1);
        if ($first_str == '/') {
            $jg = '';
        } else {
            $jg = '/';
        }
        $baseUrl = $this->getBaseUrl();

        if (is_array($params) && !empty($params)) {
            $arr = [];
            foreach ($params as $k => $v) {
                $arr[] = $k.'='.$v;
            }

            return $baseUrl.$jg.$path.'?'.implode('&', $arr);
        }

        return $baseUrl.$jg.$path;
    }

    /**
     * get current base url , is was generate by http(or https ).'://'.store_code.
     */
    protected function actionGetCurrentBaseUrl()
    {
        if (!$this->_currentBaseUrl) {
            $homeUrl = $this->homeUrl();
            if ($this->showScriptName) {
                $homeUrl .= '/index.php';
            }
            $this->_currentBaseUrl = $homeUrl;
            //if(!$this->_httpType)
            //	$this->_httpType = $this->secure() ? 'https' : 'http';
            //$this->_currentBaseUrl = str_replace("http",$this->_httpType,$homeUrl);
        }

        return $this->_currentBaseUrl;
    }

    /**
     * get current home url , is was generate by 'http://'.store_code.
     */
    public function homeUrl()
    {
        return Yii::$app->getHomeUrl();
    }

    /**
     * get  base url.
     */
    protected function getBaseUrl()
    {
        if (!$this->_baseUrl) {
            $this->_baseUrl = $this->homeUrl();
            if ($this->showScriptName) {
                $this->_baseUrl .= '/index.php';
            }
        }

        return $this->_baseUrl;
    }

    protected function newModel()
    {
        return Yii::$service->url->rewrite->newModel();
    }

    protected function find()
    {
        return Yii::$service->url->rewrite->find();
    }

    protected function findOne($where)
    {
        return Yii::$service->url->rewrite->findOne($where);
    }

    /**
     * check current url type is http or https. https is secure url type.
     */
    /*
    protected function secure(){
        if($this->_secure === null){

            if($_SERVER['SERVER_PORT'] == 443){
                $this->_secure = true;
            }else{
                $this->_secure = false;
            }
        }
        return $this->_secure;
    }
    */

    /**
     * get rewrite url key.
     */
    protected function getRewriteUrlKey($urlKey, $originUrl)
    {
        $model = $this->find();
        $data = $model->where([
            'custom_url_key' => $urlKey,
        ])->andWhere(['<>', 'origin_url', $originUrl])
        ->asArray()->one();
        if (isset($data['custom_url_key'])) {
            $urlKey = $this->getRandomUrlKey($urlKey);

            return $this->getRewriteUrlKey($urlKey, $originUrl);
        } else {
            return $urlKey;
        }
    }

    /**
     * generate random string.
     */
    protected function getRandom($length = '')
    {
        if (!$length) {
            $length = $this->randomCount;
        }
        $str = null;
        $strPol = '123456789';
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $strPol[rand(0, $max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }
    
    protected function isRandomStr($randomStr)
    {
        $f = substr($randomStr, 0, 1);
        if ($f !== '-') {
            return false;
        }
        $s =  substr($randomStr, 1);
        if (strlen($s) != $this->randomCount) {
            return false;
        }
        if(!is_numeric($s)){
            return false;
        }
        
        return true;
    }

    /**
     * if url key is exist in url_rewrite table ,Behind url add some random string.
     */
    protected function getRandomUrlKey($url)
    {
        if ($this->_origin_url) {
            $suffix = '';
            $o_url = $this->_origin_url;
            if (strstr($this->_origin_url, '.')) {
                list($o_url, $suffix) = explode('.', $this->_origin_url);
                $l = $this->randomCount +1;
                if (strlen($o_url) > $l) {
                    $randomStrSub = substr($o_url, strlen($o_url) - $l , $l );
                    $orignUrlK = substr($o_url, 0, strlen($o_url) - $l );
                    if ($this->isRandomStr($randomStrSub)) {
                        $o_url = $orignUrlK;
                    }
                }
                
                $randomStr = $this->getRandom();

                return $o_url.'-'.$randomStr.'.'.$suffix;
            } else {
                $l = $this->randomCount +1;
                
                if (strlen($o_url) > $l) {
                    $randomStr = substr($o_url, strlen($o_url) - $l , $l );
                    $orignUrlK = substr($o_url, 0, strlen($o_url) - $l );
                    if ($this->isRandomStr($randomStr)) {
                        $o_url = $orignUrlK;
                    }
                }
                $randomStr = $this->getRandom();

                return $o_url.'-'.$randomStr;
            }
            
        }
    }

    /**
     * clear character that can not use for url.
     */
    protected function generateUrlByName($name)
    {
        setlocale(LC_ALL, '');
        $url = iconv('UTF-8', 'ASCII//TRANSLIT', $name);

        $url = preg_replace('{[^a-zA-Z0-9_.| -]}', '', $url);
        $url = strtolower(trim($url, '-'));
        $url = preg_replace('{[_| -]+}', '-', $url);
        $url = '/'.trim($url, '/');
        $this->_origin_url = $url;

        return $url;
    }

    /**
     * @param $url|string  要处理的url ， 一般是当前的url
     * @param $removeUrlParamStr|string  在url中删除的部分，一般是某个key对应的某个val，譬如color=green
     * @param $backToPage1|bool  删除后，页数由原来的页数变成第一页？
     */
    protected function actionRemoveUrlParamVal($url, $removeUrlParamStr, $backToPage1 = true)
    {
        $return_url = $url;
        if (strstr($url, '?'.$removeUrlParamStr.'&')) {
            $return_url = str_replace('?'.$removeUrlParamStr.'&', '?', $url);
        } elseif (strstr($url, '?'.$removeUrlParamStr)) {
            $return_url = str_replace('?'.$removeUrlParamStr, '', $url);
        } elseif (strstr($url, '&'.$removeUrlParamStr)) {
            $return_url = str_replace('&'.$removeUrlParamStr, '', $url);
        }
        if ($backToPage1) {
            $pVal = Yii::$app->request->get('p');
            if ($pVal) {
                $originPUrl = 'p='.$pVal;
                $afterPUrl = 'p=1';
            }
            if ($originPUrl) {
                $return_url = str_replace($originPUrl, $afterPUrl, $return_url);
            }
        }

        return $return_url;
    }

    /**
     * url 跳转.
     */
    protected function actionRedirect($url)
    {
        if ($url) {
            //session_commit();
            Yii::$app->getResponse()->redirect($url)->send();
            //header("Location: $url");
        }
    }

    protected function actionRedirectByUrlKey($urlKey, $params = [])
    {
        if ($urlKey) {
            $url = $this->getUrl($urlKey, $params);
            //session_commit();
            Yii::$app->getResponse()->redirect($url)->send();
            //header("Location: $url");
        }
    }

    protected function actionRedirectHome()
    {
        $homeUrl = $this->HomeUrl();
        if ($homeUrl) {
            Yii::$app->getResponse()->redirect($homeUrl)->send();
            //session_commit();
            //header("Location: $homeUrl");
        }
    }
    
    protected function actionRedirect404()
    {
        $error404UrlKey = Yii::$app->errorHandler->errorAction;
        $error404Url    = $this->getUrl($error404UrlKey);
        if ($error404Url) {
            Yii::$app->getResponse()->redirect($error404Url)->send();
        }
    }
    // 判断是否是首页
    public function isHomePage()
    {
        $rules = Yii::$app->urlManager->rules;
        $route = '';
        if (!is_array($rules)) {
            return false;
        }
        foreach ($rules as $one) {
            $name = $one->name;
            if ($name === '') {
                $route = $one->route;
            }
        }
        if (!$route) {
            return false;
        }
        //  $route  默认为  cms/home/index
        $arr = explode('/', $route);
        if (count($arr) != 3) {
            return false;
        }
        $mId = Yii::$app->controller->module->id;
        $cId = Yii::$app->controller->id;
        $aId = Yii::$app->controller->action->id;
        // 通过module controler  action 的id 与 $routeArr核对，不一致则为false
        if ($mId != $arr[0] || $cId != $arr[1] || $aId != $arr[2]) {
            return false;
        }
        
        return true;
    }
}
