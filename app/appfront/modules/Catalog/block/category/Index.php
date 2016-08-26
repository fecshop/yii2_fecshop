<?php
/*
 * 存放 一些基本的非数据库数据 如 html
 * 都是数组
 */
namespace fecshop\app\appfront\modules\Catalog\block\category;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
class Index {
	
	protected $_category;
	protected $_title;
	protected $_primaryVal;
	protected $_defautOrder;
	protected $_defautOrderDirction = SORT_DESC;
	
	public function getLastData(){
		
		//echo Yii::$service->page->translate->__('fecshop,{username}', ['username' => 'terry']);
		$this->initHead();
		
		# change current layout File.
		//Yii::$service->page->theme->layoutFile = 'home.php';
		return [
			'title' 		=> $this->_title,
			'name'			=> Yii::$service->store->getStoreAttrVal($this->_category['name'],'name'),
			'products'		=> $this->getCategoryProductColl(),
			//'content' => Yii::$service->store->getStoreAttrVal($this->_category['content'],'content'),
			//'created_at' => $this->_category['created_at'],
		];
	}
	
	protected function getOrderBy(){
		$primaryKey = Yii::$service->category->getPrimaryKey();
		$order 	= Yii::$app->request->get('order');
		$order  = $order ? $order : $primaryKey;
		$dir 	= Yii::$app->request->get('dir');
		//$dir 	= $dir ? $dir : $this->_defautOrderDirction;
		if($dir){
			if($dir == 'asc'){
				$dir = 1;
			}else{
				$dir = -1;
			}
		}else{
			$dir = ($this->_defautOrderDirction == SORT_DESC) ? -1 : 1;
		}
		return [$order => $dir];
	}
	
	protected function getCategoryProductColl(){
		
		$filter = [
			'pageNum'	  => Yii::$app->request->get('p'),
			'numPerPage'  => Yii::$app->request->get('numPerPage'),
			'orderBy'	  => $this->getOrderBy(),
			'where'		  => [
				'category' => $this->_primaryVal,
			],
		];
		return Yii::$service->category->product->getFrontList($filter);
	}
	
	
	protected function initHead(){
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
		$this->_title = $this->_title ? $this->_title : Yii::$service->store->getStoreAttrVal($category['name'],'name');
		Yii::$app->view->title = $this->_title;
	}
	
}
















