<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\appserverstorelang;

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
    public $_key = 'appserver_store_lang';
    protected $_one;
    protected $_service;
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = Yii::$service->url->getUrl('config/appserverstorelang/managersave');
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
        $configLangs = Yii::$service->fecshoplang->getLangAndCodeArr();
        $configLangsSelect = $this->getLangSelect($configLangs);
        return [
            'id'            =>   $id, 
            'configLangs'  => $configLangs,
            'configLangsSelect' => $configLangsSelect,
            'langs'      => $this->_one['value'],
            'saveUrl' => $this->_saveUrl ,
        ];
    }
    public function getLangSelect($configLangs)
    {
        $str = '<select class=\"lang_select\">';
        if (is_array($configLangs)){
            foreach ($configLangs as  $code => $name){
                $str .= '<option value=\"'.$code.'\">' . Yii::$service->page->translate->__($name).'</option>';
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
        $configLangs = Yii::$service->fecshoplang->getLangAndCodeArr();
        $langCodes = [];
        foreach ($langArr as $one) {
            if ($one) {
                list($lang_name, $lang_code) = explode('##', $one);
                if ($lang_name && $lang_code) {
                    $lang_code_name  = isset($configLangs[$lang_code])  ? $configLangs[$lang_code] : '';  
                    if (in_array($lang_code, $langCodes)) {
                        continue;
                    }
                    $langCodes[] = $lang_code;
                    $arr[] = [
                        'languageName' => $lang_name,
                        'code' => $lang_code,
                        'language' => $lang_code_name,
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