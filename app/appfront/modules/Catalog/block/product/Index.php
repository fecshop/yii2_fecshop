<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalog\block\product;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index {
	
	protected $_product;
	protected $_title;
	protected $_primaryVal;
	
	
	public function getLastData(){
		$productImgSize = Yii::$app->controller->module->params['productImgSize'];
		$productImgMagnifier = Yii::$app->controller->module->params['productImgMagnifier'];
		$this->initProduct();
		return [
			'name' => Yii::$service->store->getStoreAttrVal($this->_product['name'],'name'),
			'image'=> $this->_product['image'],
			'media_size' => [
				'small_img_width'  => $productImgSize['small_img_width'],
				'small_img_height' => $productImgSize['small_img_height'],
				'middle_img_width' => $productImgSize['middle_img_width'],
			],
			'productImgMagnifier'  => $productImgMagnifier,
		];
	}
	
	protected function initProduct(){
		$primaryKey = Yii::$service->product->getPrimaryKey();
		$primaryVal = Yii::$app->request->get($primaryKey);
		$this->_primaryVal = $primaryVal;
		$product 	= Yii::$service->product->getByPrimaryKey($primaryVal);
		$this->_product = $product ;
		Yii::$app->view->registerMetaTag([
			'name' => 'keywords',
			'content' => Yii::$service->store->getStoreAttrVal($product['meta_keywords'],'meta_keywords'),
		]);
		Yii::$app->view->registerMetaTag([
			'name' => 'description',
			'content' => Yii::$service->store->getStoreAttrVal($product['meta_description'],'meta_description'),
		]);
		$this->_title = Yii::$service->store->getStoreAttrVal($product['meta_title'],'meta_title');
		$name = Yii::$service->store->getStoreAttrVal($product['name'],'name');
		//$this->breadcrumbs($name);
		$this->_title = $this->_title ? $this->_title : $name;
		Yii::$app->view->title = $this->_title;
		//$this->_where = $this->initWhere();
	}
	
	# Ãæ°üÐ¼µ¼º½
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
