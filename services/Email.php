<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;

/**
 * service mail ：邮件服务部分
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Email extends Service
{
    public $mailerConfig;

    // public $defaultForm;

    public $mailerInfo;

    /**
     * 邮件模板部分动态数据提供类的返回数据的函数名字，使用默认值即可。
     */
    public $defaultObMethod = 'getLastData';

    protected $_mailer;      // Array

    protected $_mailer_from; //Array

    protected $_from;

    public function init()
    {
        parent::init();
        $this->mailerInfo['storeName'] = Yii::$app->store->get('email', 'baseStoreName');
        $this->mailerInfo['phone'] = Yii::$app->store->get('email', 'baseContactsPhone');
        $this->mailerInfo['contacts']['emailAddress'] = Yii::$app->store->get('email', 'baseContactsEmail');
        $this->mailerConfig['default']['class'] = 'yii\swiftmailer\Mailer';
        $this->mailerConfig['default']['transport'] = [
            'class' => 'Swift_SmtpTransport',
            'host'              => Yii::$app->store->get('email', 'default_smtp_host'),            //SMTP Host
            'username'      => Yii::$app->store->get('email', 'default_smtp_username'),     //SMTP 账号
            'password'      => Yii::$app->store->get('email', 'default_smtp_password'),     //SMTP 密码
            'port'              => Yii::$app->store->get('email', 'default_smtp_port'),                     //SMTP 端口
            'encryption'    => Yii::$app->store->get('email', 'default_smtp_encryption'),  
        ];
        $this->mailerConfig['default']['messageConfig'] = ['charset'=>'UTF-8'];
    }
    /**
     * 在邮箱中显示的 邮箱地址
     */
    public function contactsEmailAddress()
    {
        $mailerInfo = $this->mailerInfo;
        if (isset($mailerInfo['contacts']['emailAddress'])) {
            return $mailerInfo['contacts']['emailAddress'];
        }
    }

    /**
     * 在邮箱中显示的 商城名字(Store Name).
     */
    public function storeName()
    {
        $mailerInfo = $this->mailerInfo;
        if (isset($mailerInfo['storeName'])) {
            return $mailerInfo['storeName'];
        }
    }

    /**
     * 在邮件中显示的 联系手机号
     * Yii::$service->email->customer->contactsPhone();.
     */
    public function contactsPhone()
    {
        $mailerInfo = $this->mailerInfo;
        if (isset($mailerInfo['phone'])) {
            return $mailerInfo['phone'];
        }
    }

    /**
     * @param $key | String
     * 得到MailConfig.
     */
    protected function getMailerConfig($key = 'default')
    {
        if (isset($this->mailerConfig[$key]) && $this->mailerConfig[$key]) {
            if (is_array($this->mailerConfig[$key])) {
                return $this->mailerConfig[$key];
            } elseif (is_string($this->mailerConfig[$key])) {
                return $this->getMailerConfig($this->mailerConfig[$key]);
            }
        }

        return '';
    }

    /**
     * 默认的默认form。邮件from.
     */
    protected function defaultForm($mailerConfig)
    {
        if (isset($mailerConfig['transport']['username'])) {
            if (!empty($mailerConfig['transport']['username'])) {
                return $mailerConfig['transport']['username'];
            }
        }
        return '';
    }

    /**
     * @param $mailerConfig | Array or String  mailer组件的配置， 您可以设置为空，使用默认的邮箱配置，也可以设置为字符串，字符串对应配置中$mailerConfig对应的key。
     * 1.打开@fecshop/config/services/Config.php ， 可以看到 $mailerConfig =>  ['default' => [...]]的配置，当该参数为空或'default'的时候，就使用该默认配置。
     * 2.当该参数设置除default之外的字符串的时候，就是 $mailerConfig 配置数组中其他的key对应的配置，如果不存在，则返回为空。
     * 3.您可以完全不使用配置数组中的配置，完全动态配置他，下面该参数动态配置的例子：
     * 注意：如果自定义传递邮箱配置，不同的配置，要使用不同的configKey
     * [
     *      'configKey' => [   # 唯一key，这个必须在 @fecshop/config/services/Config.php 中 $mailerConfig 配置数组中不存在该key值，否则将会重复。
     *          'class' => 'yii\swiftmailer\Mailer',  # email组件对应的class
     *          'transport' => [            # 组件注入的配置参数。
     *             'class' => 'Swift_SmtpTransport',
     *             'host' => 'smtp.qq.net',
     *             'username' => 'support@mail.com',
     *             'password' => 'xxxx',
     *             'port' => '587',
     *            'encryption' => 'tls',
     *          ],
     *          'messageConfig'=>[
     *              'charset'=>'UTF-8',
     *          ],
     *      ] //数组中只能一个configKey,配置多个无效，只有第一个有效。
     * ]
     * @return yii的mail组件compoent
     * 通过 $mailerConfigParam 的三种方式，可以使用系统配置的mail组件，也可以自己动态配置mail组件
     * 增强mail组件使用的方面和灵活。
     */
    protected function actionMailer($mailerConfigParam = '')
    {
        if (!$mailerConfigParam) {
            $key = 'default';
        } elseif (is_array($mailerConfigParam)) {
            $key_arr = array_keys($mailerConfigParam);
            $key = $key_arr[0];
        } elseif (is_string($mailerConfigParam)) {
            $key = $mailerConfigParam;
        } else {
            Yii::$service->helper->errors->add('you mail config param is not correct');
            return;
        }
        if (!$key) {
            Yii::$service->helper->errors->add('mail config key is empty');
            return;
        }
        if (!$this->_mailer[$key]) {
            $component_name = 'mailer_'.$key;
            if (!$mailerConfigParam) {
                $mailerConfig = $this->getMailerConfig();
                if (!is_array($mailerConfig) || empty($mailerConfig)) {
                    Yii::$service->helper->errors->add('you must config mail var $mailerConfig is your mail config file');
                    
                    return;
                }
                Yii::$app->set($component_name, $mailerConfig);
            } elseif (is_array($mailerConfigParam)) {
                $mailerConfig = $mailerConfigParam[$key];
                if (!is_array($mailerConfig) || empty($mailerConfig)) {
                    Yii::$service->helper->errors->add('function param $mailerConfigParam format is not correct');
                    
                    return;
                }
                $component_name .= 'custom_';
                Yii::$app->set($component_name, $mailerConfig);
            } elseif (is_string($mailerConfigParam)) {
                $mailerConfig = $this->getMailerConfig($mailerConfigParam);
                if (!is_array($mailerConfig) || empty($mailerConfig)) {
                    Yii::$service->helper->errors->add('string param ($mailerConfigParam) can not find in config file , you must config var $mailerConfig in mail config file');
                    
                    return;
                }
                Yii::$app->set($component_name, $mailerConfig);
            }
            $this->_mailer_from[$key] = $this->defaultForm($mailerConfig);
            $this->_mailer[$key] = Yii::$app->get($component_name);
        }
        $this->_from = isset($this->_mailer_from[$key]) ? $this->_mailer_from[$key] : '';
        return isset($this->_mailer[$key]) ? $this->_mailer[$key] : '' ;
    }

    /**
     * @param $sendInfo | Array ， example：
     * [
     *	'to' => $to,
     *	'subject' => $subject,
     *	'htmlBody' => $htmlBody,
     *	'senderName'=> $senderName,
     * ]
     * @param $mailerConfigParam | array or String，对于该参数的配置，
     * 您可以参看上面的函数 function actionMailer($mailerConfigParam = '') 或者到 @fecshop/config/services/Email.php参看 $mailerConfig的配置
     * 该函数用于发送邮件.
     */
    protected function actionSend($sendInfo, $mailerConfigParam = '')
    {
        $to         = isset($sendInfo['to']) ? $sendInfo['to'] : '';
        $subject    = isset($sendInfo['subject']) ? $sendInfo['subject'] : '';
        $htmlBody   = isset($sendInfo['htmlBody']) ? $sendInfo['htmlBody'] : '';
        $senderName = isset($sendInfo['senderName']) ? $sendInfo['senderName'] : '';
        if (!$subject) {
            Yii::$service->helper->errors->add('email title is empty');

            return false;
        }
        if (!$htmlBody) {
            Yii::$service->helper->errors->add('email body is empty');

            return false;
        }

        $mailer = $this->mailer($mailerConfigParam);
        if (!$mailer) {
            Yii::$service->helper->errors->add('compose is empty, you must check you email config');

            return false;
        }

        if (!$this->_from) {
            Yii::$service->helper->errors->add('email send from is empty');

            return false;
        } else {
            $from = $this->_from;
        }
        if ($senderName) {
            $setFrom = [$from => $senderName];
        } else {
            $setFrom = $from;
        }
        try {
            $mailer->compose()
                ->setFrom($setFrom)
                ->setTo($to)
                ->setSubject($subject)
                ->setHtmlBody($htmlBody)
                ->send();
            return true;
        } catch (\Swift_TransportException $e) {
            $errorMessage = $e->getMessage();
            Yii::$service->helper->errors->add($errorMessage);
            return false;
        } catch (\Exception $e) {
            Yii::$service->helper->errors->add('send email fail');
            return false;
        }
    }

    /**
     * @param  $widget | String，邮件模板中的动态数据的提供部分的class
     * @param  $viewPath | String，邮件模板中的显示数据的html部分。
     * @param  $langCode 当前的语言
     * @proeprty  $params 传递给 $widget 对应的class，用于将数据传递过去。
     * 根据提供的动态数据提供者$widget 和 view路径$viewPath，语言$langCode，以及其他参数$params（这个数组会设置到$widget对应的class的params变量中）
     * 最终得到邮件标题和邮件内容
     * 如果当前语言的邮件模板不存在，则使用默认语言的模板。
     * 关于函数参数的例子值，可以参看配置文件 @fecshop/config/services/Email.php
     * 打开这个配置文件，可以看到 emailTheme部分的配置， 里面有 widget 和 viewPath的配置，
     * 配置和下面的参数是对应起来的，在执行下面的函数，会使用配置里面的参数，譬如：
     * @fecshop/services/email/Customer.php 中的函数  sendRegisterEmail($emailInfo) 里面对该函数的调用。
     */
    public function getSubjectAndBody($widget, $viewPath, $langCode = '', $params = [])
    {
        if (!$langCode) {
            $langCode = Yii::$service->store->currentLangCode;
        }
        if (!$langCode) {
            Yii::$service->helper->errors->add('langCode is empty');

            return;
        }
        $defaultLangCode = Yii::$service->fecshoplang->defaultLangCode;
        // 得到body部分的配置数组
        $bodyViewFile = $viewPath.'/body_'.$langCode.'.php';
        $bodyViewFilePath = Yii::getAlias($bodyViewFile);
        if (!file_exists($bodyViewFilePath)) { //如果当前语言的模板不存在，则使用默认语言的模板。
            $bodyViewFile = $viewPath.'/body_'.$defaultLangCode.'.php';
            $bodyViewFilePath = Yii::getAlias($bodyViewFile);
        }
        $bodyConfig = [
            'class' => $widget,
            'view'  => $bodyViewFilePath,
        ];
        if (!empty($params)) {
            $bodyConfig['params'] = $params;
        }
        // 得到subject部分的配置数组
        $subjectViewFile = $viewPath.'/subject_'.$langCode.'.php';
        $subjectViewFilePath = Yii::getAlias($subjectViewFile);
        if (!file_exists($subjectViewFilePath)) {
            $subjectViewFile = $viewPath.'/subject_'.$defaultLangCode.'.php';
            $subjectViewFilePath = Yii::getAlias($subjectViewFile);
        }

        $subjectConfig = [
            'class' => $widget,
            'view'  => $subjectViewFilePath,
        ];
        if (!empty($params)) {
            $subjectConfig['params'] = $params;
        }
        $emailSubject = $this->getHtmlContent($subjectConfig);
        $emailBody = $this->getHtmlContent($bodyConfig);

        return [$emailSubject, $emailBody];
        //$emailSubject = Yii::$service->page->widget->render($subjectConfigKey,$parentThis);
        //$emailBody = Yii::$service->page->widget->render($bodyConfigKey,$parentThis);
    }

    /**
     * @param $config | Array,example:
     *	[
     *		'class' => $widget,
     *		'view'  => $subjectViewFile,
     *		'params'=> $params
     *	];
     * @return String(text)
     *                      通过配置得到邮件内容，原理是使用了 Yii::$app->view->renderFile()函数。
     */
    public function getHtmlContent($config)
    {
        if (isset($config['view']) && !empty($config['view'])) {
            $viewFile = $config['view'];
            unset($config['view']);
            $method = $this->defaultObMethod;
            $ob = Yii::createObject($config);
            $params = $ob->$method();

            return Yii::$app->view->renderFile($viewFile, $params);
        } else {
            //errors
        }
    }

    /**
     * @param $email_address | String  邮箱地址字符串
     * @return bool 如果格式正确，返回true
     */
    protected function actionValidateFormat($email_address)
    {
        if (preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email_address)) {
            return true;
        } else {
            return false;
        }
    }
}
