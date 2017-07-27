<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\productinfo\index;

use fec\helpers\CRequest;
//use fecshop\app\appadmin\modules\Catalog\helper\Product as ProductHelper;
use Yii;

/**
 * block catalog/productinfo.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Attr
{
    protected $_currentAttrGroup;
    protected $_attrInfo;
    /**
     * 为了可以使用rewriteMap，use 引入的文件统一采用下面的方式，通过Yii::mapGet()得到className和Object
     */
    protected $_productHelperName = '\fecshop\app\appadmin\modules\Catalog\helper\Product';
    protected $_productHelper;

    public function __construct($one)
    {
        /**
         * 通过Yii::mapGet() 得到重写后的class类名以及对象。Yii::mapGet是在文件@fecshop\yii\Yii.php中
         */
        list($this->_productHelperName,$this->_productHelper) = Yii::mapGet($this->_productHelperName);  
        
        $currentAttrGroup = CRequest::param('attr_group');
        if ($currentAttrGroup) {
            $this->_currentAttrGroup = $currentAttrGroup;
        } elseif (isset($one['attr_group']) && $one['attr_group']) {
            $this->_currentAttrGroup = $one['attr_group'];
        } else {
            $this->_currentAttrGroup = Yii::$service->product->getDefaultAttrGroup();
        }

        Yii::$service->product->addGroupAttrs($this->_currentAttrGroup);

        //$this->_attrInfo = Yii::$service->product->getGroupAttrInfo($this->_currentAttrGroup);
        //if(is_array($this->_attrInfo) && !empty($this->_attrInfo)){
        //	$attrs = array_keys($this->_attrInfo);
        //	\fecshop\models\mongodb\Product::addCustomProductAttrs($attrs);
        //}
    }

    public function getGroupAttr()
    {
        if (!$this->_attrInfo) {
            $this->_attrInfo = Yii::$service->product->getGroupAttrInfo($this->_currentAttrGroup);
        }

        return $this->_attrInfo;
    }

    public function getProductAttrGroupSelect()
    {
        $attrGroup = Yii::$service->product->getCustomAttrGroup();
        $str = '';
        if (is_array($attrGroup) && !empty($attrGroup)) {
            $str .= '<select name="attr_group" class="attr_group required">';

            foreach ($attrGroup as $k=>$v) {
                if ($this->_currentAttrGroup == $v) {
                    $str .= '<option value="'.$v.'" selected="selected">'.$v.'</option>';
                } else {
                    $str .= '<option value="'.$v.'" >'.$v.'</option>';
                }
            }
            $str .= '</select>';
        }

        return $str;
    }

    public function getRelationInfo()
    {
        return [
            [
                'label'=>'相关产品sku（逗号隔开）',
                'name'=>'relation_sku',
                'display'=>[
                    'type' => 'inputString',

                ],
                'require' => 0,
            ],

            [
                'label'=>'买了还买sku（逗号隔开）',
                'name'=>'buy_also_buy_sku',
                'display'=>[
                    'type' => 'inputString',

                ],
                'require' => 0,
            ],

            [
                'label'=>'看了还看sku（逗号隔开）',
                'name'=>'see_also_see_sku',
                'display'=>[
                    'type' => 'inputString',

                ],
                'require' => 0,
            ],

        ];
    }

    public function getBaseInfo()
    {
        return [
            [
                'label'=>'产品名字',
                'name'=>'name',
                'display'=>[
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'require' => 1,
            ],
            [
                'label'=>'SPU',
                'name'=>'spu',
                'display'=>[
                    'type' => 'inputString',
                    'lang' => false,

                ],
                'require' => 1,
            ],
            [
                'label'=>'SKU',
                'name'=>'sku',
                'display'=>[
                    'type' => 'inputString',
                    'lang' => false,

                ],
                'require' => 1,
            ],
            [
                'label'=>'重量(KG)',
                'name'=>'weight',
                'display'=>[
                    'type' => 'inputString',
                    'lang' => false,

                ],
                'require' => 0,
            ],
            [
                'label'=>'分值',
                'name'=>'score',
                'display'=>[
                    'type' => 'inputString',
                    'lang' => false,

                ],
                'require' => 0,
            ],
            [
                'label'=>'状态',
                'name'=>'status',
                'display'=>[
                    'type' => 'select',
                    'data' => $this->_productHelper->getStatusArr(),
                ],
                'require' => 1,
                'default' => 1,
            ],

            [
                'label'=>'新产品开始时间',
                'name'=>'new_product_from',
                'display'=>[
                    'type' => 'inputDate',
                ],
                'require' => 0,
            ],

            [
                'label'=>'新产品结束时间',
                'name'=>'new_product_to',
                'display'=>[
                    'type' => 'inputDate',
                ],
                'require' => 0,
            ],

            [
                'label'=>'Url Key',
                'name'=>'url_key',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],

            [
                'label'=>'库存个数',
                'name'=>'qty',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],

            [
                'label'=>'库存状态',
                'name'=>'is_in_stock',
                'display'=>[
                    'type' => 'select',
                    'data' => $this->_productHelper->getInStockArr(),
                ],
                'require' => 1,
                'default' => 1,
            ],

            [
                'label'=>'备注',
                'name'=>'remark',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],

        ];
    }

    public function getPriceInfo()
    {
        return [
            [
                'label'=>'成本价格',
                'name'=>'cost_price',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label'=>'销售价格',
                'name'=>'price',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'=>'销售特价',
                'name'=>'special_price',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],

            [
                'label'=>'特价开始时间',
                'name'=>'special_from',
                'display'=>[
                    'type' => 'inputDate',
                ],
                'require' => 0,
            ],

            [
                'label'=>'特价结束时间',
                'name'=>'special_to',
                'display'=>[
                    'type' => 'inputDate',
                ],
                'require' => 0,
            ],
        ];
    }

    public function getMetaInfo()
    {
        return [
            [
                'label'=>'Meta Title',
                'name'=>'meta_title',
                'display'=>[
                    'type' => 'inputString',
                    'lang' => true,

                ],
                'require' => 0,
            ],

            [
                'label'=>'Meta Keywords',
                'name'=>'meta_keywords',
                'display'=>[
                    'type' => 'inputString',
                    'lang' => true,

                ],
                'require' => 0,
            ],

            [
                'label'=>'Meta Description',
                'name'=>'meta_description',
                'display'=>[
                    'type' => 'textarea',
                    'lang' => true,
                    'rows'    => 14,
                    'cols'    => 100,
                ],
                'require' => 0,
            ],
        ];
    }

    public function getDescriptionInfo()
    {
        return [
            [
                'label'=>'产品Short描述',
                'name'=>'short_description',
                'display'=>[
                    'type' => 'textarea',
                    'lang' => true,
                    'rows'    => 14,
                    'cols'    => 100,
                ],
                'require' => 0,
            ],

            [
                'label'=>'产品描述（<b>必填</b>）',
                'name'=>'description',
                'display'=>[
                    'type' => 'textarea',
                    'lang' => true,
                    'rows'    => 14,
                    'cols'    => 100,
                ],
                'require' => 1,
            ],
        ];
    }

    public function getCatalogInfo()
    {
        return [

        ];
    }
}
