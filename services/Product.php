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
use fecshop\services\product\ProductMysqldb;
use fecshop\services\product\ProductMongodb;
/**
 * Product Service is the component that you can get product info from it.
 * @property Image|\fecshop\services\Product\Image $image ,This property is read-only.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Product extends Service
{
	
	public $storage = 'mongodb';
	public $customAttrGroup;
	protected $_product;
	protected $_defaultAttrGroup = 'default';
	
	public function init(){
		if($this->storage == 'mongodb'){
			$this->_product = new ProductMongodb;
		//}else if($this->storage == 'mysqldb'){
			//$this->_category = new CategoryMysqldb;
		}
	}
	# Yii::$service->product->getCustomAttrGroup();
	/**
	 * 得到产品的所有的属性组。
	 */
	protected function actionGetCustomAttrGroup(){
		$customAttrGroup = $this->customAttrGroup;
		$arr = array_keys($customAttrGroup);
		$arr[] = $this->_defaultAttrGroup;
		return $arr;
	}
	/**
	 * @property $productAttrGroup|String
	 *  得到这个产品属性组里面的所有的产品属性，
	 *  注解：不同类型的产品，对应不同的属性组，譬如衣服有颜色尺码，电脑类型的有不同cpu型号等
	 *  属性组，以及属性组对应的属性，是在Product Service config中配置的。
	 */
	protected function actionGetGroupAttrInfo($productAttrGroup){
		if($productAttrGroup == $this->_defaultAttrGroup){
			return [];
		}else if(isset($this->customAttrGroup[$productAttrGroup])){
			return isset($this->customAttrGroup[$productAttrGroup]) ? $this->customAttrGroup[$productAttrGroup] : [];
		}
	}
	/**
	 * 得到默认的产品属性组。
	 */
	protected function actionGetDefaultAttrGroup(){
		return $this->_defaultAttrGroup;
	}
	
	/**
	 * 得到主键的名称
	 */
	protected function actionGetPrimaryKey(){
		return $this->_product->getPrimaryKey();
	}
	/**
	 * get Category model by primary key.
	 */
	protected function actionGetByPrimaryKey($primaryKey){
		return $this->_product->getByPrimaryKey($primaryKey);
	}
	
	
	
	/**
	 * @property $filter|Array
	 * get artile collection by $filter
	 * example filter:
	 * [
	 * 		'numPerPage' 	=> 20,  	
	 * 		'pageNum'		=> 1,
	 * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
	 * 		where'			=> [
				['>','price',1],
				['<=','price',10]
	 * 			['sku' => 'uk10001'],
	 * 		],
	 * 	'asArray' => true,
	 * ]
	 */
	protected function actionColl($filter=''){
		return $this->_product->coll($filter);
	}
	
	protected function actionCollCount($filter=''){
		return $this->_product->collCount($filter);
	}
	
	/**
	 * 通过where条件 和 查找的select 字段信息，得到产品的列表信息，
	 * 这里一般是用于前台的区块性的不分页的产品查找。
	 * 结果数据没有进行进一步处理，需要前端获取数据后在处理。
	 */
	protected function actionGetProducts($filter){
		return $this->_product->getProducts($filter);
	}
	/**
	 * @property  $product_id_arr | Array
	 * @property  $category_id | String
	 * 在给予的产品id数组$product_id_arr中，找出来那些产品属于分类 $category_id
	 * 该功能是后台分类编辑中，对应的分类产品列表功能
	 * 也就是在当前的分类下，查看所有的产品，属于当前分类的产品，默认被勾选。
	 */
	protected function actionGetCategoryProductIds($product_id_arr,$category_id){
		return $this->_product->getCategoryProductIds($product_id_arr,$category_id);
	}
	/**
	 * @property $one|Array , 产品数据数组
	 * @property $originUrlKey|String , 分类的原来的url key ，也就是在前端，分类的自定义url。
	 * 保存产品（插入和更新），以及保存产品的自定义url  
     * 如果提交的数据中定义了自定义url，则按照自定义url保存到urlkey中，如果没有自定义urlkey，则会使用name进行生成。	 
	 */
	protected function actionSave($one,$originUrlKey='catalog/product/index'){
		return $this->_product->save($one,$originUrlKey);
	}
	/**
	 * @property $ids | Array or String
	 * 删除产品，如果ids是数组，则删除多个产品，如果是字符串，则删除一个产品
	 * 在产品产品的同时，会在url rewrite表中删除对应的自定义url数据。
	 */
	protected function actionRemove($ids){
		return $this->_product->remove($ids);
	}
	/**
	 * @property $category_id | String  分类的id的值
	 * @property $addCateProductIdArr | Array 分类中需要添加的产品id数组，也就是给这个分类增加这几个产品。
	 * @property $deleteCateProductIdArr | Array 分类中需要删除的产品id数组，也就是在这个分类下面去除这几个产品的对应关系。
	 * 这个函数是后台分类编辑功能中使用到的函数，在分类中可以一次性添加多个产品，也可以删除多个产品，产品和分类是多对多的关系。
	 */
	protected function actionAddAndDeleteProductCategory($category_id,$addCateProductIdArr,$deleteCateProductIdArr){
		return $this->_product->addAndDeleteProductCategory($category_id,$addCateProductIdArr,$deleteCateProductIdArr);
	}
	/**
	 *[
	 *	'category_id' 	=> 1,
	 *	'pageNum'		=> 2,
	 *	'numPerPage'	=> 50,
	 *	'orderBy'		=> 'name',
	 *	'where'			=> [
	 *		['>','price',11],
	 *		['<','price',22],
	 *	],
	 *	'select'		=> ['xx','yy'],
	 *	'group'			=> '$spu',
	 * ]
	 * 得到分类下的产品，在这里需要注意的是：
	 * 1.同一个spu的产品，有很多sku，但是只显示score最高的产品，这个score可以通过脚本取订单的销量（最近一个月，或者
	 *   最近三个月等等），或者自定义都可以。
	 * 2.结果按照filter里面的orderBy排序
	 * 3.由于使用的是mongodb的aggregate(管道)函数，因此，此函数有一定的限制，就是该函数
	 *   处理后的结果不能大约32MB，因此，如果一个分类下面的产品几十万的时候可能就会出现问题，
	 *   这种情况可以用专业的搜索引擎做聚合工具。
	 *   不过，对于一般的用户来说，这个不会成为瓶颈问题，一般一个分类下的产品不会出现几十万的情况。
	 * 4.最后就得到spu唯一的产品列表（多个spu相同，sku不同的产品，只要score最高的那个）
	 */	
	protected function actionGetFrontCategoryProducts($filter){
		return $this->_product->getFrontCategoryProducts($filter);
		
	}
	/**
	 * @property $filter_attr | String 需要进行统计的字段名称
	 * @propertuy $where | Array  搜索条件。这个需要些mongodb的搜索条件。
	 * 得到的是个属性，以及对应的个数。
	 * 这个功能是用于前端分类侧栏进行属性过滤。
	 */
	protected function actionGetFrontCategoryFilter($filter_attr,$where){
		return $this->_product->getFrontCategoryFilter($filter_attr,$where);
	}
	/**
	 * 全文搜索
	 * $filter Example:
	 *	$filter = [
	 *		'pageNum'	  => $this->getPageNum(),
	 *		'numPerPage'  => $this->getNumPerPage(),
	 *		'where'  => $this->_where,
	 *		'product_search_max_count' => 	Yii::$app->controller->module->params['product_search_max_count'],		
	 *		'select' 	  => $select,
	 *	];
	 *  因为mongodb的搜索涉及到计算量，因此产品过多的情况下，要设置 product_search_max_count的值。减轻服务器负担
     *  因为对客户来说，前10页的产品已经足矣，后面的不需要看了，限定一下产品个数，减轻服务器的压力。	 
	 *  多个spu，取score最高的那个一个显示。
	 *  按照搜索的匹配度来进行排序，没有其他排序方式
	 */
	//protected function actionFullTearchText($filter){
	//	return $this->_product->fullTearchText($filter);
	//}
	
	
}


