<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalog\block\reviewproduct;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
use fecshop\app\appfront\modules\Catalog\helpers\Review as ReviewHelper;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Lists {
	
	public  $product_id;
	public  $spu;
	public  $filterBySpu = true;
	public  $filterOrderBy = 'review_date';
	public  $_page = 'p';
	public  $numPerPage = 20;
	public  $pageNum;
	
	public function __construct(){
		ReviewHelper::initReviewConfig();
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
	
	public function initParam(){
		$this->pageNum = Yii::$app->request->get($this->_page);
		$this->pageNum = $this->pageNum ? $this->pageNum : 1;
		$this->spu = Yii::$app->request->get('spu');
		$this->product_id = Yii::$app->request->get('_id');
		$review = Yii::$app->getModule('catalog')->params['review'];
		$productPageReviewCount = isset($review['productPageReviewCount']) ? $review['productPageReviewCount'] : 10;
		$this->numPerPage = $productPageReviewCount ? $productPageReviewCount : $this->numPerPage;
		
	}
	
	public function getLastData(){
		$this->initParam();
		if(!$this->spu || !$this->product_id){
			return ;
		}
		$product = Yii::$service->product->getByPrimaryKey($this->product_id);
		if(!$product['spu']){
			Yii::$service->page->message->addError('product _id:'.$_id.'  is not exist in product collection');
			return [];
		}
		$price_info 	= $this->getProductPriceInfo($product);
		$spu 			= $product['spu'];
		$image 			= $product['image'];
		$main_img 		= isset($image['main']['image']) ? $image['main']['image'] : '';
		$url_key 		= $product['url_key'];
		$name 			= Yii::$service->store->getStoreAttrVal($product['name'],'name');
		$addReviewUrl	= Yii::$service->url->getUrl('catalog/reviewproduct/add',['_id'=> $this->product_id,'spu'=>$spu]);
		if($this->filterBySpu){
			$data = $this->getReviewsBySpu($this->spu);
			$count = $data['count'];
			
			$pageToolBar = $this->getProductPage($count);
			$coll  = $data['coll'];
			$ReviewAndStarCount = ReviewHelper::getReviewAndStarCount($product);
			list($review_count,$reviw_rate_star_average) = $ReviewAndStarCount;
			return [
				'_id' => $this->product_id,
				'spu' => $this->spu,
				'review_count'				=> $review_count,
				'reviw_rate_star_average'	=> $reviw_rate_star_average,
				'pageToolBar'	=> $pageToolBar,
				'coll'			=> $coll ,
				'noActiveStatus'=> Yii::$service->product->review->noActiveStatus(),
				'addReviewUrl'	=> $addReviewUrl,
				'name' 			=> $name,
				'price_info' 	=> $price_info,
				'main_img' 		=> $main_img,
				'editForm'		=> $editForm,
				'url'		=> Yii::$service->url->getUrl($url_key),
			];
		}
		
		
	}
	
	
	
	public function getReviewsBySpu($spu){
		$currentIp = \fec\helpers\CFunc::get_real_ip();
		$filter = [
	  		'numPerPage' 	=> $this->numPerPage,  	
	  		'pageNum'		=> $this->pageNum,
	  		'orderBy'	=> [ $this->filterOrderBy => SORT_DESC ],
	 		'where'			=> [
				[
					'$or' => [
						[
							'status' => Yii::$service->product->review->activeStatus(),
							'product_spu' => $spu
						],
						[
							'status' => Yii::$service->product->review->noActiveStatus(),
							'product_spu' => $spu,
							'ip' => $currentIp
						]
					]
				],
			],
		];
		return Yii::$service->product->review->getListBySpu($filter);
		
	}
	
	
	protected function getProductPriceInfo($product){
		$price			= $product['price'];
		$special_price	= $product['special_price'];
		$special_from	= $product['special_from'];
		$special_to		= $product['special_to'];
		return Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($price,$special_price,$special_from,$special_to);
		
	}
	
	
	
	
	
}
