<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\productattrgroup;

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
        $this->_saveUrl = CUrl::getUrl('catalog/productattrgroup/managereditsave');
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        $filter = [
            'where' => [
                ['status' => Yii::$service->product->attr->getEnableStatus()]
            ],
            'orderBy' => ['id' => SORT_DESC],
            'fetchAll' => true,
            'asArray' => true,
        ];
        $data = Yii::$service->product->attr->coll($filter);
        $attrs = $data['coll'];
        $select_attr_ids = $this->_one['attr_ids'];
        if ($select_attr_ids) {
            $select_attr_ids = unserialize($select_attr_ids);
        }
        return [
            'editBar'      => $this->getEditBar(),
            'attrs' => $attrs,
            'select_attr_ids' => $select_attr_ids,
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'     => $this->_saveUrl,
        ];
    }

    public function setService()
    {
        $this->_service = Yii::$service->product->attrGroup;
    }

    public function getEditArr()
    {
        // $attrTypes = Yii::$service->product->attr->getAttrTypes();
        // $dbTypes = Yii::$service->product->attr->getDbTypes();
        
        return [
            [
                'label'  => Yii::$service->page->translate->__('Attr Group Name'),
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
            
            
        ];
    }
    
    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        $attr_ids = $this->_param['attr_ids'];
        if ($attr_ids) {
            $attr_ids = trim($attr_ids, '||');
            $attr_id_arr = explode('||', $attr_ids);
            if (is_array($attr_id_arr) && !empty($attr_id_arr)) {
                $arr = [];
                foreach ($attr_id_arr as $one) {
                    list($attr_id, $sort_order) = explode('##', $one);
                    if ($attr_id) {
                        $arr[] = [
                            'attr_id' => $attr_id,
                            'sort_order' => (int)$sort_order
                        ];
                    }
                }
                $this->_param['attr_ids'] = serialize($arr);
            }
        }
        
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
