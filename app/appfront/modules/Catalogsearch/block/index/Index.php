<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalogsearch\block\index;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index {
	
	protected $_searchText;
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
	protected $_numPerPageVal;
	
	
	public function getLastData(){
		$this->getNumPerPage();
		//echo Yii::$service->page->translate->__('fecshop,{username}', ['username' => 'terry']);
		$this->initSearch();
		# change current layout File.
		//Yii::$service->page->theme->layoutFile = 'home.php';
		
		$productCollInfo 	= $this->getSearchProductColl();
		$products 			= $productCollInfo['coll'];
		$this->_productCount= $productCollInfo['count'];
		//echo $this->_productCount;
		return [
			'searchText'		=> $this->_searchText,
			'title' 		=> $this->_title,
			'name'			=> Yii::$service->store->getStoreAttrVal($this->_category['name'],'name'),
			'image'			=> $this->_category['image'] ? Yii::$service->category->image->getUrl($this->_category['image']) : '',
			'description'	=> Yii::$service->store->getStoreAttrVal($this->_category['description'],'description'),
			'products'		=> $products,
			'query_item'	=> $this->getQueryItem(),
			'product_page'	=> $this->getProductPage(),
			'refine_by_info'=> $this->getRefineByInfo(),
			'filter_info'	=> $this->getFilterInfo(),
			'filter_price'	=> $this->getFilterPrice(),
			//'filter_category'=> $this->getFilterCategoryHtml(),
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
		if(is_array($filter_category) && !empty($filter_category)){
			$str .= '<ul>';
			foreach($filter_category as $cate){
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
		$productNumPerPage 	= $this->getNumPerPage();
		$productCount 		= $this->_productCount;
		$pageNum 			= $this->getPageNum();
		//echo $productCount;
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
		$category_query 	= Yii::$app->controller->module->params['search_query'];
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
		
		$data = [
			'frontNumPerPage' => $frontNumPerPage,
		//	'frontSort' => $frontSort,
		];
		//var_dump($data);
		return $data;
	}
	
	protected function getFilterAttr(){
		if(!$this->_filter_attr){
			$this->_filter_attr = $filterAttr = Yii::$service->search->filterAttr;
		}
		return $this->_filter_attr;
	}
	
	protected function getRefineByInfo(){
		
		$get_arr = Yii::$app->request->get();
		//var_dump($get_arr);
		if(is_array($get_arr) && !empty($get_arr)){
			$refineInfo = [];
			$filter_attrs = $this->getFilterAttr();
			$filter_attrs[] = 'price';
			//var_dump($filter_attrs);
			$currentUrl = Yii::$service->url->getCurrentUrl();
			foreach($get_arr as $k=>$v){
				$attr = Yii::$service->url->category->urlStrConvertAttrVal($k);
				//echo $attr;
				if(in_array($attr,$filter_attrs)){
					if($attr == 'price'){
						$refine_attr_str = $this->getFormatFilterPrice($v);
						//$refine_attr_str = Yii::$service->url->category->urlStrConvertAttrVal($v);
					}else{
						$refine_attr_str = Yii::$service->url->category->urlStrConvertAttrVal($v);
					}
					$removeUrlParamStr = $k.'='.$v;
					$refine_attr_url = Yii::$service->url->removeUrlParamVal($currentUrl,$removeUrlParamStr);
					$refineInfo[] = [
						'name' =>  $refine_attr_str,
						'url'  =>  $refine_attr_url,	
					];
				}
			}
		}
		if(!empty($refineInfo)){
			$arr[] = [
				'name' 	=> 'clear all',
				'url'	=> Yii::$service->url->getCurrentUrlNoParam().'?q='.Yii::$app->request->get('q'),
			];
			$refineInfo = array_merge($arr,$refineInfo);
		}
		return $refineInfo;
	}
	
	protected function getFilterInfo(){
		$filter_info  = [];
		$filter_attrs = $this->getFilterAttr();
		if(is_array($filter_attrs) && !empty($filter_attrs)){
			foreach($filter_attrs as $attr){
				$filter_info[$attr] = Yii::$service->search->getFrontSearchFilter($attr,$this->_where);
			}
		}
		return $filter_info;
	}
	
	protected function getFilterPrice(){
		$filter = [];
		$priceInfo = Yii::$app->controller->module->params['search_query'];
		//var_dump($priceInfo);
		if(isset($priceInfo['price_range']) && !empty($priceInfo['price_range'])  && is_array($priceInfo['price_range'])){
			foreach($priceInfo['price_range'] as $price_item){
				$info = Yii::$service->url->category->getFilterChooseAttrUrl($this->_filterPrice,$price_item,$this->_page);
				$info['val'] = $this->getFormatFilterPrice($price_item);
				$filter[$this->_filterPrice][] = $info;
			}
		}
		return $filter;
	}
	
	protected function getFormatFilterPrice($price_item){
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
		return $str;
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
		if(!$this->_numPerPageVal){
			$numPerPage = Yii::$app->request->get($this->_numPerPage);
			$category_query_config = Yii::$app->controller->module->params['search_query'];
			if(!$numPerPage){
				if(isset($category_query_config['numPerPage'])){
					if(is_array($category_query_config['numPerPage'])){
						$this->_numPerPageVal = $category_query_config['numPerPage'][0];
					}
				}
			}else if(!$this->_numPerPageVal){
				if(isset($category_query_config['numPerPage']) && is_array($category_query_config['numPerPage'])){
					$numPerPageArr = $category_query_config['numPerPage'];
					if(in_array((int)$numPerPage,$numPerPageArr)){
						$this->_numPerPageVal = $numPerPage;
					}else{
						throw new InvalidValueException('Incorrect numPerPage value:'.$numPerPage);
					}
				}
			}
		}
		return $this->_numPerPageVal;
	}
	
	protected function getPageNum(){
		$numPerPage = Yii::$app->request->get($this->_page);
		return $numPerPage ? (int)$numPerPage : 1;
	}
	
	
	protected function getSearchProductColl(){
		$select = [
			'sku','spu','name','image',
			'price','special_price',
			'special_from','special_to',
			'url_key','score',
		];
		$where = $this->_where;
		$search_text = Yii::$app->controller->module->params['search_query'];
		$pageNum	    = $this->getPageNum();
		$numPerPage	    = $this->getNumPerPage();
		$product_search_max_count = Yii::$app->controller->module->params['product_search_max_count'];
		return Yii::$service->search->getSearchProductColl($select,$where,$pageNum,$numPerPage,$product_search_max_count);
		
	}
	
	protected function initWhere(){
		$filterAttr = $this->getFilterAttr();
		if(is_array($filterAttr) && !empty($filterAttr)){
			foreach($filterAttr as $attr){
				$attrUrlStr = Yii::$service->url->category->attrValConvertUrlStr($attr);
				$val = Yii::$app->request->get($attrUrlStr);
				if($val){
					$val = Yii::$service->url->category->urlStrConvertAttrVal($val);
					$where[$attr] = $val;
				}
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
		//$where['category'] = $this->_primaryVal;
		//var_dump($where);exit;
		$where['$text'] = ['$search' => $this->_searchText];
		//$where['status'] = 1;
		//$where['is_in_stock'] = 1;
		$this->_where = $where;
		return $where;
	}
	protected function initSearch(){
		//$primaryKey = Yii::$service->category->getPrimaryKey();
		//$primaryVal = Yii::$app->request->get($primaryKey);
		//$this->_primaryVal = $primaryVal;
		//$category 	= Yii::$service->category->getByPrimaryKey($primaryVal);
		//$this->_category = $category ;
		$searchText = Yii::$app->request->get('q');
		$searchText = \Yii::$service->helper->htmlEncode($searchText);
		$this->_searchText = $searchText;
		$search_page_title_format 			= Yii::$app->controller->module->params['search_page_title_format'];
		$search_page_meta_keywords_format 	= Yii::$app->controller->module->params['search_page_meta_keywords_format'];
		$search_page_meta_description_format= Yii::$app->controller->module->params['search_page_meta_description_format'];
		$this->breadcrumbs();
		if($search_page_title_format){
			$title = str_replace('%s',$searchText,$search_page_title_format); 
		}else{
			$title = $searchText;
		}
		if($search_page_meta_keywords_format){
			$meta_keywords = str_replace('%s',$searchText,$search_page_meta_keywords_format); 
		}else{
			$meta_keywords = $searchText;
		}
		if($search_page_meta_description_format){
			$meta_description = str_replace('%s',$searchText,$search_page_meta_description_format); 
		}else{
			$meta_description = $searchText;
		}
		
		
		Yii::$app->view->registerMetaTag([
			'name' => 'keywords',
			'content' => $meta_keywords,
		]);
		Yii::$app->view->registerMetaTag([
			'name' => 'description',
			'content' => $meta_description,
		]);
		$this->_title = $title;
		Yii::$app->view->title = $this->_title;
		$this->_where = $this->initWhere();
	}
	
	# 面包屑导航
	protected function breadcrumbs(){
		if(Yii::$app->controller->module->params['search_breadcrumbs']){
			Yii::$service->page->breadcrumbs->addItems(['name' => $this->_searchText]);
		}else{
			Yii::$service->page->breadcrumbs->active = false;
		}
		
	}
	
}
