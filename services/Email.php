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
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use yii\base\BootstrapInterface;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Email extends Service
{
	public $mailerConfig;
	public $defaultForm;
	
	protected $_mailer;  # Array
	protected $_mailer_from; #Array
	protected $_from;
	/**
	 * 得到MailConfig
	 */
	protected function getMailerConfig($key = 'default'){
		if(isset($this->mailerConfig[$key]) && $this->mailerConfig[$key]){
			if(is_array($this->mailerConfig[$key])){
				return $this->mailerConfig[$key];
			}else if(is_string($this->mailerConfig[$key])){
				return $this->getMailerConfig($this->mailerConfig[$key]);
			}
		}
		return '';
	}
	/**
	 * 默认的默认form。邮件from
	 */
	protected function defaultForm($mailerConfig){
		if(isset($mailerConfig['transport']['username'])){
			if(!empty($mailerConfig['transport']['username'])){
				return $mailerConfig['transport']['username'];
			}
			
		}
		return ;
	}
	/**
	 * @property $mailerConfig | Array or String  mailer组件的配置，下面是例子，
		您可以使用在email service里面默认的配置，也可以动态配置他，下面是参数的例子：
		注意：如果自定义传递邮箱配置，不同的配置，要使用不同的configKey
		[
			'configKey' => [
				'class' => 'yii\swiftmailer\Mailer',
				'transport' => [
					'class' => 'Swift_SmtpTransport',
					'host' => 'smtp.sendgrid.net',
					'username' => 'support@mail.com',
					'password' => 'xxxx', 
					'port' => '587',
					'encryption' => 'tls',
				],
				'messageConfig'=>[  
				   'charset'=>'UTF-8',  
				],  
			],
		]
	 * @return yii的mail组件、
	 *
	 */
	protected function actionMailer($mailerConfigParam = ''){
		
		if(!$mailerConfigParam){
			$key = 'default';
		}else if(is_array($mailerConfigParam)){
			$key_arr = array_keys($mailerConfigParam);
			$key = $key_arr[0];
		}else if(is_string($mailerConfigParam)){
			$key = $mailerConfigParam;
		}else{
			return;
		}
		if(!$key){
			return;
		}
		
		//exit; 
		if(!$this->_mailer[$key]){
			$component_name = 'mailer_'.$key;
			if(!$mailerConfigParam){
				$mailerConfig = $this->getMailerConfig();
				if(!is_array($mailerConfig) || empty($mailerConfig)){
					return;
				}
				Yii::$app->set($component_name,$mailerConfig);
			}else if(is_array($mailerConfigParam)){
				$mailerConfig = $mailerConfigParam[$key];
				if(!is_array($mailerConfig) || empty($mailerConfig)){
					return;
				}
				$component_name .= 'custom_';
				Yii::$app->set($component_name,$mailerConfig);
			}else if(is_string($mailerConfigParam)){
				$mailerConfig = $this->getMailerConfig($mailerConfigParam);
				if(!is_array($mailerConfig) || empty($mailerConfig)){
					return;
				}
				Yii::$app->set($component_name,$mailerConfig);
			
			}
			$this->_mailer_from[$key] = $this->defaultForm($mailerConfig);
			$this->_mailer[$key] = Yii::$app->get($component_name);
		}
		$this->_from = $this->_mailer_from[$key];
		//var_dump($this->_mailer[$key]);exit;
		return $this->_mailer[$key];
	}
	
	/**
	 * [
		'to' => $to,
		'subject' => $subject,
		'htmlBody' => $htmlBody,
		'senderName'=> $senderName,
	 ]
	 */
	protected function actionSend($sendInfo,$mailerConfigParam=''){
		$to 		= isset($sendInfo['to']) ? $sendInfo['to'] : '';
		$subject 	= isset($sendInfo['subject']) ? $sendInfo['subject'] : '';
		$htmlBody 	= isset($sendInfo['htmlBody']) ? $sendInfo['htmlBody'] : '';
		$senderName = isset($sendInfo['senderName']) ? $sendInfo['senderName'] : '';
		/*
		$this->mailer()->compose()
			->setFrom('support@fecshop.com')
			->setTo('2851823529@qq.com')
			->setSubject('111111Message subject222')
			->setHtmlBody('<b>HTML content333333333df</b>')
			->send();
		*/
		if(!$subject){
			Yii::$service->helper->errors->add('email title is empty');
			return;
		}
		if(!$htmlBody){
			Yii::$service->helper->errors->add('email body is empty');
			return;
		}
		
		$mailer = $this->mailer($mailerConfigParam);
		if(!$mailer){
			//error
			Yii::$service->helper->errors->add('compose is empty, you must check you email config');
			return;
		}
		
		if(!$this->_from){
			//error
			Yii::$service->helper->errors->add('email send from is empty');				
			return;
		}else{
			$from = $this->_from;
		}
		/*
		echo $from;
		echo '<br/><br/>';
		echo $to;echo '<br/><br/>';
		echo $subject;echo '<br/><br/>';
		echo $htmlBody;echo '<br/><br/>';
		var_dump($mailer);
		*/
		if($senderName){
			$setFrom = [$from => $senderName];
		}else{
			$setFrom = $from;
		}
		$mailer->compose()
			->setFrom($setFrom)
			->setTo($to)
			->setSubject($subject)
			->setHtmlBody($htmlBody)
			->send();
			
	}
	
	
}
