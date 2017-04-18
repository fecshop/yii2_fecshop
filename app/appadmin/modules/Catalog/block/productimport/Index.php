<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\block\productimport;
use Yii;
use fec\helpers\CExcel;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use fec\helpers\CUrl;
use fecshop\app\appadmin\modules\Catalog\helper\Product as ProductHelper;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
/**
 * block cms\article
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
	public $_param;
	
	public function getLastData(){
		return [];
		
	}
	
	public function importProductByExcel(){
		set_time_limit(0);
        ini_set('memory_limit', '2048M');
        $name = $_FILES["file"]["name"];
        $error = $_FILES["file"]["error"];
        switch ($error) {
            case 0:
                break;
            case 1:
                echo json_encode(
                    ['status' => 'fail', 'content' => '文件过大，请重新上传不超过5M的文件！']
                );
                exit;
            case 2:
                echo json_encode(
                    ['status' => 'fail', 'content' => '文件过大，请重新上传不超过5M的文件！']
                );
                exit;
            case 3:
                echo json_encode(
                    ['status' => 'fail', 'content' => '文件上传失败，请重新上传']
                );
                exit;
            case 4:
                echo json_encode(['status' => 'fail', 'content' => '上传文件不存在']);
                exit;
            case 5:
                echo json_encode(['status' => 'fail', 'content' => '文件上传大小为0']);
                exit;

        }

        if (strstr($name, '.xlsx') || strstr($name, '.xls')) {
            $dir = Yii::getAlias('@appadmin/uploads/product');
            if (!is_dir($dir)) {
                $res = mkdir($dir, 0777, true);
                if (!$res) {
                    echo json_encode(
                        ['status'  => 'fail',
                         'content' => $dir . '文件夹创建失败，请手动创建,并设置777权限']
                    );
                    exit;
                }
                
            }
			$fileName = 'product_'.time().'_'. $name;
            $fileFullDir = $dir.'/'.$fileName;
            $result = @move_uploaded_file(
                $_FILES["file"]["tmp_name"], $fileFullDir
            );
            if ($result) {
                $data = CExcel::getExcelContent($fileFullDir);
                foreach ($data as  $one) {
                    $this->saveProduct($one);
                }
            } else {
                \Yii::info('文件上传失败', 'appCustom');
                echo json_encode(
                    ['status' => 'fail', 'content' => '上传失败，请重新上传！']
                );
            }
        } else {
            echo json_encode(['status' => 'fail', 'content' => '文件格式不对']);
        }
        exit;
		
	}
	
	
	public function saveProduct($one){
		$this->_param = $one
		$this->initParamType();
		/**
		 * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
		 * you must convert string datetime to time , use strtotime function.
		 */
		// var_dump()
		
		if(Yii::$app->request->post('operate') == 'copy'){
			
			if(isset($this->_param['_id'])){
				unset($this->_param['_id']);
				//echo 111;
				//var_dump($this->_param);
				//exit;
			}	
		}
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
		//$this->_param['attr_group'] 	= CRequest::param('attr_group');
		/*
			$custom_option = CRequest::param('custom_option');
			//var_dump($custom_option);
			$custom_option	= $custom_option ? json_decode($custom_option,true) : [];
			$custom_option_arr = [];
			if(is_array($custom_option) && !empty($custom_option)){
				foreach($custom_option as $one){
					$one['qty'] 	= (int)$one['qty'];
					$one['price'] 	= (float)$one['price'];
					$custom_option_arr[$one['sku']] = $one;
				}
			}
		*/
		$this->_param['custom_option'] = $custom_option_arr;
		
		
		// 分类部分
		/*
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
		*/
		$this->_param['category'];
		
		
		
		/* 图片部分
		$image_gallery 		= CRequest::param('image_gallery');
		$image_main 		= CRequest::param('image_main');
		$save_gallery = [];
		
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
		*/
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
		$this->_param['image']['gallery'] 	= $save_gallery;
		$this->_param['image']['main'] 	= $save_main;
		
		
		
		
		
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
		$this->_param['score'] = $this->_param['score']  ? (int)($this->_param['score']) : 0;
		//status
		$this->_param['status'] = $this->_param['status'] ? (float)($this->_param['status']) : 0;
		
		
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
		
		#tier price
		$tier_price = $this->_param['tier_price'];
		$tier_price_arr = [];
		if($tier_price){
			$arr = explode('||',$tier_price);
			if(is_array($arr) && !empty($arr)){
				foreach($arr as $ar){
					list($tier_qty,$tier_price) = explode('##',$ar);
					if($tier_qty && $tier_price){
						$tier_qty = (int)$tier_qty;
						$tier_price = (float)$tier_price;
						$tier_price_arr[] = [
							'qty' 	=> $tier_qty,
							'price'	=> $tier_price,
						];
						
					}
				}
			}
		}
		$tier_price_arr = \fec\helpers\CFunc::array_sort($tier_price_arr,'qty','asc');
		$this->_param['tier_price'] = $tier_price_arr;
		
	}
}