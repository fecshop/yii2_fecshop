<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product;

//use fecshop\models\mongodb\Product;
//use fecshop\models\mysqldb\product\ProductFlatQty;
//use fecshop\models\mysqldb\product\ProductCustomOptionQty;
use fecshop\services\Service;
use Yii;

/**
 * Stock sub-service of product service.
 *
 * @method deduct($items = [])
 * @see \fecshop\services\product\Stock::actionDeduct()
 * @method productIsInStock($product, $qty, $custom_option_sku)
 * @see Stock::actionProductIsInStock()
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Stock extends Service
{
    // 零库存：也就是说库存忽略掉，产品的库存
    public $zeroInventory = false;

    // product model arr
    protected $_product_arr;

    // product items（譬如购物车信息）
    protected $_product_items;

    // 是否已经检查产品是否有库存。
    protected $_checkItemsStockStatus;

    //protected $CheckItemsStock
    
    protected $_flatQtyModelName = '\fecshop\models\mysqldb\product\ProductFlatQty';

    protected $_flatQtyModel;

    protected $_COQtyModelName = '\fecshop\models\mysqldb\product\ProductCustomOptionQty';

    protected $_COQtyModel;
    
    public function init()
    {
        parent::init();
        list($this->_flatQtyModelName, $this->_flatQtyModel) = \Yii::mapGet($this->_flatQtyModelName);
        list($this->_COQtyModelName, $this->_COQtyModel) = \Yii::mapGet($this->_COQtyModelName);
        $zeroInventory = Yii::$app->store->get('product','zeroInventory');
        if ($zeroInventory == Yii::$app->store->enable) {
            $this->zeroInventory = true;
        }
    }

    /**
     * @param $productIds | Array ,  字符串数组
     * @return  Array ，example
     * [
     *      'product_id'  => 'qty',
     * ]
     * 得到产品的主库存，对于Custom Option里面的库存，该函数无法获取。
     */
    public function getQtyByProductIds($productIds)
    {
        if (!is_array($productIds)) {
            Yii::$service->helper->errors->add('ProductIds must be Array');
            return false;
        }
        $data = $this->_flatQtyModel->find()->asArray()->where([
            'in','product_id',$productIds
        ])->all();
        $arr = [];
        foreach ($data as $one) {
            $arr[$one['product_id']] = $one['qty'];
        }
        return $arr;
    }
    
    /**
     *  @param $product_id | String , mongodb中的产品id字符串
     *  @param $one | Array ， data example：
     *  $one = [
     *      'qty'           => 44,  # sku的库存个数
     *      'custom_option' => [
     *          'red-s-s2-s3' => [
     *               'qty' => 44,
     *           ] # 淘宝模式的sku的库存格式，red-s-s2-s3代表自定义属性sku
     *      ]
     *  ]
     *  由于存在淘宝和京东模式，因此库存有两种，对于单个产品，只有一种是有效的，
     *  对于产品的淘宝和京东模式，可以参看文档：http://www.fecshop.com/doc/fecshop-guide/instructions/cn-1.0/guide-fecshop_product.html#
     */
    public function saveProductStock($product_id, $one)
    {
        if (!$product_id) {
            Yii::$service->helper->errors->add('save product qty error: product is empty');
        }
        if (!isset($one['qty'])) {
            Yii::$service->helper->errors->add('save product qty error: product qty is empty');
        }
        
        // 保存自定义部分的qty
        if (is_array($one['custom_option']) && !empty($one['custom_option'])) {
            $custom_option_qty = 0;
            //得到所有的产品custom option qty数据
            $co_sku_arr = $this->getProductCustomOptionSkuArr($product_id);
            $product_sku_arr = [];
            foreach ($one['custom_option'] as $custom_option_sku => $c_one) {
                $productCustomOptionQty = $this->_COQtyModel->find()
                    ->where([
                        'product_id' => $product_id,
                        'custom_option_sku' => $custom_option_sku,
                    ])
                    ->one();
                $product_sku_arr[] = $custom_option_sku;
                if (!$productCustomOptionQty['product_id']) {
                    $productCustomOptionQty = new $this->_COQtyModelName;
                    $productCustomOptionQty->product_id = $product_id;
                    $productCustomOptionQty->custom_option_sku = $custom_option_sku;
                }
                $productCustomOptionQty->qty = $c_one['qty'];
                $custom_option_qty += $c_one['qty'];
                $productCustomOptionQty->save();
            }
            // 如果是custom option，主库存 = 各个custom option 的库存的相加和
            $one['qty'] = $custom_option_qty;
            $delete_sku_arr = array_diff($co_sku_arr, $product_sku_arr);
            //var_dump($delete_sku_arr);
            // 删除掉产品中不存在customOptionSku对应的库存、
            if (!empty($delete_sku_arr) && is_array($delete_sku_arr)) {
                $this->_COQtyModel->deleteAll([
                    'and',
                    ['product_id' => $product_id],
                    ['in','custom_option_sku',$delete_sku_arr]
                ]);
            }
        }
        // 保存产品flat qty
        $productFlatQty = $this->_flatQtyModel->find()
            ->where(['product_id' => $product_id])
            ->one();
        if (!$productFlatQty['product_id']) {
            $productFlatQty = new $this->_flatQtyModelName;
            $productFlatQty->product_id = $product_id;
        }
        $productFlatQty->qty = $one['qty'];
        $productFlatQty->save();
        return true;
    }

    /**
     *  @param $product_id | String , mongodb中的产品id字符串
     *  产品做删除的时候，需要在mysql中删除掉库存
     */
    public function removeProductStock($product_id)
    {
        if (!$product_id) {
            Yii::$service->helper->errors->add('remove product qty error: product is empty');
        }
        // 保存产品flat qty
        $this->_flatQtyModel->deleteAll(['product_id' => $product_id]);
        $this->_COQtyModel->deleteAll(['product_id' => $product_id]);
        return true;
    }
    
    /**
     * @var array $items
     * example:
     * 	[
     *		[
     *			'product_id' => 'xxxxx',
     *			'qty' => 2,
     *          'name' => 'xxxx',
     *			'custom_option_sku' => 'cos_1',  # 存在该项，则应该到产品的
     *		],
     *		[
     *			'product_id' => 'yyyyy',
     *          'name' => 'xxxx',
     *			'qty' => 1,
     *		],
     *	]
     *  @return bool
     *  扣除产品库存。如果扣除成功，则返回true，如果返回失败，则返回false
     *
     *  **注意**：在调用该函数的时候必须使用事务，在返回false的时候要回滚。
     *  **注意**：在调用该函数的时候必须使用事务，在返回false的时候要回滚。
     *  **注意**：在调用该函数的时候必须使用事务，在返回false的时候要回滚。
     */
    protected function actionDeduct($items = [])
    {
        if (!$items) {
            // 如果 $items 为空，则去购物车取数据。
            $cartInfo = Yii::$service->cart->getCartInfo(true);
            $items = isset($cartInfo['products']) ? $cartInfo['products'] : [];
        }
        // 如果items为空，则返回
        if (!is_array($items) || empty($items)) {
            Yii::$service->helper->errors->add('cart products is empty');
            return false;
        }    
        // 查看产品的状态，上下架状态，以及产品库存检查是否够用。
        foreach ($items as $k=>$item) {
            $product_id        = $item['product_id'];
            $sale_qty           = (int)$item['qty'];
            $custom_option_sku  = $item['custom_option_sku'];
            $product = Yii::$service->product->getByPrimaryKey($product_id);
            // 购物车中的产品已经被删除，则会查询不到
            if (!$product['sku']) {
                Yii::$service->helper->errors->add('product: [ {product_id} ] is not exist', ['product_id' => $product_id]);
                return false;
            } 
            $status = $this->productIsInStock($product, $sale_qty, $custom_option_sku);
            if (!$status) {
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
        
        // 开始扣除库存。
        foreach ($items as $k=>$item) {
            $product_id         = $item['product_id'];
            $sale_qty           = (int)$item['qty'];
            $product_name       = Yii::$service->store->getStoreAttrVal($item['product_name'], 'name');
            $custom_option_sku  = $item['custom_option_sku'];
            if ($product_id && $sale_qty) {
                // 应对高并发库存超卖的控制，扣除库存的时候，加上qty个数的查询，不满足查询条件则不扣除库存
                $updateColumns = $this->_flatQtyModel->updateAllCounters(
                    ['qty' => 0 - $sale_qty],
                    ['and', ['product_id' => $product_id], ['>=', 'qty', $sale_qty]]
                );
                if (empty($updateColumns)) {// 上面更新sql返回的更新行数如果为0，则说明更新失败，产品不存在，或者产品库存不够
                    Yii::$service->helper->errors->add('product: [ {product_name} ] is stock out', ['product_name' => $product_name]);
                    return false;
                }
                // 对于custom option（淘宝模式）的库存扣除
                if ($custom_option_sku) {
                    $updateColumns = $this->_COQtyModel->updateAllCounters(
                        ['qty' => 0 - $sale_qty],
                        [
                            'and',
                            [
                                'custom_option_sku' => $custom_option_sku,
                                'product_id'        => $product_id
                            ],
                            ['>=','qty',$sale_qty]
                        ]
                    );
                    if (empty($updateColumns)) {// 上面更新sql返回的更新行数如果为0，则说明更新失败，产品不存在，或者产品库存不够
                        Yii::$service->helper->errors->add('product: [ {product_name} ] is stock out', ['product_name' => $product_name]);
                        return false;
                    }
                }
            }
        }
        
        return true;
    }
    
    
    /**
     * @var array $items
     * example:
     * [
     *		[
     *			'sku' => '22221',
     *			'qty' => 2,
     *			'custom_option_sku' => 'black-s',  # 存在该项，则应该到产品的
     *		],
     *		[
     *			'sku' => 'op0002',
     *			'qty' => -1,
     *		],
     *	]
     *  @return bool
     *  通过累加和累减的方式更新产品库存，通过累加和累减的方式如果扣除成功，则返回true，如果返回失败，则返回false
     *  如果库存为正数，则累加，如果库存为负数，则累减。
     *  一般是api的方式更新库存。  
     *
     *  **注意**：在调用该函数的时候必须使用事务，在返回false的时候要回滚。
     *  **注意**：在调用该函数的时候必须使用事务，在返回false的时候要回滚。
     *  **注意**：在调用该函数的时候必须使用事务，在返回false的时候要回滚。
     */
    protected function actionUpdatebybase($items = [])
    {
        if (empty($items) || !is_array($items)) {
            Yii::$service->helper->errors->add('param items is not array or is empty');
            
            return false;
        }
        
        
        // 查看产品的状态，上下架状态，以及产品库存检查是否够用。
        foreach ($items as $k=>$item) {
            $sku        = $item['sku'];
            $sale_qty           = (int)$item['qty'];
            $custom_option_sku  = $item['custom_option_sku'];
            $product = Yii::$service->product->getBySku($sku);
            // 购物车中的产品已经被删除，则会查询不到
            if (!$product['sku']) {
                Yii::$service->helper->errors->add('product: [ {sku} ] is not exist', ['sku' => $sku]);
                
                return false;
            } 
            $status = $this->productIsInStock($product, $sale_qty, $custom_option_sku, false);
            if (!$status) {
                
                return false;
            }
            $primaryKey = Yii::$service->product->getPrimaryKey();
            $items[$k]['product_id'] = (string)$product[$primaryKey];
            
            
        } 
        
        /**
         * $this->checkItemsStock 函数检查产品是否都是上架状态
         * 如果满足上架状态 && 零库存为1，则直接返回。
         */
        if ($this->zeroInventory) {
            
            return true; // 零库存模式 不会更新产品库存。
        }
        
        // 开始扣除库存。
        foreach ($items as $k=>$item) {
            $sku         = $item['sku'];
            $product_id         = $item['product_id'];
            $sale_qty           = (int)$item['qty'];
            $custom_option_sku  = $item['custom_option_sku'];
            if ($product_id && $sale_qty) {
                // 应对高并发库存超卖的控制，扣除库存的时候，加上qty个数的查询，不满足查询条件则不扣除库存
                
                $updateColumns = $this->_flatQtyModel->updateAllCounters(
                    ['qty' => $sale_qty],
                    ['and', ['product_id' => $product_id], ['>=', 'qty', 0 - $sale_qty]]
                );
                
                if (empty($updateColumns)) {// 上面更新sql返回的更新行数如果为0，则说明更新失败，产品不存在，或者产品库存不够
                    Yii::$service->helper->errors->add('product: [ {sku} ] is stock out', ['sku' => $sku]);
                    
                    return false;
                }
                // 对于custom option（淘宝模式）的库存扣除
                if ($custom_option_sku) {
                    $updateColumns = $this->_COQtyModel->updateAllCounters(
                        ['qty' => $sale_qty],
                        [
                            'and',
                            [
                                'custom_option_sku' => $custom_option_sku,
                                'product_id'        => $product_id
                            ],
                            ['>=', 'qty', 0 - $sale_qty]
                        ]
                    );
                    if (empty($updateColumns)) {// 上面更新sql返回的更新行数如果为0，则说明更新失败，产品不存在，或者产品库存不够
                        Yii::$service->helper->errors->add('product: [ {sku} ] is stock out', ['sku' => $sku]);
                        
                        return false;
                    }
                }
            }
        }
        
        return true;
    }
    
    /**
     * @param $product_items | Array ， example:
     * 	[
     *		[
     *			'product_id' => 'xxxxx',
     *			'qty' => 2,
     *			'custom_option_sku' => 'cos_1',  # 存在该项，则应该到产品的
     *		],
     *		[
     *			'product_id' => 'yyyyy',
     *			'qty' => 1,
     *		],
     *	]
     * @return bool
     *              返还产品库存。如果在返还过程中产品不存在，也不会返回false
     */
    protected function actionReturnQty($product_items)
    {
        if ($this->zeroInventory) {
            return true; // 零库存模式不扣产品库存，也不需要返还库存。
        }
        // 开始扣除库存。
        if (is_array($product_items) && !empty($product_items)) {
            foreach ($product_items as $k=>$item) {
                $product_id         = $item['product_id'];
                $sale_qty           = $item['qty'];
                $custom_option_sku  = $item['custom_option_sku'];
                if ($product_id && $sale_qty) {
                    $updateColumns = $this->_flatQtyModel->updateAllCounters(['qty' => $sale_qty], ['product_id' => $product_id]);
                    if ($custom_option_sku) {
                        // 对于custom option（淘宝模式）的库存扣除
                        $updateColumns = $this->_COQtyModel->updateAllCounters(['qty' => $sale_qty], ['product_id' => $product_id,'custom_option_sku' => $custom_option_sku]);
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param $product | Object,  Product Model
     * @param $sale_qty | Int 需要出售的个数
     * @param $custom_option_sku | String 产品custom option sku
     * @param $checkDbQty | boolean, 是否检查商品库存？如果为false，则不对库存进行检查。
     * @return bool
     *  查看产品库存
     */
    protected function actionProductIsInStock($product, $sale_qty, $custom_option_sku, $checkDbQty = true)
    {
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        $is_in_stock = $product['is_in_stock'];
        $product_id = $product[$productPrimaryKey];
        $product_name       = Yii::$service->store->getStoreAttrVal($product['name'], 'name');
        // 查看产品状态（产品如果disabled，则意味产品被清除）
        if ($product['status'] != Yii::$service->product->getEnableStatus()) {
            Yii::$service->helper->errors->add('product: [ {product_name} ] status is not active', ['product_name' => $product_name]);
            
            return false;
        }
        // 零库存模式 && 产品是上架状态 直接返回true
        if ($this->zeroInventory && $this->checkOnShelfStatus($is_in_stock)) {
            
            return true; // 零库存模式不扣产品库存，也不需要返还库存。
        }
        // 查看产品的上下架状态
        if (!$this->checkOnShelfStatus($is_in_stock)) {
            Yii::$service->helper->errors->add(
                'Product Id: {product_id}, The product has off the shelf',
                ['product_id' => $product[$productPrimaryKey]]
            );
            
            return false;
        }
        // 如果不检查数据库里面的库存，那么直接返回true
        if (!$checkDbQty) {
            
            return true;
        }
        if ($custom_option_sku) {
            $productCustomOptionQty = $this->_COQtyModel->find()->where([
                'product_id'        => $product_id,
                'custom_option_sku' => $custom_option_sku
            ])->one();
            if (!$productCustomOptionQty['qty']) {
                Yii::$service->helper->errors->add('product: [ {product_name} ] is stock out', ['product_name' => $product_name]);
                
                return false;
            }
            if ($productCustomOptionQty['qty'] < $sale_qty) {
                Yii::$service->helper->errors->add('product: [ {product_name} ] is stock out', ['product_name' => $product_name]);
                
                return false;
            }
        } else {
            $productFlatQty = $this->_flatQtyModel->find()->where([
                'product_id' => $product_id
            ])->one();
            
            if (!$productFlatQty['qty']) {
                $productName = Yii::$service->store->getStoreAttrVal($product['name'], 'name');
                Yii::$service->helper->errors->add(
                    'Product Name: {product_name} , The product is not in stock',
                    ['product_name' => $productName]
                );
                
                return false;
            }    
            if ($productFlatQty['qty'] < $sale_qty) {
                Yii::$service->helper->errors->add(
                        'Product Id: {product_id}, Product inventory is less than [{sale_qty}]',
                        ['product_id' => $product[$productPrimaryKey], 'sale_qty' => $sale_qty]
                    );
                return false;
            }
        }
 
        return true;
    }
    
    
    /**
     * @param $items | Array ， example:
     * 	[
     *		[
     *			'product_id' => 'xxxxx',
     *			'qty' => 2,
     *			'custom_option_sku' => 'cos_1',  # 存在该项，则应该到产品的
     *		],
     *		[
     *			'product_id' => 'yyyyy',
     *			'qty' => 1,
     *		],
     *	]
     *  @return Mix 有库存返回的数据格式如下：
     *    [
     *        'stockStatus'           => 1,
     *        'outStockProducts'    => '',
     *    ];
     *    无库存返回的数据格式，2代表库存返回失败
     *    [
     *        'stockStatus'           => 2,
     *         库存不足的产品数组。
     *        'outStockProducts'    => [
     *            [
     *                'product_id'        => $product_id,
     *                'custom_option_sku' => '',
     *                'stock_qty'         => 0,
     *           ],
     *            [
     *                'product_id'        => $product_id,
     *                'custom_option_sku' => $custom_option_sku,
     *                'stock_qty'         => $productCustomOptionM['qty'],
     *            ],
     *
     *        ],
     *    ];
     *    购物车没有产品返回null
     *
     */
    protected function actionCheckItemsQty()
    {
        $cartInfo = Yii::$service->cart->getCartInfo(true);
        $items = isset($cartInfo['products']) ? $cartInfo['products'] : '';
        $outStockProducts = [];
        // 开始扣除库存。
        if (is_array($items) && !empty($items)) {
            foreach ($items as $k=>$item) {
                $product_id               = $item['product_id'];
                $item_product_name  = $item['product_name'];
                $sale_qty                  = (int)$item['qty'];
                $custom_option_sku   = $item['custom_option_sku'];
                $product = Yii::$service->product->getByPrimaryKey($product_id);
                // 产品是否存在判断
                if (!$product['sku']) {
                    $outStockProducts[] = [
                        'product_id'        => $product_id,
                        'product_name'      => $item_product_name,
                        'custom_option_sku' => $custom_option_sku,
                        'stock_qty'         => 0,
                    ];
                }
                $product_name       = Yii::$service->store->getStoreAttrVal($product['name'], 'name');
                $is_in_stock            = $product['is_in_stock'];
                // 查看产品状态（产品如果disabled，则意味产品被清除）
                if ($product['status'] != Yii::$service->product->getEnableStatus()) {
                    $outStockProducts[] = [
                        'product_id'        => $product_id,
                        'product_name'      => $product_name,
                        'custom_option_sku' => $custom_option_sku,
                        'stock_qty'         => 0,
                    ];
                }
                // 零库存模式 && 产品是上架状态 不需要继续判断库存
                if ($this->zeroInventory && $this->checkOnShelfStatus($is_in_stock)) {
                    continue;
                }
                // 产品的个数判断
                if ($product_id && $sale_qty) {
                    if (!$custom_option_sku) {
                        $productM = $this->_flatQtyModel->find()->where([
                            'product_id' => $product_id
                        ])->one();

                        if ($productM['qty']) {
                            //echo $productM['qty'].'####'.$sale_qty.'<br>';
                            if ($productM['qty'] < $sale_qty) {
                                $outStockProducts[] = [
                                    'product_id'        => $product_id,
                                    'custom_option_sku' => '',
                                    'product_name'      => $product_name,
                                    'stock_qty'         => $productM['qty'],
                                ];
                            }
                        } else {
                            $outStockProducts[] = [
                                'product_id'        => $product_id,
                                'custom_option_sku' => '',
                                'product_name'      => $product_name,
                                'stock_qty'         => 0,
                            ];
                        }
                    } else {
                        $productCustomOptionM = $this->_COQtyModel->find()->where([
                            'product_id'        => $product_id,
                            'custom_option_sku' => $custom_option_sku,
                        ])->one();
                        
                        if ($productCustomOptionM['qty']) {
                            if ($productCustomOptionM['qty'] < $sale_qty) {
                                $outStockProducts[] = [
                                    'product_id'        => $product_id,
                                    'custom_option_sku' => $custom_option_sku,
                                    'product_name'      => $product_name,
                                    'stock_qty'         => $productCustomOptionM['qty'],
                                ];
                            }
                        } else {
                            $outStockProducts[] = [
                                'product_id'        => $product_id,
                                'product_name'      => $product_name,
                                'custom_option_sku' => $custom_option_sku,
                                'stock_qty'         => 0,
                            ];
                        }
                    }
                }
            }
            if (empty($outStockProducts)) {
                return [
                    'stockStatus'           => 1,
                    'outStockProducts'    => '',
                ];
            } else {
                return [
                    'stockStatus'           => 2,
                    'outStockProducts'    => $outStockProducts,
                ];
            }
        } else {
            Yii::$service->helper->errors->add('cart products is empty');
            return null;
        }
    }

    

    /**
     * @param $is_in_stock | Int,  状态
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
    
    /**
     * @param $product_id | String
     * 得到产品的库存个数（Flat Qty）
     */
    public function getProductFlatQty($product_id)
    {
        $productFlatQty = $this->_flatQtyModel->find()->asArray()
            ->where([
                'product_id' => $product_id
            ])->one();
        if (isset($productFlatQty['qty'])) {
            return $productFlatQty['qty'] ? $productFlatQty['qty'] : 0;
        } else {
            return 0;
        }
    }
    
    /**
     * @param $product_id | String
     * @param $onlySku | boolean  返回数组是否只有 $custom_option_sku
     * 得到产品的custom option 部分的库存
     */
    public function getProductCustomOptionQty($product_id, $onlySku=false)
    {
        $arr = $this->_COQtyModel->find()->asArray()
            ->where([
                'product_id' => $product_id
            ])->all();
        $r_arr = [];
        if (is_array($arr)) {
            foreach ($arr as $one) {
                $custom_option_sku = $one['custom_option_sku'];
                $qty = $one['qty'];
                $r_arr[$custom_option_sku] = $qty;
            }
        }
        
        return $r_arr;
    }

    /**
     * @param $product_id | String
     * 得到产品的所有custom_option_sku 数组
     */
    public function getProductCustomOptionSkuArr($product_id)
    {
        $arr = $this->_COQtyModel->find()->asArray()
            ->where([
                'product_id' => $product_id
            ])->all();
        $sku_arr = [];
        if (is_array($arr)) {
            foreach ($arr as $one) {
                $sku_arr[] = $one['custom_option_sku'];
            }
        }
        return $sku_arr;
    }
    
    /**
     * @param $product_id | String
     * 得到产品的custom option 部分，相应的$custom_option_sku的库存
     */
    public function getProductCustomOptionSkuQty($product_id, $custom_option_sku)
    {
        $productCustomOptionQty = $this->_COQtyModel->find()->asArray()
            ->where([
                'product_id' => $product_id,
                'custom_option_sku' => $custom_option_sku
            ])->one();
        if (isset($productCustomOptionQty['qty'])) {
            return $productCustomOptionQty['qty'];
        } else {
            return 0;
        }
    }
}
