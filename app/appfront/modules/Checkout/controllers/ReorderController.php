<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Checkout\controllers;

use fecshop\app\appfront\modules\AppfrontController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ReorderController extends AppfrontController
{
    
    
    public function actionIndex()
    {
        $increment_id = Yii::$app->request->get('increment_id');
        if (!$increment_id) {
            
            return $this->errorMessage('The order increment_id is empty');
        }
        $order = Yii::$service->order->getByIncrementId($increment_id);
        
        if (!$order['increment_id']) {
            
            return $this->errorMessage('The order is not exist');
        }
        $order_id = $order['order_id'];
        
        $this->addOrderProductToCart($order_id);

        return Yii::$service->url->redirectByUrlKey('checkout/cart');
    }

    public function addOrderProductToCart($order_id)
    {
        $items = Yii::$service->order->item->getByOrderId($order_id);
        //var_dump($items);
        if (is_array($items) && !empty($items)) {
            foreach ($items as $one) {
                $item = [
                    'product_id'        => $one['product_id'],
                    'custom_option_sku' => $one['custom_option_sku'],
                    'qty'                => (int) $one['qty'],
                ];
                //var_dump($item);exit;
                Yii::$service->cart->addProductToCart($item);
            }
        }
    }

    /**
     * @param $message | String
     * 添加报错信息
     */
    public function errorMessage($message)
    {
        Yii::$service->page->message->addError($message);

        return Yii::$service->url->redirectByUrlKey('checkout/cart');
    }
    
    
}
