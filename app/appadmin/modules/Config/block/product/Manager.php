<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\product;

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
class Manager extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;
    // 需要配置
    public $_key = 'product';
    public $_type;
    protected $_attrArr = [
        'imageFloder',
        'maxUploadMSize',
        'pngCompressionLevel',
        'jpegCompressionLevel',
        'ifSpecialGtPriceFinalPriceEqPrice',
        'zeroInventory',
    ];
    
    public function init()
    {
        
         // 需要配置
        $this->_saveUrl = CUrl::getUrl('config/product/managersave');
        $this->_editFormData = 'editFormData';
        $this->setService();
        $this->_param = CRequest::param();
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
            'editBar'      => $this->getEditBar(),
            'textareas'   => $this->_textareas,
            'lang_attr'   => $this->_lang_attr,
            'saveUrl'     => $this->_saveUrl,
        ];
    }
    public function setService()
    {
        $this->_service = Yii::$service->storeBaseConfig;
    }
    public function getEditArr()
    {
        $deleteStatus = Yii::$service->customer->getStatusDeleted();
        $activeStatus = Yii::$service->customer->getStatusActive();
        
        return [
            // 需要配置
            [
                'label'  => Yii::$service->page->translate->__('Product Image Floder'),
                'name' => 'imageFloder',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => '产品图片存放路径。根路径为：@appimage/common',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Image MaxUploadSize(M)'),
                'name' => 'maxUploadMSize',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'MB  # 图片最大尺寸',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Image Png Compress Level'),
                'name' => 'pngCompressionLevel',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'png图片resize压缩的质量数：范围为  0-9，数越大质量越高容量越大, 数越低图片越模糊容量越小（设置后，同下）',
            ],
            [
                'label'  => Yii::$service->page->translate->__('Image Jpeg Compress Level'),
                'name' => 'jpegCompressionLevel',
                'display' => [
                    'type' => 'inputString',
                ],
                'remark' => 'jpeg, jpg, pjpeg压缩 范围：1-100，数越大质量越高(设置后，对于已经resize的历史图片不生效，您可以手动清空@appimage/common/[imageFloder]/cache/文件夹下的图片,重新生成)',
            ],
            
            [
                'label' => Yii::$service->page->translate->__('ifSpecialGtPriceFinalPriceEqPrice'),
                'name'  => 'ifSpecialGtPriceFinalPriceEqPrice',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '设置为true后，如果产品的special_price > price， 则 special_price无效，价格为price'
            ],
            
            
            [
                'label' => Yii::$service->page->translate->__('zeroInventory'),
                'name'  => 'zeroInventory',
                'display' => [
                    'type' => 'select',
                    'data' => [
                        Yii::$app->store->enable => 'Yes',
                        Yii::$app->store->disable => 'No',
                    ],
                ],
                'remark' => '是否零库存？'
            ],
            
        ];
    }
    
    public function getArrParam(){
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        $param = [];
        $attrVals = [];
        foreach($this->_param as $attr => $val) {
            if (in_array($attr, $this->_attrArr)) {
                $attrVals[$attr] = $val;
            } else {
                $param[$attr] = $val;
            }
        }
        $param['value'] = $attrVals;
        $param['key'] = $this->_key;
        
        return $param;
    }
    
    /**
     * save article data,  get rewrite url and save to article url key.
     */
    public function save()
    {
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        // 设置 bdmin_user_id 为 当前的user_id
        $this->_service->saveConfig($this->getArrParam());
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
    
    
    
    public function getVal($name, $column){
        if (is_object($this->_one) && property_exists($this->_one, $name) && $this->_one[$name]) {
            
            return $this->_one[$name];
        }
        $content = $this->_one['value'];
        if (is_array($content) && !empty($content) && isset($content[$name])) {
            
            return $content[$name];
        }
        
        return '';
    }
}