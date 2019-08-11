<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\currency;

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
    public $_key = 'currency';
    protected $_one;
    protected $_service;
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = Yii::$service->url->getUrl('config/currency/managersave');
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
        return [
            'id'            =>   $id,
            'currencys'      => $this->_one['value'],
            'saveUrl' => $this->_saveUrl ,
        ];
    }
    public function setService()
    {
        $this->_service = Yii::$service->storeBaseConfig;
    }
    
    
    public function getEditParam($currencys)
    {
        $arr = [];
        $currencyArr = explode('||', $currencys);
        foreach ($currencyArr as $one) {
            if ($one) {
                list($currency_code, $currency_symbol, $currency_rate) = explode('##', $one);
                if ($currency_code && $currency_symbol && $currency_rate) {
                    $arr[] = [
                        'currency_code' => $currency_code,
                        'currency_symbol' => $currency_symbol,
                        'currency_rate' => $currency_rate,
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
        $currencys = $editFormData['currencys'];
        
        $saveData = $this->getEditParam($currencys);
         // 得到baseCurrencyCode
        $base_currency = Yii::$app->store->get('base_info', 'base_currency');
        if ($base_currency){
            $hasBase = false;
            if (isset($saveData['value']) && is_array($saveData['value'])) {
                foreach ($saveData['value'] as $one) {
                    // var_dump($one);exit;
                    $currency_code = $one['currency_code'];
                    if ($base_currency == $currency_code) {
                        $hasBase = true;
                        break;
                    }
                }
            }
            if (!$hasBase) {
                echo  json_encode([
                    'statusCode' => '300',
                    'message'    => 'you can not delete base currency code: '.$base_currency,
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