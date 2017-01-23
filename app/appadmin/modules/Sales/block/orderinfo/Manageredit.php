<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Sales\block\orderinfo;
use Yii;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
/**
 * block cms\article
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit
{
	public  $_saveUrl;
	
	
	public function init(){
		//$this->_saveUrl = CUrl::getUrl('sales/orderinfo/managereditsave');
		//parent::init();
	}
	# 传递给前端的数据 显示编辑form	
	public function getLastData(){
		$order_id = Yii::$app->request->get('order_id');
		//$order = Yii::$service->order->getByPrimaryKey($order_id);
		$order_info = Yii::$service->order->getOrderInfoById($order_id);
		return [
			'order' => $order_info,
			//'editBar' 	=> $this->getEditBar(),
			//'textareas'	=> $this->_textareas,
			//'lang_attr'	=> $this->_lang_attr,
			//'saveUrl' 	=> $this->_saveUrl,
		];
	}
	
	
	
	
	
	
	
	
}