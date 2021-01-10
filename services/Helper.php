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
 * Helper service.
 *
 * @property \fecshop\services\helper\Appapi $appapi
 * @property \fecshop\services\helper\Appserver $appserver appserver sub-service of helper service
 * @property \fecshop\services\helper\AR $ar
 * @property \fecshop\services\helper\Captcha $captcha
 * @property \fecshop\services\helper\Country $country
 * @property \fecshop\services\helper\Echart $echart
 * @property \fecshop\services\helper\ErrorHandler $errorHandler
 * @property \fecshop\services\helper\Errors $errors errors sub-service of helper service
 * @property \fecshop\services\helper\Format $format
 * @property \fecshop\services\helper\MobileDetect $mobileDetect
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Helper extends Service
{
    protected $_app_name;

    protected $_param;
    
    /**
     * Returns a string representing the current version of the Yii framework.
     * @return string the version of Yii framework
     */
    public  function getVersion()
    {
        return '2.10.0';
    }
    
    /**
     * 得到当前的app入口的名字，譬如 appfront apphtml5  appserver等.
     */
    public function getAppName()
    {
        return   Yii::$app->params['appName'];
    }
    
    public function getBaseWebsiteName()
    {
        $websiteName = Yii::$app->store->get('base_info', 'company_name');
        if (!$websiteName) {
            $websiteName = 'FECMALL.COM';
        }
        
        return $websiteName;
    }
    
    public function getBaseWebsitePerson()
    {
        $websitePerson = Yii::$app->store->get('base_info', 'company_person');
        if (!$websitePerson) {
            $websitePerson = 'FECMALL.COM';
        }
        
        return $websitePerson;
    }
    
    public function getBaseWebsitePhone()
    {
        $websitePhone = Yii::$app->store->get('base_info', 'company_phone');
        if (!$websitePhone) {
            $websitePhone = 'FECMALL.COM';
        }
        
        return $websitePhone;
    }

    /**
     * @param $var | String Or Array 需要进行Html::encode()操作的变量。
     * @return $var | String Or Array 去除xss攻击字符后的变量
     */
    public function htmlEncode($var)
    {
        if (is_array($var) && !empty($var)) {
            foreach ($var as $k=>$v) {
                if (is_array($v) && !empty($v)) {
                    $var[$k] = $this->htmlEncode($v);
                } elseif (empty($v)) {
                    $var[$k] = $v;
                } else {
                    if (is_string($v)) {
                        $var[$k] = \yii\helpers\Html::encode($v);
                    }
                }
            }
        } elseif (empty($var)) {
        } else {
            if (is_string($var)) {
                $var = \yii\helpers\Html::encode($var);
            }
        }

        return $var;
    }
    
    /**
     * @param $domain | String vue类型的appserver传递的domain
     * 这个是appservice发送邮件，在邮件里面的url链接地址，在这里保存
     */
    public function setAppServiceDomain($domain)
    {
        $this->_param['appServiceDomain'] = $domain;
        
        return true;
    }
    
    public function getAppServiceDomain()
    {
        return isset($this->_param['appServiceDomain']) ? $this->_param['appServiceDomain'] : false;
    }

    /**
     * 该端口是否是Api入口，譬如appserver  appapi等，都是属于api的入口
     * api入口都会将 Yii::$app->user->enableSession 关闭，因此通过该值判断， 是否是Api App
     *
     */
    public function isApiApp()
    {
        if (\Yii::$service->store->isApiStore() == true) {
            
            return true;
        } else {
            
            return false;
        }
    }
    
    public function getCustomerIp()
    {
        return Yii::$app->request->userIP;
    }
    
    
    function createNoncestr( $length = 32 ){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        
        return $str;
    }
    // 递归删除文件夹以及里面的所有的子文件夹和子文件
    public function deleteDir($path) {
        if (is_dir($path)) {
            //扫描一个目录内的所有目录和文件并返回数组
            $dirs = scandir($path);
            foreach ($dirs as $dir) {
                //排除目录中的当前目录(.)和上一级目录(..)
                if ($dir != '.' && $dir != '..') {
                    //如果是目录则递归子目录，继续操作
                    $sonDir = $path.'/'.$dir;
                    if (is_dir($sonDir)) {
                        //递归删除
                        $this->deleteDir($sonDir);
                        //目录内的子目录和文件删除后删除空目录
                        @rmdir($sonDir);
                    } else {
                        //如果是文件直接删除
                        @unlink($sonDir);
                    }
                }
            }
            @rmdir($path);
        }
        
        return true;
    }
    
     /**
     * 图片文件复制，注意，如果某个文件不是图片类型，则不会被复制（仅仅复制图片）
     * 文件夹图片文件拷贝, 如果文件存在，则会被强制覆盖。
     * @param string $sourcePath 来源文件夹
     * @param string $targetPath 目的地文件夹
     * @param boolean $isForce 是否强制复制
     * @return bool
     */
    public function copyDirImage($sourcePath, $targetPath, $isForce = true)
    {
        if (empty($sourcePath) || empty($targetPath)) {
            return false;
        }
        $dir = opendir($sourcePath);
        $this->dir_mkdir($targetPath);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $sourcePathFile = $sourcePath . '/' . $file;
                $targetPathFile = $targetPath . '/' . $file;
                if (is_dir($sourcePathFile)){
                    $this->copyDirImage($sourcePathFile, $targetPathFile);
                } else if (Yii::$service->image->isAllowImgType($sourcePathFile, $file)){
                    if ($isForce) {
                        copy($sourcePathFile, $targetPathFile);
                    } else if (!file_exists($targetPathFile)) {
                        copy($sourcePathFile, $targetPathFile);
                    } else {
                        Yii::$service->helper->errors->add('target path:' . $targetPathFile . ' is exist.');
                    }
                } else {
                    Yii::$service->helper->errors->add('file is not image:' . $sourcePathFile);
                }
            }
        }
        closedir($dir);
     
        return true;
    }
    
    /**
     * 文件夹文件拷贝
     *
     * @param string $sourcePath 来源文件夹
     * @param string $targetPath 目的地文件夹
     * @param boolean $isForce 是否强制复制
     * @return bool
     */
    public function copyDir($sourcePath, $targetPath, $isForce = true)
    {
        if (empty($sourcePath) || empty($targetPath))
        {
            return false;
        }
     
        $dir = opendir($sourcePath);
        $this->dir_mkdir($targetPath);
        while (false !== ($file = readdir($dir)))
        {
            if (($file != '.') && ($file != '..')) {
                $sourcePathFile = $sourcePath . '/' . $file;
                $targetPathFile = $targetPath . '/' . $file;
                if (is_dir( $sourcePathFile)) {
                    $this->copyDir( $sourcePathFile, $targetPathFile);
                } else {
                    //copy($sourcePath . '/' . $file, $targetPath . '/' . $file);
                    if ($isForce) {
                        copy($sourcePathFile, $targetPathFile);
                    } else if (!file_exists($targetPathFile)) {
                        copy($sourcePathFile, $targetPathFile);
                    } else {
                        Yii::$service->helper->errors->add('target path:' . $targetPathFile . ' is exist.');
                    }
                }
            }
        }
        closedir($dir);
     
        return true;
    }
    
    /**
     * 创建文件夹
     *
     * @param string $path 文件夹路径
     * @param int $mode 访问权限
     * @param bool $recursive 是否递归创建
     * @return bool
     */
    public function dir_mkdir($path = '', $mode = 0777, $recursive = true)
    {
        clearstatcache();
        if (!is_dir($path))
        {
            mkdir($path, $mode, $recursive);
            
            return chmod($path, $mode);
        }
     
        return true;
    }
    
    public function scanAllDirSubFile($dir, $subDir='/')
    {	
        if(is_dir($dir)){
            $files = array();
            $child_dirs = scandir($dir);
            foreach ($child_dirs as $child_dir){
                //'.'和'..'是Linux系统中的当前目录和上一级目录，必须排除掉，  
                //否则会进入死循环，报segmentation falt 错误
                if($child_dir != '.' && $child_dir != '..'){
                    if(is_dir($dir.'/'.$child_dir)){
                        //$files[$child_dir] = my_scandir($dir.'/'.$child_dir);
                        $files = array_merge($files, $this->scanAllDirSubFile($dir.'/'.$child_dir, $subDir.$child_dir.'/'));
                    }else{
                        $files[] = $subDir.$child_dir;
                    }
                }
            }
            
            return $files;
        }else{
            
            return $subDir.$dir;
        }
    }
}
