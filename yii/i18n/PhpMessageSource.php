<?php

namespace fecshop\yii\i18n;

use Yii;
use yii\i18n\PhpMessageSource as YiiPhpMessageSource;

class PhpMessageSource extends YiiPhpMessageSource
{
    
    protected function loadMessages($category, $language)
    {
        $messageFile = $this->getMessageFilePath($category, $language);
        if(is_array($messageFile)){
			foreach($messageFile as $messFile){
				$messages = $this->loadMessagesFromFile($messFile);
		
				$fallbackLanguage = substr($language, 0, 2);
				$fallbackSourceLanguage = substr($this->sourceLanguage, 0, 2);

				if ($language !== $fallbackLanguage) {
					$messages = $this->loadFallbackMessages($category, $fallbackLanguage, $messages, $messFile);
				} elseif ($language === $fallbackSourceLanguage) {
					$messages = $this->loadFallbackMessages($category, $this->sourceLanguage, $messages, $messFile);
				} else {
					if ($messages === null) {
						Yii::error("The message file for category '$category' does not exist: $messFile", __METHOD__);
					}
				}
			}
			return $messages;
		}else{
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

			return (array) $messages;
		}
		
    }

    /**
     * The method is normally called by [[loadMessages]] to load the fallback messages for the language.
     * Method tries to load the $category messages for the $fallbackLanguage and adds them to the $messages array.
     *
     * @param string $category the message category
     * @param string $fallbackLanguage the target fallback language
     * @param array $messages the array of previously loaded translation messages.
     * The keys are original messages, and the values are the translated messages.
     * @param string $originalMessageFile the path to the file with messages. Used to log an error message
     * in case when no translations were found.
     * @return array the loaded messages. The keys are original messages, and the values are the translated messages.
     * @since 2.0.7
     */
    protected function loadFallbackMessages($category, $fallbackLanguage, $messages, $originalMessageFile)
    {
        $fallbackMessageFile = $this->getMessageFilePath($category, $fallbackLanguage);
        
		if(is_array($fallbackMessageFile)){
			foreach($fallbackMessageFile as $file){
				$fallbackMessages = $this->loadMessagesFromFile($file);

				if (
					$messages === null && $fallbackMessages === null
					&& $fallbackLanguage !== $this->sourceLanguage
					&& $fallbackLanguage !== substr($this->sourceLanguage, 0, 2)
				) {
					Yii::error("The message file for category '$category' does not exist: $originalMessageFile "
						. "Fallback file does not exist as well: $file", __METHOD__);
				} elseif (empty($messages)) {
					return $fallbackMessages;
				} elseif (!empty($fallbackMessages)) {
					foreach ($fallbackMessages as $key => $value) {
						if (!empty($value) && empty($messages[$key])) {
							$messages[$key] = $fallbackMessages[$key];
						}
					}
				}
				return (array) $messages;
			}
		}else{
			$fallbackMessages = $this->loadMessagesFromFile($fallbackMessageFile);

			if (
				$messages === null && $fallbackMessages === null
				&& $fallbackLanguage !== $this->sourceLanguage
				&& $fallbackLanguage !== substr($this->sourceLanguage, 0, 2)
			) {
				Yii::error("The message file for category '$category' does not exist: $originalMessageFile "
					. "Fallback file does not exist as well: $fallbackMessageFile", __METHOD__);
			} elseif (empty($messages)) {
				return $fallbackMessages;
			} elseif (!empty($fallbackMessages)) {
				foreach ($fallbackMessages as $key => $value) {
					if (!empty($value) && empty($messages[$key])) {
						$messages[$key] = $fallbackMessages[$key];
					}
				}
			}

			return (array) $messages;
		}
		
    }

    /**
     * Returns message file path for the specified language and category.
     *
     * @param string $category the message category
     * @param string $language the target language
     * @return string path to message file
     */
    protected function getMessageFilePath($category, $language)
    {
		if(is_array($this->basePath)){
			$messageFiles = [];
			foreach($this->basePath as $bp){
				$messageFile = Yii::getAlias($bp) . "/$language/";
				if (isset($this->fileMap[$category])) {
					$messageFile .= $this->fileMap[$category];
				} else {
					$messageFile .= str_replace('\\', '/', $category) . '.php';
				}
				$messageFiles[] = $messageFile;
			}
			return $messageFiles;
		}else{
			$messageFile = Yii::getAlias($this->basePath) . "/$language/";
			if (isset($this->fileMap[$category])) {
				$messageFile .= $this->fileMap[$category];
			} else {
				$messageFile .= str_replace('\\', '/', $category) . '.php';
			}

			return $messageFile;
		}
		
		
       
    }

   
}
