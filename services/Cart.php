<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
/**
 * Cart services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Cart extends Service
{
	
	public $noLoginUserCartStorage 	= 'session';
	public $cartMergeCurrency 		= 'current' ;# 当购物车合并的时候，当前的货币和购物车的货币不一致的情况下，以哪个为准？
	public $cartMergeLanguage		= 'current'; # 当购物车合并的时候，当前的语言和购物车的语言不一致的情况下，以哪个为准？
	
	
	protected $cartMergeCurrencyArr = ['current','account',]; # 在用户登录账户的时候，当前的货币和语言与购物车的货币和语言不一致的情况下，current代表是当前为准则，account代表以购物车为准则
	/**
	 * @property $item|Array
	 * add product info to cart,before add to cart,check if product is exist in db,
	 * check if product info is correct .
	 * then , if customer is login,use db to save customer cart info, else,
	 * use session to save custom cart info, also ,you can config varibale $noLoginUserCartStorage ,save cart info to db.
	 *
	 */
	protected function actionAddProductToCart($item){
		$cart_qty 			= $item['qty'];
		$custom_option 	= $item['custom_option'];
		$product_id 	= $item['product_id'];
		# 验证提交产品数据
		$product = Yii::$service->product->getByPrimaryKey($product_id);
		if(!$product['sku']){
			Yii::$service->helper->errors->add('this product is not exist');
			return;
		}
		$product_custom_option = $product['custom_option'];
		$co_vd = Yii::$service->product->info->validateProductCustomOption($custom_option,$product['custom_option']);
		if(!$co_vd){
			return;
		}
		# 验证库存 是否库存满足？
		Yii::$service->product->info->productIsCanSale($product,$cart_qty);
		# 得到当前美元单价
		$current_price = Yii::$service->product->price->getFinalPrice(
			$product['price'],$product['special_price']	,
			$product['special_from'],$product['special_to']	,
			$cart_qty, $product['tier_price']
		);
		$base_row_total = $current_price * $cart_qty;
		
		$current_currency_price = Yii::$service->page->currency->getCurrentCurrencyPrice($current_price);
		$row_total = $current_currency_price * $cart_qty;
		
		$weight = $product['weight'];
		$row_weight = $weight * $cart_qty;
		# 得到当前的cart，如果不存在，则新建cart信息
		
		# 开启事务，将产品信息插入到cart_item中
		
		# 将item的所有信息计算得到cart的总信息，然后更新cart表信息
		# 加入优惠券信息，得到购物车总价，保存
	}
	
	protected function actionGetUserCartInfo(){
		
		
	}
	
	protected function actionChangeItemQty($sku){
		
	}
	
	/**
	 *  merge cart , if current cart currency is not equals to user cart currency when user login account.
	 */
	protected function actionMergeCartAfterUserLogin(){
		
		
	}
	
	/**
	 * change current cart currency 
	 * 1. check if currency is allowed to change.
	 */
	protected function actionChangeCartCurrency(){
		
		
	}
	
	/**
	 * @property $language|String
	 * change current language , cart product  language change to current language.
	 */
	protected function actionChangeProductLanguage($language=''){
		
		
	}
	
	/**
	 * @property $address|Array
	 * save cart address.like,,  customer name,tel,email,address ,,etc,,.
	 */
	protected function actionSaveCartAddress($address){
		
		
	}
	
	/**
	 * @property $shippingId | Int 
	 * 1.check if $shippingId is effective
	 * 2.add or change shipping to cart.
	 * 3.change shipping cost after change
	 * 
	 */
	protected function actionSaveCartShipping($shippingId){
		
		
	}
	
	/**
	 * @property $payment | Int 
	 * 1.check if $paymentId is effective
	 * 2.add or change payment to cart.
	 */
	protected function actionSaveCartPayment($paymentId){
		
		
	}
	
	/**
	 * $item_ids
	 * remove cart items by $items_ids
	 */
	protected function actionRemoveItems($item_ids){
		
		
	}
	
	/**
	 * clear cart product.
	 */
	protected function actionClearCart(){
		
		
	}
	
	/**
	 * generate order by current Cart.
	 */
	protected function actionGenerateOrderByCart(){
		
		
	}
	
	/**
	 * add cart items by pending order Id
	 * 1. check if the order is exist ,and belong to current customer.
	 * 2. get all item sku and custom option.
	 * 3. add to cart like in product page ,click add to cart button.
	 */
	protected function actionAddItemsByPendingOrder($order_id){
		
		
	}
	
	
}