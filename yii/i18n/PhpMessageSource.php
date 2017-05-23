<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\yii\i18n;

use Yii;
use yii\i18n\PhpMessageSource as YiiPhpMessageSource;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class PhpMessageSource extends YiiPhpMessageSource
{
    public $basePaths = [];

    protected function loadMessages($category, $language)
    {
        $message_merge = [];
        if (is_array($this->basePaths) && !empty($this->basePaths)) {
            $paths = array_reverse($this->basePaths);
            foreach ($paths as $base) {
                $this->basePath = $base;
                $messageFile = $this->getMessageFilePath($category, $language);
                $messages = $this->loadMessagesFromFile($messageFile);

                $fallbackLanguage = substr($language, 0, 2);
                $fallbackSourceLanguage = substr($this->sourceLanguage, 0, 2);

                if ($language !== $fallbackLanguage) {
                    $messages = $this->loadFallbackMessages($category, $fallbackLanguage, $messages, $messageFile);
                } elseif ($language === $fallbackSourceLanguage) {
                    $messages = $this->loadFallbackMessages($category, $this->sourceLanguage, $messages, $messageFile);
                } else {
                    if ($messages === null) {
                        Yii::error("The message file for category '$category' does not exist: $messageFile", __METHOD__);
                    }
                }
                if (is_array($messages)) {
                    $message_merge = array_merge($message_merge, $messages);
                }
            }
            //var_dump($message_merge);exit;
            return (array) $message_merge;
        }
    }
}
