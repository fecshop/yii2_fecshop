<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Catalog\block\product;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CustomOption
{
    public $custom_option;
    public $attr_group;
    public $product_id;
    public $middle_img_width; // 图片的宽度。
    protected $_custom_option_arr;

    public function getLastData()
    {
        $items = $this->getAllItems();
        //var_dump($items);exit;
        return [
            'items' => $items,
            'product_id'        => $this->product_id,
            'custom_option_arr' => json_encode($this->_custom_option_arr),
            'middle_img_width'  => $this->middle_img_width,
        ];
    }
    /**
     * 得到custom option 部分
     */
    public function getAllItems()
    {
        $custom_option_attr_info = Yii::$service->product->getCustomOptionAttrInfo($this->attr_group);

        //#########
        $my_arr = [];
        $arr = [];
        //#在custom_option里面第一个属性
        $img_attr = '';
        if (is_array($custom_option_attr_info) && !empty($custom_option_attr_info)) {
            foreach ($custom_option_attr_info as $attr => $info) {
                if (isset($info['showAsImg']) && $info['showAsImg']) {
                    $img_attr = $attr;
                    break;
                }
            }
        }
        $img_arr = [];
        if (is_array($this->custom_option) && (!empty($this->custom_option))) {
            foreach ($this->custom_option as $option) {
                $qty = $option['qty'];
                if ($qty > 0) {
                    $this->_custom_option_arr[] = $option;
                    if (isset($option[$img_attr])) {
                        $val = $option[$img_attr];
                        $img_arr[$val] = $option['image'];
                    }
                    foreach ($option as $k=>$v) {
                        $my_arr[$k][] = $v;
                    }
                }
            }
        }
        if (is_array($custom_option_attr_info) && !empty($custom_option_attr_info)) {
            foreach ($custom_option_attr_info as $attr => $info) {
                if (isset($info['display']['type']) && ($info['display']['type'] == 'select')) {
                    if (isset($info['display']['data']) && is_array($info['display']['data'])) {
                        foreach ($info['display']['data'] as $key=>$val) {
                            if (is_array($my_arr[$attr]) && in_array($key, $my_arr[$attr])) {
                                $t_arr = [
                                    'key' => $key,
                                    'val' => $val,
                                ];
                                $require = isset($info['require']) ? $info['require'] : 0;
                                if (isset($info['showAsImg']) && $info['showAsImg']) {
                                    if (isset($img_arr[$key])) {
                                        $t_arr['image'] = $img_arr[$key];
                                    }
                                }
                                $arr[$attr]['info'][] = $t_arr;
                                $arr[$attr]['require'] = $require;
                            }
                        }
                    }
                }
            }
        }

        return $arr;
    }
}
