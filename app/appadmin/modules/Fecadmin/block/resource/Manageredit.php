<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Fecadmin\block\resource;

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
        $this->_saveUrl = CUrl::getUrl('fecadmin/resource/managereditsave');
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        return [
            'editBar'    => $this->getEditBar(),
            'textareas'    => $this->_textareas,
            'lang_attr'    => $this->_lang_attr,
            'saveUrl'    => $this->_saveUrl,
        ];
    }

    public function setService()
    {
        $this->_service = Yii::$service->admin->urlKey;;
    }

    public function getEditArr()
    {
        return [
            [
                'label'=>'name',
                'name'=>'name',
                'display'=>[
                    'type' => 'inputString',

                ],
                'require' => 1,
            ],


            [
                'label'=>'resource',
                'name'=>'url_key',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'=>'tag',
                'name'=>'tag',
                'display'=>[
                    'type' => 'select',
                    'data' => Yii::$service->admin->urlKey->getTags(),
                ],
                'require' => 1,
            ],

            [
                'label'=>'tag sort order',
                'name'=>'tag_sort_order',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 0,
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
        $this->_service->save($this->_param);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode'=>'200',
                'message'=>'save success',
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode'=>'300',
                'message'=>$errors,
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
                'statusCode'=>'200',
                'message'=>'remove data  success',
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode'=>'300',
                'message'=>$errors,
            ]);
            exit;
        }
    }
}
