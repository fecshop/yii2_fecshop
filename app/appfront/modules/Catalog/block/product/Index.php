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
			'name' 					=> Yii::$service->store->getStoreAttrVal($this->_product['name'],'name'),
			'image'					=> $this->_product['image'],
			'sku'					=> $this->_product['sku'],
			'price_info'			=> $this->getProductPriceInfo(),
			'tier_price'			=> $this->_product['tier_price'],
			'media_size' => [
				'small_img_width'  	=> $productImgSize['small_img_width'],
				'small_img_height' 	=> $productImgSize['small_img_height'],
				'middle_img_width' 	=> $productImgSize['middle_img_width'],
			],
			'productImgMagnifier'  	=> $productImgMagnifier,
			'options'				=> $this->getSameSpuInfo(),
			'custom_option'			=> $this->_product['custom_option'],
			'description'			=> Yii::$service->store->getStoreAttrVal($this->_product['description'],'description'),
		
		];
	}
	
	
	protected function getSpuData(){
		$spu	= $this->_product['spu'];
		$filter = [
	  		'select' 	=> ['name','image','color','size','url_key'],  	
	   		'where'			=> [
	 			['spu' => $spu],
	  		],
			'asArray' => true,
		];
		$coll = Yii::$service->product->coll($filter);
		return $coll['coll'];
		
	}
	/**
	 * @property $data | Array 和当前产品的spu相同，但sku不同的产品  数组。
	 * @property $current_size | String 当前产品的size值
	 * @property $current_color | String 当前产品的颜色值
	 * @return Array 分别为
	 *		$all_color 所有的颜色数组
	 *		$all_size  所有的尺码数组
	 *		$color_2_size 当前尺码下的所有颜色数组。
	 *		$size_2_color 当前颜色下的所有尺码数组。
	 */
	protected function getColorAndSizeInfo($data,$current_size,$current_color){
		$all_color 		= [];
		$all_size 		= [];
		$color_2_size 	= [];
		$size_2_color 	= [];
		foreach($data as $one){
			$color 	= $one['color'];
			$size 	= $one['size'];
			//echo $color;
			//echo $size;
			//echo '##';
			$image 	= $one['image'];
			$name 	= $one['name'];
			$url_key= $one['url_key'];
			if($color || $size){
				$all_color[$color] = [
					'name' 		=> $name,
					'image' 	=> $image,
					'url_key' 	=> $url_key,
				];
				$all_size[$size] = [
					'name' 		=> $name,
					'image' 	=> $image,
					'url_key' 	=> $url_key,
				];
				//echo $size.'#'.$current_size;
				//echo '<br/>';
				if($current_size == $size){
					$color_2_size[$color] = [
						'name' 		=> $name,
						'image' 	=> $image,
						'url_key' 	=> $url_key,
					]; 
				}
				if($current_color == $color){
					$size_2_color[$size] = [
						'name' 		=> $name,
						'image' 	=> $image,
						'url_key' 	=> $url_key,
					]; 
				}
			}
		}
		return [$all_color,$all_size,$color_2_size,$size_2_color ];
	}
	/**
	 * 这个有点像定制化的范畴，目前我只做了color和size这类
	 * 衣服类产品需要用到的属性，对于其他的类似属性，就需要自己做定制修改了
	 * @return 返回和当前产品spu相同的其他产品，然后以颜色和尺码的方式罗列出来。
	 */
	protected function getSameSpuInfo(){
		//echo $this->_product['sku'];
		//var_dump($this->_product);
		$current_color 	= isset($this->_product['color']) ? $this->_product['color'] :'';
		$current_size 	= isset($this->_product['size']) ? $this->_product['size'] : '';
		if(!$current_size && !$current_color){
			return ;
		}
		$data = $this->getSpuData();
		list($all_color,$all_size,$color_2_size,$size_2_color) = $this->getColorAndSizeInfo($data,$current_size,$current_color);
		$all_size = $this->sortSizeArr($all_size); 
		return [$current_color,$current_size,$all_color,$all_size,$color_2_size,$size_2_color];
	}
	/**
	 *	@property $data | Array  各个尺码对应的产品数组
	 *  @return Array 排序后的数组
	 *		该函数，按照在配置中的size的顺序，将$data中的数据进行排序，让其按照尺码的由小到大的顺序
	 * 		排列，譬如 ：s,m,l,xl,xxl,xxxl等
	 */
	protected function sortSizeArr($data){
		# 对size排序一下
		$size = [];
		$attr_group = $this->_product['attr_group'];
		$attrInfo = Yii::$service->product->getGroupAttrInfo($attr_group);
		$size_arr = isset($attrInfo['size']['display']['data']) ? $attrInfo['size']['display']['data'] : '';
		$d_arr = [];
		if(is_array($size_arr) && !empty($size_arr)){
			foreach($size_arr as $size){
				if(isset($data[$size])){
					$d_arr[$size] = $data[$size];
				}
			}
			return $d_arr;
		}
		return $data;
	}
	/**
	 * @return  Array
	 *  得到当前货币状态下的产品的价格和特价信息。
	 */
	protected function getProductPriceInfo(){
		$price			= $this->_product['price'];
		$special_price	= $this->_product['special_price'];
		$special_from	= $this->_product['special_from'];
		$special_to		= $this->_product['special_to'];
		return Yii::$service->product->price->getCurrentCurrencyProductPriceInfo($price,$special_price,$special_from,$special_to);
		
	}
	/**
	 * 初始化数据包括 
	 * 主键值：$this->_primaryVal
	 * 当前产品对象：$this->_product
	 * Meta keywords ， Meta Description等信息的设置。
	 * Title的设置。
	 * 根据当前产品的attr_group(属性组信息)重新给Product Model增加相应的属性组信息
	 * 然后，重新获取当前产品对象：$this->_product，此时加入了配置中属性组对应的属性。
	 *
	 */
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
		
		# 通过上面查询的属性组，得到属性组对应的属性列表
		# 然后重新查询产品
		$attr_group = $this->_product['attr_group'];
		$attrInfo = Yii::$service->product->getGroupAttrInfo($attr_group);
		if(is_array($attrInfo) && !empty($attrInfo)){
			$attrs = array_keys($attrInfo);
			\fecshop\models\mongodb\Product::addCustomProductAttrs($attrs);
		}
		# 重新查询产品信息。
		$product 	= Yii::$service->product->getByPrimaryKey($primaryVal);
		$this->_product = $product ;
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
