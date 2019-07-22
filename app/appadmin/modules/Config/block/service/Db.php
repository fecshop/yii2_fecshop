<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\service;

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
class Db extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;
    // 需要配置
    public $_key = 'service_db';
    public $_type;
    protected $_attrArr = [
        'error_handle_log',
        'newsletter',
        'url_rewrite',
        'article_and_staticblock',
        'product_review',
        'product_favorite',
        'category_and_product',
        
        
    ];
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/service/save');
        $this->_editFormData = 'editFormData';
        $this->setService();
        $this->_param = CRequest::param();
        $this->_primaryKey = $this->_service->getPrimaryKey();
        $identity = Yii::$app->user->identity;
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
                'label' => Yii::$service->page->translate->__('Category And Product Storage'),
                'name'  => 'category_and_product',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->serviceMongodbName => 'Mongodb',
                        Yii::$app->store->serviceMysqldbName => 'Mysqldb',
                    ],
                ],
                'remark' => 'Set Product And Category Services Storage, default is mysqldb, After you change it, you must run urlRewrite Script.'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Product Favorite Storage'),
                'name'  => 'product_favorite',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->serviceMongodbName => 'Mongodb',
                        Yii::$app->store->serviceMysqldbName => 'Mysqldb',
                    ],
                ],
                'remark' => 'Product Favorite Storage, default is mysqldb'
            ],
            [
                'label' => Yii::$service->page->translate->__('Product Review Storage'),
                'name'  => 'product_review',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->serviceMongodbName => 'Mongodb',
                        Yii::$app->store->serviceMysqldbName => 'Mysqldb',
                    ],
                ],
                'remark' => 'Product Review Storage, default is mysqldb'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Article And StaticBlock Storage'),
                'name'  => 'article_and_staticblock',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->serviceMongodbName => 'Mongodb',
                        Yii::$app->store->serviceMysqldbName => 'Mysqldb',
                    ],
                ],
                'remark' => 'Article And StaticBlock Storage, default is mysqldb'
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Url Rewrite Storage'),
                'name'  => 'url_rewrite',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->serviceMongodbName => 'Mongodb',
                        Yii::$app->store->serviceMysqldbName => 'Mysqldb',
                    ],
                ],
                'remark' => 'Url Rewrite Storage, default is mysqldb'
            ],
            
            
            [
                'label' => Yii::$service->page->translate->__('Newsletter Storage'),
                'name'  => 'newsletter',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->serviceMongodbName => 'Mongodb',
                        Yii::$app->store->serviceMysqldbName => 'Mysqldb',
                    ],
                ],
                'remark' => 'Newsletter Storage, default is mysqldb'
            ],
            
            
            [
                'label' => Yii::$service->page->translate->__('Error Handle Log Storage'),
                'name'  => 'error_handle_log',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->serviceMongodbName => 'Mongodb',
                        Yii::$app->store->serviceMysqldbName => 'Mysqldb',
                    ],
                ],
                'remark' => 'Error Handle Log Storage, default is mysqldb'
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
        $identity = Yii::$app->user->identity;
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