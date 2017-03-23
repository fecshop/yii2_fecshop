<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\email;
use Yii;
use fec\helpers\CConfig;
use fec\controllers\FecController;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Customer
{
	
	/**
	 * 邮件模板部分配置
	 */
	public $emailTheme;
	
	
	
	/**
	 * @property $toEmail | String   send to email address.
	 * 客户注册用户发送邮件
	 * Yii::$service->email->customer->sendRegisterEmail($emailInfo);
	 */
	public function sendRegisterEmail($emailInfo){
		$toEmail 		= $emailInfo['email'];
		$registerInfo 	= $this->emailTheme['register'];
		if(isset($registerInfo['enable']) && $registerInfo['enable']){
			$mailerConfigParam = '';
			if(isset($registerInfo['mailerConfig']) && $registerInfo['mailerConfig']){
				$mailerConfigParam = $registerInfo['mailerConfig'];	
			}
			if(isset($registerInfo['widget']) && $registerInfo['widget']){
				$widget = $registerInfo['widget'];
			}
			if(isset($registerInfo['viewPath']) && $registerInfo['viewPath']){
				$viewPath = $registerInfo['viewPath'];
			}
			if($widget && $viewPath){
				list($subject,$htmlBody) = Yii::$service->email->getSubjectAndBody($widget,$viewPath,'',$emailInfo);
				$sendInfo = [
					'to' 		=> $toEmail,
					'subject' 	=> $subject,
					'htmlBody' => $htmlBody,
					'senderName'=> Yii::$service->store->currentStore,
				];
				Yii::$service->email->send($sendInfo,$mailerConfigParam);
				return true;
			}
		}
	}
	
	/**
	 * @property $emailInfo | Array
	 * 客户登录账号发送邮件
	 */
	public  function sendLoginEmail($emailInfo){
		$toEmail 		= $emailInfo['email'];
		$loginInfo 		= $this->emailTheme['login'];
		if(isset($loginInfo['enable']) && $loginInfo['enable']){
			$mailerConfigParam = '';
			if(isset($loginInfo['mailerConfig']) && $loginInfo['mailerConfig']){
				$mailerConfigParam = $loginInfo['mailerConfig'];	
			}
			if(isset($loginInfo['widget']) && $loginInfo['widget']){
				$widget = $loginInfo['widget'];
			}
			if(isset($loginInfo['viewPath']) && $loginInfo['viewPath']){
				$viewPath = $loginInfo['viewPath'];
			}
			if($widget && $viewPath){
				list($subject,$htmlBody) = Yii::$service->email->getSubjectAndBody($widget,$viewPath,'',$emailInfo);
				$sendInfo = [
					'to' 		=> $toEmail,
					'subject' 	=> $subject,
					'htmlBody' 	=> $htmlBody,
					'senderName'=> Yii::$service->store->currentStore,
				];
				Yii::$service->email->send($sendInfo,$mailerConfigParam);
				return true;
			}
		}
	}
	
	
	/**
	 * @property $emailInfo | Array
	 * 客户忘记秒发送的邮件
	 */
	public function sendForgotPasswordEmail($emailInfo){
		$toEmail 		= $emailInfo['email'];
		$forgotPasswordInfo 		= $this->emailTheme['forgotPassword'];
		if(isset($forgotPasswordInfo['enable']) && $forgotPasswordInfo['enable']){
			$mailerConfigParam = '';
			if(isset($forgotPasswordInfo['mailerConfig']) && $forgotPasswordInfo['mailerConfig']){
				$mailerConfigParam = $forgotPasswordInfo['mailerConfig'];	
			}
			if(isset($forgotPasswordInfo['widget']) && $forgotPasswordInfo['widget']){
				$widget = $forgotPasswordInfo['widget'];
			}
			if(isset($forgotPasswordInfo['viewPath']) && $forgotPasswordInfo['viewPath']){
				$viewPath = $forgotPasswordInfo['viewPath'];
			}
			if($widget && $viewPath){
				list($subject,$htmlBody) = Yii::$service->email->getSubjectAndBody($widget,$viewPath,'',$emailInfo);
				$sendInfo = [
					'to' 		=> $toEmail,
					'subject' 	=> $subject,
					'htmlBody' 	=> $htmlBody,
					'senderName'=> Yii::$service->store->currentStore,
				];
				Yii::$service->email->send($sendInfo,$mailerConfigParam);
				return true;
			}
		}
	}
	/**
	 * 超时时间:忘记密码发送邮件，内容中的修改密码链接的超时时间。
	 */
	public function getPasswordResetTokenExpire(){
		$forgotPasswordInfo 		= $this->emailTheme['forgotPassword'];
		if(isset($forgotPasswordInfo['passwordResetTokenExpire']) && $forgotPasswordInfo['passwordResetTokenExpire']){
			return $forgotPasswordInfo['passwordResetTokenExpire'];
		}		
	}
	
	/**
	 * @property $emailInfo | Array ， 数组
	 * 客户联系我们邮件。
	 */
	public function sendContactsEmail($emailInfo){
		
		$contactsInfo = $this->emailTheme['contacts'];
		$toEmail 			= $contactsInfo['address'];
		if(!$toEmail){
			Yii::$service->page->message->addError(['Contact us : receive email is empty , you must config it in email customer contacts email']);
			return;
		} 
		if(isset($contactsInfo['enable']) && $contactsInfo['enable']){
			$mailerConfigParam = '';
			if(isset($contactsInfo['mailerConfig']) && $contactsInfo['mailerConfig']){
				$mailerConfigParam = $contactsInfo['mailerConfig'];	
			}
			if(isset($contactsInfo['widget']) && $contactsInfo['widget']){
				$widget = $contactsInfo['widget'];
			}
			if(isset($contactsInfo['viewPath']) && $contactsInfo['viewPath']){
				$viewPath = $contactsInfo['viewPath'];
			}
			if($widget && $viewPath){
				list($subject,$htmlBody) = Yii::$service->email->getSubjectAndBody($widget,$viewPath,'',$emailInfo);
				$sendInfo = [
					'to' 		=> $toEmail,
					'subject' 	=> $subject,
					'htmlBody' 	=> $htmlBody,
					'senderName'=> Yii::$service->store->currentStore,
				];
				Yii::$service->email->send($sendInfo,$mailerConfigParam);
				return true;
			}
		}
	}
	
	
	/**
	 * @property $emailInfo | Array ， 数组。
	 * 订阅邮件成功邮件
	 */
	public function sendNewsletterSubscribeEmail($emailInfo){
		$toEmail 			= $emailInfo['email'];
		$newsletterInfo = $this->emailTheme['newsletter'];
		if(isset($newsletterInfo['enable']) && $newsletterInfo['enable']){
			$mailerConfigParam = '';
			if(isset($newsletterInfo['mailerConfig']) && $newsletterInfo['mailerConfig']){
				$mailerConfigParam = $newsletterInfo['mailerConfig'];	
			}
			if(isset($newsletterInfo['widget']) && $newsletterInfo['widget']){
				$widget = $newsletterInfo['widget'];
			}
			if(isset($newsletterInfo['viewPath']) && $newsletterInfo['viewPath']){
				$viewPath = $newsletterInfo['viewPath'];
			}
			if($widget && $viewPath){
				list($subject,$htmlBody) = Yii::$service->email->getSubjectAndBody($widget,$viewPath,'',$emailInfo);
				$sendInfo = [
					'to' 		=> $toEmail,
					'subject' 	=> $subject,
					'htmlBody' 	=> $htmlBody,
					'senderName'=> Yii::$service->store->currentStore,
				];
				Yii::$service->email->send($sendInfo,$mailerConfigParam);
				return true;
			}
		}
	}
}
