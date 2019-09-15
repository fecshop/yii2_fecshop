<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\System\block\extensioninstalled;

use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\staticblock.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Managerview extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;

    public function init()
    {
        $this->_saveUrl = CUrl::getUrl('system/extensioninstalled/managereditsave');
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        return [
            'editBar'      => $this->getEditBar(),
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'      => $this->_saveUrl,
        ];
    }

    public function setService()
    {
        $this->_service = Yii::$service->extension;
    }

    public function getEditArr()
    {
        return [
            [
                'label'  => Yii::$service->page->translate->__('Extension Name'),
                'name' => 'name',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Extension Package'),
                'name' => 'package',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Extension Folder'),
                'name' => 'folder',
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
                        1    => Yii::$service->page->translate->__('Enable'),
                        2    => Yii::$service->page->translate->__('Disable'),
                    ],
                ],
                'require' => 1,
                'default' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Extension Type'),
                'name' => 'type',
                'display' => [
                    'type' => 'select',
                    'data' =>  Yii::$service->extension->getTypeArr(),
                ],
                'require' => 1,
                'default' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Namespace'),
                'name' => 'namespace',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            
            
            [
                'label'  => Yii::$service->page->translate->__('Config File Path'),
                'name' => 'config_file_path',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Extension Version'),
                'name' => 'version',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Priority'),
                'name' => 'priority',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Installed Status'),
                'name' => 'installed_status',
                'display' => [
                    'type' => 'select',
                    'data' => Yii::$service->extension->getInstallStatusArr(),
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
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        $this->_service->save($this->_param, 'cms/article/index');
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
    // 插件激活
    public function extensionEnable()
    {
        $ids = Yii::$app->request->post('ids');
        $idArr = explode(',', $ids);
        if (!Yii::$service->extension->enableAddons($idArr)) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__('Enable Extension fail'),
            ]);
            exit;
        }
        echo  json_encode([
            'statusCode' => '200',
            'message'    => Yii::$service->page->translate->__('Enable Extension Success') ,
        ]);
        exit;
    }
    // 插件关闭
    public function extensionDisable()
    {
        $ids = Yii::$app->request->post('ids');
        $idArr = explode(',', $ids);
        if (!Yii::$service->extension->disableAddons($idArr)) {
            echo  json_encode([
                'statusCode' => '300',
                'message'    => Yii::$service->page->translate->__('Enable Extension fail'),
            ]);
            exit;
        }
        echo  json_encode([
            'statusCode' => '200',
            'message'    => Yii::$service->page->translate->__('Enable Extension Success') ,
        ]);
        exit;
        
        
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
                'message'    => Yii::$service->page->translate->__('Remove Success') ,
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
