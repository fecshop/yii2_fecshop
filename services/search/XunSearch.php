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
use fecshop\models\xunsearch\Search as XunSearchModel;
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
		return;
	}
	/**
	 * 将产品信息同步到xunSearch引擎中
	 */
	protected function actionSyncProductInfo($product_ids,$numPerPage){
		if(is_array($product_ids) && !empty($product_ids)){
			$productPrimaryKey  = Yii::$service->product->getPrimaryKey();
			$searchModel 		= new Search;
			$filter['select'] 	= $searchModel->attributes();
			$filter['asArray']	= true;
			$filter['where'][]	= ['in',$productPrimaryKey,$product_ids];
			$filter['numPerPage']= $numPerPage;
			$filter['pageNum']	= 1;
			$coll = Yii::$service->product->coll($filter);
			if(is_array($coll['coll']) && !empty($coll['coll'])){
				foreach($coll['coll'] as $one){
					$one_name 				= $one['name'];
					$one_description 		= $one['description'];
					$one_short_description 	= $one['short_description'];
					if(!empty($this->searchLang) && is_array($this->searchLang)){
						foreach($this->searchLang as $langCode){
							$XunSearchModel = new XunSearchModel();
							$XunSearchModel->_id = $one['_id']->{'$id'};
							$one['name'] 		= Yii::$service->fecshoplang->getLangAttrVal($one_name,'name',$langCode);
							$one['description'] = Yii::$service->fecshoplang->getLangAttrVal($one_description,'description',$langCode);
							$one['short_description'] = Yii::$service->fecshoplang->getLangAttrVal($one_short_description,'short_description',$langCode);
							$one['sync_updated_at'] = time();
							$serialize = true;
							Yii::$service->helper->ar->save($XunSearchModel,$one,$serialize);
							if($errors = Yii::$service->helper->errors->get()){
								# 报错。
								echo  $errors; 
								//return false;
							}
							
						}
					}
				}
			}
		}
		return true;
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



