<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Mailer;
use Yii;
use fec\helpers\CConfig;
use fec\controllers\FecController;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Email
{
	
	public static function getSubjectAndBody($block,$viewPath,$langCode=''){
		if(!$langCode){
			$langCode = Yii::$service->store->currentLangCode;
		}
		if(!$langCode){
			Yii::$service->helper->errors->add('langCode is empty');
			return ;
		}
			
		$bodyViewFile	= $viewPath.'/body_'.$langCode.'.php';
		$bodyConfigKey = [
			'class' => $block,
			'view'  => $bodyViewFile,
		];
		$subjectViewFile	= $viewPath.'/subject_'.$langCode.'.php';
		$subjectConfigKey = [
			'view'  => $subjectViewFile,
		];
		$emailSubject = Yii::$service->page->widget->render($subjectConfigKey);
		$emailBody = Yii::$service->page->widget->render($bodyConfigKey);
		
		return [$emailSubject,$emailBody];
	}
	
	
	/**
	 * @property $toEmail | String   send to email address.
	 *
	 */
	public static function sendRegisterEmail($toEmail){
		$registerParam = Yii::$app->getModule('customer')->params['register'];
		if(isset($registerParam['email']['enable']) && $registerParam['email']['enable']){
			$mailerConfigParam = '';
			if(isset($registerParam['email']['mailerConfig']) && $registerParam['email']['mailerConfig']){
				$mailerConfigParam = $registerParam['email']['mailerConfig'];	
			}
			if(isset($registerParam['email']['block']) && $registerParam['email']['block']){
				$block = $registerParam['email']['block'];
			}
			if(isset($registerParam['email']['viewPath']) && $registerParam['email']['viewPath']){
				$viewPath = $registerParam['email']['viewPath'];
			}
			if($block && $viewPath){
				list($subject,$htmlBody) = \fecshop\app\appfront\modules\Mailer\Email::getSubjectAndBody($block,$viewPath);
				Yii::$service->email->send($toEmail,$subject,$htmlBody,$mailerConfigParam);
	
			}
		
		}
	}
	
	
	public static function sendLoginEmail($toEmail){
		$registerParam = Yii::$app->getModule('customer')->params['login'];
		if(isset($registerParam['email']['enable']) && $registerParam['email']['enable']){
			$mailerConfigParam = '';
			if(isset($registerParam['email']['mailerConfig']) && $registerParam['email']['mailerConfig']){
				$mailerConfigParam = $registerParam['email']['mailerConfig'];	
			}
			if(isset($registerParam['email']['block']) && $registerParam['email']['block']){
				$block = $registerParam['email']['block'];
			}
			if(isset($registerParam['email']['viewPath']) && $registerParam['email']['viewPath']){
				$viewPath = $registerParam['email']['viewPath'];
			}
			if($block && $viewPath){
				list($subject,$htmlBody) = \fecshop\app\appfront\modules\Mailer\Email::getSubjectAndBody($block,$viewPath);
				Yii::$service->email->send($toEmail,$subject,$htmlBody,$mailerConfigParam);
	
			}
			
		
		}
	}
	
	
}
