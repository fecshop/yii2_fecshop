<?php
/**
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
 * Page Translate services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Translate extends Service
{
    /**
     * current i18n category. it will set in controller init .
     * example: fecshop\app\appfront\modules\AppfrontController
     * code: 	Yii::$service->page->translate->category = 'appfront';.
     * 入口的名字。
     */
    public $category;

    /**
     * @property $text | String，需要翻译的文字字符串
     * @property $arr | Array，一些动态变量（不需要翻译）的相应的值
     * 下面是一个调用该方法的例子：
     * Yii::$service->page->translate->__('Hello, {username}!', ['username' => $username]);.
     */
    public function __($text, $arr = [])
    {
        if (!$this->category) {
            return $text;
        } else {
            return Yii::t($this->category, $text, $arr);
        }
    }
    /**
     * @property $language | String，设置当前的语言。
     * 语言部分使用的是Yii2的语言功能。
     */
    protected function actionSetLanguage($language)
    {
        Yii::$app->language = $language;
    }
}
