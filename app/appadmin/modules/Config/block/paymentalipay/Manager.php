<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\paymentalipay;

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
    public $_key = 'payment_alipay';
    public $_type;
    protected $_attrArr = [
        'app_id',
        'seller_id',
        'rsa_private_key',
        'rsa_public_key',
        'alipay_env',
         /**
          * 支付宝库包的选项：
          * SDK工作目录
          * 存放日志，AOP缓存数据
          * window 将其修改成您自己的支付目录（因为win下面没有/tmp/文件目录）
          */
        'alipay_aop_sdk_work_dir',
        /**
          * 支付宝库包的选项：
          * 是否处于开发模式
          * 在你自己电脑上开发程序的时候千万不要设为false，以免缓存造成你的代码修改了不生效
          * 部署到生产环境正式运营后，如果性能压力大，可以把此常量设定为false，能提高运行速度（对应的代价就是你下次升级程序时要清一下缓存）
          */
        'alipay_aop_sdk_dev_mode',
    ];
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/paymentalipay/managersave');
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
                'label'  => Yii::$service->page->translate->__('Alipay Env'),
                'name' => 'alipay_env',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$service->payment->env_sanbox =>  Yii::$service->page->translate->__('Sanbox Env'),
                        Yii::$service->payment->env_product =>  Yii::$service->page->translate->__('Product Env'),
                    ],
                ],
                'require' => 1,
                'remark' => ''
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('App Id'),
                'name' => 'app_id',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Seller Id'),
                'name' => 'seller_id',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Rsa Private Key'),
                'name' => 'rsa_private_key',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Rsa Public Key'),
                'name' => 'rsa_public_key',
                'display' => [
                    'type' => 'inputString',
                ],
            ],
            [
                'label'  => Yii::$service->page->translate->__('Alipay Aop Sdk Work Dir'),
                'name' => 'alipay_aop_sdk_work_dir',
                'display' => [
                    'type' => 'inputString',
                ],
                'default' => '/tmp',
                'remark' => '支付宝库包的选项：SDK工作目录, 存放日志，AOP缓存数据, Linux 下填写/tmp 即可，window 将其修改成您自己的支付目录（因为win下面没有/tmp/文件目录）'
               
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Alipay Aop Sdk Dev Mode'),
                'name' => 'alipay_aop_sdk_dev_mode',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        1 =>  Yii::$service->page->translate->__('Enable'),
                        2 =>  Yii::$service->page->translate->__('Disable'),
                    ],
                ],
                'require' => 1,
                'remark' => '支付宝库包的选项：开发模式必须设置true，以免缓存造成你的代码修改了不生效,部署到生产环境如果性能压力大，可以设置为false提高运行速度（对应的代价：下次升级程序需要清缓存）',
                'default' => 1,
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