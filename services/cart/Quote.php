<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\cart;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\services\Service;
use fecshop\models\mysqldb\Cart as MyCart;
/**
 * Cart services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Quote extends Service
{
	
	
	
	
	const SESSION_CART_ID = 'current_session_cart_id';
	
	protected $_cart_id;
	protected $_cart;
	
	/**
	 
	public function getCartId(){
		if(!$this->_cart_id){
			$cart_id = Yii::$app->session->get(self::SESSION_CART_ID);
			if($cart_id){
				$one = MyCart::findOne($cart_id);
				if($one['cart_id']){
					$this->_cart_id = $cart_id;
					$this->_my_cart = $one;
				}
			}
		}
		return $this->_cart_id;
	}
	 
	*/
	
	
	# 得到cart_id
	public function getCartId(){
		if(!$this->_cart_id){
			$cart_id = Yii::$app->session->get(self::SESSION_CART_ID);
			$this->_cart_id = $cart_id;	
		}
		return $this->_cart_id;
	}
	
	#
	public function getCart(){
		if(!$this->_cart){
			$cart_id = $this->getCartId();
			
			if(!$cart_id){
				$this->createCart();
			}else{
				
				$one = MyCart::findOne(['cart_id' => $cart_id]);
				if($one['cart_id']){
					$this->_cart = $one;
				}
			}
		}
		return $this->_cart;
	}
	
	public function setCart($cart){
		$this->_cart = $cart;
	}
	
	
	
	
	/**
	 * 得到购物车中产品的个数。头部的ajax请求一般访问这个
	 */
	public function getCartItemCount(){
		$items_count = 0;
		if($cart_id = $this->getCartId()){
			$one = MyCart::findOne(['cart_id' => $cart_id]);
			if($one['items_count']){
				$items_count = $one['items_count'];
			}
		}
		return $items_count;
	}
	/**
	 * 当购物车的产品变动后，更新cart表的产品总数
	 */
	public function computeCartInfo(){
		$item_qty = Yii::$service->cart->quoteItem->getItemQty();
		$cart =  $this->getCart();
		$cart->items_count = $item_qty;
		$cart->save();
		return true;
	}
	
	public function getCartItemsCount(){
		$cart =  $this->getCart();
		return $cart->items_count;
	}
	/**
	 * 返回当前的购物车Db对象
	 */
	/*
	public function getMyCart(){
		if(!$this->_my_cart){
			if($cart_id = $this->getCartId()){
				if(!$this->_my_cart){
					$this->_my_cart = MyCart::findOne(['cart_id'=>$cart_id]);
				}
			}else{
				$this->createCart();
			}
		}
		return $this->_my_cart;
	}
	*/
	
	/**
	 * @property $cart_id | int
	 * 设置cart_id 到类变量以及session
	 */
	protected function setCartId($cart_id){
		$this->_cart_id = $cart_id;
		Yii::$app->session->set(self::SESSION_CART_ID,$cart_id);
	}
	
	protected function actionClearCart(){
		Yii::$app->session->remove(self::SESSION_CART_ID);
	}
	
	/**
	 * 初始化创建cart信息，
	 * 在用户的第一个产品加入购物车时，会在数据库中创建购物车
	 */
	protected function actionCreateCart(){
		$myCart = new MyCart;
		$myCart->store = Yii::$service->store->currentStore;
		$myCart->created_at = time();
		$myCart->updated_at = time();
		if(!Yii::$app->user->isGuest){
			$identity 	= Yii::$app->user->identity;
			$id 		= $identity['id'];
			$firstname 	= $identity['firstname'];
			$lastname 	= $identity['lastname'];
			$email 		= $identity['email'];
			$myCart->customer_id 		= $id;
			$myCart->customer_email 	= $email;
			$myCart->customer_firstname = $firstname;
			$myCart->customer_lastname 	= $lastname;
			$myCart->customer_is_guest	= 2;
		}else{
			$myCart->customer_is_guest	= 1;
		}
		$myCart->remote_ip = \fec\helpers\CFunc::get_real_ip();
		$myCart->app_name  = Yii::$service->helper->getAppName();
		$myCart->save();
		$cart_id = Yii::$app->db->getLastInsertId();
		$this->setCartId($cart_id);
		$this->setCart(MyCart::findOne($cart_id));
	}
	
	public function addCustomerDefautAddressToCart(){
		if(!Yii::$app->user->isGuest){
			$cart = $this->getCart();
			# 购物车没有customer address  id，则
			# 使用登录用户的默认address
			$identity = Yii::$app->user->identity;
			//echo $cart['customer_id'] ;
			//echo "##";
			//echo $identity['id'];
			//exit;
			if($cart['customer_id'] == $identity['id']){
				if(!isset($cart['customer_address_id']) || empty($cart['customer_address_id'])){
					$defaultAddress = Yii::$service->customer->address->getDefaultAddress();
					if(is_array($defaultAddress) && !empty($defaultAddress)){
						$cart->customer_telephone = isset($defaultAddress['telephone']) ? $defaultAddress['telephone'] : '';
						$cart->customer_email= isset($defaultAddress['email']) ? $defaultAddress['email'] : '';
						$cart->customer_firstname= isset($defaultAddress['first_name']) ? $defaultAddress['first_name'] : '';
						$cart->customer_lastname= isset($defaultAddress['last_name']) ? $defaultAddress['last_name'] : '';
						$cart->customer_address_id= isset($defaultAddress['address_id']) ? $defaultAddress['address_id'] : '';
						$cart->customer_address_country= isset($defaultAddress['country']) ? $defaultAddress['country'] : '';
						$cart->customer_address_state= isset($defaultAddress['state']) ? $defaultAddress['state'] : '';
						$cart->customer_address_city= isset($defaultAddress['city']) ? $defaultAddress['city'] : '';
						$cart->customer_address_zip= isset($defaultAddress['zip']) ? $defaultAddress['zip'] : '';
						$cart->customer_address_street1= isset($defaultAddress['street1']) ? $defaultAddress['street1'] : '';
						$cart->customer_address_street2= isset($defaultAddress['street2']) ? $defaultAddress['street2'] : '';
						$cart->save();
						$this->setCart($cart);
						
					}
				
				}
			}
		}
	}
	
	public function hasAddressId(){
		$cart = $this->getCart();
		$address_id = $cart['customer_address_id'];
		if($address_id){
			return true;
		}
	}
	
	/**
	 * 得到购物车的信息
	 */
	public function getCartInfo(){
		$cart_id = $this->getCartId();
		if(!$cart_id){
			return ;
		}
		
		$cart = $this->getCart();
		$items_qty = $cart['items_count'];
		if($items_qty <= 0){
			return ;
		}
		$coupon_code = $cart['coupon_code'];
		$cart_product_info = Yii::$service->cart->quoteItem->getCartProductInfo();
		if(is_array($cart_product_info)){
			
			$products = $cart_product_info['products'];
			$product_total = $cart_product_info['product_total'];
			if($products && $product_total){
				$shippingCost   = $this->getShippingCost();
				$couponCost		= $this->getCouponCost($product_total,$coupon_code);
				$grand_total	= $product_total + $shippingCost - $couponCost;
				
				return [
					'coupon_code'	=> $coupon_code,
					'grand_total' 	=> $grand_total,
					'shipping_cost' => $shippingCost,
					'coupon_cost' 	=> $couponCost,
					'product_total' => $product_total,
					'products' 		=> $products,
				];
			}
			
		}
	}
	
	public function getShippingCost(){
		return 0;
	}
	/**
	 * 得到优惠券的折扣金额
	 */
	public function getCouponCost($product_total,$coupon_code){
		$dc_price = Yii::$service->page->currency->getDefaultCurrencyPrice($product_total);
		$dc_discount = Yii::$service->cart->coupon->getDiscount($coupon_code,$dc_price);
		return $dc_discount;
	}
	
	public function setCartCoupon($coupon_code){
		$cart = $this->getCart();
		$cart->coupon_code = $coupon_code;
		$cart->save();
		return true;
	}
	
	public function cancelCartCoupon($coupon_code){
		$cart = $this->getCart();
		$cart->coupon_code = null;
		$cart->save();
		return true;
	}
	
	
	public function mergeCartAfterUserLogin(){
		if(!Yii::$app->user->isGuest){
			$identity = Yii::$app->user->identity;
			$customer_id = $identity['id'];
			$email = $identity->email;
			$customer_firstname = $identity->firstname;
			$customer_lastname  = $identity->lastname;
			$customer_cart = $this->getCartByCustomerId($customer_id);
			$cart_id = $this->getCartId();
			if(!$customer_cart){
				//echo 111;exit;
				if($cart_id){
					$cart = $this->getCart();
					if($cart){
						$cart['customer_email'] = $email ;
						$cart['customer_id'] = $customer_id ;
						$cart['customer_firstname'] = $customer_firstname ;
						$cart['customer_lastname'] = $customer_lastname ;
						$cart['customer_is_guest'] = 2;
						$cart->save();
						
					}
				}
			}else{
				//echo 22;exit;
				$cart = $this->getCart();
				if(!$cart || !$cart_id){
					//echo 111;exit;
					$cart_id = $customer_cart['cart_id'];
					$this->setCartId($cart_id);
				}else{
					# 将无用户产品（当前）和 购物车中的产品（登录用户对应的购物车）进行合并。
					$new_cart_id = $customer_cart['cart_id'];
					if($cart['coupon_code']){
						# 如果有优惠券则取消，以登录用户的购物车的优惠券为准。
						Yii::$service->cart->coupon->cancelCoupon($cart['coupon_code']);
					}
					# 将当前购物车产品表的cart_id 改成 登录用户对应的cart_id
					Yii::$service->cart->quoteItem->updateCartId($new_cart_id,$cart_id);
					# 当前的购物车删除掉
					$cart->delete();
					# 设置当前的cart_id
					$this->setCartId($new_cart_id);
					# 设置当前的cart
					$this->setCart($customer_cart);
					# 重新计算购物车中产品的个数
					$this->computeCartInfo();
					
				}
			}
		}
	}
	
	
	public function getCartByCustomerId($customer_id){
		if($email){
			$one = MyCart::findOne(['customer_id' => $customer_id]);
			if($one['cart_id']){
				return $one;
			}
		}
	}
	
	
	
	
	
	
}