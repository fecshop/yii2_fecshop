<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\block\category;
use Yii;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index //extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
	/**
	 * init param function ,execute in construct
	 */
	public function init(){
		/**
		 * edit data url
		 */
		$this->_editUrl 	= CUrl::getUrl("catalog/urlrewrite/manageredit");
		/**
		 * delete data url
		 */
		$this->_deleteUrl 	= CUrl::getUrl("catalog/urlrewrite/managerdelete");
		/**
		 * service component, data provider
		 */
		$this->_service = Yii::$service->url->rewrite;
		parent::init();
		
	}
	
	public function getLastData(){
		return [
			
		];
	}
	
	
	
}