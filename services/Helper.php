<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;

/**
 * Helper services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Helper extends Service
{
    protected $_app_name;

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
                    if (is_string($var)) {
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
}
