<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\helper;

use fecshop\services\Service;
use Yii;

/**
 * Helper Errors services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Errors extends Service
{
    protected $_errors = false;
    public $status = true;

    /**
     * @property $errros | String , 错误信息
     * @property $arr | Array 变量替换对应的数组
     * Yii::$service->helper->errors->add('Hello, {username}!', ['username' => $username])
     */
    public function add($errros, $arr = [])
    {
        if ($errros) {
            $errros = Yii::$service->page->translate->__($errros, $arr);
            $this->_errors[] = $errros;
        }
    }
    /**
     * @property $model_errors | Array
     * Yii2的model在使用rules验证数据格式的时候，报错保存在errors中
     * 本函数将errors的内容添加到errors services中。
     */
    public function addByModelErrors($model_errors)
    {
        $error_arr = [];
        if (is_array($model_errors)) {
            foreach ($model_errors as $errors) {
                $arr = [];

                foreach ($errors as $s) {
                    $arr[] = Yii::$service->page->translate->__($s);
                }
                $error_arr[] = implode(',', $arr);
            }
            if (!empty($error_arr)) {
                $this->_errors[] = implode(',', $error_arr);
            }
        }
    }

    /**
     * @property $separator 如果是false，则返回数组，
     *						如果是true则返回用| 分隔的字符串
     *						如果是传递的分隔符的值，譬如“,”，则返回用这个分隔符分隔的字符串
     */
    public function get($separator = false)
    {
        if ($errors = $this->_errors) {
            $this->_errors = false;
            if(is_array($errors) && !empty($errors)){
                if ($separator) {
                    if ($separator === true) {
                        $separator = '|';
                    }

                    return implode($separator, $errors);
                } else {
                    return $errors;
                }
            }
        }

        return false;
    }
}
