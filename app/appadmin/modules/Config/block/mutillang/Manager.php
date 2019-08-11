<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\mutillang;

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
class Manager extends \yii\base\BaseObject
{
    protected $_saveUrl;
    // 需要配置
    public $_key = 'mutil_lang';
    protected $_one;
    protected $_service;
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = Yii::$service->url->getUrl('config/mutillang/managersave');
        $this->setService();
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
        $search_engines = Yii::$service->search->getAllChildServiceName();
        $search_engines_select = $this->getSearchSelect($search_engines);
        return [
            'id'            =>   $id, 
            'search_engines'  => $search_engines,
            'search_engines_select' => $search_engines_select,
            'langs'      => $this->_one['value'],
            'saveUrl' => $this->_saveUrl ,
        ];
    }
    public function getSearchSelect($search_engines)
    {
        $str = '<select class=\"search_engine\">';
        if (is_array($search_engines)){
            foreach ($search_engines as $search_engine){
                $str .= '<option value=\"'.$search_engine.'\">' . Yii::$service->page->translate->__($search_engine).'</option>';
            }
        }
        $str .= '</select>';
        
        return $str;
    }
    public function setService()
    {
        $this->_service = Yii::$service->storeBaseConfig;
    }
    
    
    public function getEditParam($langs)
    {
        $arr = [];
        $langArr = explode('||', $langs);
        foreach ($langArr as $one) {
            if ($one) {
                list($lang_name, $lang_code, $search_engine) = explode('##', $one);
                if ($lang_name && $lang_code && $search_engine) {
                    $arr[] = [
                        'lang_name' => $lang_name,
                        'lang_code' => $lang_code,
                        'search_engine' => $search_engine,
                    ];
                }
            }
            
        }
        
        return [
            'key' => $this->_key,
            'value' => $arr,
        ];
    }
    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        $editFormData = Yii::$app->request->post('editFormData');
        $langs = $editFormData['langs'];
        
        $saveData = $this->getEditParam($langs);
        // 得到defaultLangCode
        $default_lang = Yii::$app->store->get('base_info', 'default_lang');
        //echo $default_lang;exit;
        if ($default_lang){
            $hasDefault = false;
            if (isset($saveData['value']) && is_array($saveData['value'])) {
                foreach ($saveData['value'] as $one) {
                    $lang_code = $one['lang_code'];
                    if ($default_lang == $lang_code) {
                        $hasDefault = true;
                        break;
                    }
                }
            }
            if (!$hasDefault) {
                echo  json_encode([
                    'statusCode' => '300',
                    'message'    => 'you can not delete default lang code: ' . $default_lang,
                ]);
                exit;
            }
        } 
            
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        // 设置 bdmin_user_id 为 当前的user_id
        $this->_service->saveConfig($saveData);
        
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
    
    
}