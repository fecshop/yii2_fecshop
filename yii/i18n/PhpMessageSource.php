<?php

namespace fecshop\yii\i18n;

use Yii;
use yii\i18n\PhpMessageSource as YiiPhpMessageSource;

class PhpMessageSource extends YiiPhpMessageSource
{
    public $basePaths = [];
    protected function loadMessages($category, $language)
    {
		$message_merge = [];
		foreach($this->basePaths as $base){
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
			if(is_array($messages)){
				$message_merge = array_merge($message_merge,$messages);
			}
		}
		//var_dump($message_merge);exit;
        return (array) $message_merge;
    }

   
}
