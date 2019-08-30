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
     * 得到当前的app入口的名字，譬如 appfront apphtml5  appserver等.
     */
    public function getAppName()
    {
        return   Yii::$app->params['appName'];
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
    }
}
