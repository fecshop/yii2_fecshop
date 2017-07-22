<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

//use fecshop\models\mongodb\Product;
use fecshop\services\Service;
use Yii;

/**
 * Product Info Services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Info extends Service
{
    
    //protected $_productModelName = '\fecshop\models\mongodb\Product';
    //protected $_productModel;
    
    //public function __construct(){
        //list($this->_productModelName,$this->_productModel) = \Yii::mapGet($this->_productModelName);  
    //}
    /**
     * @property $custome_option | Array
     * $custome_option = [
     *      "my_color" 	=> "red",
     *      "my_size" 	=> "M",
     *      "my_size2" 	=> "M2",
     *      "my_size3" 	=> "L3"
     * ]
     * 通过custom的各个值，生成custom option sku
     */
    public function getCustomOptionSkuByValue($custome_option)
    {
        $str = '';
        $arr = [];
        if (is_array($custome_option) && !empty($custome_option)) {
            foreach ($custome_option as $k=>$v) {
                $arr[] = str_replace(' ', '*', $v);
            }
        }

        return implode('-', $arr);
    }

    /**
     * @property $custom_option | Array 前台传递的custom option 一维数组。
     * @property $product_custom_option | Array  数据库中存储的产品custom_option的值
     * 验证前台传递的custom option 是否正确。
     */
    public function validateProductCustomOption($custom_option, $product_custom_option)
    {
        if (empty($product_custom_option) && empty($custom_option)) {
            return true; // 都为空，说明不需要验证。
        }
        if ($custom_option) {
            $co_sku = $this->getCustomOptionSkuByValue($custom_option);
            //$product_custom_option = $product['custom_option'];
            if (!is_array($product_custom_option)) {
                Yii::$service->helper->errors->add('this product custom option is error');

                return;
            }
            foreach ($product_custom_option as $p_sku => $option) {
                if ($p_sku == $co_sku) {
                    return true;
                }
            }
        }
        Yii::$service->helper->errors->add('this product custom option can not find in this product');

        return false;
    }

    /**
     * 通过返回的值，得到product custom option 的sku key.
     */

    /**
     * @property $custom_option_arr | Array ， 用户选择提交数据，格式为
     *    [
     *        'color' => 'red',
     *        'size'  => 'L',
     *    ]
     * @property $product_custom_option | Array ， 产品表中的custom_option属性值，譬如：
     *  [
     *      "black-s-s2-s3": [
     *          "my_color": "black",
     *          "my_size": "S",
     *          "my_size2": "S2",
     *          "my_size3": "S3",
     *          "sku": "black-s-s2-s3",
     *          "qty": NumberInt(99999),
     *          "price": 0,
     *          "image": "/2/01/20161024170457_10036.jpg"
     *      ],
     *  ]
     * 通过前台传递的custom option 得到customOptionSku.
     */
    public function getProductCOSku($custom_option_arr, $product_custom_option)
    {
        if (is_array($product_custom_option) && !empty($product_custom_option)) {
            foreach ($product_custom_option as $co_sku => $info) {
                $bool = true;
                if (is_array($info) && !empty($info)) {
                    foreach ($info as $k=>$v) {
                        if (isset($custom_option_arr[$k]) && ($custom_option_arr[$k] != $v)) {
                            $bool = false;
                            break;
                        }
                    }
                    if ($bool) {
                        return $co_sku;
                    }
                }
            }
        }
    }
}
