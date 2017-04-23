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
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fecshop\models\mongodb\Product;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductMongodb implements ProductInterface
{
	public $numPerPage = 20;
	
	public function getPrimaryKey(){
		return '_id';
	}
	
	public function getByPrimaryKey($primaryKey){
		if($primaryKey){
			return Product::findOne($primaryKey);
		}else{
			return new Product;
		}
	}
	/**
	 * @property $sku|Array 
	 * @property $returnArr|boolean 返回的数据是否是数组格式，如果设置为
	 *		false，则返回的是对象数据
	 * @return  Array or Object
	 * 通过sku 获取产品，一个产品
	 */
	public function getBySku($sku,$returnArr=true){
		if($sku){
			if($returnArr){
				$product =  Product::find()->asArray()
					->where(['sku' => $sku])
					->one()
					;
			}else{
				$product = Product::findOne(['sku' => $sku]);
			} 
			$primaryKey = $this->getPrimaryKey() ;
			if(isset($product[$primaryKey]) && !empty($product[$primaryKey])){
				return $product;
			}
		}
	}
	
	/**
	 * @property $spu|Array 
	 * @property $returnArr|boolean 返回的数据是否是数组格式，如果设置为
	 *		false，则返回的是对象数据
	 * @return  Array or Object
	 *  通过spu 获取产品数组
	 */
	public function getBySpu($spu,$returnArr=true){
		if($spu){
			if($returnArr){
				return Product::find()->asArray()
					->where(['spu' => $spu])
					->all();
			}else{
				return Product::find()
					->where(['spu' => $spu])
					->all();
			} 
		}
	}
	
	/*
	 * example filter:
	 * [
	 * 		'numPerPage' 	=> 20,  	
	 * 		'pageNum'		=> 1,
	 * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
	 * 		'where'			=> [
				['>','price',1],
				['<=','price',10]
	 * 			['sku' => 'uk10001'],
	 * 		],
	 * 	'asArray' => true,
	 * ]
	 */
	public function coll($filter=''){
		$query = Product::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		return [
			'coll' => $query->all(),
			'count'=> $query->count(),
		];
	}
	/**
	 * 和coll()的不同在于，该方式不走active record，因此可以获取产品的所有数据的。
	 * 走这种方式，可以绕过产品属性组，因为产品属性组需要根据不同的
	 * 属性组，在active record 上面附加相应的属性，对api这种不适合。
	 */
	public function apicoll(){
		$collection = Product::find()->getCollection();
		$cursor = $collection->find();
		$count = $collection->count();
		$arr = [];
		foreach($cursor as $k =>$v){
			$v['_id'] = (string) $v['_id'] ;
			$arr[$k] = $v;
		}
		return [
			'coll' => $arr,
			'count'=> $count
		];
		
	}
	
	/**
	 * 和getByPrimaryKey()的不同在于，该方式不走active record，因此可以获取产品的所有数据的。
	 */
	public function apiGetByPrimaryKey($primaryKey){
		$collection = Product::find()->getCollection();
		$cursor = $collection->findOne(['_id' => $primaryKey]);
		$arr = [];
		foreach($cursor as $k => $v){
			$arr[$k] = $v;
		}
		return $arr;
	}
	
	/**
	 * 通过api保存产品
	 */
	public function apiSave($product_one){
		$collection = Product::find()->getCollection();
		$collection->save($product_one);
		return true;
	}
	/**
	 * 通过api保存产品
	 */
	public function apiDelete($primaryKey){
		$collection = Product::find()->getCollection();
		$collection->remove(['_id' => $primaryKey]);
		return true;
	}
	
	
	/**
	 *  得到总数
	 */
	public function collCount($filter=''){
		$query = Product::find();
		$query = Yii::$service->helper->ar->getCollByFilter($query,$filter);
		return $query->count();
	}
	
	/**
	 * @property  $product_id_arr | Array
	 * @property  $category_id | String
	 * 在给予的产品id数组$product_id_arr中，找出来那些产品属于分类 $category_id
	 * 该功能是后台分类编辑中，对应的分类产品列表功能
	 * 也就是在当前的分类下，查看所有的产品，属于当前分类的产品，默认被勾选。
	 */
	public function getCategoryProductIds($product_id_arr,$category_id){
		$id_arr = [];
		if(is_array($product_id_arr) && !empty($product_id_arr)){
			$query = Product::find()->asArray();
			$mongoIds = [];
			foreach($product_id_arr as $id){
				$mongoIds[] = new \MongoDB\BSON\ObjectId($id);
			}
			//var_dump($mongoIds);
			$query->where(['in',$this->getPrimaryKey(),$mongoIds]);
			$query->andWhere(['category'=>$category_id]);
			$data = $query->all();
			if(is_array($data) && !empty($data) ){
				foreach($data as $one){
					$id_arr[] = $one[$this->getPrimaryKey()];
				}
			}
		}
		//echo '####';
		//var_dump($id_arr);
		//echo '####';
		return $id_arr;
	}
	/**
	 * @property $attr_group | String
	 * 根据产品的属性组名，得到属性数组，然后将属性数组附加到Product(model)的属性中。
	 */
	public function addGroupAttrs($attr_group){
		$attrInfo = Yii::$service->product->getGroupAttrInfo($attr_group);
		if(is_array($attrInfo) && !empty($attrInfo)){
			$attrs = array_keys($attrInfo);
			\fecshop\models\mongodb\Product::addCustomProductAttrs($attrs);
		}
		
	}
	
	
	/**
	 * @property $one|Array , 产品数据数组
	 * @property $originUrlKey|String , 产品的原来的url key ，也就是在前端，分类的自定义url。
	 * 保存产品（插入和更新），以及保存产品的自定义url  
     * 如果提交的数据中定义了自定义url，则按照自定义url保存到urlkey中，如果没有自定义urlkey，则会使用name进行生成。	 
	 */
	public function save($one,$originUrlKey='catalog/product/index'){
		
		if(!$this->initSave($one)){
			return;
		}
		$currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
		$primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
		if($primaryVal){
			$model = Product::findOne($primaryVal);
			if(!$model){
				Yii::$service->helper->errors->add('Product '.$this->getPrimaryKey().' is not exist');
				return;
			}	
			
			#验证sku 是否重复
			$product_one = Product::find()->asArray()->where([
				'<>',$this->getPrimaryKey(),(new \MongoDB\BSON\ObjectId($primaryVal))
			])->andWhere([
				'sku' => $one['sku'],
			])->one();
			if($product_one['sku']){
				Yii::$service->helper->errors->add('Product Sku 已经存在，请使用其他的sku');
				return;
			}
		}else{
			$model = new Product;
			$model->created_at = time();
			$model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
			$primaryVal = new \MongoDB\BSON\ObjectId();
			$model->{$this->getPrimaryKey()} = $primaryVal;
			#验证sku 是否重复
			$product_one = Product::find()->asArray()->where([
				'sku' => $one['sku'],
			])->one();
			if($product_one['sku']){
				Yii::$service->helper->errors->add('Product Sku 已经存在，请使用其他的sku');
				return;
			}
		}
		$model->updated_at = time();
		/**
		 * 计算出来产品的最终价格。
		 */
		$one['final_price']= Yii::$service->product->price->getFinalPrice($one['price'],$one['special_price'],$one['special_from'],$one['special_to']);
		$one['score'] = (int)$one['score'];
		unset($one['_id']);
		/**
		 * 保存产品
		 */
		$saveStatus = Yii::$service->helper->ar->save($model,$one);
		/**
		 * 自定义url部分
		 */
		if($originUrlKey){
			$originUrl = $originUrlKey.'?'.$this->getPrimaryKey() .'='. $primaryVal;
			$originUrlKey = isset($one['url_key']) ? $one['url_key'] : '';
			$defaultLangTitle = Yii::$service->fecshoplang->getDefaultLangAttrVal($one['name'],'name');
			$urlKey = Yii::$service->url->saveRewriteUrlKeyByStr($defaultLangTitle,$originUrl,$originUrlKey);
			$model->url_key = $urlKey;
			$model->save();
		}
		/**
		 * 更新产品信息到搜索表。
		 */
		$product_ids = [$model->{$this->getPrimaryKey()} ];
		Yii::$service->search->syncProductInfo($product_ids);
		return true;
	}
	/**
	 * @property $one|Array
	 * 对保存的数据进行数据验证
	 * sku  spu   默认语言name ， 默认语言description不能为空。
	 */
	protected function initSave($one){
		if(!isset($one['sku']) || empty($one['sku'])){
			Yii::$service->helper->errors->add(' sku 必须存在 ');
			return false;
		}
		if(!isset($one['spu']) || empty($one['spu'])){
			Yii::$service->helper->errors->add(' spu 必须存在 ');
			return false;
		}
		$defaultLangName = \Yii::$service->fecshoplang->getDefaultLangAttrName('name'); 
		if(!isset($one['name'][$defaultLangName]) || empty($one['name'][$defaultLangName])){
			Yii::$service->helper->errors->add(' name '.$defaultLangName.' 不能为空 ');
			return false;
		}
		$defaultLangDes = \Yii::$service->fecshoplang->getDefaultLangAttrName('description');
		if(!isset($one['description'][$defaultLangDes]) || empty($one['description'][$defaultLangDes])){
			Yii::$service->helper->errors->add(' description '.$defaultLangDes.'不能为空 ');
			return false;
		}
		return true;
	}
	
	/**
	 * @property $ids | Array or String
	 * 删除产品，如果ids是数组，则删除多个产品，如果是字符串，则删除一个产品
	 * 在产品产品的同时，会在url rewrite表中删除对应的自定义url数据。
	 */ 
	public function remove($ids){
		if(empty($ids)){
			Yii::$service->helper->errors->add('remove id is empty');
			return false;
		}
		if(is_array($ids)){
			foreach($ids as $id){
				$model = Product::findOne($id);
				if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
					$url_key =  $model['url_key'];
					# 删除在重写url里面的数据。
					Yii::$service->url->removeRewriteUrlKey($url_key);
					# 删除在搜索表（各个语言）里面的数据
					Yii::$service->search->removeByProductId($model[$this->getPrimaryKey()]);
					$model->delete();
					//$this->removeChildCate($id);
				}else{
					Yii::$service->helper->errors->add("Product Remove Errors:ID:$id is not exist.");
					return false;
				}
			}
		}else{
			$id = $ids;
			$model = Product::findOne($id);
			if(isset($model[$this->getPrimaryKey()]) && !empty($model[$this->getPrimaryKey()]) ){
				$url_key =  $model['url_key'];
				# 删除在重写url里面的数据。
				Yii::$service->url->removeRewriteUrlKey($url_key);
				# 删除在搜索里面的数据
				Yii::$service->search->removeByProductId($model[$this->getPrimaryKey()]);
				$model->delete();
				//$this->removeChildCate($id);
			}else{
				Yii::$service->helper->errors->add("Product Remove Errors:ID:$id is not exist.");
				return false;
			}
		}
		return true;
	}
	
	/**
	 * @property $category_id | String  分类的id的值
	 * @property $addCateProductIdArr | Array 分类中需要添加的产品id数组，也就是给这个分类增加这几个产品。
	 * @property $deleteCateProductIdArr | Array 分类中需要删除的产品id数组，也就是在这个分类下面去除这几个产品的对应关系。
	 * 这个函数是后台分类编辑功能中使用到的函数，在分类中可以一次性添加多个产品，也可以删除多个产品，产品和分类是多对多的关系。
	 */
	public function addAndDeleteProductCategory($category_id,$addCateProductIdArr,$deleteCateProductIdArr){
		# 在 addCategoryIdArr 查看哪些产品，分类id在product中已经存在，
		$idKey = $this->getPrimaryKey();
		//var_dump($addCateProductIdArr);
		if(is_array($addCateProductIdArr) && !empty($addCateProductIdArr) && $category_id){
			$addCateProductIdArr = array_unique($addCateProductIdArr);
			foreach($addCateProductIdArr as $product_id){
				if(!$product_id){
					continue;
				}
				$product = Product::findOne($product_id);
				if(!$product[$idKey]){
					continue;
				}
				$category = $product->category;
				$category = ($category && is_array($category)) ? $category : [];
				//echo $category_id;
				if(!in_array($category_id,$category)){
					//echo $category_id;
					$category[] = $category_id;
					$product->category = $category;
					$product->save();
				}
			}
		}
		
		if(is_array($deleteCateProductIdArr) && !empty($deleteCateProductIdArr) && $category_id){
			$deleteCateProductIdArr = array_unique($deleteCateProductIdArr);
			foreach($deleteCateProductIdArr as $product_id){
				if(!$product_id){
					continue;
				}
				$product = Product::findOne($product_id);
				if(!$product[$idKey]){
					continue;
				}
				$category = $product->category;
				if(in_array($category_id,$category)){
					$arr = [];
					foreach($category as $c){
						if($category_id != $c){
							$arr[] = $c;
						}
					}
					$product->category = $arr;
					$product->save();
				}
			}
		}
	}
	
	/**
	 * 通过where条件 和 查找的select 字段信息，得到产品的列表信息，
	 * 这里一般是用于前台的区块性的不分页的产品查找。
	 * 结果数据没有进行进一步处理，需要前端获取数据后在处理。
	 */
	public function getProducts($filter){
		$where 			= $filter['where'];
		if(empty($where))
			return [];
		$select			= $filter['select'];
		$query = Product::find()->asArray();
		$query->where($where);
		if(is_array($select) && !empty($select)){
			$query->select($select);
		}
		return $query->all();
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
	 
	public function getFrontCategoryProducts($filter){
		$where 			= $filter['where'];
		if(empty($where))
			return [];
		$orderBy 		= $filter['orderBy'];
		$pageNum 		= $filter['pageNum'];
		$numPerPage 	= $filter['numPerPage'];
		$select			= $filter['select'];
		$group['_id'] 	= $filter['group'];
		$project 		= [];
		foreach($select as $column){
			$project[$column] 	= 1;
			$group[$column] 	= ['$first' => '$'.$column];
		}
		$pipelines = [
			[
				'$match' 	=> $where,
			],
			[
				'$sort' => [
					'score' => -1
				]
			],
			[
				'$project' 	=> $project
			],
			[
				'$group'	=> $group,
			],
			[
				'$sort' 	=> $orderBy,
			],
		];
		//var_dump($orderBy);
		//exit;
		$product_data = Product::getCollection()->aggregate($pipelines);
		$product_total_count = count($product_data);
		$pageOffset = ($pageNum - 1) * $numPerPage;
		$products = array_slice($product_data, $pageOffset, $numPerPage);
		return [
			'coll' => $products,
			'count' => $product_total_count,
		];
	}
	/**
	 * @property $filter_attr | String 需要进行统计的字段名称
	 * @propertuy $where | Array  搜索条件。这个需要些mongodb的搜索条件。
	 * 得到的是个属性，以及对应的个数。
	 * 这个功能是用于前端分类侧栏进行属性过滤。
	 */
	public function getFrontCategoryFilter($filter_attr,$where){
		if(empty($where))
			return [];
		$group['_id'] 	= '$'.$filter_attr;
		$group['count'] = ['$sum'=> 1];
		$project 		= [$filter_attr => 1];
		$pipelines = [
			[
				'$match' 	=> $where,
			],
			[
				'$project' 	=> $project
			],
			[
				'$group'	=> $group,
			],
		];
		$filter_data = Product::getCollection()->aggregate($pipelines);
		return $filter_data;
	}
	
	
	/*
	$project 		= [];
	$group['_id'] 	= $filter['group'];
	foreach($select as $column){
		$project[$column] 	= 1;
		if($column != '_id'){
			$group[$column] 	= ['$first' => '$'.$column];
		}else{
			$group['mongo_id'] 	= ['$first' => '$'.$column];
		
		}
	
	}
	
	$pipelines = [
		[
			'$match' 	=> $where,
		],
		
		[
			'$project' 	=> $project
		],
		[
			'$group'	=> $group,
		],
		
	];
	//var_dump($pipelines);
	//exit;
	$data = Product::getCollection()->aggregate($pipelines);
	
	*/
	//var_dump($where);exit;
	//var_dump($productIds );
	//$data = Product::getCollection()
	//	->fullTextSearch($searchText,[],['_id'],['limit'=>$product_search_max_count])
	//	;
	//exit;
		
	public function updateProductReviewInfo($spu,$avag_rate,$count,$lang_code,$avag_lang_rate,$lang_count){
		$data = Product::find()->where([
			'spu' => $spu
		])->all();
		if(!empty($data) && is_array($data)){
			$attrName =  'reviw_rate_star_average_lang';
			$review_star_lang = Yii::$service->fecshoplang->getLangAttrName($attrName,$lang_code);
			$attrName =  'review_count_lang';
			$review_count_lang = Yii::$service->fecshoplang->getLangAttrName($attrName,$lang_code);
			
			foreach($data as $one){
				$one['reviw_rate_star_average'] = $avag_rate;
				$one['review_count'] = $count;
				$a 						= $one['reviw_rate_star_average_lang'];
				$a[$review_star_lang]  	= $avag_lang_rate ;
				$b 						= $one['review_count_lang'];
				$b[$review_count_lang]  = $lang_count ;
				$one['reviw_rate_star_average_lang'] = $a;
				$one['review_count_lang']			 = $b;
				$one->save() ;
			}
		}
	}
}


