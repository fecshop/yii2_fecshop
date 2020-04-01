<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\productbrand;

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
        $this->_saveUrl = CUrl::getUrl('catalog/productbrand/managereditsave');
        parent::init();
    }

    // 传递给前端的数据 显示编辑form
    public function getLastData()
    {
        return [
            'editBar'      => $this->getEditBar(),
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'     => $this->_saveUrl,
            'image'                     => $this->_one['logo'],
            'imageurl'                  => Yii::$service->category->image->getUrl($this->_one['logo']),
        ];
    }

    public function setService()
    {
        $this->_service = Yii::$service->product->brand;
    }

    public function getEditArr()
    {
        $brandCategorys = Yii::$service->product->brandcategory->getBrandCategoryIdAndNames(); 
        $brandStatus = Yii::$service->product->brand->getStatusArr();
        
        return [
            [
                'label'  => Yii::$service->page->translate->__('Brand Name'),
                'name' => 'name',
                'display' => [
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'require' => 1,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Website Url'),
                'name' => 'website_url',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            [
                'label'  => Yii::$service->page->translate->__('Status'),
                'name' => 'status',
                'display' => [
                    'type' => 'select',
                    'data' => $brandStatus,
                ],
                'require' => 1,
                'default' => 1,
            ],
            [
                'label'  => Yii::$service->page->translate->__('Brand Category'),
                'name' => 'brand_category_id',
                'display' => [
                    'type' => 'select',
                    'data' => $brandCategorys,
                ],
                'require' => 0,
                'default' => 1,
            ],
        ];
    }

    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
         $this->_param = Yii::$app->request->post($this->_editFormData);
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        $image = Yii::$app->request->post('image');
        $this->_param['logo'] = $image;
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
