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
 * Translate sub service of [[\Yii::$service->page]] Page.
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Translate extends Service
{
    /**
     * Current i18n translate category name.
     * The category value will be set in init().
     *
     * You can see in the following example:
     * ```php
     * \Yii::$service->page->translate->category = 'appserver';
     * ```
     */
    public $category;

    /**
     * @param string $text 需要翻译的文字字符串
     * @param array $arr 一些动态变量（不需要翻译）的相应的值
     *
     * You can see in the following example：
     *
     * ```php
     * \Yii::$service->page->translate->__('Hello, {username}!', ['username' => $username]);
     * ```
     *
     * @return string the translated language.
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
     * Set current application's language.
     *
     * @param string $language the language to be set.
     */
    protected function actionSetLanguage($language)
    {
        Yii::$app->language = $language;
    }
}
