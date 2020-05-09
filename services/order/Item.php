<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\order;

//use fecshop\models\mysqldb\order\Item as MyOrderItem;
use fecshop\services\Service;
use Yii;

/**
 * Cart items services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Item extends Service
{
    protected $_itemModelName = '\fecshop\models\mysqldb\order\Item';

    protected $_itemModel;
    
    public function init()
    {
        parent::init();
        list($this->_itemModelName, $this->_itemModel) = \Yii::mapGet($this->_itemModelName);
    }
    
    /**
     * 得到order 表的id字段。
     */
    public function getPrimaryKey()
    {
        return 'item_id';
    }

    /**
     * @param $primaryKey | Int
     * @return Object($this->_itemModel)
     * 通过主键值，返回Order Model对象
     */
    public function getByPrimaryKey($primaryKey)
    {
        $one = $this->_itemModel->findOne($primaryKey);
        $primaryKey = $this->getPrimaryKey();
        if ($one[$primaryKey]) {
            
            return $one;
        } else {
            
            return new $this->_orderModelName();
        }
    }
    
    /**
     * @param $product_id | string , 产品的id
     * @param  $customer_id | int， 用户的id
     * @param $month | int, 几个月内的订单
     * 通过product_id和customerId，得到$month个月内下单支付成功的产品
     */
    public function getByProductIdAndCustomerId($product_id, $month, $customer_id = 0)
    {
        if (!$customer_id) {
            if (Yii::$app->user->isGuest) {
                
                return false;
            } else {
                $customer_id = Yii::$app->user->identity->id;
            }
        }
        // 得到产品
        $time_gt = strtotime("-0 year -$month month -0 day");
        $orderStatusArr = Yii::$service->order->getOrderPaymentedStatusArr();
        $orders = Yii::$service->order->coll([
            'select' => ['order_id'],
            'where' => [
                ['customer_id' => $customer_id],
                ['>', 'created_at', $time_gt],
                ['in', 'order_status',  $orderStatusArr]
            ],
            'asArray' => true,
            'numPerPage' 	=> 500,
        ]);
        $order_ids = [];
        if (isset($orders['coll']) && !empty($orders['coll'])) {
            foreach ($orders['coll'] as $order) {
                $order_ids[] = $order['order_id'];
            }
        }
        if (empty($order_ids)) {
            
            return false;
        }
        $items = $this->_itemModel->find()->asArray()->where([
            'product_id' => $product_id,
        ])->andWhere([
            'in', 'order_id', $order_ids
        ])
            ->all();
        if (!empty($items)) {
            
            return $items;
        } else {
            
            return false;
        }
    }

    /**
     * @param $order_id | Int
     * @param $onlyFromTable | 从数据库取出不做处理
     * @return array
     *               通过order_id 得到所有的items
     */
    public function getByOrderId($order_id, $onlyFromTable = false)
    {
        $items = $this->_itemModel->find()->asArray()->where([
            'order_id' => $order_id,
        ])->all();
        if ($onlyFromTable) {
            
            return $items;
        }
        foreach ($items as $k=>$one) {
            $product_id = $one['product_id'];
            $product_one = Yii::$service->product->getByPrimaryKey($product_id);
            $productSpuOptions = $this->getProductSpuOptions($product_one);
            $items[$k]['spu_options'] = $productSpuOptions;
            $items[$k]['custom_option'] = $product_one['custom_option'];
            $items[$k]['custom_option_info'] = $this->getProductOptions($items[$k]);
            $items[$k]['image'] = $this->getProductImage($product_one, $one);
        }

        return $items;
    }
    
    /**
     * @param $order_ids | array
     * @param $onlyFromTable | 从数据库取出不做处理
     * @return array
     *               通过order_id 得到所有的items
     */
    public function getByOrderIds($order_ids, $onlyFromTable = false)
    {
        $items = $this->_itemModel->find()->asArray()->where([
            'in', 'order_id', $order_ids,
        ])->all();
        if ($onlyFromTable) {
            
            return $items;
        }
        foreach ($items as $k=>$one) {
            $product_id = $one['product_id'];
            $product_one = Yii::$service->product->getByPrimaryKey($product_id);

            $productSpuOptions = $this->getProductSpuOptions($product_one);
            //var_dump($productSpuOptions);
            $items[$k]['spu_options'] = $productSpuOptions;
            $items[$k]['custom_option'] = $product_one['custom_option'];
            $items[$k]['custom_option_info'] = $this->getProductOptions($items[$k]);
            $items[$k]['image'] = $this->getProductImage($product_one, $one);
        }

        return $items;
    }

    /**
     * @param $product_one | Object, product model
     * @param $item_one | Array , order item ，是订单产品表取出来的数据
     * 得到产品的图片。如果存在custom option image，则返回custom option image，如果不存在，则返回产品的主图
     */
    public function getProductImage($product_one, $item_one)
    {
        $custom_option = $product_one['custom_option'];
        $custom_option_sku = $item_one['custom_option_sku'];
        $image = '';
        // 设置图片
        if (isset($product_one['image']['main']['image'])) {
            $image = $product_one['image']['main']['image'];
        }
        $custom_option_image = isset($custom_option[$custom_option_sku]['image']) ? $custom_option[$custom_option_sku]['image'] : '';
        if ($custom_option_image) {
            $image = $custom_option_image;
        }
        if (!$image) {
            $image = $item_one['image'];
        }

        return $image;
    }

    /**
     * @param $item_one | Array , order item
     * 通过$item_one 的$item_one['custom_option_sku']，$item_one['custom_option'] , $item_one['spu_options']
     * 将spu的选择属性和自定义属性custom_option 组合起来，返回一个统一的数组
     */
    public function getProductOptions($item_one)
    {
        $custom_option_sku = $item_one['custom_option_sku'];
        $custom_option_info_arr = [];
        $custom_option = isset($item_one['custom_option']) ? $item_one['custom_option'] : '';
        if (isset($custom_option[$custom_option_sku]) && !empty($custom_option[$custom_option_sku])) {
            $custom_option_info = $custom_option[$custom_option_sku];
            foreach ($custom_option_info as $attr=>$val) {
                if (!in_array($attr, ['qty', 'sku', 'price', 'image'])) {
                    $attr = str_replace('_', ' ', $attr);
                    $attr = ucfirst($attr);
                    $custom_option_info_arr[$attr] = $val;
                }
            }
        }
        $spu_options = isset($item_one['spu_options']) ? $item_one['spu_options'] : '';
        if (is_array($spu_options) && !empty($spu_options)) {
            foreach ($spu_options as $label => $val) {
                $custom_option_info_arr[$label] = $val;
            }
        }

        return $custom_option_info_arr;
    }

    /**
     * @param $productOb | Object，类型：\fecshop\models\mongodb\Product
     * 得到产品的spu对应的属性以及值。
     * 概念 - spu options：当多个产品是同一个spu，但是不同的sku的时候，他们的产品表里面的
     * spu attr 的值是不同的，譬如对应鞋子，size 和 color 就是spu attr，对于同一款鞋子，他们
     * 是同一个spu，对于尺码，颜色不同的鞋子，是不同的sku，他们的spu attr 就是 color 和 size。
     */
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
     * @param $items | Array , example:
     *	$itmes = [
     *		[
     *			'item_id' => $one['item_id'],
     *			'product_id' 		=> $product_id ,
     *			'sku'				=> $product_one['sku'],
     *			'name'				=> Yii::$service->store->getStoreAttrVal($product_one['name'],'name'),
     *			'qty' 				=> $qty ,
     *			'custom_option_sku' => $custom_option_sku ,
     *			'product_price' 	=> $product_price ,
     *			'product_row_price' => $product_row_price ,
     *
     *			'base_product_price' 	=> $base_product_price ,
     *			'base_product_row_price' => $base_product_row_price ,
     *
     *			'product_name'		=> $product_one['name'],
     *			'product_weight'	=> $p_wt,
     *			'product_row_weight'=> $p_wt * $qty,
     *			'product_url'		=> $product_one['url_key'],
     *			'product_image'		=> $product_one['image'],
     *			'custom_option'		=> $product_one['custom_option'],
     *			'spu_options' 			=> $productSpuOptions,
     *		]
     *	];
     * @param $order_id | Int
     * 保存订单的item信息
     */
    public function saveOrderItems($items, $order_id, $store)
    {
        /**
         * 由于是通过session查订单的方式，而不是新建，paypal报错可能多次下单（更新方式），
         * 因此在添加订单产品的时候先进行一次删除产品操作。
         */
        $customer_id = '';
        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            $customer_id = $identity->id;
        }
        $this->_itemModel->deleteAll(['order_id' => $order_id]);
        if (is_array($items) && !empty($items) && $order_id && $store) {
            foreach ($items as $item) {
                $myOrderItem = new $this->_itemModelName();
                $myOrderItem['order_id'] = $order_id;
                $myOrderItem['store'] = $store;
                $myOrderItem['customer_id'] = $customer_id;
                $myOrderItem['created_at'] = time();
                $myOrderItem['updated_at'] = time();
                $myOrderItem['product_id'] = $item['product_id'];
                $myOrderItem['sku'] = $item['sku'];
                $myOrderItem['name'] = $item['name'];
                $myOrderItem['custom_option_sku'] = $item['custom_option_sku'];
                $myOrderItem['image'] = isset($item['product_image']['main']['image']) ? $item['product_image']['main']['image'] : '';
                $myOrderItem['weight'] = $item['product_weight'];
                $myOrderItem['qty'] = $item['qty'];
                $myOrderItem['row_weight'] = $item['product_row_weight'];
                $myOrderItem['price'] = $item['product_price'];
                $myOrderItem['base_price'] = $item['base_product_price'];
                $myOrderItem['row_total'] = $item['product_row_price'];
                $myOrderItem['base_row_total'] = $item['base_product_row_price'];
                $myOrderItem['redirect_url'] = $item['product_url'];
                if (!$myOrderItem->validate()) {
                    $errors = $myOrderItem->errors;
                    Yii::$service->helper->errors->addByModelErrors($errors);

                    return false;
                }
                $saveStatus = $myOrderItem->save();
                // 如果保存失败，直接返回。
                if (!$saveStatus) {
                    
                    return $saveStatus;
                }
            }
        }
        
        return true;
    }
    
    /**
     * @param $filter|array
     * @return Array;
     *              通过过滤条件，得到coupon的集合。
     *              example filter:
     *              [
     *                  'numPerPage' 	=> 20,
     *                  'pageNum'		=> 1,
     *                  'orderBy'	    => ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     *                  'where'			=> [
     *                      ['>','price',1],
     *                      ['<=','price',10]
     * 			            ['sku' => 'uk10001'],
     * 		            ],
     * 	                'asArray' => true,
     *              ]
     * 根据$filter 搜索参数数组，返回满足条件的订单数据。
     */
    public function coll($filter = '')
    {
        $query  = $this->_itemModel->find();
        $query  = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll   = $query->all();
        
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }
}
