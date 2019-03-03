<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\productreview;

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
        $this->_saveUrl = CUrl::getUrl('catalog/productreview/managereditsave');
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        return [
            'editBar'     => $this->getEditBar(),
            'review'      => $this->_one,
            'textareas'  => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'     => $this->_saveUrl,
        ];
    }

    public function setService()
    {
        $this->_service = Yii::$service->product->review;
    }

    public function getEditArr()
    {
        $activeStatus = Yii::$service->product->review->activeStatus();
        $refuseStatus = Yii::$service->product->review->refuseStatus();
        $noActiveStatus = Yii::$service->product->review->noActiveStatus();

        return [
            [
                'label' => Yii::$service->page->translate->__('Rate Star'),
                'name' => 'rate_star',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        1    => Yii::$service->page->translate->__('1 Star'),
                        2    => Yii::$service->page->translate->__('2 Star'),
                        3    => Yii::$service->page->translate->__('3 Star'),
                        4    => Yii::$service->page->translate->__('4 Star'),
                        5    => Yii::$service->page->translate->__('5 Star'),
                    ],
                ],
                'require' => 1,
                'default' => 4,
            ],

            [
                'label' => Yii::$service->page->translate->__('Review Person'),
                'name' => 'name',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],

            [
                'label' => Yii::$service->page->translate->__('Summary'),
                'name'=> 'summary',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],

            [
                'label'  => Yii::$service->page->translate->__('Review Content'),
                'name' => 'review_content',
                'display' => [
                    'type' => 'textarea',
                    'rows'   => 14,
                    'cols'    => 110,
                ],
                'require' => 0,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Review Status'),
                'name' => 'status',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        $noActiveStatus => Yii::$service->page->translate->__('Pending Review'),
                        $activeStatus     => Yii::$service->page->translate->__('Approved'),
                        $refuseStatus    => Yii::$service->page->translate->__('Not Approved'),
                    ],
                ],
                'require' => 1,
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
        /**
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        $identity = Yii::$app->user->identity;
        $audit_user_id = $identity['id'];
        $this->_param['audit_user'] = $audit_user_id;
        $this->_param['audit_date'] = time();

        //$this->_param['review_date'] = strtotime($this->_param['review_date']);
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
                'statusCode'=>'300',
                'message'=>$errors,
            ]);
            exit;
        }
    }

    public function audit()
    {
        $ids = '';
        if ($id = CRequest::param($this->_primaryKey)) {
            $ids = $id;
        } elseif ($ids = CRequest::param($this->_primaryKey.'s')) {
            $ids = explode(',', $ids);
        }
        $this->_service->auditReviewByIds($ids);

        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message' => Yii::$service->page->translate->__('Batch review comments passed - successful'),
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

    public function auditRejected()
    {
        $ids = '';
        if ($id = CRequest::param($this->_primaryKey)) {
            $ids = $id;
        } elseif ($ids = CRequest::param($this->_primaryKey.'s')) {
            $ids = explode(',', $ids);
        }
        $this->_service->auditRejectedReviewByIds($ids);

        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message' => Yii::$service->page->translate->__('Bulk review comment rejection - successful'),
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
