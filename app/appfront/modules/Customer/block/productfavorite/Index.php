<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Customer\block\productfavorite;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index {
	
	public $pageNum ;
	public $numPerPage = 20;
	public $_page = 'p';
	
	public function initFavoriteParam(){
		$pageNum = Yii::$app->request->get($this->_page);
		$this->pageNum = $pageNum ? $pageNum : 1;
	}
	public function getLastData(){
		$this->initFavoriteParam();
		$identity = Yii::$app->user->identity;
		$user_id  = $identity->id;
		if(!$user_id){
			Yii::$service->helper->errors->add('current user id is empty');
			return;
		}
		$filter = [
			'pageNum'	=> $this->pageNum,
			'numPerPage'=> $this->numPerPage,
			'orderBy'	=> ['updated_at' => SORT_DESC],
			'where'			=> [
				['user_id' => $user_id],
			],
			'asArray' => true,
		];
		$data = Yii::$service->product->favorite->list($filter);
		$coll = $data['coll'];
		$count = $data['count'];
		$pageToolBar = $this->getProductPage($count);
		$product_arr = $this->getProductInfo($coll);
		return [
			'coll' => $product_arr ,
			'pageToolBar'	=> $pageToolBar,
		];
	
	}
	# 得到产品的一些信息，来显示Favorite 的产品列表。
	public function getProductInfo($coll){
		$product_ids = [];
		$favorites = [];
		foreach($coll as $one){
			
			$p_id = $one['product_id'];
			$product_ids[] = new \MongoDB\BSON\ObjectId($p_id) ;
			$favorites[$p_id] = [
				'updated_at' => $one['updated_at'],
				'favorite_id' => (string)$one['_id'],
			];
		}
		# 得到产品的信息
		$product_filter = [
			'where'			=> [
				['in','_id',$product_ids],
			],
			'select' => [
				'name','image',
				'price','special_price',
				'special_from','special_to',
				'url_key'
			],
			'asArray' => true,
		];
		$data = Yii::$service->product->coll($product_filter);
		$product_arr = [];
		if(is_array($data['coll']) && !empty($data['coll'])){
			foreach($data['coll'] as $one){
				$p_id = (string)$one['_id'];
				$one['updated_at']  = $favorites[$p_id]['updated_at'];
				$one['favorite_id'] = $favorites[$p_id]['favorite_id'];
				$product_arr[] = $one;
			}
		}
		return \fec\helpers\CFunc::array_sort($product_arr,'updated_at','desc');
	}
	/**
	 * @property $favorite_id|String
	 */ 
	public function remove($favorite_id){
		Yii::$service->product->favorite->currentUserRemove($favorite_id);
		return;
	}
	
	
	protected function getProductPage($countTotal){
		if($countTotal <= $this->numPerPage){
			return '';
		}
		$config = [
			'class' 		=> 'fecshop\app\appfront\widgets\Page',
			'view'  		=> 'widgets/page.php',
			'pageNum' 		=> $this->pageNum,
			'numPerPage' 	=> $this->numPerPage,
			'countTotal' 	=> $countTotal,
			'page' 			=> $this->_page,
		];
		return Yii::$service->page->widget->renderContent('category_product_page',$config);
	}
}
