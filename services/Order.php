<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

//use fecshop\models\mysqldb\Order as MyOrder;
use Yii;

/**
 * Order services. 
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Order extends Service
{
    public $requiredAddressAttr; // 必填的订单字段。
    // 下面是订单支付状态
    public $payment_status_pending          = 'pending';
    public $payment_status_processing       = 'processing';
    public $payment_status_canceled         = 'canceled';
    public $payment_status_complete         = 'complete';
    public $payment_status_holded           = 'holded';
    public $payment_status_suspected_fraud  = 'suspected_fraud';
    // 订单号格式。
    public $increment_id = 1000000000;
    // 将xx分钟内未支付的pending订单取消掉，并释放产品库存的设置
    public $minuteBeforeThatReturnPendingStock  = 60;
    // 每次处理未支付的pending订单的个数限制。
    public $orderCountThatReturnPendingStock    = 30;
    // 支付类型，目前只有standard 和 express 两种，express 指的是在购物车点击支付按钮的方式，譬如paypal的express
    // standard类型指的是填写完货运地址后生成订单跳转到第三方支付平台的支付类型。
    protected $checkout_type;
    // 当前的订单信息保存到这个变量中，订单信息是从数据库中取出来订单和产品信息，然后进行了一定的数据处理后，再保存到该变量的。
    protected $_currentOrderInfo;
    // 支付类型常量
    const CHECKOUT_TYPE_STANDARD    = 'standard';
    const CHECKOUT_TYPE_EXPRESS     = 'express';
    const CHECKOUT_TYPE_ADMIN_CREATE= 'admin_create';
    // 作为保存incrementId到session的key，把当前的order incrementId保存到session的时候，对应的key就是该常量。
    const CURRENT_ORDER_INCREAMENT_ID = 'current_order_increament_id';
    
    protected $_orderModelName = '\fecshop\models\mysqldb\Order';
    protected $_orderModel;
    
    public function __construct(){
        list($this->_orderModelName,$this->_orderModel) = \Yii::mapGet($this->_orderModelName);  
    }
    
    /**
     * @return array 
     * 将订单所有的支付类型，组合成一个数组，进行返回。
     */
    protected function actionGetCheckoutTypeArr(){
        return [
            self::CHECKOUT_TYPE_ADMIN_CREATE => self::CHECKOUT_TYPE_ADMIN_CREATE,
            self::CHECKOUT_TYPE_STANDARD     => self::CHECKOUT_TYPE_STANDARD,
            self::CHECKOUT_TYPE_EXPRESS      => self::CHECKOUT_TYPE_EXPRESS,
        ];
    }
    /**
     * @return array 
     * 将订单所有的状态，组合成一个数组，进行返回。
     */
    protected function actionGetStatusArr(){
        return [
            $this->payment_status_pending         => $this->payment_status_pending,
            $this->payment_status_processing      => $this->payment_status_processing,
            $this->payment_status_canceled        => $this->payment_status_canceled,
            $this->payment_status_complete        => $this->payment_status_complete,
            $this->payment_status_holded          => $this->payment_status_holded,
            $this->payment_status_suspected_fraud => $this->payment_status_suspected_fraud,
            
        ];
        
    }
    
    /**
     * @property $checkout_type | String  ，支付类型
     * 设置支付类型，其他计算以此设置作为基础，进而获取其他的配置。
     */
    protected function actionSetCheckoutType($checkout_type)
    {
        $arr = [self::CHECKOUT_TYPE_STANDARD, self::CHECKOUT_TYPE_EXPRESS];
        if (in_array($checkout_type, $arr)) {
            $this->checkout_type = $checkout_type;

            return true;
        }

        return false;
    }
    /**
     * 得到支付类型
     */
    protected function actionGetCheckoutType()
    {
        return $this->checkout_type;
    }

    /**
     * @property $billing | Array
     * @return bool
     *              通过$this->requiredAddressAttr，检查地址的必填。
     */
    protected function actionCheckRequiredAddressAttr($billing)
    {
        //$this->requiredAddressAttr;
        if (is_array($this->requiredAddressAttr) && !empty($this->requiredAddressAttr)) {
            foreach ($this->requiredAddressAttr as $attr) {
                if (!isset($billing[$attr]) || empty($billing[$attr])) {
                    Yii::$service->helper->errors->add($attr.' can not empty');

                    return false;
                }
            }
        }

        return true;
    }
    /**
     * 得到order 表的id字段。
     */
    protected function actionGetPrimaryKey()
    {
        return 'order_id';
    }

    /**
     * @property $primaryKey | Int
     * @return Object($this->_orderModel)
     * 通过主键值，返回Order Model对象
     */
    protected function actionGetByPrimaryKey($primaryKey)
    {
        $one = $this->_orderModel->findOne($primaryKey);
        $primaryKey = $this->getPrimaryKey();
        if ($one[$primaryKey]) {
            return $one;
        } else {
            return new $this->_orderModelName();
        }
    }
    
    /**
     * @property $increment_id | String , 订单号
     * @return object （$this->_orderModel），返回 $this->_orderModel model
     * 通过订单号incrementId，得到订单Model对象。
     */
    protected function actionGetByIncrementId($increment_id)
    {
        $one = $this->_orderModel->findOne(['increment_id' => $increment_id]);
        $primaryKey = $this->getPrimaryKey();
        if ($one[$primaryKey]) {
            return $one;
        } else {
            return false;
        }
    }

    /**
     * @property $reflush | boolean 是否从数据库中重新获取，如果是，则不会使用类变量中计算的值
     * 获取当前的订单信息，原理为：
     * 通过从session中取出来订单的increment_id,
     * 在通过increment_id(订单编号)取出来订单信息。
     */
    protected function actionGetCurrentOrderInfo($reflush = false)
    {
        if (!$this->_currentOrderInfo || $reflush) {
            $increment_id = Yii::$service->order->getSessionIncrementId();
            $this->_currentOrderInfo = Yii::$service->order->getOrderInfoByIncrementId($increment_id);
        }

        return $this->_currentOrderInfo;
    }

    /**
     * @property $increment_id | String 订单编号
     * @return array
     *               通过increment_id 从数据库中取出来订单数据，
     *               然后进行一系列的处理，返回订单数组数据。
     */
    protected function actionGetOrderInfoByIncrementId($increment_id)
    {
        $one = $this->getByIncrementId($increment_id);
        if (!$one) {
            return;
        }

        $primaryKey = $this->getPrimaryKey();
        if (!isset($one[$primaryKey]) || empty($one[$primaryKey])) {
            return;
        }
        $order_info = [];
        foreach ($one as $k=>$v) {
            $order_info[$k] = $v;
        }
        $order_info['customer_address_state_name']      = Yii::$service->helper->country->getStateByContryCode($order_info['customer_address_country'], $order_info['customer_address_state']);
        $order_info['customer_address_country_name']    = Yii::$service->helper->country->getCountryNameByKey($order_info['customer_address_country']);
        $order_info['currency_symbol']                  = Yii::$service->page->currency->getSymbol($order_info['order_currency_code']);
        $order_info['products']                         = Yii::$service->order->item->getByOrderId($one[$primaryKey]);

        return $order_info;
    }

    /**
     * @property $order_id | Int
     * @return array
     *               通过order_id 从数据库中取出来订单数据，
     *               然后进行一系列的处理，返回订单数组数据。
     */
    protected function actionGetOrderInfoById($order_id)
    {
        if (!$order_id) {
            return;
        }
        $one = $this->_orderModel->findOne($order_id);
        $primaryKey = $this->getPrimaryKey();
        if (!isset($one[$primaryKey]) || empty($one[$primaryKey])) {
            return;
        }
        $order_info = [];
        foreach ($one as $k=>$v) {
            $order_info[$k] = $v;
        }
        $order_info['customer_address_state_name']  = Yii::$service->helper->country->getStateByContryCode($order_info['customer_address_country'], $order_info['customer_address_state']);
        $order_info['customer_address_country_name']= Yii::$service->helper->country->getCountryNameByKey($order_info['customer_address_country']);
        $order_info['currency_symbol']              = Yii::$service->page->currency->getSymbol($order_info['order_currency_code']);
        $order_info['products']                     = Yii::$service->order->item->getByOrderId($order_id);

        return $order_info;
    }

    /**
     * @property $filter|array
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
    protected function actionColl($filter = '')
    {
        $query  = $this->_orderModel->find();
        $query  = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll   = $query->all();
        
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * @property $one|array , save one data .
     * @return int 保存order成功后，返回保存的id。
     */
    protected function actionSave($one)
    {
        $time = time();
        $primaryKey = $this->getPrimaryKey();
        $primaryVal = isset($one[$primaryKey]) ? $one[$primaryKey] : '';
        if ($primaryVal) {
            $model = $this->_orderModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('order '.$this->getPrimaryKey().' is not exist');

                return;
            }
        } else {
            $model = new $this->_orderModelName();
            $model->created_at = time();
        }
        $model->updated_at = time();
        $model = Yii::$service->helper->ar->save($model, $one);
        $primaryVal = $model[$this->getPrimaryKey()];
        
        return $primaryVal;
    }

    /**
     * @property $ids | Int or Array
     * @return bool
     *              如果传入的是id数组，则删除多个
     *              如果传入的是Int，则删除一个
     */
    protected function actionRemove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_orderModel->findOne($id);
                if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                    $model->delete();
                } else {
                    Yii::$service->helper->errors->add("Order Remove Errors:ID $id is not exist.");

                    return false;
                }
            }
        } else {
            $id = $ids;
            $model = $this->_orderModel->findOne($id);
            if (isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()])) {
                $model->delete();
            } else {
                Yii::$service->helper->errors->add("Coupon Remove Errors:ID:$id is not exist.");

                return false;
            }
        }

        return true;
    }
    /**
     * @property $increment_id | String , 订单号
     * @return object （$this->_orderModel），返回 $this->_orderModel model
     *                通过订单号，得到订单以及订单产品信息。
     */
    protected function actionGetInfoByIncrementId($increment_id)
    {
        $order      = $this->getByIncrementId($increment_id);
        $orderInfo  = [];
        if ($order) {
            $primaryKey = $this->getPrimaryKey();
            $order_id   = $order[$primaryKey];
            $items      = Yii::$service->order->item->getByOrderId($order_id);
            foreach ($order as $k=>$v) {
                $orderInfo[$k] = $v;
            }
            $orderInfo['items'] = $items;

            return $orderInfo;
        } else {
            return;
        }
    }
    /**
     * @property $token | String  , paypal 支付获取的token，订单生成后只有三个字段
     *       order_id, increment_id , payment_token ，目的就是将token对应到一个increment_id
     *       在paypal 点击continue的时候，可以通过token找到对应的订单。
     */
    protected function actionGeneratePPExpressOrder($token){
        $myOrder = new $this->_orderModelName();
        $myOrder->payment_token = $token;
        $myOrder->save();
        $order_id = $myOrder['order_id'];
        if($order_id){
            $increment_id = $this->generateIncrementIdByOrderId($order_id);
            $myOrder['increment_id'] = $increment_id;
            $myOrder->save();
            $this->setSessionIncrementId($increment_id);
            return true;
        }else{
            Yii::$service->helper->errors->add('generate order fail');
            return false;
        }
    }
    /**
     * @property $token | String  , paypal 支付获取的token，
     *   通过token 得到订单 Object
     */
    protected function actionGetByPaymentToken($token){
        $one = $this->_orderModel->find()->where(['payment_token' => $token])
            ->one();
        if(isset($one['order_id']) && $one['order_id']){
            return $one;
        }else{
            return '';
        }
    }
    
    
    /**
     * @property $reflush | boolean 是否从数据库中重新获取，如果是，则不会使用类变量中计算的值
     * 通过从session中取出来订单的increment_id
     * 在通过increment_id(订单编号)取出来订单信息。
     */
    protected function actionGetInfoByPaymentToken($token)
    {
        $orderModel = $this->getByPaymentToken($token);
        $increment_id = isset($orderModel['increment_id']) ? $orderModel['increment_id'] : '';
        return Yii::$service->order->getOrderInfoByIncrementId($increment_id);
        
    }
    
    

    /**
     * @property $address | Array 货运地址
     * @property $shipping_method | String 货运快递方式
     * @property $payment_method | Array 支付方式、
     * @property $clearCartAndDeductStock | boolean 是否清空购物车，并扣除库存，这种情况是先 生成订单，在支付的情况下失败的处理方式。
     
     * @property $token | string 代表 通过payment_token得到order，然后更新order信息的方式生成order，这个是paypal购物车express支付对应的功能
     * @return bool 通过购物车的数据生成订单是否成功
     *              通过购物车中的产品信息，以及传递的货运地址，货运快递方式，支付方式生成订单。
     */
    protected function actionGenerateOrderByCart($address, $shipping_method, $payment_method, $clearCart = true , $token = '')
    {
        $cart = Yii::$service->cart->quote->getCurrentCart();
        if (!$cart) {
            Yii::$service->helper->errors->add('current cart is empty');
        }
        $currency_info  = Yii::$service->page->currency->getCurrencyInfo();
        $currency_code  = $currency_info['code'];
        $currency_rate  = $currency_info['rate'];
        $country        = $address['country'];
        $state          = $address['state'];
        //echo "$shipping_method,$country,$state";exit;
        $cartInfo       = Yii::$service->cart->getCartInfo($shipping_method, $country, $state);
        // 检查cartInfo中是否存在产品
        if (!is_array($cartInfo) && empty($cartInfo)) {
            Yii::$service->helper->errors->add('current cart product is empty');

            return false;
        }
        // 检查产品是否有库存，如果没有库存则返回false
        //$deductStatus = Yii::$service->product->stock->checkItemsStock($cartInfo['products']);
        //if (!$deductStatus) {
        //    return false;
        //}
        // 扣除库存。（订单生成后，库存产品库存。）
        //     （备注）需要另起一个脚本，用来处理半个小时后，还没有支付的订单，将订单取消，然后将订单里面的产品库存返还。
        // 			如果是无限库存（没有库存就去采购的方式），那么不需要跑这个脚本，将库存设置的非常大即可。
        $deductStatus = Yii::$service->product->stock->deduct($cartInfo['products']);
        if(!$deductStatus){
            // 库存不足则返回
            return false;
        }
        $beforeEventName = 'event_generate_order_before';
        $afterEventName  = 'event_generate_order_after';
        Yii::$service->event->trigger($beforeEventName, $cartInfo);
        if($token){
            // 有token 代表前面已经生成了order，直接通过token查询出来即可。
            $myOrder = $this->getByPaymentToken($token);
            if(!$myOrder){
                Yii::$service->helper->errors->add('order increment id is not exist.');
                return false;
            }else{
                $increment_id = $myOrder['increment_id'];
            }
        }else{
            $myOrder = new $this->_orderModelName();
        }
        $myOrder['order_status']        = $this->payment_status_pending;
        $myOrder['store']               = $cartInfo['store'];
        $myOrder['created_at']          = time();
        $myOrder['updated_at']          = time();
        $myOrder['items_count']         = $cartInfo['items_count'];
        $myOrder['total_weight']        = $cartInfo['product_weight'];
        $myOrder['order_currency_code'] = $currency_code;
        $myOrder['order_to_base_rate']  = $currency_rate;
        $myOrder['grand_total']         = $cartInfo['grand_total'];
        $myOrder['base_grand_total']    = $cartInfo['base_grand_total'];
        $myOrder['subtotal']            = $cartInfo['product_total'];
        $myOrder['base_subtotal']       = $cartInfo['base_product_total'];
        $myOrder['subtotal_with_discount'] = $cartInfo['coupon_cost'];
        $myOrder['base_subtotal_with_discount'] = $cartInfo['base_coupon_cost'];
        $myOrder['shipping_total']      = $cartInfo['shipping_cost'];
        $myOrder['base_shipping_total'] = $cartInfo['base_shipping_cost'];
        $myOrder['checkout_method']     = $this->getCheckoutType();
        if ($address['customer_id']) {
            $is_guest = 2;
        } else {
            $is_guest = 1;
        }
        if (!Yii::$app->user->isGuest) {
            $customer_id = Yii::$app->user->identity->id;
        } else {
            $customer_id = '';
        }
        $myOrder['customer_id']             = $customer_id;
        $myOrder['customer_email']          = $address['email'];
        $myOrder['customer_firstname']      = $address['first_name'];
        $myOrder['customer_lastname']       = $address['last_name'];
        $myOrder['customer_is_guest']       = $is_guest;
        $myOrder['customer_telephone']      = $address['telephone'];
        $myOrder['customer_address_country']= $address['country'];
        $myOrder['customer_address_state']  = $address['state'];
        $myOrder['customer_address_city']   = $address['city'];
        $myOrder['customer_address_zip']    = $address['zip'];
        $myOrder['customer_address_street1']= $address['street1'];
        $myOrder['customer_address_street2']= $address['street2'];
        $myOrder['coupon_code']             = $cartInfo['coupon_code'];
        $myOrder['payment_method']          = $payment_method;
        $myOrder['shipping_method']         = $shipping_method;
        $myOrder->save();
        $order_id = $myOrder['order_id'];
        if(!$increment_id){
            $increment_id = $this->generateIncrementIdByOrderId($order_id);
            $myOrder['increment_id'] = $increment_id;
            $myOrder->save();
        }
        Yii::$service->event->trigger($afterEventName, $myOrder);
        if ($myOrder[$this->getPrimaryKey()]) {
            Yii::$service->order->item->saveOrderItems($cartInfo['products'], $order_id, $cartInfo['store']);
            
            // 有token的，代表是更新类型，譬如购物车点击paypal express支付的方式
            // 这种类型要进行检查，不能多次执行。该函数必须在订单操作完成的情况下执行。
            if($token){
                if(!$this->checkOrderVersion($increment_id)){
                    return false;
                }
            }
            // 优惠券
            // 优惠券是在购物车页面添加的，添加后，优惠券的使用次数会被+1，
            // 因此在生成订单部分，是没有优惠券使用次数操作的（在购物车添加优惠券已经被执行该操作）
            // 生成订单后，购物车的数据会被清空，其中包括优惠券信息的清空。

            // 如果是登录用户，那么，在生成订单后，需要清空购物车中的产品和coupon。
            if (!Yii::$app->user->isGuest && $clearCart) {
                Yii::$service->cart->clearCartProductAndCoupon();
            }
            // 执行成功，则在session中设置increment_id
            $this->setSessionIncrementId($increment_id);
            return true;
        } else {
            Yii::$service->helper->errors->add('generate order fail');

            return false;
        }
    }
    
    /**
     * @property $increment_id | String 每执行一次，version都会+1 （version默认为0）
     * 执行完，查看version是否为1，如果不为1，则说明已经执行过了，返回false
     */
    protected  function  checkOrderVersion($increment_id){
        # 更新订单版本号，防止被多次执行。
        $sql    = 'update '.$this->_orderModel->tableName().' set version = version + 1  where increment_id = :increment_id';
        $data   = [
            'increment_id'  => $increment_id,
        ];
        $result     = $this->_orderModel->getDb()->createCommand($sql,$data)->execute();
        $myOrder    = $this->_orderModel->find()->where([
            'increment_id'  => $increment_id,
        ])->one();
        # 如果版本号不等于1，则回滚
        if($myOrder['version'] > 1){
            Yii::$service->helper->errors->add('Your order has been paid');
            return false;
        }else if($myOrder['version'] < 1){
            Yii::$service->helper->errors->add('Your order is error');
            return false;
        }else{
            return true;
        }
    }

    /**
     * @property $increment_id | String ,order订单号
     * 将生成的订单号写入session
     */
    protected function actionSetSessionIncrementId($increment_id)
    {
        Yii::$service->session->set(self::CURRENT_ORDER_INCREAMENT_ID, $increment_id);
    }

    /**
     * 从session中取出来订单号.
     */
    protected function actionGetSessionIncrementId()
    {
        return Yii::$service->session->get(self::CURRENT_ORDER_INCREAMENT_ID);
    }
    /**
     * @property $increment_id | String 订单号
     * @property $token | String ，通过api支付的token
     * 通过订单号，更新订单的支付token
     */
    protected function actionUpdateTokenByIncrementId($increment_id,$token){
        $myOrder = Yii::$service->order->getByIncrementId($increment_id);
        if($myOrder){
            $myOrder->payment_token = $token;
            $myOrder->save();
        }
    }

    /**
     * 从session中销毁订单号.
     */
    protected function actionRemoveSessionIncrementId()
    {
        return Yii::$service->session->remove(self::CURRENT_ORDER_INCREAMENT_ID);
    }

    /**
     * @property $order_id | Int
     * @return $increment_id | Int
     *                       通过 order_id 生成订单号。
     */
    protected function generateIncrementIdByOrderId($order_id)
    {
        $increment_id = (int) $this->increment_id + (int) $order_id;

        return $increment_id;
    }

    /**废弃
     * get order list by customer account id.
     */
    protected function actionGetCustomerOrderList($customer_id = '')
    {
    }

    /**废弃
     * @property $order_id 订单id
     * 订单支付成功后，更改订单的状态为支付成功状态。
     */
    protected function actionOrderPaySuccess($order_id)
    {
    }

    /**
     * @property $increment_id | String
     * @return bool
     *              取消订单，更新订单的状态为cancel。
     *              并且释放库存给产品
     */
    protected function actionCancel($increment_id = '')
    {
        if (!$increment_id) {
            $increment_id = $this->getSessionIncrementId();
        }
        if ($increment_id) {
            $order = $this->getByIncrementId($increment_id);
            if ($order) {
                $order->order_status    = $this->payment_status_canceled;
                $order->updated_at      = time();
                $order->save();
                // 释放库存
                $order_primary_key      = $this->getPrimaryKey();
                $product_items          = Yii::$service->order->item->getByOrderId($order[$order_primary_key],true);
                Yii::$service->product->stock->returnQty($product_items);
                
                return true;
            }
        }

        return false;
    }

    /**
     * 将xx时间内未支付的pending订单取消掉，并释放产品库存。
     * 这个是后台脚本执行的函数。
     */
    protected function actionReturnPendingStock()
    {
        $minute     = $this->minuteBeforeThatReturnPendingStock;
        $begin_time = strtotime(date('Y-m-d H:i:s'). ' -'.$minute.' minutes ');

        // 不需要释放库存的支付方式。譬如货到付款，在系统中
        // pending订单，如果一段时间未付款，会释放产品库存，但是货到付款类型的订单不会释放，
        // 如果需要释放产品库存，客服在后台取消订单即可释放产品库存。
        $noRelasePaymentMethod = Yii::$service->payment->noRelasePaymentMethod;
        $where = [
            ['<', 'updated_at', $begin_time],
            ['order_status' => $this->payment_status_pending],
            ['if_is_return_stock' => 2],
        ];
        if ($noRelasePaymentMethod) {
            $where[] = ['<>', 'payment_method', $noRelasePaymentMethod];
        }

        $filter = [
            'where'         => $where,
            'numPerPage'    => $this->orderCountThatReturnPendingStock,
            'pageNum'       => 1,
            'asArray'       => false,
        ];

        $data   = $this->coll($filter);
        $coll   = $data['coll'];
        $count  = $data['count'];

        if ($count > 0) {
            foreach ($coll as $one) {
                $order_id = $one['order_id'];
                $product_items = Yii::$service->order->item->getByOrderId($order_id, true);
                Yii::$service->product->stock->returnQty($product_items);
                $one->if_is_return_stock = 1;
                // 将订单取消掉。取消后的订单不能再次支付。
                $one->order_status = $this->payment_status_canceled;
                $one->save();
            }
        }
    }
}
