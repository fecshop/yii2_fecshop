<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\apphtml5base;

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
    public $_key = 'apphtml5_base';
    public $_type;
    protected $_attrArr = [
        'assetForceCopy',
        'js_version',
        'css_version',
        'third_trace_js',
    ];
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/apphtml5base/managersave');
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
                'label' => Yii::$service->page->translate->__('Asset Js Css Force Copy'),
                'name'  => 'assetForceCopy',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '模板中的css，js文件是否每次都进行forceCopy，开发模式请设置Yes，线上如果并发高，可以设置成No节省资源。'
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Js Version'),
                'name' => 'js_version',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'Js Url的后缀参数，线上发新版本更新js，可以将这个值+1，这样可以让浏览器不加载浏览器缓存',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Css Version'),
                'name' => 'css_version',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'Css Url的后缀参数，线上发新版本更新css，可以将这个值+1，这样可以让浏览器不加载浏览器缓存',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Thrid Trace Js'),
                'name' => 'third_trace_js',
                'display' => [
                    'type' => 'textarea',
                    'notEditor' => true,
                ],
                'remark' => '您可以在这里添加百度统计js，Google Analysis js代码，或者GTM(Google Tag Manager) js等（多个js片段，譬如GTM，将2个js代码片段换行添加在这里即可）',
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