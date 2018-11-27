<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Fecadmin\block\config;

use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;

    public function init()
    {
        $this->_saveUrl = CUrl::getUrl('fecadmin/config/managereditsave');
        parent::init();
    }
    public function setService()
    {
        $this->_service = Yii::$service->admin->config;
    }
    # 传递给前端的数据 显示编辑form    
    public function getLastData(){
        
        return [
            'editBar' => $this->getEditBar(),
            'saveUrl' => CUrl::getUrl('fecadmin/config/managereditsave'),
        ];
    }
    
    public function save()
    {
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        $this->_service->save($this->_param);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message' => Yii::$service->page->translate->__('Save Success'),
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode' => '300',
                'message' => $errors,
            ]);
            exit;
        }
    }
    // 批量删除
    public function delete()
    {
        $ids = '';
        if ($id = CRequest::param($this->_primaryKey)) {
            $ids = $id;
        } elseif ($ids = CRequest::param($this->_primaryKey.'s')) {
            $ids = explode(',', $ids);
        }
        $this->_service->remove($ids);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message' => Yii::$service->page->translate->__('Remove Success'),
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode' => '300',
                'message' => $errors,
            ]);
            exit;
        }
    }
    
    public function getEditArr(){
        
        return [
            [
                'label' => Yii::$service->page->translate->__('Config Label'),
                'name' => 'label',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label' => Yii::$service->page->translate->__('Config Key'),
                'name' => 'key',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label' => Yii::$service->page->translate->__('Config Value'),
                'name' => 'value',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label' => Yii::$service->page->translate->__('Description'),
                'name' => 'description',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
        ];
    }
    
    public function getEditBar($editArr = []){
        if (empty($editArr)) {
            $editArr = $this->getEditArr();
        }
        $str = '';
        if ($this->_param[$this->_primaryKey]) {
            $str = '<input type="hidden"  value="'.$this->_param[$this->_primaryKey].'" size="30" name="editFormData['.$this->_primaryKey.']" class="textInput ">';
        }
        foreach ($editArr as $column) {  
            $name = $column['name'];
            $require = $column['require'] ? 'required' : '';
            $label = $column['label'] ? $column['label'] : $this->_one->getAttributeLabel($name);
            $display = isset($column['display']) ? $column['display'] : '';
            if (empty($display)) {
                $display = ['type' => 'inputString'];
            }
            $value = $this->_one[$name] ? $this->_one[$name] : $column['default'];
            $display_type = isset($display['type']) ? $display['type'] : 'inputString';
            if ($display_type == 'inputString') {
                $str .='<p>
                            <label>'.$label.'：</label>
                            <input type="text"  value="'.$value.'" size="30" name="editFormData['.$name.']" class="textInput '.$require.' ">
                        </p>';
            } else if ($display_type == 'inputDate') {
                $str .='<p>
                            <label>'.$label.'：</label>
                            <input type="text"  value="'.($value ? date("Y-m-d",strtotime($value)) : '').'" size="30" name="editFormData['.$name.']" class="date textInput '.$require.' ">
                        </p>';
            } else if ($display_type == 'inputEmail') {
                $str .='<p>
                            <label>'.$label.'：</label>
                            <input type="text"  value="'.$value.'" size="30" name="editFormData['.$name.']" class="email textInput '.$require.' ">
                        </p>';
            } else if ($display_type == 'inputPassword') {
                $str .='<p>
                            <label>'.$label.'：</label>
                            <input type="password"  value="" size="30" name="editFormData['.$name.']" class=" textInput '.$require.' ">
                        </p>';
            } else if ($display_type == 'select') {
                $data = isset($display['data']) ? $display['data'] : '';
                $select_str = '';
                if (is_array($data)) {
                    $select_str .= '<select class="combox '.$require.'" name="editFormData['.$name.']" >';
                    $select_str .='<option value="">'.$label.'</option>';
                    foreach ($data as $k => $v) {
                        if ($value == $k) {
                            //echo $value."#".$k;
                            $select_str .='<option selected="selected" value="'.$k.'">'.$v.'</option>';
                        } else {
                            $select_str .='<option value="'.$k.'">'.$v.'</option>';
                        }
                    }
                    $select_str .= '</select>';
                }
                $str .='<p>
                            <label>'.$label.'：</label>
                            '.$select_str.'
                        </p>';
            }
        }
        return $str;
    }
    
    
}
















