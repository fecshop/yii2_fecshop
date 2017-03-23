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
	public $mailerInfo;
	/**
	 * 邮件模板部分动态数据提供类的返回数据的函数名字，使用默认值即可。
	 */
	public $defaultObMethod = 'getLastData';
	
	protected $_mailer;  # Array
	protected $_mailer_from; #Array
	protected $_from;
	
	
	/**
	 * 在邮箱中显示的 邮箱地址
	 */
	public  function contactsEmailAddress(){
		$mailerInfo =  $this->mailerInfo;
		if(isset($mailerInfo['contacts']['emailAddress'])){
			return $mailerInfo['contacts']['emailAddress'];
		}
	}
	/**
	 * 在邮箱中显示的 商城名字(Store Name)
	 */
	public  function storeName(){
		$mailerInfo =  $this->mailerInfo;
		if(isset($mailerInfo['storeName'])){
			return $mailerInfo['storeName'];
		}
	}
	/**
	 * 在邮件中显示的 联系手机号
	 * Yii::$service->email->customer->contactsPhone();
	 */
	public  function contactsPhone(){
		$mailerInfo =  $this->mailerInfo;
		if(isset($mailerInfo['phone'])){
			return $mailerInfo['phone'];
		}
	}
	
	
	
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
					'host' => 'smtp.qq.net',
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
	 * @property $sendInfo | Array ， example：
	 * [
	 *	'to' => $to,
	 *	'subject' => $subject,
	 *	'htmlBody' => $htmlBody,
	 *	'senderName'=> $senderName,
	 * ]
	 * @property $mailerConfigParam | array or String， 具体为@fecshop/config/services/Email.php
	 * 中的mailerConfig的配置对应的值。
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
	
	
	/**
	 * @property  $widget | String，邮件模板中的动态数据的提供部分的class
	 * @property  $viewPath | String，邮件模板中的显示数据的html部分。
	 * @property  $langCode 当前的语言
	 * @proeprty  $params 传递给 $widget 对应的class，用于将数据传递过去。
	 * 根据提供的动态数据提供者$widget 和 view路径$viewPath，语言$langCode，以及其他参数$params（这个数组会设置到$widget对应的class的params变量中）
	 * 最终得到邮件标题和邮件内容
	 * 如果当前语言的邮件模板不存在，则使用默认语言的模板。
	 * 关于函数参数的例子值，可以参看配置文件 @fecshop/config/services/Email.php
	 */
	public  function getSubjectAndBody($widget,$viewPath,$langCode='',$params=[]){
		if(!$langCode){
			$langCode = Yii::$service->store->currentLangCode;
		}
		if(!$langCode){
			Yii::$service->helper->errors->add('langCode is empty');
			return ;
		}
		$defaultLangCode = Yii::$service->fecshoplang->defaultLangCode;		
		# 得到body部分的配置数组
		$bodyViewFile	= $viewPath.'/body_'.$langCode.'.php';
		$bodyViewFilePath = Yii::getAlias($bodyViewFile);
		if(!file_exists($bodyViewFilePath)){ #如果当前语言的模板不存在，则使用默认语言的模板。
			$bodyViewFile	= $viewPath.'/body_'.$defaultLangCode.'.php';
			$bodyViewFilePath = Yii::getAlias($bodyViewFile);
		}
		$bodyConfig = [
			'class' => $widget,
			'view'  => $bodyViewFilePath,
		];
		if(!empty($params)){
			$bodyConfig['params'] = $params;
		}
		# 得到subject部分的配置数组
		$subjectViewFile	= $viewPath.'/subject_'.$langCode.'.php';
		$subjectViewFilePath = Yii::getAlias($subjectViewFile);
		if(!file_exists($subjectViewFilePath)){
			$subjectViewFile	= $viewPath.'/subject_'.$defaultLangCode.'.php';
			$subjectViewFilePath = Yii::getAlias($subjectViewFile);
		}
		
		$subjectConfig = [
			'class' => $widget,
			'view'  => $subjectViewFilePath,
		];
		if(!empty($params)){
			$subjectConfig['params'] = $params;
		}
		$emailSubject 	= $this->getHtmlContent($subjectConfig);
		$emailBody 		= $this->getHtmlContent($bodyConfig);
		return [$emailSubject,$emailBody];
		//$emailSubject = Yii::$service->page->widget->render($subjectConfigKey,$parentThis);
		//$emailBody = Yii::$service->page->widget->render($bodyConfigKey,$parentThis);
	}
	
	
	/**
	 * @property $config | Array,example:
	 *	[
	 *		'class' => $widget,
	 *		'view'  => $subjectViewFile,
	 *		'params'=> $params	
	 *	];
	 * @return String(text)
	 * 通过配置得到邮件内容。
	 */
	public function getHtmlContent($config){
		if(isset($config['view']) && !empty($config['view'])){
			$viewFile = $config['view'];
			unset($config['view']);
			$method = $this->defaultObMethod;
			$ob = Yii::createObject($config);
			$params = $ob->$method();
			return Yii::$app->view->renderFile($viewFile, $params);
		}else{
			//errors
		}
	}
	
	
	/**
	 * @property $email_address | String  邮箱地址字符串
	 * @return boolean 如果格式正确，返回true
	 */
	protected function actionValidateFormat($email_address){
		if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$email_address)){ 
			return true;
		}else{
			return false;
		}

	}
}
