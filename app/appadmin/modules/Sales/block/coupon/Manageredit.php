<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Sales\block\coupon;

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
    protected $_type_percent;
    protected $_type_direct;
    
    public function init()
    {
        $this->_saveUrl = CUrl::getUrl('sales/coupon/managereditsave');
        $this->_type_percent = Yii::$service->cart->coupon->coupon_type_percent;
        $this->_type_direct = Yii::$service->cart->coupon->coupon_type_direct;
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        return [
            'editBar'       => $this->getEditBar(),
            'textareas'    => $this->_textareas,
            'lang_attr'    => $this->_lang_attr,
            'saveUrl'       => $this->_saveUrl,
        ];
    }

    public function setService()
    {
        $this->_service = Yii::$service->cart->coupon;
    }

    public function getEditArr()
    {
        return [
            [
                'label'  => Yii::$service->page->translate->__('Coupon Code'),
                'name' => 'coupon_code',
                'display'  => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Maximum usage per user'),
                'name' => 'users_per_customer',
                'display'  => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Type'),
                'name' => 'type',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        $this->_type_percent => Yii::$service->page->translate->__('Percentage'),
                        $this->_type_direct  => Yii::$service->page->translate->__('Direct reduction'),
                    ],
                ],
                'require' => 1,
                'default' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Amount >? can be used'),
                'name' => 'conditions',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Discount'),
                'name' => 'discount',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Expiration Date'),
                'name' => 'expiration_date',
                'display' => [
                    'type' => 'inputDate',
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
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        $this->_param['expiration_date'] = strtotime($this->_param['expiration_date']);
        $this->_service->save($this->_param);
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
