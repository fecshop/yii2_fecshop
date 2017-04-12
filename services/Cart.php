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
use fecshop\models\mysqldb\Cart as MyCart;
use fecshop\models\mysqldb\Cart\Item as MyCartItem;
/**
 * Cart services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Cart extends Service
{
	
	
	/**
	 * 将某个产品加入到购物车中
	 * @property $item|Array
	 * $item = [
	 *		'product_id' 		=> 22222,
	 *		'custom_option_sku' => ['color'=>'red','size'=>'l'],
	 *		'qty' 				=> 22,
	 * ];
	 * 注意： $item['custom_option_sku'] 除了为上面的数组格式，还可以为字符串
	 * 为字符串的时候，字符串标示的就是产品的custom option  sku
	 * 
	 */
	protected function actionAddProductToCart($item){
		$product = Yii::$service->product->getByPrimaryKey($item['product_id']);
		# 根据传递的值，得到custom_option_sku的值。
		if(isset($item['custom_option_sku']) && !empty($item['custom_option_sku'])){
			if(is_array($item['custom_option_sku'])){
				$custom_option_sku = Yii::$service->cart->info->getCustomOptionSku($item,$product);
				
				if(!$custom_option_sku){
					Yii::$service->helper->errors->add('product custom_option_sku is not exist');
					return false;
				}
			}
			$item['custom_option_sku'] = $custom_option_sku;
		}
		# 检查产品满足加入购物车的条件
		$productValidate = Yii::$service->cart->info->checkProductBeforeAdd($item,$product);
		if(!$productValidate){
			return false;
		}
		# 开始加入购物车
		# service 里面不允许有事务，请在调用层使用事务。
		//$innerTransaction = Yii::$app->db->beginTransaction();
		//try {
			
		$beforeEventName 	= 'event_add_to_cart_before';
		$afterEventName  	= 'event_add_to_cart_after';
		Yii::$service->event->trigger($beforeEventName,$item); # 触发事件 - 加购物车前事件
		Yii::$service->cart->quoteItem->addItem($item);
		Yii::$service->event->trigger($afterEventName,$item);  # 触发事件 - 加购物车前事件
			//$innerTransaction->commit();
		//} catch (Exception $e) {
		//	$innerTransaction->rollBack();
		//}
		return true;
		
	}
	
	# 得到购物车中产品的个数
	protected function actionGetCartItemQty(){
		return Yii::$service->cart->quote->getCartItemCount();
		
	}
	/**
	 * 得到购物车中的信息。
	 */ 
	protected function actionGetCartInfo($shipping_method='',$country='',$region='*'){
		return Yii::$service->cart->quote->getCartInfo($shipping_method,$country,$region);
	}
	
	
	/**
	 * @property $item_id | Int 购物车产品表的id字段
	 * 通过item id 将购物车中的某个产品的个数加一
	 */
	protected function actionAddOneItem($item_id){
		$innerTransaction = Yii::$app->db->beginTransaction();
		try {
			$status = Yii::$service->cart->quoteItem->addOneItem($item_id);
			if(!$status){
				$innerTransaction->rollBack();
				return false;
			}
			Yii::$service->cart->quote->computeCartInfo();
			$innerTransaction->commit();
			return true;
		} catch (Exception $e) {
			$innerTransaction->rollBack();
		}
		return false;
		
	}
	/**
	 * @property $item_id | Int 购物车产品表的id字段
	 * 通过item id 将购物车中的某个产品的个数减一
	 */
	protected function actionLessOneItem($item_id){
		$innerTransaction = Yii::$app->db->beginTransaction();
		try {
			$status = Yii::$service->cart->quoteItem->lessOneItem($item_id);
			if(!$status){
				$innerTransaction->rollBack();
				return false;
			}
			Yii::$service->cart->quote->computeCartInfo();
	
			$innerTransaction->commit();
			return true;
		} catch (Exception $e) {
			$innerTransaction->rollBack();
		}
		return false;
	}
	
	/**
	 * @property $item_id | Int 购物车产品表的id字段
	 * 通过item id 删除购物车中的某个产品
	 */
	protected function actionRemoveItem($item_id){
		$innerTransaction = Yii::$app->db->beginTransaction();
		try {
			$status = Yii::$service->cart->quoteItem->removeItem($item_id);
			if(!$status){
				$innerTransaction->rollBack();
				return false;
			}
			Yii::$service->cart->quote->computeCartInfo();
			$innerTransaction->commit();
			return true;
		} catch (Exception $e) {
			$innerTransaction->rollBack();
		}
		return false;
	}
	/**
	 * @property $coupon_code 优惠卷码
	 * @return boolean 优惠券使用成功则返回true，失败则返回false
	 */
	//protected function actionAddCoupon($coupon_code){
		
		
	//}
	
	
	/**
	 *  merge cart , if current cart currency is not equals to user cart currency when user login account.
	 */
	protected function actionMergeCartAfterUserLogin(){
		Yii::$service->cart->quote->mergeCartAfterUserLogin();
		
	}
	
	
	
	
	/**
	 * @property $address|Array
	 * save cart address.like,,  customer name,tel,email,address ,,etc,,.
	 */
	protected function actionUpdateGuestCart($address,$shipping_method,$payment_method){
		return Yii::$service->cart->quote->updateGuestCart($address,$shipping_method,$payment_method);
	}
	
	protected function actionUpdateLoginCart($address_id,$shipping_method,$payment_method){
		return Yii::$service->cart->quote->updateLoginCart($address_id,$shipping_method,$payment_method);
	}
	
	
	/**
	 * 清空购物车中的产品和优惠券
	 * 在生成订单的时候被调用
	 */
	protected function actionClearCartProductAndCoupon(){
		Yii::$service->cart->quoteItem->removeItemByCartId();
		
		# 清空cart中的优惠券
		$cart = Yii::$service->cart->quote->getCurrentCart();
		if(!$cart['cart_id']){
			Yii::$service->helper->errors->add('current cart is empty');
			return false;
		}
		# 如果购物车中存在优惠券，则清空优惠券。
		if($cart->coupon_code){
			$cart->coupon_code = NULL;
			$cart->save();
		}
		return true;
	}
	
	/**
	 * 完全与当前购物车脱节，如果产品添加购物车，会创建新的cart_id
	 * 目前仅仅在登录用户退出账号的时候使用。
	 * 该操作仅仅remove掉session保存的cart_id。
	 */
	protected function actionClearCart(){
		Yii::$service->cart->quote->clearCart();
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