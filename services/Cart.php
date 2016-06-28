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
	public function addProductToCart($item){
		
		
	}
	
	public function getUserCartInfo(){
		
		
	}
	
	public function changeItemQty($sku){
		
	}
	
	/**
	 *  merge cart , if current cart currency is not equals to user cart currency when user login account.
	 */
	public function mergeCartAfterUserLogin(){
		
		
	}
	
	/**
	 * change current cart currency 
	 * 1. check if currency is allowed to change.
	 */
	public function changeCartCurrency(){
		
		
	}
	
	/**
	 * @property $language|String
	 * change current language , cart product  language change to current language.
	 */
	public function changeProductLanguage($language=''){
		
		
	}
	
	/**
	 * @property $address|Array
	 * save cart address.like,,  customer name,tel,email,address ,,etc,,.
	 */
	public function saveCartAddress($address){
		
		
	}
	
	/**
	 * @property $shippingId | Int 
	 * 1.check if $shippingId is effective
	 * 2.add or change shipping to cart.
	 * 3.change shipping cost after change
	 * 
	 */
	public function saveCartShipping($shippingId){
		
		
	}
	
	/**
	 * @property $payment | Int 
	 * 1.check if $paymentId is effective
	 * 2.add or change payment to cart.
	 */
	public function saveCartPayment($paymentId){
		
		
	}
	
	/**
	 * $item_ids
	 * remove cart items by $items_ids
	 */
	public function removeItems($item_ids){
		
		
	}
	
	/**
	 * clear cart product.
	 */
	public function clearCart(){
		
		
	}
	
	/**
	 * generate order by current Cart.
	 */
	public function generateOrderByCart(){
		
		
	}
	
	/**
	 * add cart items by pending order Id
	 * 1. check if the order is exist ,and belong to current customer.
	 * 2. get all item sku and custom option.
	 * 3. add to cart like in product page ,click add to cart button.
	 */
	public function addItemsByPendingOrder($order_id){
		
		
	}
	
	
}