<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\paymentwxpay;

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
    public $_key = 'payment_wxpay';
    public $_type;
    protected $_attrArr = [
        'wechat_service_app_id',   // 微信服务号APPID
        'wechat_service_app_secret',   // 微信服务号App Secret
        
        'wechat_micro_app_id',   // 微信小程序APPID
        'wechat_micro_app_secret',   // 微信小程序App Secret
        
        'merchant_key',   // 商户支付平台 - KEY
        'merchant_mch_id',   // 商户支付平台 - APPSECRET
        
    ];
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/paymentwxpay/managersave');
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
        
            [
                'label'  => Yii::$service->page->translate->__('WeChat Service App Id'),
                'name' => 'wechat_service_app_id',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信服务号AppId',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('WeChat Service App Secret'),
                'name' => 'wechat_service_app_secret',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信服务号AppSecret',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Wechat Micro App Id'),
                'name' => 'wechat_micro_app_id',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信小程序AppId',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Wechat Micro App Secret'),
                'name' => 'wechat_micro_app_secret',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信小程序AppSecret',
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('Merchant MCHID'),
                'name' => 'merchant_mch_id',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '微信商户平台MCHID',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Merchant Key'),
                'name' => 'merchant_key',
                'display' => [
                    'type' => 'inputString',
                ],
                 'remark' => '微信商户平台KEY',
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