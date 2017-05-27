<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

use fecshop\models\mongodb\Product;
use fecshop\services\Service;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Stock extends Service
{
    // 零库存：也就是说库存忽略掉，产品的库存

    public $zeroInventory = 0;

    // product model arr
    protected $_product_arr;
    // product items（譬如购物车信息）
    protected $_product_items;
    // 是否已经检查产品是否有库存。
    protected $_checkItemsStockStatus;
    //protected $CheckItemsStock

    /**
     * @property $items | Array ， example:
     * 	[
     *		[
     *			'sku' => 'xxxxx',
     *			'qty' => 2,
     *			'custom_option_sku' => 'cos_1',  # 存在该项，则应该到产品的
     *		],
     *		[
     *			'sku' => 'yyyyy',
     *			'qty' => 1,
     *		],
     *	]
     * @return bool
     *              扣除产品库存。如果扣除成功，则返回true，如果返回失败，则返回false
     */
    protected function actionDeduct($items = '')
    {
        if (!$items) { //如果$items为空，则去购物车取数据。
            $cartInfo = Yii::$service->cart->getCartInfo();
            $items = isset($cartInfo['products']) ? $cartInfo['products'] : '';
        }
        if (!$this->_checkItemsStockStatus) {
            if (!$this->checkItemsStock($items)) {
                return false;
            }
        }
        /**
         * $this->checkItemsStock 函数检查产品是否都是上架状态
         * 如果满足上架状态 && 零库存为1，则直接返回。
         */
        if ($this->zeroInventory) {
            return true; // 零库存模式 不会更新产品库存。
        }

        $product_arr = $this->_product_arr;
        $product_items = $this->_product_items;
        // 开始扣除库存。
        if (is_array($product_items) && !empty($product_items)) {
            foreach ($product_items as $k=>$item) {
                $sku = $item['sku'];
                $sale_qty = $item['qty'];
                $custom_option_sku = $item['custom_option_sku'];
                if ($sku && $sale_qty) {
                    $product = $product_arr[$k];
                    if ($product) {
                        if (!$custom_option_sku) {
                            $is_in_stock = $product['is_in_stock'];
                            $product_qty = $product['qty'];
                            $product->qty = $product_qty - $sale_qty;
                            $product->save();
                        } else {
                            $custom_option = $product['custom_option'];
                            if (isset($custom_option[$custom_option_sku]['qty']) && !empty($custom_option[$custom_option_sku]['qty'])) {
                                $custom_option[$custom_option_sku]['qty'] -= $sale_qty;
                                $product->custom_option = $custom_option;
                                $product->save();
                            }
                        }
                    }
                }
            }

            return true;
        }
    }

    /**
     * @property $items | Array ， example:
     * 	[
     *		[
     *			'sku' => 'xxxxx',
     *			'qty' => 2,
     *			'custom_option_sku' => 'cos_1',  # 存在该项，则应该到产品的
     *		],
     *		[
     *			'sku' => 'yyyyy',
     *			'qty' => 1,
     *		],
     *	]
     * @return bool
     *              检查产品的库存是否允许扣除。如果扣除成功，则返回true，如果返回失败，则返回false
     */
    protected function actionCheckItemsStock($items)
    {
        $product_arr = [];
        $i = 0;
        $product_items = [];
        // 首先检查，库存是否满足，不满足则返回false
        if (is_array($items) && !empty($items)) {
            foreach ($items as $item) {
                //var_dump($item);
                $sku = $item['sku'];
                $sale_qty = $item['qty'];
                $custom_option_sku = $item['custom_option_sku'];
                if ($sku && $sale_qty) {
                    $product = Yii::$service->product->getBySku($sku, false);
                    if ($product) {
                        if ($this->productIsInStock($product, $sale_qty, $custom_option_sku)) {
                            $product_items[$i] = $item;
                            $product_arr[$i] = $product;
                            $i++;
                        } else {
                            Yii::$service->helper->errors->add('product sku('.$sku.') is stock out');
                            //echo 3;
                            return false;
                        }
                    } else {
                        Yii::$service->helper->errors->add('product sku('.$sku.') is not exist');
                        //echo 4;
                        return false;
                    }
                } else {
                    Yii::$service->helper->errors->add('cart sku  qty is empty');
                    //echo 5;
                    return false;
                }
            }
        }
        $this->_checkItemsStockStatus = true;
        $this->_product_arr = $product_arr;
        $this->_product_items = $product_items;

        return true;
    }

    /**
     * @property $product_items | Array ， example:
     * 	[
     *		[
     *			'sku' => 'xxxxx',
     *			'qty' => 2,
     *			'custom_option_sku' => 'cos_1',  # 存在该项，则应该到产品的
     *		],
     *		[
     *			'sku' => 'yyyyy',
     *			'qty' => 1,
     *		],
     *	]
     * @return bool
     *              返还产品库存。
     */
    protected function actionReturnQty($product_items)
    {
        if ($this->zeroInventory) {
            return true; // 零库存模式不扣产品库存，也不需要返还库存。
        }
        // 开始扣除库存。
        if (is_array($product_items) && !empty($product_items)) {
            foreach ($product_items as $k=>$item) {
                $sku = $item['sku'];
                $sale_qty = $item['qty'];
                $custom_option_sku = $item['custom_option_sku'];
                if ($sku && $sale_qty) {
                    echo 'SKU:'.$sku."\n";
                    echo 'sale_qty:'.$sale_qty."\n";
                    $product = Yii::$service->product->getBySku($sku, false);
                    if ($product) {
                        echo $product->sku."\n";
                        echo $custom_option_sku."\n\n\n";
                        if (!$custom_option_sku) {
                            $is_in_stock = $product['is_in_stock'];
                            $product_qty = $product['qty'];
                            $product->qty = $product_qty + $sale_qty;
                            $product->save();
                        } else {
                            $custom_option = $product['custom_option'];
                            if (isset($custom_option[$custom_option_sku]['qty']) && !empty($custom_option[$custom_option_sku]['qty'])) {
                                $custom_option[$custom_option_sku]['qty'] += $sale_qty;
                                $product->custom_option = $custom_option;
                                $product->save();
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * @property $product | Object,  Product Model
     * @property sale_qty | Int 需要出售的个数
     * @property custom_option_sku | String 产品custom option sku
     * @return bool
     *              返还产品库存。
     */
    protected function actionProductIsInStock($product, $sale_qty, $custom_option_sku)
    {
        $is_in_stock = $product['is_in_stock'];
        /*
         * 零库存模式 && 产品是上架状态 直接返回true
         */
        if ($this->zeroInventory && $this->checkOnShelfStatus($is_in_stock)) {
            return true; // 零库存模式不扣产品库存，也不需要返还库存。
        }
        $product_qty = $product['qty'];
        if ($this->checkOnShelfStatus($is_in_stock)) {
            if ($custom_option_sku) {
                $custom_option = $product['custom_option'];
                if (isset($custom_option[$custom_option_sku]['qty']) && !empty($custom_option[$custom_option_sku]['qty'])) {
                    $custom_option_qty = $custom_option[$custom_option_sku]['qty'];
                    if (($custom_option_qty > 0) && ($custom_option_qty > $sale_qty)) {
                        return true;
                    }
                }
            } elseif (($product_qty > 0) && ($product_qty > $sale_qty)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @property $is_in_stock | Int,  状态
     * @return bool
     *              检查产品是否是上架上台
     */
    protected function actionCheckOnShelfStatus($is_in_stock)
    {
        if ($is_in_stock === 1) {
            return true;
        }

        return false;
    }
}
