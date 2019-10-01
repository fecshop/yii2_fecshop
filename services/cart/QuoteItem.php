<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\cart;

use fecshop\services\Service;
use Yii;

/**
 * Cart services. 对购物车产品操作的具体实现部分。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class QuoteItem extends Service
{
    public $itemDefaultActiveStatus = 1;

    public $activeStatus = 1;

    public $noActiveStatus = 2;
    
    protected $_my_cart_item;    // 购物车cart item 对象

    protected $_cart_product_info;
    
    protected $_itemModelName = '\fecshop\models\mysqldb\cart\Item';

    /**
     * @var \fecshop\models\mysqldb\cart\Item
     */
    protected $_itemModel;
    
    public function init()
    {
        parent::init();
        list($this->_itemModelName, $this->_itemModel) = Yii::mapGet($this->_itemModelName);
    }
    
    /**
     * 将某个产品加入到购物车中。
     * 在添加到 cart_item 表后，更新购物车中产品的总数。
     * @param array $item
     * @param Object $product ， Product Model
     * @return mixed
     * example:
     * $item = [
     *		'product_id' 		=> 22222,
     *		'custom_option_sku' => red-xxl,
     *		'qty' 				=> 22,
     *      'sku' 				=> 'xxxx',
     * ];
     */
    public function addItem($item, $product)
    {
        $cart_id = Yii::$service->cart->quote->getCartId();
        if (!$cart_id) {
            Yii::$service->cart->quote->createCart();
            $cart_id = Yii::$service->cart->quote->getCartId();
        }
        // 
        if (!isset($item['product_id']) || empty($item['product_id'])) {
            Yii::$service->helper->errors->add('add to cart error, product id is empty');

            return false;
        }
        
        
        $where = [
            'cart_id'    => $cart_id,
            'product_id' => $item['product_id'],
        ];
        if (isset($item['custom_option_sku']) && !empty($item['custom_option_sku'])) {
            $where['custom_option_sku'] = $item['custom_option_sku'];
        }
        /** @var \fecshop\models\mysqldb\cart\Item $item_one */
        $item_one = $this->_itemModel->find()->where($where)->one();
        
        if ($item_one['cart_id']) {
            // 检查产品满足加入购物车的条件
            $checkItem = $item;
            $checkItem['qty'] = $item['qty'] + $item_one['qty'];
            $productValidate = Yii::$service->cart->info->checkProductBeforeAdd($checkItem, $product);
            
            if (!$productValidate) {
                return false;
            }
            $item_one->active = $this->itemDefaultActiveStatus;
            $item_one->qty = $item['qty'] + $item_one['qty'];
            $item_one->save();
            // 重新计算购物车的数量
            Yii::$service->cart->quote->computeCartInfo();
        } else {
            // 检查产品满足加入购物车的条件
            $checkItem = $item;
            $productValidate = Yii::$service->cart->info->checkProductBeforeAdd($checkItem, $product);
            if (!$productValidate) {
                return false;
            }
            $item_one = new $this->_itemModelName;
            $item_one->store = Yii::$service->store->currentStore;
            $item_one->cart_id = $cart_id;
            $item_one->created_at = time();
            $item_one->updated_at = time();
            $item_one->product_id = $item['product_id'];
            $item_one->qty = $item['qty'];
            $item_one->active = $this->itemDefaultActiveStatus;
            $item_one->custom_option_sku = ($item['custom_option_sku'] ? $item['custom_option_sku'] : '');
            $item_one->save();
            // 重新计算购物车的数量,并写入 sales_flat_cart 表存储
            Yii::$service->cart->quote->computeCartInfo();
        }
        
        $item['afterAddQty'] = $item_one->qty;
        $this->sendTraceAddToCartInfoByApi($item);

        return true;
    }

    /**
     * @param $item | Array, example:
     * $item = [
     *		'product_id' 		=> 22222,
     *		'custom_option_sku' => red-xxl,
     *		'qty' 				=> 22,    // 添加购物车的产品个数
     *      'sku' 				=> 'xxxx',
     *      'afterAddQty'       => 33,  // 添加后，该产品在sku中的个数，这个个数是为了计算购物车中产品的价格
     * ];
     * 将加入购物车的操作，加入trace
     */
    public function sendTraceAddToCartInfoByApi($item)
    {
        if (Yii::$service->page->trace->traceJsEnable) {
            $product_price_arr  = Yii::$service->product->price->getCartPriceByProductId($item['product_id'], $item['afterAddQty'], $item['custom_option_sku'], 2);
            $base_product_price = isset($product_price_arr['base_price']) ? $product_price_arr['base_price'] : 0;
            // $price = $base_product_price * $item['qty'];
            $trace_cart_info = [
                [
                    'sku'   => $item['sku'],
                    'price' => $base_product_price,
                    'qty'   => $item['qty'],
                ]
            ];
            Yii::$service->page->trace->sendTraceAddToCartInfoByApi($trace_cart_info);
        }
    }

    /**
     * @param $item | Array, example:
     * $item = [
     *		'product_id' 		=> 22222,
     *		'custom_option_sku' => red-xxl,
     *		'qty' 				=> 22,
     * ];
     * @return boolean;
     *                  将购物车中的某个产品更改个数，更改后的个数就是上面qty的值。
     * @deprecated 该函数已经被遗弃
     */
    /*
    public function changeItemQty($item)
    {
        $cart_id = Yii::$service->cart->quote->getCartId();
        // 查看是否存在此产品，如果存在，则更改
        $item_one = $this->_itemModel->find()->where([
            'cart_id'           => $cart_id,
            'product_id'        => $item['product_id'],
            'custom_option_sku' => $item['custom_option_sku'],
        ])->one();
        if ($item_one['cart_id']) {
            $item_one->qty = $item['qty'];
            $item_one->save();
            // 重新计算购物车的数量
            Yii::$service->cart->quote->computeCartInfo();

            return true;
        } else {
            Yii::$service->helper->errors->add('This product is not available in the shopping cart');

            return false;
        }
    }
    */

    /**
     * 通过quoteItem表，计算得到所有产品的总数
     * 得到购物车中产品的总数，不要使用这个函数，这个函数的作用：
     * 在购物车中产品有变动后，使用这个函数得到产品总数，更新购物车中
     * 的产品总数。
     */
    public function getItemAllQty()
    {
        $cart_id = Yii::$service->cart->quote->getCartId();
        $item_qty = 0;
        if ($cart_id) {
            $data = $this->_itemModel->find()->asArray()->where([
                'cart_id' => $cart_id,
            ])->all();
            if (is_array($data) && !empty($data)) {
                foreach ($data as $one) {
                    $product_id = $one['product_id'];
                    $productModel = Yii::$service->product->getByPrimaryKey($product_id);
                    if ($productModel && isset($productModel['status']) && Yii::$service->product->isActive($productModel['status'])) {
                        $item_qty += $one['qty'];
                    }
                }
            }
        }

        return $item_qty;
    }
    
    /**
     * 通过quoteItem表，计算得到所有产品的总数
     * 得到购物车中产品的总数，不要使用这个函数，这个函数的作用：
     * 在购物车中产品有变动后，使用这个函数得到产品总数，更新购物车中
     * 的产品总数。
     */
    public function getActiveItemQty()
    {
        $cart_id = Yii::$service->cart->quote->getCartId();
        $item_qty = 0;
        if ($cart_id) {
            $data = $this->_itemModel->find()->asArray()->where([
                'cart_id' => $cart_id,
                'active' => $this->activeStatus,
            ])->all();
            if (is_array($data) && !empty($data)) {
                foreach ($data as $one) {
                    $product_id = $one['product_id'];
                    $productModel = Yii::$service->product->getByPrimaryKey($product_id);
                    if ($productModel && isset($productModel['status']) && Yii::$service->product->isActive($productModel['status'])) {
                        $item_qty += $one['qty'];
                    }
                }
            }
        }

        return $item_qty;
    }

    /**
     * @param $activeProduct | boolean , 是否只要active的产品
     * @return array ， foramt：
     *               [
     *               'products' 		=> $products, 				# 产品详细信息，详情参看代码中的$products。
     *               'product_total' => $product_total, 			# 产品的当前货币总额
     *               'base_product_total' => $base_product_total,# 产品的基础货币总额
     *               'product_weight'=> $product_weight,			# 蟾皮的总重量、
     *               ]
     *               得到当前购物车的产品信息，具体参看上面的example array。
     */
    public function getCartProductInfo($activeProduct = true)
    {
        $cart_id        = Yii::$service->cart->quote->getCartId();
        $products       = [];
        $product_total  = 0;
        $product_weight = 0;
        $product_volume_weight = 0;
        $base_product_total = 0;
        $product_volume = 0;
        $product_qty_total = 0;
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        if ($cart_id) {
            if (!isset($this->_cart_product_info[$cart_id])) {
                $data = $this->_itemModel->find()->where([
                    'cart_id' => $cart_id,
                ])->orderBy( ['active' => SORT_ASC, 'updated_at' => SORT_DESC])  // 加入按照active  updated_at 进行排序
                ->all();
                if (is_array($data) && !empty($data)) {
                    foreach ($data as $one) {
                        $active             = $one['active'];
                        if ($activeProduct && ($active != $this->activeStatus)) {
                            continue;
                        }
                        $product_id     = $one['product_id'];
                        $product_one    = Yii::$service->product->getByPrimaryKey($product_id);
                        if ($product_one[$productPrimaryKey]) {
                            $qty                = $one['qty'];
                            
                            $custom_option_sku  = $one['custom_option_sku'];
                            $product_price_arr  = Yii::$service->product->price->getCartPriceByProductId($product_id, $qty, $custom_option_sku, 2);
                            $curr_product_price = isset($product_price_arr['curr_price']) ? $product_price_arr['curr_price'] : 0;
                            $base_product_price = isset($product_price_arr['base_price']) ? $product_price_arr['base_price'] : 0;
                            $product_price      = isset($curr_product_price['value']) ? $curr_product_price['value'] : 0;

                            $product_row_price  = $product_price * $qty;
                            $base_product_row_price = $base_product_price * $qty;
                            
                            $volume = Yii::$service->shipping->getVolume($product_one['long'], $product_one['width'], $product_one['high']);
                            $p_pv               = $volume * $qty;
                            $p_wt               = $product_one['weight'] * $qty;
                            $p_vwt              = $product_one['volume_weight'] * $qty;
                            
                            if ($active == $this->activeStatus) {
                                $product_total          += $product_row_price;
                                $base_product_total     += $base_product_row_price;
                                $product_weight         += $p_wt;
                                $product_volume_weight  += $p_vwt;
                                $product_volume         += $p_pv;
                                $product_qty_total      += $qty;
                            }
                            $productSpuOptions  = $this->getProductSpuOptions($product_one);
                            $products[] = [
                                'item_id'           => $one['item_id'],
                                'active'            => $active,
                                'product_id'        => $product_id,
                                'sku'               => $product_one['sku'],
                                'name'              => Yii::$service->store->getStoreAttrVal($product_one['name'], 'name'),
                                'qty'               => $qty,
                                'custom_option_sku' => $custom_option_sku,
                                'product_price'     => $product_price,
                                'product_row_price' => $product_row_price,

                                'base_product_price'    => $base_product_price,
                                'base_product_row_price'=> $base_product_row_price,

                                'product_name'      => $product_one['name'],
                                'product_weight'    => $product_one['weight'],
                                'product_row_weight'=> $p_wt,
                                'product_volume_weight'     => $product_one['volume_weight'],
                                'product_row_volume_weight' => $p_vwt,
                                'product_volume'        => $volume,
                                'product_row_volume'    => $p_pv,
                                'product_url'       => $product_one['url_key'],
                                'product_image'     => $product_one['image'],
                                'custom_option'     => $product_one['custom_option'],
                                'spu_options'       => $productSpuOptions,
                            ];
                        }
                    }
                    $this->_cart_product_info[$cart_id] = [
                        'products'              => $products,
                        'product_qty_total'     => $product_qty_total,
                        'product_total'         => $product_total,
                        'base_product_total'    => $base_product_total,
                        'product_weight'        => $product_weight,
                        'product_volume_weight' => $product_volume_weight,
                        'product_volume'        => $product_volume,
                        
                    ];
                }
            }

            return $this->_cart_product_info[$cart_id];
        }
    }

    /**
     * @param $productOb | Object，类型：\fecshop\models\mongodb\Product
     * 得到产品的spu对应的属性以及值。
     * 概念 - spu options：当多个产品是同一个spu，但是不同的sku的时候，他们的产品表里面的
     * spu attr 的值是不同的，譬如对应鞋子，size 和 color 就是spu attr，对于同一款鞋子，他们
     * 是同一个spu，对于尺码，颜色不同的鞋子，是不同的sku，他们的spu attr 就是 color 和 size。
     */
    protected function getProductSpuOptions($productOb)
    {
        $custom_option_info_arr = [];
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        $productAttrGroup = $productOb['attr_group'];
        if (isset($productOb['attr_group']) && !empty($productOb['attr_group'])) {
            $spuArr     = Yii::$service->product->getSpuAttr($productAttrGroup);
            //var_dump($productOb['attr_group_info']);
            // mysql存储
            if (isset($productOb['attr_group_info']) && is_array($productOb['attr_group_info'])) {
                $attr_group_info = $productOb['attr_group_info'];
                if (is_array($spuArr) && !empty($spuArr)) {
                    foreach ($spuArr as $spu_attr) {
                        if (isset($attr_group_info[$spu_attr]) && !empty($attr_group_info[$spu_attr])) {
                            // 进行翻译。
                            $spu_attr_label = Yii::$service->page->translate->__($spu_attr);
                            $spu_attr_val = Yii::$service->page->translate->__($attr_group_info[$spu_attr]);
                            $custom_option_info_arr[$spu_attr_label] = $spu_attr_val;
                        }
                    }
                }
            } else { // mongodb类型
                Yii::$service->product->addGroupAttrs($productAttrGroup);
                $productOb  = Yii::$service->product->getByPrimaryKey((string) $productOb[$productPrimaryKey ]);
                if (is_array($spuArr) && !empty($spuArr)) {
                    foreach ($spuArr as $spu_attr) {
                        if (isset($productOb[$spu_attr]) && !empty($productOb[$spu_attr])) {
                            // 进行翻译。
                            $spu_attr_label = Yii::$service->page->translate->__($spu_attr);
                            $spu_attr_val = Yii::$service->page->translate->__($productOb[$spu_attr]);
                            $custom_option_info_arr[$spu_attr_label] = $spu_attr_val;
                        }
                    }
                }
            }
        }
        return $custom_option_info_arr;
    }

    /**
     * @param $item_id | Int ， quoteItem表的id
     * @return bool
     *              将这个item_id对应的产品个数+1.
     */
    public function addOneItem($item_id)
    {
        
        $cart_id = Yii::$service->cart->quote->getCartId();
        if ($cart_id) {
            $one = $this->_itemModel->find()->where([
                'cart_id' => $cart_id,
                'item_id' => $item_id,
            ])->one();
            $product_id = $one['product_id'];
            if ($one['item_id'] && $product_id) {
                $product = Yii::$service->product->getByPrimaryKey($product_id);
                // 检查产品满足加入购物车的条件
                $checkItem = [
                    'product_id' 		=> $one['product_id'],
                    'custom_option_sku' => $one['custom_option_sku'],
                    'qty' 				=> $one['qty'] + 1,
                ];
                $productValidate = Yii::$service->cart->info->checkProductBeforeAdd($checkItem, $product);
            
                if (!$productValidate) {
                    return false;
                }
                $changeQty = Yii::$service->cart->getCartQty($product['package_number'], 1);
                $one['qty'] = $one['qty'] + $changeQty;
                $one->save();
                // 重新计算购物车的数量
                Yii::$service->cart->quote->computeCartInfo();
                $item = [
                    'product_id' 		=> $product_id,
                    'custom_option_sku' => $one['custom_option_sku'],
                    'qty' 				=> $changeQty,
                    'sku' 				=> $product['sku'],
                    'afterAddQty'       => $one['qty'],
                ];
                // 购物车数据加1
                $this->sendTraceAddToCartInfoByApi($item);
                return true;
            }
        }

        return false;
    }

    /**
     * @param $item_id | Int ， quoteItem表的id
     * @return bool
     *              将这个item_id对应的产品个数-1.
     */
    public function lessOneItem($item_id)
    {
        $cart_id = Yii::$service->cart->quote->getCartId();
        if ($cart_id) {
            $one = $this->_itemModel->find()->where([
                'cart_id' => $cart_id,
                'item_id' => $item_id,
            ])->one();
            $product_id = $one['product_id'];
            $product = Yii::$service->product->getByPrimaryKey($one['product_id']);
            $changeQty = Yii::$service->cart->getCartQty($product['package_number'], 1);
            $lessedQty = $one['qty'] - $changeQty;
            $min_sales_qty = 1;
            if ($product['min_sales_qty'] && $product['min_sales_qty'] >= 2) {
                $min_sales_qty = $product['min_sales_qty'];
            }
            if ($lessedQty < $min_sales_qty) {
                Yii::$service->helper->errors->add('product less buy qty is {min_sales_qty}', ['min_sales_qty' => $product['min_sales_qty']]);
                
                return false;
            }
            
            if ($one['item_id']) {
                if ($one['qty'] > 1) {
                    $one['qty'] = $lessedQty;
                    $one->save();
                    // 重新计算购物车的数量
                    Yii::$service->cart->quote->computeCartInfo();

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $item_id | Int ， quoteItem表的id
     * @return bool
     *              将这个item_id对应的产品删除
     */
    public function removeItem($item_id)
    {
        $cart_id = Yii::$service->cart->quote->getCartId();
        if ($cart_id) {
            $one = $this->_itemModel->find()->where([
                'cart_id' => $cart_id,
                'item_id' => $item_id,
            ])->one();
            if ($one['item_id']) {
                $one->delete();
                // 重新计算购物车的数量
                Yii::$service->cart->quote->computeCartInfo();

                return true;
            }
        }

        return false;
    }
    
    /**
     * @param $item_id | Int ， quoteItem表的id
     * @return bool
     *              将这个item_id对应的产品个数+1.
     */
    public function selectOneItem($item_id, $checked)
    {
        $cart_id = Yii::$service->cart->quote->getCartId();
        if ($cart_id) {
            $one = $this->_itemModel->find()->where([
                'cart_id' => $cart_id,
                'item_id' => $item_id,
            ])->one();
            $product_id = $one['product_id'];
            if ($one['item_id'] && $product_id) {
                //$product = Yii::$service->product->getByPrimaryKey($product_id);
                //$changeQty = Yii::$service->cart->getCartQty($product['package_number'], 1);
                //$one['qty'] = $one['qty'] + $changeQty;
                if ($checked == true) {
                    $one->active = $this->activeStatus;
                } else {
                    $one->active = $this->noActiveStatus;
                }
                $one->save();
                // 重新计算购物车的数量
                Yii::$service->cart->quote->computeCartInfo();

                return true;
            }
        }

        return false;
    }
    
    /**
     * @param $item_id | Int ， quoteItem表的id
     * @return bool
     *              将这个item_id对应的产品个数+1.
     */
    public function selectAllItem($checked)
    {
        $cart_id = Yii::$service->cart->quote->getCartId();
        if ($cart_id) {
            $active = $this->noActiveStatus;
            if ($checked == true) {
                $active = $this->activeStatus;
            }
            $updateCount = $this->_itemModel->updateAll(
                ['active'  => $active],
                ['cart_id' => $cart_id]
            );
            if ($updateCount > 0) {
                Yii::$service->cart->quote->computeCartInfo();
            }
            
            return true;
        }

        return false;
    }
    
    /**
     * @param $cart_id | int 购物车id
     * 删除购物车中的所有的active产品。对于noActive产品保留
     * 注意：清空购物车并不是清空所有信息，仅仅是清空用户购物车中的产品。
     * 另外，购物车的数目更改后，需要更新cart中产品个数的信息。
     */
    public function removeNoActiveItemsByCartId($cart_id = '')
    {
        if (!$cart_id) {
            $cart_id = Yii::$service->cart->quote->getCartId();
        }
        if ($cart_id) {
            $columns = $this->_itemModel->deleteAll([
                'cart_id' => $cart_id,
                'active'  => $this->activeStatus,
            ]);
            if ($columns > 0) {
                // 重新计算购物车的数量
                Yii::$service->cart->quote->computeCartInfo();

                return true;
            }
        }
    }

    /** 废弃，改为 removeNoActiveItemsByCartId()，因为购物车改为勾选下单方式。
     * @param $cart_id | int 购物车id
     * 删除购物车中的所有产品。
     * 注意：清空购物车并不是清空所有信息，仅仅是清空用户购物车中的产品。
     * 另外，购物车的数目更改后，需要更新cart中产品个数的信息。
     */
    public function removeItemByCartId($cart_id = '')
    {
        if (!$cart_id) {
            $cart_id = Yii::$service->cart->quote->getCartId();
        }
        if ($cart_id) {
            $items = $this->_itemModel->deleteAll([
                'cart_id' => $cart_id,
                //'item_id' => $item_id,
            ]);
            // 重新计算购物车的数量
            Yii::$service->cart->quote->computeCartInfo(0);
        }

        return true;
    }

    /**
     * @param $new_cart_id | int 更新后的cart_id
     * @param $cart_id | int 更新前的cart_id
     * 删除购物车中的所有产品。
     * 这里仅仅更改cart表的cart_id， 而不会做其他任何事情。
     */
    public function updateCartId($new_cart_id, $cart_id)
    {
        if ($cart_id && $new_cart_id) {
            $this->_itemModel->updateAll(
                ['cart_id' => $new_cart_id],  // $attributes
                ['cart_id' => $cart_id]       // $condition
            );
            // 重新计算购物车的数量
            //Yii::$service->cart->quote->computeCartInfo();
            return true;
        }

        return false;
    }
}
