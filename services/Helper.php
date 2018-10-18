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
 * @property \fecshop\services\helper\Appserver $appserver appserver sub-service of helper service
 * @property \fecshop\services\helper\AR $ar ar sub-service of helper service
 * @property \fecshop\services\helper\Errors $errors errors sub-service of helper service
 *
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
     * @property $var | String Or Array 需要进行Html::encode()操作的变量。
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
     * @property $domain | String vue类型的appserver传递的domain
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
}
