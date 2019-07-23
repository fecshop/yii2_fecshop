<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\appserverstore;

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
    public $_key = 'appserver_store';
    public $_type;
    protected $_attrArr = [
        'key',
        'lang',
        'lang_name',
        'currency',
        'https_enable',
        'facebook_login_app_id',
        'facebook_login_app_secret',
        'google_login_client_id',
        'google_login_client_secret',
    ];
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/appserverstore/managersave');
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
        
        $allLangs = Yii::$service->fecshoplang->getAllLangName();
        $allLangArr = [];
        foreach ($allLangs as $k) {
            $allLangArr[$k] = $k;
        }
        $currencys = Yii::$service->page->currency->getCurrencys();
        $currencyArr = [];
        foreach ($currencys as $code => $info) {
            $currencyArr[$code] = $code;
        }
        
        return [
        
        
            // 需要配置
            [
                'label'  => Yii::$service->page->translate->__('Store Key'),
                'name' => 'key',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Default Language'),
                'name' => 'lang',
                'display' => [
                    'type' => 'select',
                    'data' => $allLangArr,
                ],
                'require' => 1,
                'default' => 1,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Default Language Name'),
                'name' => 'lang_name',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Default Currency'),
                'name' => 'currency',
                'display' => [
                    'type' => 'select',
                    'data' => $currencyArr,
                ],
                'require' => 1,
            ],
             [
                'label'  => Yii::$service->page->translate->__('Https Enable'),
                'name' => 'https_enable',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        1    => Yii::$service->page->translate->__('Enable'),
                        2    => Yii::$service->page->translate->__('Disable'),
                    ],
                ],
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('FB Login AppId'),
                'name' => 'facebook_login_app_id',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('FB Login AppSecret'),
                'name' => 'facebook_login_app_secret',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Google Login Client Id'),
                'name' => 'google_login_client_id',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Google Login Client Secret'),
                'name' => 'google_login_client_secret',
                'display' => [
                    'type' => 'inputString',
                ],
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