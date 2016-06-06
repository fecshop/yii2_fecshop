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
/**
 * Product Service is the component that you can get product info from it.
 * @property Image|\fecshop\services\Product\Image $image ,This property is read-only.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Product extends Service
{
	public $_productId;
	/**
	 * 1.得到单个产品的详细信息。
	 * 2.通过当前的语言，名字，描述等都会被转换成当前语言的value
	 * 3.价格将会被转换成当前货币的价格。
	 * 4.图片将会被转换成url（根据当前的domain）
	 * 5.这个功能提供给产品详细页面使用。返回的值比较全面
	 */
	public function getProductInfo($productId='',$selectAttr=[])
	{
		if(!$productId)
			$productId = $this->getCurrentProductId();
		if(!$productId)
			throw new InvalidValueException('productId is empty,you must pass a ProductId');
		
	
	}
	
	
	
	
	/**
	 * 通过where条件，得到product的数组，
	 * 您可以在 $selectAttr 中指定返回的字段。
	 * 如果您选择的属性中有图片，那么图片将会返回url
	 * 这个功能给分类页面使用
	 */
	public function getProductCollByWhere($where , $selectAttr){
		
		
	}
	
	/**
	 * 得到销量最好的产品，这个给首页等其他地方使用
	 */
	public function getBestSellProduct(){
		
		
	}
	
	
	/**
	 * 得到某个分类的产品销量最好
	 */
	public function getCategoryBestSellProduct(){
		return Yii::$app->product->bestSell->getCategoryProduct();
		
	}
	
	
	
	
	public function setCurrentProductId($productId){
		$this->_productId = $productId;
	}
	
	public function getCurrentProductId(){
		return $this->_productId;
	}
	
	
	
	
	
	
}