<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\product;
use Yii;
use yii\base\InvalidConfigException;
use fecshop\services\ChildService;
use fecshop\models\db\product\ViewLog as DbViewLog;
use fecshop\models\mongodb\product\ViewLog as MongodbViewLog;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ViewLog extends ChildService
{
	/**
	 *	data:
	 	$product = [
			'id' 		=> 44,
			'sku'		=> 'ttt',
			'image' 	=> '/xx/tt/dfas/dsd.jpg',
			'name' 		=> 'xxxxx',
			'user_id' 	=> 22, # 如果选填，则通过user组件，选择当前的用户id
		];
		
		#use mongodb save product view log history
		Yii::$app->product->viewLog->mongodb->setHistory($product);
		
		#use mongodb get product view log history
		$d = Yii::$app->product->viewLog->mongodb->getHistory();
		
		#use mysql save product view log history
		Yii::$app->product->viewLog->db->setHistory($product);
		
		#use mysql get product view log history
		$d = Yii::$app->product->viewLog->db->getHistory();
		
		
		#use session save product view log history
		Yii::$app->product->viewLog->session->setHistory($product);
		
		#use session get product view log history
		$history = Yii::$app->product->viewLog->session->getHistory();
		
	 */
}