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
	 */
	protected function actionAddProductToCart($item){
		$product = Yii::$service->product->getByPrimaryKey($item['product_id']);
		$productValidate = Yii::$service->cart->info->validateProduct($item,$product);
		if(!$productValidate){
			return false;
		}
		//echo $item['custom_option_sku'];
		if(isset($item['custom_option_sku']) && !empty($item['custom_option_sku'])){
			$custom_option_sku = Yii::$service->cart->info->getCustomOptionSku($item,$product);
			if(!$custom_option_sku){
				return false;
			}
			$item['custom_option_sku'] = $custom_option_sku;
		}
		
		$innerTransaction = Yii::$app->db->beginTransaction();
		try {
			Yii::$service->cart->quoteItem->addItem($item);
			$innerTransaction->commit();
		} catch (Exception $e) {
			$innerTransaction->rollBack();
		}
		return true;
		
	}
	
	# 得到购物车中产品的个数
	protected function actionGetCartItemQty(){
		return Yii::$service->cart->quote->getCartItemCount();
		
	}
	/**
	 * 得到购物车中的信息。
	 */ 
	protected function actionGetCartInfo(){
		return Yii::$service->cart->quote->getCartInfo();
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
	protected function actionAddCoupon($coupon_code){
		
		
	}
	
	
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
	 * clear cart product.
	 */
	protected function actionClearCart(){
		Yii::$service->cart->quote->clearCart();
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