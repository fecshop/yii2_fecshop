<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\page;

use fecshop\services\Service;
use Yii;

/**
 * page asset services. Yii2的Asset部分，这里对这个功能做了一定的重构
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Asset extends Service
{
    public $cssOptions = [];

    public $jsOptions = [];

    public $jsVersion = 1;   //?v=115

    public $cssVersion = 1;
    //?v=115
    // 【这个字段已经废弃，请使用$baseUrl，设置】js 和 css 如果想用独立的域名，可以在这里设置相应的域名。
    //public $jsCssDomain;

    /**
     * 在模板路径下的相对文件夹。
     * 譬如模板路径为@fecshop/app/theme/base/front
     * 那么js,css路径默认为@fecshop/app/theme/base/front/assets.
     */
    public $defaultDir = 'assets';
    
    /**
     * @var string the root directory storing the published asset files.
     * 譬如设置为：'@appimage/assets'，也可以将 @appimage 换成绝对路径
     */
    public $basePath = '@webroot/assets';

    /**
     * @var string the base URL through which the published asset files can be accessed.
     * 可以将 @web 换成域名 ， 譬如  `http:://www/fecshop.com/assets`
     * 这样就可以将js和css文件使用独立的域名了【把域名对应的地址对应到$basePath】。
     */
    public $baseUrl = '@web/assets';

    // 是否每次访问都强制复制css js img等文件到发布地址，true代表每次访问都发布
    // 一般开发环境用true，线上用false。当线上更新jscss文件，可以清空assets发布路径下的文件的方式来更新
    public $forceCopy = true;
    
    public function init()
    {
        parent::init();
        $appName = Yii::$service->helper->getAppName();
        $assetForceCopy = Yii::$app->store->get($appName.'_base', 'assetForceCopy');
        $this->forceCopy = ($assetForceCopy  == Yii::$app->store->enable) ? true : false;
        $js_version = Yii::$app->store->get($appName.'_base', 'js_version');
        $css_version = Yii::$app->store->get($appName.'_base', 'css_version');
        $this->jsVersion = $js_version;  
        $this->cssVersion = $css_version; 
    }
    /**
     * 文件路径默认放到模板路径下面的assets里面.
     */
    public function register($view)
    {
        if ($this->basePath) {
            $view->assetManager->basePath = Yii::getAlias($this->basePath);
        }
        if ($this->baseUrl) {
            $view->assetManager->baseUrl = $this->baseUrl;
        }
        $view->assetManager->forceCopy = $this->forceCopy;
        $assetArr = [];
        // 模板路径优先级（由高到底）
        $themeDir = Yii::$service->page->theme->getThemeDirArr();
        // 根据模板路径的优先级，初始化asset数组顺序，进而决定css的优先级
        $assetThemeDirs = array_reverse($themeDir);
        // pushArr数组
        $publishArr = [];
        foreach ($assetThemeDirs as $assetThemeDir) {
            $dir2 = $assetThemeDir.'/'.$this->defaultDir.'/';
            $assetArr[$dir2] = [];
            if(is_dir($dir2)) {
                $publishDir = $view->assetManager->publish($dir2);
                $publishArr[$dir2] = $publishDir;
            }
            
        }
        $jsV = '?v='.$this->jsVersion;
        $cssV = '?v='.$this->cssVersion;
        // 根据模板的优先级，查找js和css文件
        if (is_array($themeDir) && !empty($themeDir)) {
            if (is_array($this->jsOptions) && !empty($this->jsOptions)) {
                foreach ($this->jsOptions as $jsOption) {
                    if (isset($jsOption['js']) && is_array($jsOption['js']) && !empty($jsOption['js'])) {
                        foreach ($jsOption['js'] as $jsPath) {
                            foreach ($themeDir as $dir) {
                                $dir = $dir.'/'.$this->defaultDir.'/';
                                $jsAbsoluteDir = $dir.$jsPath;
                                if (file_exists($jsAbsoluteDir)) {
                                    $publishDir = $publishArr[$dir];
                                    $cOptions = isset($jsOption['options']) ? $this->initOptions($jsOption['options']) : null ;
                                    $view->registerJsFile($publishDir[1].'/'.$jsPath.$jsV, $cOptions);
                                    
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            if (is_array($this->cssOptions) && !empty($this->cssOptions)) {
                foreach ($this->cssOptions as $cssOption) {
                    if (isset($cssOption['css']) && is_array($cssOption['css']) && !empty($cssOption['css'])) {
                        foreach ($cssOption['css'] as $cssPath) {
                            foreach ($themeDir as $dir) {
                                $dir = $dir.'/'.$this->defaultDir.'/';
                                $cssAbsoluteDir = $dir.$cssPath;
                                if (file_exists($cssAbsoluteDir)) {
                                    $publishDir = $publishArr[$dir];
                                    $cOptions = isset($cssOption['options']) ? $this->initOptions($cssOption['options']) : null;
                                    $view->registerCssFile($publishDir[1].'/'.$cssPath.$cssV, $cOptions);
                                    
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        
    }

    protected function initOptions($options)
    {
        if (isset($options['position'])) {
            if ($options['position'] == 'POS_HEAD') {
                $options['position'] = \yii\web\View::POS_HEAD;
            } elseif ($options['position'] == 'POS_END') {
                $options['position'] = \yii\web\View::POS_END;
            }
        }

        return $options;
    }
}
