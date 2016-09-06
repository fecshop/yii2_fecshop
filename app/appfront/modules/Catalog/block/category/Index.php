<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalog\block\category;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index {
	
	protected $_category;
	protected $_title;
	protected $_primaryVal;
	protected $_defautOrder;
	protected $_defautOrderDirection = SORT_DESC;
	protected $_where;
	protected $_numPerPage	= 'numPerPage';
	protected $_direction  	= 'dir';
	protected $_sort  		= 'sort';
	protected $_page  		= 'p';
	protected $_filterPrice = 'price';
	protected $_filterPriceAttr = 'price';
	protected $_productCount;
	protected $_filter_attr;
	
	public function getLastData(){
		
		//echo Yii::$service->page->translate->__('fecshop,{username}', ['username' => 'terry']);
		$this->initCategory();
		
		# change current layout File.
		//Yii::$service->page->theme->layoutFile = 'home.php';
		
		$productCollInfo = $this->getCategoryProductColl();
		$products 			= $productCollInfo['coll'];
		$this->_productCount= $productCollInfo['count'];
		//echo $this->_productCount;
		return [
			'title' 		=> $this->_title,
			'name'			=> Yii::$service->store->getStoreAttrVal($this->_category['name'],'name'),
			'image'			=> $this->_category['image'] ? Yii::$service->category->image->getUrl($this->_category['image']) : '',
			'description'	=> Yii::$service->store->getStoreAttrVal($this->_category['description'],'description'),
			'products'		=> $products,
			'filter_info'	=> $this->getFilterInfo(),
			'query_item'	=> $this->getQueryItem(),
			'product_page'	=> $this->getProductPage(),
			'filter_price'	=> $this->getFilterPrice(),
			'filter_category'=> $this->getFilterCategoryHtml(),
			//'content' => Yii::$service->store->getStoreAttrVal($this->_category['content'],'content'),
			//'created_at' => $this->_category['created_at'],
		];
	}
	/**
	 * 得到子分类，如果子分类不存在，则返回同级分类。
	 */
	protected function getFilterCategory(){
		$category_id 	= $this->_primaryVal;
		$parent_id 		= $this->_category['parent_id'];
		$filter_category = Yii::$service->category->getFilterCategory($category_id,$parent_id);
		return $filter_category;
	}
	
	
	protected function getFilterCategoryHtml($filter_category=''){
		$str = '';
		if(!$filter_category){
			$filter_category = $this->getFilterCategory();
		}
		//var_dump($filter_category);
		//exit;
		if(is_array($filter_category) && !empty($filter_category)){
			$str .= '<ul>';
			foreach($filter_category as $cate){
				//var_dump($cate);
				//echo '<br><br>';
				//continue;
				$name = Yii::$service->store->getStoreAttrVal($cate['name'],'name');
				$url  = Yii::$service->url->getUrl($cate['url_key']);
				$current = '';
				if(isset($cate['current']) && $cate['current']){
					$current = 'class="current"';
				}
				$str .= '<li '.$current.'><a href="'.$url.'">'.$name.'</a>';
				if(isset($cate['child']) && is_array($cate['child'] ) && !empty($cate['child'])){
					$str .= $this->getFilterCategoryHtml($cate['child']);
					
				}
				$str .= '</li>';
			}
			$str .= '</ul>';
		}
		//exit;
		return $str;
	}
	
	
	protected function getProductPage(){
		$spaceShowNum = 4;
		$productNumPerPage 	= $this->getNumPerPage();
		$productCount 		= $this->_productCount;
		$pageNum 			= $this->getPageNum();
		$config = [
			'class' 		=> 'fecshop\app\appfront\widgets\Page',
			'view'  		=> 'widgets/page.php',
			'pageNum' 		=> $pageNum,
			'numPerPage' 	=> $productNumPerPage,
			'countTotal' 	=> $productCount,
			'page' 			=> $this->_page,
		];
		return Yii::$service->page->widget->renderContent('category_product_page',$config);
	}
	
	
	
	
	
	protected function getQueryItem(){
		$category_query 	= Yii::$app->controller->module->params['category_query'];
		$numPerPage			= $category_query['numPerPage'];	
		$sort				= $category_query['sort'];
		$frontNumPerPage = [];
		if(is_array($numPerPage) && !empty($numPerPage)){
			$attrUrlStr = $this->_numPerPage;
			foreach($numPerPage as $np){
				$urlInfo = Yii::$service->url->category->getFilterChooseAttrUrl($attrUrlStr,$np,$this->_page);
				//var_dump($url);
				//exit;
				$frontNumPerPage[] = [
					'value' 	=> $np,
					'url'		=> $urlInfo['url'],
					'selected'	=> $urlInfo['selected'],
				];
			}
		}
		$frontSort = [];
		if(is_array($sort) && !empty($sort)){
			$attrUrlStr = $this->_sort;
			$dirUrlStr  = $this->_direction;
			foreach($sort as $np=>$info){
				$label 		= $info['label'];
				$direction 	= $info['direction'];
				$arr['sort'] = [
					'key' => $attrUrlStr,
					'val' => $np,
				];
				$arr['dir'] = [
					'key' => $dirUrlStr,
					'val' => $direction,
				];
				$urlInfo = Yii::$service->url->category->getFilterSortAttrUrl($arr,$this->_page);
				//var_dump($urlInfo);
				//exit;
				$frontSort[] = [
					'label'     => $label ,
					'value' 	=> $np,
					'url'		=> $urlInfo['url'],
					'selected'	=> $urlInfo['selected'],
				];
			}
		}
		$data = [
			'frontNumPerPage' => $frontNumPerPage,
			'frontSort' => $frontSort,
		];
		return $data;
	}
	
	protected function getFilterAttr(){
		if(!$this->_filter_attr){
			$filter_default = Yii::$app->controller->module->params['category_filter_attr'];
			$current_fileter_select = $this->_category['filter_product_attr_selected'];
			$current_fileter_unselect = $this->_category['filter_product_attr_unselected'];
			$current_fileter_select_arr = $this->getFilterArr($current_fileter_select);
			$current_fileter_unselect_arr = $this->getFilterArr($current_fileter_unselect);
			$filter_attrs =  array_merge($filter_default, $current_fileter_select_arr);
			$filter_attrs =   array_diff($filter_attrs,$current_fileter_unselect_arr);
			$this->_filter_attr = $filter_attrs;
		}
		return $this->_filter_attr;
	}
	
	protected function getFilterInfo(){
		$filter_info  = [];
		$filter_attrs = $this->getFilterAttr();
		foreach($filter_attrs as $attr){
			$filter_info[$attr] = Yii::$service->product->getFrontCategoryFilter($attr,$this->_where);
		}
		return $filter_info;
		
	}
	
	protected function getFilterPrice(){
		$filter = [];
		$priceInfo = Yii::$app->controller->module->params['category_query'];
		if(isset($priceInfo['price_range']) && !empty($priceInfo['price_range'])  && is_array($priceInfo['price_range'])){
			foreach($priceInfo['price_range'] as $price_item){
				$info = Yii::$service->url->category->getFilterChooseAttrUrl($this->_filterPrice,$price_item,$this->_page);
				$info['val'] =  $price_item;
				list($f_price,$l_price) = explode('-',$price_item);
				$str = '';
				if($f_price == '0' || $f_price){
					$f_price = Yii::$service->product->price->formatPrice($f_price);
					$str  .= $f_price['symbol'].$f_price['value'].'---';
				}
				if($l_price){
					$l_price = Yii::$service->product->price->formatPrice($l_price);
					$str  .= $l_price['symbol'].$l_price['value'];
				
				}
				$info['val'] = $str;
				$filter[$this->_filterPrice][] = $info;
			}
		}
		return $filter;
	}
	
	protected function getFilterArr($str){
		$arr = [];
		if($str){
			$str = str_replace('，',',',$str);
			$str_arr = explode(",",$str);
			foreach($str_arr as $a){
				$a = trim($a);
				if($a){
					$arr[] = trim($a);
				}
			}
		}
		return $arr;
	}
	
	protected function getOrderBy(){
		$primaryKey = Yii::$service->category->getPrimaryKey();
		$sort 		= Yii::$app->request->get($this->_sort);
		$direction 	= Yii::$app->request->get($this->_direction);
		
		$category_query_config = Yii::$app->controller->module->params['category_query'];
		if(isset($category_query_config['sort'])){
			$sortConfig = $category_query_config['sort'];
			if(is_array($sortConfig)){
				//return $category_query_config['numPerPage'][0];
				if($sort && isset($sortConfig[$sort])){
					$orderInfo = $sortConfig[$sort];
				}else{
					foreach($sortConfig as $k => $v){
						$orderInfo = $v;
						if(!$direction){
							$direction = $v['direction'];
						}
						break;
					}
				}
				$db_columns = $orderInfo['db_columns'];
				if($direction == 'desc'){
					$direction = -1;
				}else{
					$direction = 1;
				}
				//var_dump([$db_columns => $direction]);
				
				return [$db_columns => $direction];
			}
		}
		
		
	}
	
	protected function getNumPerPage(){
		$numPerPage = Yii::$app->request->get($this->_numPerPage);
		if(!$numPerPage){
			$category_query_config = Yii::$app->controller->module->params['category_query'];
			if(isset($category_query_config['numPerPage'])){
				if(is_array($category_query_config['numPerPage'])){
					return $category_query_config['numPerPage'][0];
				}
			}
		}
		return $numPerPage;
	}
	
	protected function getPageNum(){
		$numPerPage = Yii::$app->request->get($this->_page);
		return $numPerPage ? (int)$numPerPage : 1;
	}
	
	
	protected function getCategoryProductColl(){
		$filter = [
			'pageNum'	  => $this->getPageNum(),
			'numPerPage'  => $this->getNumPerPage(),
			'orderBy'	  => $this->getOrderBy(),
			'where'		  => $this->_where,
			'select' 	  => [
				'sku','spu','name','image',
				'price','special_price',
				'special_from','special_to',
				'url_key','score',
			],
		];
		//var_dump($filter);exit;
		return Yii::$service->category->product->getFrontList($filter);
	}
	
	protected function initWhere(){
		$filterAttr = $this->getFilterAttr();
		foreach($filterAttr as $attr){
			$attrUrlStr = Yii::$service->url->category->attrValConvertUrlStr($attr);
			$val = Yii::$app->request->get($attrUrlStr);
			if($val){
				$val = Yii::$service->url->category->urlStrConvertAttrVal($val);
				$where[$attr] = $val;
			}
		}
		$filter_price = Yii::$app->request->get($this->_filterPrice);
		list($f_price,$l_price) = explode('-',$filter_price);
		if($f_price == '0' || $f_price){
			$where[$this->_filterPriceAttr]['$gte'] = (float)$f_price;
		}
		if($l_price){
			$where[$this->_filterPriceAttr]['$lte'] = (float)$l_price;
		}
		$where['category'] = $this->_primaryVal;
		//var_dump($where);exit;
		return $where;
	}
	protected function initCategory(){
		$primaryKey = Yii::$service->category->getPrimaryKey();
		$primaryVal = Yii::$app->request->get($primaryKey);
		$this->_primaryVal = $primaryVal;
		$category 	= Yii::$service->category->getByPrimaryKey($primaryVal);
		$this->_category = $category ;
		Yii::$app->view->registerMetaTag([
			'name' => 'keywords',
			'content' => Yii::$service->store->getStoreAttrVal($category['meta_keywords'],'meta_keywords'),
		]);
		Yii::$app->view->registerMetaTag([
			'name' => 'description',
			'content' => Yii::$service->store->getStoreAttrVal($category['meta_description'],'meta_description'),
		]);
		$this->_title = Yii::$service->store->getStoreAttrVal($category['title'],'title');
		$name = Yii::$service->store->getStoreAttrVal($category['name'],'name');
		$this->breadcrumbs($name);
		$this->_title = $this->_title ? $this->_title : $name;
		Yii::$app->view->title = $this->_title;
		$this->_where = $this->initWhere();
	}
	
	# 面包屑导航
	protected function breadcrumbs($name){
		if(Yii::$app->controller->module->params['category_breadcrumbs']){
			$parent_info = Yii::$service->category->getAllParentInfo($this->_category['parent_id']);
			if(is_array($parent_info) && !empty($parent_info)){
				foreach($parent_info as $info){
					$parent_name = Yii::$service->store->getStoreAttrVal($info['name'],'name');
					$parent_url  = Yii::$service->url->getUrl($info['url_key']);
					Yii::$service->page->breadcrumbs->addItems(['name' => $parent_name , 'url' => $parent_url  ]);
				}
			}
			Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
		}else{
			Yii::$service->page->breadcrumbs->active = false;
		}
	}
}
