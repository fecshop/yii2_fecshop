<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\productattr;

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
        $this->_saveUrl = CUrl::getUrl('catalog/productattr/managereditsave');
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        
        $display_data = $this->_one['display_data'];
        $display_type = $this->_one['display_type'];
        if ($display_data) {
            $display_data = unserialize($display_data);
        }
        $displayTypes = Yii::$service->product->attr->getDisplayTypes();
        return [
            'editBar'      => $this->getEditBar(),
            'display_data' => $display_data,
            'display_type' => $display_type,
            'display_types' => $displayTypes,
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'     => $this->_saveUrl,
        ];
    }

    public function setService()
    {
        $this->_service = Yii::$service->product->attr;
    }

    public function getEditArr()
    {
        $attrTypes = Yii::$service->product->attr->getAttrTypes();
        $dbTypes = Yii::$service->product->attr->getDbTypes();
        
        return [
            [
                'label'  => Yii::$service->page->translate->__('Attr Type'),
                'name' => 'attr_type',
                'display' => [
                    'type' => 'select',
                    'data' => $attrTypes,
                ],
                'require' => 1,
                'default' => 'general_attr',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Attr Name'),
                'name' => 'name',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Status'),
                'name' => 'status',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        1   => Yii::$service->page->translate->__('Enable'),
                        2   => Yii::$service->page->translate->__('Disable'),
                    ],
                ],
                'require' => 1,
                'default' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Db Type'),
                'name' => 'db_type',
                'display' => [
                    'type' => 'select',
                    'data' => $dbTypes,
                ],
                'require' => 1,
                'default' => 'String',
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Show As Img'),
                'name' => 'show_as_img',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        1   => Yii::$service->page->translate->__('Yes'),
                        2   => Yii::$service->page->translate->__('No'),
                    ],
                ],
                'require' => 1,
                'default' => 2,
            ],
            /*
            [
                'label'  => Yii::$service->page->translate->__('Display Type'),
                'name' => 'display_type',
                'display' => [
                    'type' => 'select',
                    'data' => $displayTypes,
                ],
                'require' => 1,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('display_data Type'),
                'name' => 'display_data',
                'display' => [
                    'type' => 'textarea',
                    'notEditor' => true,
                ],
                'require' => 1,
            ],
            */
            
            [
                'label'  => Yii::$service->page->translate->__('Is Require'),
                'name' => 'is_require',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        1   => Yii::$service->page->translate->__('Yes'),
                        2   => Yii::$service->page->translate->__('No'),
                    ],
                ],
                'require' => 1,
                'default' => 2,
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('Default Value'),
                'name' => 'default',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
        ];
    }
    // display_data
    public function getSaveDisplayData()
    {
        $dd = $this->_param['display_data'];
        $display_data_arr = [];
        if ($dd) {
            $arr = explode('||', $dd );
            foreach ($arr as $a) {
                if ($a) {
                    $display_data_arr[] = [
                        'key' => $a,
                    ];
                }
            }
        }
        if (empty($display_data_arr)) {
            return '';
        }
        
        return serialize($display_data_arr);
    }
    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        $this->_param['display_data'] = $this->getSaveDisplayData();
        $this->_service->save($this->_param);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message'    => Yii::$service->page->translate->__('Save Success') ,
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
                'message'    => Yii::$service->page->translate->__('Remove Success'),
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
