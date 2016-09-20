<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\search;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\models\mongodb\Search;
use fecshop\services\Service;
/**
 * Search
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class XunSearch extends Service implements SearchInterface
{
	public $searchIndexConfig;
	public $searchLang;
	/**
	 * 初始化xunSearch索引
	 */
	protected function actionInitFullSearchIndex(){
		
		
	}
	/**
	 * 将产品信息同步到xunSearch引擎中
	 */
	protected function actionSyncProductInfo($product_ids,$numPerPage){
		
	}
	/**
	 * 批量更新过程中，被更新的产品都会更新字段sync_updated_at
	 * 删除xunSearch引擎中sync_updated_at小于$nowTimeStamp的字段
	 */
	protected function actionDeleteNotActiveProduct($nowTimeStamp){
		
	}
	
	/**
	 * 得到搜索的sku列表
	 */
	protected function actionGetSearchProductColl($select,$where,$pageNum,$numPerPage,$product_search_max_count){
		
		
	}
	
	/**
	 * 得到搜索的sku列表侧栏的过滤
	 */
	protected function actionGetFrontSearchFilter($filter_attr,$where){
		
		
	}
	/**
	 * 通过product_id删除搜索数据
	 */
	protected function actionRemoveByProductId($product_id){
		
		
	}
	
	
}



