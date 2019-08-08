<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\appfrontaccount;

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
    public $_key = 'appfront_account';
    public $_type;
    protected $_attrArr = [
        'registerSuccessAutoLogin',
        'registerSuccessRedirectUrlKey',
        'registerPageCaptcha',
        
        'loginPageCaptcha',
        
        'forgotPasswordCaptcha',
        'contactsCaptcha',
        
        
        'min_name_length',
        'max_name_length',
        'min_pass_length',
        'max_pass_length',
    ];
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/appfrontaccount/managersave');
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
                'label' => Yii::$service->page->translate->__('Register Page Captcha'),
                'name'  => 'registerPageCaptcha',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '注册页面的验证码是否开启'
            ],
            [
                'label' => Yii::$service->page->translate->__('Register Success AutoLogin'),
                'name'  => 'registerSuccessAutoLogin',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '账号注册成功后，是否自动登录'
            ],
            [
                'label'  => Yii::$service->page->translate->__('Register Success RedirectUrlKey'),
                'name' => 'registerSuccessRedirectUrlKey',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '注册登录成功后，跳转的url',
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Login Page Captcha'),
                'name'  => 'loginPageCaptcha',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '登录页面的验证码是否开启'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Forgot Password Captcha'),
                'name'  => 'forgotPasswordCaptcha',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '忘记密码页面的验证码是否开启'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Contacts Captcha'),
                'name'  => 'contactsCaptcha',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '联系我们页面的验证码是否开启'
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Min Name Length'),
                'name' => 'min_name_length',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '注册账号的firstname的最小长度',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Max Name Length'),
                'name' => 'max_name_length',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '注册账号的firstname, lastname的最大长度',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Min Password Length'),
                'name' => 'min_pass_length',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '注册账号的密码的最小长度',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Max Password Length'),
                'name' => 'max_pass_length',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '注册账号的密码的最大长度',
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