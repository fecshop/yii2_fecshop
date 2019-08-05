<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\categorysort;

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
    public $_key = 'category_sort';
    protected $_one;
    protected $_service;
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = Yii::$service->url->getUrl('config/categorysort/managersave');
        $this->setService();
        $this->_one = $this->_service->getByKey([
            'key' => $this->_key,
        ]);
        //var_dump($this->_one);
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
        $sort_directions = $this->getSortDirection();
        $sort_directions_select = $this->getSortSelect($sort_directions);
        return [
            'id'            =>   $id,
            'sort_directions' => $sort_directions,
            'sort_directions_select' => $sort_directions_select,
            'category_sorts'      => $this->_one['value'],
            'saveUrl' => $this->_saveUrl ,
        ];
    }
    public function getSortSelect($sort_directions)
    {
        $str = '<select class=\"sort_direction\">';
        if (is_array($sort_directions)){
            foreach ($sort_directions as $sort_direction){
                $str .= '<option value=\"'.$sort_direction.'\">' . Yii::$service->page->translate->__($sort_direction).'</option>';
            }
        }
        $str .= '</select>';
        
        return $str;
    }
    public function getSortDirection()
    {
        $sort_direction = [
            'desc' , 'asc' 
        ];
        return $sort_direction;
    }
    
    public function setService()
    {
        $this->_service = Yii::$service->storeBaseConfig;
    }
    
    
    public function getEditParam($category_sorts)
    {
        $arr = [];
        //var_dump($category_sorts);
        $categorySortArr = explode('||', $category_sorts);
        foreach ($categorySortArr as $one) {
            if ($one) {
                list($sort_key, $sort_label, $sort_db_columns, $sort_direction) = explode('##', $one);
                if ($sort_key && $sort_label && $sort_db_columns && $sort_direction) {
                    $arr[] = [
                        'sort_key' => $sort_key,
                        'sort_label' => $sort_label,
                        'sort_db_columns' => $sort_db_columns,
                        'sort_direction' => $sort_direction,
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
        $category_sorts = $editFormData['category_sorts'];
        
        $saveData = $this->getEditParam($category_sorts);
        //var_dump($saveData);
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