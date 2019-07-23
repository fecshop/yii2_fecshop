<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\appservercache;

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
    public $_key = 'appserver_cache';
    public $_type;
    protected $_attrArr = [
        'allPageCache',
        'homePageCache',
        'categoryPageCache',
        'productPageCache',
        'articlePageCache',
        
        //'headBlockCache',
        //'headerBlockCache',
        //'menuBlockCache',
        //'footerBlockCache',
    ];
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/appservercache/managersave');
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
                'label' => Yii::$service->page->translate->__('All PageCache'),
                'name'  => 'allPageCache',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Enable',
                        Yii::$app->store->disable => 'Disable',
                    ],
                ],
                'remark' => 'After you disable it, all pageCache will disabled, Even if it set to enable, it only work for pageCache'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Home PageCache'),
                'name'  => 'homePageCache',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Enable',
                        Yii::$app->store->disable => 'Disable',
                    ],
                ],
                'remark' => 'it will be enabel, after HomePageCache and AllPageCache set to enable'
            ],
            [
                'label' => Yii::$service->page->translate->__('Category PageCache'),
                'name'  => 'categoryPageCache',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Enable',
                        Yii::$app->store->disable => 'Disable',
                    ],
                ],
                'remark' => 'it will be enabel, after CategoryPageCache and AllPageCache set to enable'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Product PageCache'),
                'name'  => 'productPageCache',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Enable',
                        Yii::$app->store->disable => 'Disable',
                    ],
                ],
                'remark' => 'it will be enabel, after ProductPageCache and AllPageCache set to enable'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Article PageCache'),
                'name'  => 'articlePageCache',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Enable',
                        Yii::$app->store->disable => 'Disable',
                    ],
                ],
                'remark' => 'it will be enabel, after ArticlePageCache and AllPageCache set to enable'
            ],
            /*
            [
                'label' => Yii::$service->page->translate->__('Head BlockCache'),
                'name'  => 'headBlockCache',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Enable',
                        Yii::$app->store->disable => 'Disable',
                    ],
                ],
                'remark' => 'BlockCache for html head section'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Header BlockCache'),
                'name'  => 'headerBlockCache',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Enable',
                        Yii::$app->store->disable => 'Disable',
                    ],
                ],
                'remark' => 'BlockCache for html header section'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Menu BlockCache'),
                'name'  => 'menuBlockCache',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Enable',
                        Yii::$app->store->disable => 'Disable',
                    ],
                ],
                'remark' => 'BlockCache for html menu section'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Footer BlockCache'),
                'name'  => 'footerBlockCache',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Enable',
                        Yii::$app->store->disable => 'Disable',
                    ],
                ],
                'remark' => 'BlockCache for html footer section'
            ],
            */
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