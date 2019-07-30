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
class BatchAttr
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
    }

    public function getGroupAttr()
    {
        if (!$this->_attrInfo) {
            $this->_attrInfo = Yii::$service->product->getGroupAttrInfo($this->_currentAttrGroup);
        }

        return $this->_attrInfo;
    }
    
    public function getGroupGeneralAttr()
    {
        return Yii::$service->product->getGroupGeneralAttr($this->_currentAttrGroup);
    }
    
    public function getGroupSpuAttr()
    {
        return Yii::$service->product->getGroupSpuAttr($this->_currentAttrGroup);
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
                'label' => Yii::$service->page->translate->__('SKU of related products (comma separated)'),
                'name'  => 'relation_sku',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Bought also bought sku (comma separated)'),
                'name'  => 'buy_also_buy_sku',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Saw also saw sku (comma separated)'),
                'name'  => 'see_also_see_sku',
                'display' => [
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
                'label' => Yii::$service->page->translate->__('Product Name'),
                'name'  => 'name',
                'display' => [
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'require' => 1,
            ],
            [
                'label' => Yii::$service->page->translate->__('Spu'),
                'name'  => 'spu',
                'display' => [
                    'type' => 'inputString',
                    'lang' => false,
                ],
                'require' => 1,
            ],
            [
                'label' => Yii::$service->page->translate->__('Long (CM)'),
                'name'  => 'long',
                'display' => [
                    'type' => 'inputString',
                    'lang' => false,
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Width (CM)'),
                'name'  => 'width',
                'display' => [
                    'type' => 'inputString',
                    'lang' => false,
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('High (CM)') ,
                'name' => 'high',
                'display' => [
                    'type' => 'inputString',
                    'lang' => false,
                ],
                'require' => 0,
            ],
            [
                'label' => '<span >' . Yii::$service->page->translate->__('Volume weight (Kg) {link_a}   Formula {link_b} ', [ 'link_a' => '<a  target="_blank" href="http://www.fecshop.com/topic/659">' , 'link_b' => '</a>' ]) . '</span>' ,
                'name'  => 'volume_weight',
                'display' => [
                    'type' => 'inputString',
                    'lang' => false,
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Weight (KG)'),
                'name'  => 'weight',
                'display' => [
                    'type' => 'inputString',
                    'lang' => false,
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Score'),
                'name'  => 'score',
                'display' => [
                    'type' => 'inputString',
                    'lang' => false,
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Status'),
                'name'  => 'status',
                'display' => [
                    'type' => 'select',
                    'data' => $this->_productHelper->getStatusArr(),
                ],
                'require' => 1,
                'default' => 1,
            ],
            [
                'label' => Yii::$service->page->translate->__('New Product Begin'),
                'name'  => 'new_product_from',
                'display' => [
                    'type' => 'inputDate',
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('New Product End'),
                'name'  => 'new_product_to',
                'display' => [
                    'type' => 'inputDate',
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Url Key'),
                'name'  => 'url_key',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Package sales qty'),
                'name'  => 'package_number',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Min sale qty'),
                'name'  => 'min_sales_qty',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Stock Status'),
                'name'  => 'is_in_stock',
                'display'=>[
                    'type' => 'select',
                    'data' => $this->_productHelper->getInStockArr(),
                ],
                'require' => 1,
                'default' => 1,
            ],
            [
                'label' => Yii::$service->page->translate->__('Remark'),
                'name'  => 'remark',
                'display' => [
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
                'label' => Yii::$service->page->translate->__('Cost Price'),
                'name'  => 'cost_price',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            
            [
                'label' => Yii::$service->page->translate->__('Special Price'),
                'name'  => 'special_price',
                'display' => [
                    'type' => 'inputString',
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Special Begin'),
                'name'  => 'special_from',
                'display'=>[
                    'type' => 'inputDateTime',
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Special End'),
                'name'  => 'special_to',
                'display'=>[
                    'type' => 'inputDateTime',
                ],
                'require' => 0,
            ],
        ];
    }

    public function getMetaInfo()
    {
        return [
            [
                'label' => Yii::$service->page->translate->__('Meta Title'),
                'name'  => 'meta_title',
                'display' => [
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Meta Keywords'),
                'name'  => 'meta_keywords',
                'display'=>[
                    'type' => 'inputString',
                    'lang' => true,
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Meta Description'),
                'name'  => 'meta_description',
                'display' => [
                    'type' => 'textarea',
                    'notEditor' => true,
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
                'label' => Yii::$service->page->translate->__('Short Description') ,
                'name'  => 'short_description',
                'display' => [
                    'type' => 'textarea',
                    'lang' => true,
                    'rows'    => 14,
                    'cols'    => 100,
                ],
                'require' => 0,
            ],
            [
                'label' => Yii::$service->page->translate->__('Description {b} Require {e}', ['b' => ' (<b>' , 'e' => '</b>)']) ,
                'name'  => 'description',
                'display' => [
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
        return [];
    }
}
