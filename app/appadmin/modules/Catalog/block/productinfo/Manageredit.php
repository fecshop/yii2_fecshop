<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\block\productinfo;
use Yii;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\Catalog\block\productinfo\index\Attr;
/**
 * block catalog/productinfo
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit  extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
	public  $_saveUrl;
	protected $_attr;
	
	public function init(){
		$this->_saveUrl = CUrl::getUrl('catalog/productinfo/managereditsave');
		parent::init();
		$this->_attr = new Attr($this->_one);
		//$this->_param		= $request_param[$this->_editFormData];
	}
	
	public function setService(){
		$this->_service 	= Yii::$service->product;
	}
	
	public function getCurrentProductPrimay(){
		$primaryKey = Yii::$service->product->getPrimaryKey();
		$primaryVal = CRequest::param($primaryKey);
		if($primaryVal){
			return $primaryKey.'='.$primaryVal;
		}
		return '';
	}
	
	# 传递给前端的数据 显示编辑form	
	public function getLastData(){
		
		return [
			'baseInfo' 		=> $this->getBaseInfo(),
			'priceInfo' 	=> $this->getPriceInfo(),
			'metaInfo' 		=> $this->getMetaInfo(),
			'groupAttr'		=> $this->getGroupAttr(),
			'descriptionInfo' => $this->getDescriptionInfo(),
			'attrGroup'		=> $this->_attr->getProductAttrGroupSelect(),
			'primaryInfo' 	=> $this->getCurrentProductPrimay(),
			'img_html'		=> $this->getImgHtml(),
			'custom_option' => $this->_one['custom_option'],
			'product_id' 	=> $this->_one[Yii::$service->product->getPrimaryKey()],
			//'editBar' 	=> $this->getEditBar(),
			//'textareas'	=> $this->_textareas,
			//'lang_attr'	=> $this->_lang_attr,
			'saveUrl' 	=> $this->_saveUrl,
		];
	}
	
	
	public function getBaseInfo(){
		$this->_lang_attr = '';
		$this->_textareas = '';
		$editArr = $this->_attr->getBaseInfo();
		$editBar = $this->getEditBar($editArr);
		return $this->_lang_attr.$editBar.$this->_textareas;
	}
	
	public function getPriceInfo(){
		$this->_lang_attr = '';
		$this->_textareas = '';
		$editArr = $this->_attr->getPriceInfo();
		$editBar = $this->getEditBar($editArr);
		return $this->_lang_attr.$editBar.$this->_textareas;
	}
	
	public function getMetaInfo(){
		$this->_lang_attr = '';
		$this->_textareas = '';
		$editArr = $this->_attr->getMetaInfo();
		$editBar = $this->getEditBar($editArr);
		return $this->_lang_attr.$editBar.$this->_textareas;
	}
	
	public function getDescriptionInfo(){
		$this->_lang_attr = '';
		$this->_textareas = '';
		$editArr = $this->_attr->getDescriptionInfo();
		$editBar = $this->getEditBar($editArr);
		return $this->_lang_attr.$editBar.$this->_textareas;
	}
	
	
	public function getGroupAttr(){
		$this->_lang_attr = '';
		$this->_textareas = '';
		$editArr = $this->_attr->getGroupAttr();
		//var_dump($editArr);
		//var_dump($this->_one);
		$this->_primaryKey  = $this->_service->getPrimaryKey();
		$id 				= $this->_param[$this->_primaryKey];
		$this->_one = $this->_service->getByPrimaryKey($id);
		if(!empty($editArr)){
			$editBar = $this->getEditBar($editArr);
			return $this->_lang_attr.$editBar.$this->_textareas;
		}
		return '';
		
	}
	
	public function getImgHtml(){
		if(isset($this->_one['image']['main']) && !empty($this->_one['image']['main'])){
			$main_image = $this->_one['image']['main'];
		}
		
		if(isset($this->_one['image']['gallery']) && !empty($this->_one['image']['gallery'])){
			$gallery_image = $this->_one['image']['gallery'];
		}
		
		
		$str =
		'<div>
			
			<table class="list productimg" width="100%" >
				<thead>
					<tr>
						<td>图片</td>
						<td>label</td>
						<td>sort_order</td>
						<td>主图</td>
						<td>删除</td>
					</tr>
				</thead>
				<tbody>';
				if(!empty($main_image) && is_array($main_image)){
					$str .='<tr class="p_img" rel="1" style="border-bottom:1px solid #ccc;">
						<td style="width:120px;text-align:center;"><img  rel="'.$main_image['image'].'" style="width:100px;height:100px;" src="'.Yii::$service->product->image->getUrl($main_image['image']).'"></td>
						<td style="width:220px;text-align:center;"><input style="height:18px;width:200px;" type="text" class="image_label" name="image_label"  value="'.$main_image['label'].'" /></td>
						<td style="width:220px;text-align:center;"><input style="height:18px;width:200px;" type="text" class="sort_order"  name="sort_order" value="'.$main_image['sort_order'].'"  /></td>
						<td style="width:30px;text-align:center;"><input type="radio" name="image" checked  value="'.$main_image['image'].'" /></td>
						<td style="padding:0 0 0 20px;"><a class="delete_img btnDel" href="javascript:void(0)">删除</a></td>
					</tr>';
				}
					if(!empty($gallery_image) && is_array($gallery_image)){
						$i=2;
						foreach($gallery_image as $gallery){
							$str .='<tr class="p_img" rel="'.$i.'" style="border-bottom:1px solid #ccc;">
									<td style="width:120px;text-align:center;"><img  rel="'.$gallery['image'].'" style="width:100px;height:100px;" src="'.Yii::$service->product->image->getUrl($gallery['image']).'"></td>
									<td style="width:220px;text-align:center;"><input style="height:18px;width:200px;" type="text" class="image_label" name="image_label"  value="'.$gallery['label'].'" /></td>
									<td style="width:220px;text-align:center;"><input style="height:18px;width:200px;" type="text" class="sort_order"  name="sort_order" value="'.$gallery['sort_order'].'"  /></td>
									<td style="width:30px;text-align:center;"><input type="radio" name="image"   value="'.$gallery['image'].'" /></td>
									<td style="padding:0 0 0 20px;"><a class="delete_img btnDel" href="javascript:void(0)">删除</a></td>
								</tr>';
							$i++;
						}
						
					}
														
		$str .=	'</tbody>
			</table>
		</div>';
		return $str;
	}
	
	
	
	public function getEditArr(){
		return [
			[
				'label'=>'标题',
				'name'=>'title',
				'display'=>[
					'type' => 'inputString',
					'lang' => true,
				],
				'require' => 1,
			],
			
			[
				'label'=>'Url Key',
				'name'=>'url_key',
				'display'=>[
					'type' => 'inputString',
				],
				'require' => 0,
			],
			
			[
				'label'=>'Meta Keywords',
				'name'=>'meta_keywords',
				'display'=>[
					'type' => 'inputString',
					'lang' => true,
				],
				'require' => 0,
			],
			
			[
				'label'=>'Meta Description',
				'name'=>'meta_description',
				'display'=>[
					'type' => 'textarea',
					'lang' => true,
					'rows'	=> 14,
					'cols'	=> 110,
				],
				'require' => 0,
			],
			
			[
				'label'=>'Content',
				'name'=>'content',
				'display'=>[
					'type' => 'textarea',
					'lang' => true,
					'rows'	=> 14,
					'cols'	=> 110,
				],
				'require' => 0,
			],
			
			[
				'label'=>'用户状态',
				'name'=>'status',
				'display'=>[
					'type' => 'select',
					'data' => [
						1 	=> '激活',
						2 	=> '关闭',
					]
				],
				'require' => 1,
				'default' => 1,
			],
		];
	}
	/**
	 * save article data,  get rewrite url and save to article url key.
	 */
	public function save(){
		
		$this->initParamType();
		/**
		 * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
		 * you must convert string datetime to time , use strtotime function.
		 */
		// var_dump()
		$this->_service->save($this->_param,'catalog/product/index');
		$errors = Yii::$service->helper->errors->get();
		if(!$errors ){
			echo  json_encode(array(
				"statusCode"=>"200",
				"message"=>"save success",
			));
			exit;
		}else{
			echo  json_encode(array(
				"statusCode"=>"300",
				"message"=>$errors,
			));
			exit;
		}
		
	}
	
	protected function initParamType(){
		$request_param 		= CRequest::param();
		$this->_param		= $request_param[$this->_editFormData];
		$this->_param['attr_group'] 	= CRequest::param('attr_group');
		$this->_param['custom_option'] 	= ($custom_option = CRequest::param('custom_option')) ? json_decode($custom_option,true) : '';
		$image_gallery 		= CRequest::param('image_gallery');
		$image_main 		= CRequest::param('image_main');
		$save_gallery = [];
		// Category
		$category 	= CRequest::param('category');
		if($category){
			$category = explode(',',$category);
			if(!empty($category )){
				$cates = [];
				foreach($category  as $cate){
					if($cate){
						$cates[] = $cate;
					}
				}
				$this->_param['category'] =  $cates;
			}else{
				$this->_param['category'] = [];
			}
		}else{
			$this->_param['category'] = [];
		}
		// init image gallery
		if($image_gallery){
			$image_gallery_arr = explode("|||||",$image_gallery);
			if(!empty($image_gallery_arr)){
				foreach($image_gallery_arr as $one){
					if(!empty($one)){
						list($gallery_image,$gallery_label,$gallery_sort_order) = explode('#####',$one);
						$save_gallery[] = [
							'image' 		=> $gallery_image,
							'label' 		=> $gallery_label,
							'sort_order' 	=> $gallery_sort_order,
						];	
					}
				}
				$this->_param['image']['gallery'] 	= $save_gallery;
			}
		}
		// init image main
		if($image_main){
			list($main_image,$main_label,$main_sort_order) = explode('#####',$image_main);
			$save_main = [
				'image' 		=> $main_image,
				'label' 		=> $main_label,
				'sort_order' 	=> $main_sort_order,
			];
			$this->_param['image']['main'] 	= $save_main;
		}
		//qty
		$this->_param['qty'] = $this->_param['qty'] ? (float)($this->_param['qty']) : 0;
		//is_in_stock
		$this->_param['is_in_stock'] = $this->_param['is_in_stock'] ? (int)($this->_param['is_in_stock']) : 0;
		//price
		$this->_param['cost_price'] = $this->_param['cost_price'] ? (float)($this->_param['cost_price']) : 0;
		$this->_param['price'] = $this->_param['price'] ? (float)($this->_param['price']) : 0;
		$this->_param['special_price'] = $this->_param['special_price'] ? (float)($this->_param['special_price']) : 0;
		//date
		$this->_param['new_product_from'] = $this->_param['new_product_from'] ? (float)(strtotime($this->_param['new_product_from'])) : 0;
		$this->_param['new_product_to'] = $this->_param['new_product_to'] ? (float)(strtotime($this->_param['new_product_to'])) : 0;
		$this->_param['special_from'] = $this->_param['special_from'] ? (float)(strtotime($this->_param['special_from'])) : 0;
		$this->_param['special_to'] = $this->_param['special_to'] ? (float)(strtotime($this->_param['special_to'])) : 0;
		//weight
		$this->_param['weight'] = $this->_param['weight'] ? (float)($this->_param['weight']) : 0;
		//status
		$this->_param['status'] = $this->_param['status'] ? (float)($this->_param['status']) : 0;
		//image main sort order
		if(isset($this->_param['image']['main']['sort_order']) && !empty($this->_param['image']['main']['sort_order'])){
			$this->_param['image']['main']['sort_order'] = (int)($this->_param['image']['main']['sort_order']);
		}
		//image gallery 
		if(isset($this->_param['image']['gallery']) && is_array($this->_param['image']['gallery']) && !empty($this->_param['image']['gallery'])){
			$gallery_af = [];
			foreach($this->_param['image']['gallery'] as $gallery){
				if(isset($gallery['sort_order']) && !empty($gallery['sort_order'])){
					$gallery['sort_order'] = (int)$gallery['sort_order'];
				}
				$gallery_af[] = $gallery;
			}
			$this->_param['image']['gallery'] = $gallery_af;
		}
		# 自定义属性 也就是在 @common\config\fecshop_local_services\Product.php 产品服务的 customAttrGroup 配置的产品属性。
		$custom_attr = \Yii::$service->product->getGroupAttrInfo($this->_param['attr_group']);
		if(is_array($custom_attr) && !empty($custom_attr)){
			foreach($custom_attr as $attrInfo){
				$attr 	= $attrInfo['name'];
				$dbtype = $attrInfo['dbtype'];
				if(isset($this->_param[$attr]) && !empty($this->_param[$attr])){
					if($dbtype == 'Int'){
						if(isset($attrInfo['display']['lang']) && $attrInfo['display']['lang']){
							$langs = Yii::$service->fecshoplang->getAllLangCode();
							if(is_array($langs) && !empty($langs)){
								foreach($langs as $langCode){
									$langAttr = Yii::$service->fecshoplang->getLangAttrName($attr,$langCode);
									if(isset($this->_param[$attr][$langAttr]) && $this->_param[$attr][$langAttr]){
										$this->_param[$attr][$langAttr] = (int)$this->_param[$attr][$langAttr];
									}
								}
							}
						}else{
							$this->_param[$attr] = (Int)$this->_param[$attr];
						}
					}
					if($dbtype == 'Float'){
						if(isset($attrInfo['display']['lang']) && $attrInfo['display']['lang']){
							$langs = Yii::$service->fecshoplang->getAllLangCode();
							if(is_array($langs) && !empty($langs)){
								foreach($langs as $langCode){
									$langAttr = Yii::$service->fecshoplang->getLangAttrName($attr,$langCode);
									if(isset($this->_param[$attr][$langAttr]) && $this->_param[$attr][$langAttr]){
										$this->_param[$attr][$langAttr] = (float)$this->_param[$attr][$langAttr];
									}
								}
							}
						}else{
							$this->_param[$attr] = (Float)$this->_param[$attr];
						}
					}
				}
			}
		}
		$custom_option = isset($this->_param['custom_option']) ? $this->_param['custom_option'] : ''; 
		if(is_array($custom_option) && !empty($custom_option) ){
			$custom_option_af = [];
			foreach($custom_option as $option){
				$option['is_require'] = isset($option['is_require']) ? (int)$option['is_require'] : 0;
				$option['sort_order'] = isset($option['sort_order']) ? (int)$option['sort_order'] : 0;
				$data = isset($option['data']) ? $option['data'] : '';
				$data_af = [];
				if(!empty($data) && is_array($data)){
					foreach($data as $d){
						$d['price'] = isset($d['price']) ? (float)$d['price'] : 0;
						$d['sort_order'] = isset($d['sort_order']) ? (int)$d['sort_order'] : 0;
						$data_af[] = $d;
					}
				}
				$option['data'] = $data_af;
				$custom_option_af[] = $option;
			}
			$this->_param['custom_option'] = $custom_option_af;
		}
	}
	
	# 批量删除
	public function delete(){
		$ids = '';
		if($id = CRequest::param($this->_primaryKey)){
			$ids = $id;
		}else if($ids = CRequest::param($this->_primaryKey.'s')){
			$ids = explode(',',$ids);
		}
		$this->_service->remove($ids);
		$errors = Yii::$service->helper->errors->get();
		if(!$errors ){
			echo  json_encode(array(
				"statusCode"=>"200",
				"message"=>"remove data  success",
			));
			exit;
		}else{
			echo  json_encode(array(
				"statusCode"=>"300",
				"message"=>$errors,
			));
			exit;
		}	
	}
}



