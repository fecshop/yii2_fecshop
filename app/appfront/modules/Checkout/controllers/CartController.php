<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Checkout\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class CartController extends AppfrontController
{
    public $enableCsrfValidation = false;
	
	public function actionAdd(){
		$this->enableCsrfValidation = true;
		$custom_option 	= Yii::$app->request->post('custom_option');
		$product_id 	= Yii::$app->request->post('product_id');
		$qty 			= Yii::$app->request->post('qty');
		$qty  = abs(ceil((int)$qty));
		if($qty  && $product_id){
			if($custom_option){
				$custom_option_sku = json_decode($custom_option,true);
			}
			if(empty($custom_option_sku)){
				$custom_option_sku = null;
			}
			$item = [
				'product_id' => $product_id,
				'qty' 		=>  $qty,
				'custom_option_sku' => $custom_option_sku,
			];
			
			$addToCart = Yii::$service->cart->addProductToCart($item);
			if($addToCart){
				echo json_encode([
					'status' => 'success',
					'items_count' => Yii::$service->cart->quote->items_count,
				]);
				exit;
			}
		}
		exit;
	}
}
















