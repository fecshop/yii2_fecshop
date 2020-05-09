<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\helper;

use fecshop\services\Service;

/**
 * Helper Captcha services. 验证码部分
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Captcha extends Service
{
    public $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ0123456789'; //随机因子

    public $codelen = 4;    //验证码长度

    public $width   = 130;  //宽度

    public $height  = 50;   //高度

    public $fontsize= 20;  //指定字体大小

    public $case_sensitive = false;

    private $fontcolor; //指定字体颜色

    private $code; //验证码

    private $img; //图形资源句柄

    private $font; //指定的字体

    private $_sessionKey = 'captcha_session_key';

    /**
     *  1. 生成图片，.
     */
    //构造方法初始化
    public function init()
    {
        parent::init();
        $this->font = dirname(__FILE__).'/captcha/Elephant.ttf'; //注意字体路径要写对，否则显示不了图片
    }

    //生成随机码
    private function createCode()
    {
        $_len = strlen($this->charset) - 1;
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->code .= $this->charset[mt_rand(0, $_len)];
        }
    }

    //生成背景
    private function createBg()
    {
        $this->img  = imagecreatetruecolor($this->width, $this->height);
        $color      = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    //生成文字
    private function createFont()
    {
        $_x = $this->width / $this->codelen;

        for ($i = 0; $i < $this->codelen; $i++) {
            if (!$this->fontcolor) {
                $fontcolor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            } else {
                $fontcolor = $this->fontcolor;
            }
            imagettftext($this->img, $this->fontsize, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), $this->height / 1.4, $fontcolor, $this->font, $this->code[$i]);
        }
    }

    //生成线条、雪花
    private function createLine()
    {
        //线条
        for ($i = 0; $i < 6; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        //雪花
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    //输出
    private function outPut()
    {
        header('Content-type:image/jpg');
        imagepng($this->img);
        imagedestroy($this->img);
    }
    
    //对外生成
    public function doBase64img()
    {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        ob_start();
        imagepng($this->img);
        imagedestroy($this->img);
        $fileContent = ob_get_contents();
        ob_end_clean();
        $this->setSessionCode();
        return base64_encode($fileContent);
    }

    //对外生成
    public function doimg()
    {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->setSessionCode();
        session_commit();
        $this->outPut();
    }

    public function setSessionCode()
    {
        $code = $this->getCode($this->code);
        \Yii::$service->session->set($this->_sessionKey, $code);
    }

    //获取验证码
    public function getCode($code)
    {
        if (!$this->case_sensitive) {
            
            return strtolower($code);
        } else {
            
            return $this->code;
        }
    }

    public function validateCaptcha($captchaData)
    {
        $captchaData = $this->getCode($captchaData);
        $sessionCaptchaData = \Yii::$service->session->get($this->_sessionKey);

        return ($captchaData === $sessionCaptchaData) ? true : false;
    }
}
