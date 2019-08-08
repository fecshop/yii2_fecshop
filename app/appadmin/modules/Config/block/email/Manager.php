<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\email;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\staticblock.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;
    // 需要配置
    public $_key = 'email';
    public $_type;
    protected $_attrArr = [
        'baseStoreName',
        'baseContactsPhone',
        'baseContactsEmail',
        
        'default_smtp_host',
        'default_smtp_username',
        'default_smtp_password',
        'default_smtp_port',
        'default_smtp_encryption',
        
        'registerEnable',
        'registerAccountIsNeedEnableByEmail',
        'registerAccountEnableTokenExpire',
        'registerWidget',
        'registerViewPath',
        
        'loginEnable',
        'loginWidget',
        'loginViewPath',
        
        'forgotPasswordEnable',
        'forgotPasswordWidget',
        'forgotPasswordViewPath',
        'forgotPasswordResetTokenExpire',
        
        'contactsEnable',
        'contactsWidget',
        'contactsViewPath',
        'contactsEmailAddress',
        
        'newsletterEnable',
        'newsletterWidget',
        'newsletterViewPath',
        
        'orderGuestEnable',
        'orderGuestWidget',
        'orderGuestViewPath',
        
        'orderLoginEnable',
        'orderLoginWidget',
        'orderLoginViewPath',
    ];
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/email/managersave');
        $this->_editFormData = 'editFormData';
        $this->setService();
        $this->_param = CRequest::param();
        $this->_one = $this->_service->getByKey([
            'key' => $this->_key,
        ]);
        if ($this->_one['value']) {
            $this->_one['value'] = unserialize($this->_one['value']);
        }
    }
    
    
    
    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $id = ''; 
        if (isset($this->_one['id'])) {
           $id = $this->_one['id'];
        } 
        return [
            'id'            =>   $id, 
            'editBar'      => $this->getEditBar(),
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'     => $this->_saveUrl,
        ];
    }
    public function setService()
    {
        $this->_service = Yii::$service->storeBaseConfig;
    }
    public function getEditArr()
    {
        $deleteStatus = Yii::$service->customer->getStatusDeleted();
        $activeStatus = Yii::$service->customer->getStatusActive();
        
        return [
            // 需要配置
            [
                'label'  => Yii::$service->page->translate->__('Base Store Name'),
                'name' => 'baseStoreName',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '在邮件中显示的Store的名字',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Base Contacts Phone'),
                'name' => 'baseContactsPhone',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '在邮件中显示的联系电话',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Base Contacts Email'),
                'name' => 'baseContactsEmail',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '在邮件中显示的联系邮箱地址。',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Default Smtp Host'),
                'name' => 'default_smtp_host',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'Smtp Host',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Default Smtp Username'),
                'name' => 'default_smtp_username',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'Smtp Username',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Default Smtp Password'),
                'name' => 'default_smtp_password',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'Smtp Password',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Default Smtp Port'),
                'name' => 'default_smtp_port',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'Smtp Host',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Default Smtp Encryption'),
                'name' => 'default_smtp_encryption',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '一般为tls',
            ],
            [
                'label' => Yii::$service->page->translate->__('Register Enable'),
                'name'  => 'registerEnable',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '注册账户是否发送邮件？'
            ],
            [
                'label' => Yii::$service->page->translate->__('RegisterAccountIsNeedEnableByEmail'),
                'name'  => 'registerAccountIsNeedEnableByEmail',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '新注册用户是否需要邮件激活账户'
            ],
            [
                'label'  => Yii::$service->page->translate->__('RegisterAccountEnableTokenExpire'),
                'name' => 'registerAccountEnableTokenExpire',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '注册账户激活邮件的token的过期时间',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Register Widget'),
                'name' => 'registerWidget',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[注册账户后发送的邮件]邮件模板内容的动态数据提供Block部分',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Register View Path'),
                'name' => 'registerViewPath',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[注册账户后发送的邮件]邮件模板内容的view部分',
            ],
            [
                'label' => Yii::$service->page->translate->__('Login Enable'),
                'name'  => 'loginEnable',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '登陆账户成功是否发送邮件？'
            ],
            [
                'label'  => Yii::$service->page->translate->__('Login Widget'),
                'name' => 'loginWidget',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[登陆账户成功后发送的邮件] 邮件模板内容的动态数据提供Block部分',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Login View Path'),
                'name' => 'loginViewPath',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[登陆账户成功后的发送邮件] 邮件模板内容的view部分',
            ],
            [
                'label' => Yii::$service->page->translate->__('Forgot Password Enable'),
                'name'  => 'forgotPasswordEnable',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '忘记密码是否发送邮件？'
            ],
            [
                'label'  => Yii::$service->page->translate->__('Forgot Password Widget'),
                'name' => 'forgotPasswordWidget',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[忘记密码提交后发送的邮件] 邮件模板内容的动态数据提供Block部分',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Forgot Password ViewPath'),
                'name' => 'forgotPasswordViewPath',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[忘记密码提交后发送的邮件] 邮件模板内容的view部分',
            ],
            [
                'label'  => Yii::$service->page->translate->__('ForgotPasswordResetTokenExpire'),
                'name' => 'forgotPasswordResetTokenExpire',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[忘记密码提交后发送的邮件] ResetToken的过期时间（秒）',
            ],
            [
                'label' => Yii::$service->page->translate->__('Contacts Enable'),
                'name'  => 'contactsEnable',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '联系我们是否发送邮件？'
            ],
            [
                'label'  => Yii::$service->page->translate->__('Contacts Widget'),
                'name' => 'contactsWidget',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[联系我们发送的邮件] 邮件模板内容的动态数据提供Block部分',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Contacts ViewPath'),
                'name' => 'contactsViewPath',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[联系我们发送的邮件] 邮件模板内容的view部分',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Contacts EmailAddress'),
                'name' => 'contactsEmailAddress',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '联系我们接受的邮件地址',
            ],
            [
                'label' => Yii::$service->page->translate->__('Newsletter Enable'),
                'name'  => 'newsletterEnable',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => 'newsletter是否发送邮件？'
            ],
            [
                'label'  => Yii::$service->page->translate->__('Newsletter Widget'),
                'name' => 'newsletterWidget',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[newsletter发送的邮件] 邮件模板内容的动态数据提供Block部分',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Newsletter ViewPath'),
                'name' => 'newsletterViewPath',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[newsletter发送的邮件] 邮件模板内容的view部分',
            ],
            [
                'label' => Yii::$service->page->translate->__('Order Guest Enable'),
                'name'  => 'orderGuestEnable',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '游客下单后，是否发送邮件？'
            ],
            [
                'label'  => Yii::$service->page->translate->__('Order Guest Widget'),
                'name' => 'orderGuestWidget',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[游客下单后，发送的邮件] 邮件模板内容的动态数据提供Block部分',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Order Guest ViewPath'),
                'name' => 'orderGuestViewPath',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[游客下单后，发送的邮件] 邮件模板内容的view部分',
            ],
            [
                'label' => Yii::$service->page->translate->__('Order Login Enable'),
                'name'  => 'orderLoginEnable',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '登陆用户下单后，是否发送邮件？'
            ],
            [
                'label'  => Yii::$service->page->translate->__('Order Login Widget'),
                'name' => 'orderLoginWidget',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[登陆用户下单后发送的邮件] 邮件模板内容的动态数据提供Block部分',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Order Login ViewPath'),
                'name' => 'orderLoginViewPath',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '[登陆用户下单后发送的邮件] 邮件模板内容的view部分',
            ],
        ];
    }
    
    public function getArrParam(){
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        $param = [];
        $attrVals = [];
        foreach($this->_param as $attr => $val) {
            if (in_array($attr, $this->_attrArr)) {
                $attrVals[$attr] = $val;
            } else {
                $param[$attr] = $val;
            }
        }
        $param['value'] = $attrVals;
        $param['key'] = $this->_key;
        
        return $param;
    }
    
    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        // 设置 bdmin_user_id 为 当前的user_id
        $this->_service->saveConfig($this->getArrParam());
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('Save Success'),
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => $errors,
            ]);
            exit;
        }
    }
    
    
    
    public function getVal($name, $column){
        if (is_object($this->_one) && property_exists($this->_one, $name) && $this->_one[$name]) {
            
            return $this->_one[$name];
        }
        $content = $this->_one['value'];
        if (is_array($content) && !empty($content) && isset($content[$name])) {
            
            return $content[$name];
        }
        
        return '';
    }
}