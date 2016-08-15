<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\block\productinfo\index;
use Yii;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
/**
 * block catalog/productinfo
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Attr 
{
	
	public function getProductAttrGroupSelect(){
		$attrGroup = Yii::$service->product->getCustomAttrGroup();
		$str = '';
		$currentAttrGroup = CRequest::param('attr_group');
		$currentAttrGroup = $currentAttrGroup ? $currentAttrGroup : Yii::$service->product->getDefaultAttrGroup();
		if(is_array($attrGroup) && !empty($attrGroup)){
			$str .= '<select name="attr_group" class="attr_group required">';
		
			foreach($attrGroup as $k=>$v){
				if($currentAttrGroup == $v){
					$str .= '<option value="'.$v.'" selected="selected">'.$v.'</option>';
				}else{
					$str .= '<option value="'.$v.'" >'.$v.'</option>';
				
				}
				
			}
			$str .= '</select>';
		}
		return $str;
	}
	
	
	public function getBaseInfo(){
		return [
			[
				'label'=>'产品名字',
				'name'=>'name',
				'display'=>[
					'type' => 'inputString',
					'lang' => true,
				],
				'require' => 1,
			],
			[
				'label'=>'SPU',
				'name'=>'spu',
				'display'=>[
					'type' => 'inputString',
					'lang' => false,
					
				],
				'require' => 0,
			],
			[
				'label'=>'SKU',
				'name'=>'spu',
				'display'=>[
					'type' => 'inputString',
					'lang' => false,
					
				],
				'require' => 0,
			],
			[
				'label'=>'重量',
				'name'=>'weight',
				'display'=>[
					'type' => 'inputString',
					'lang' => false,
					
				],
				'require' => 0,
			],
			
			[
				'label'=>'分类状态',
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
			
			[
				'label'=>'Url Key',
				'name'=>'url_key',
				'display'=>[
					'type' => 'inputString',
				],
				'require' => 0,
			],
			
			
			
			
		];
	}
	
	public function getMetaInfo(){
		return [
			[
				'label'=>'Meta Title',
				'name'=>'meta_title',
				'display'=>[
					'type' => 'inputString',
					'lang' => true,
					
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
					'cols'	=> 100,
				],
				'require' => 0,
			],
		];
	}
	
	
	public function getDescriptionInfo(){
		return [
			[
				'label'=>'产品Short描述',
				'name'=>'short_description',
				'display'=>[
					'type' => 'textarea',
					'lang' => true,
					'rows'	=> 14,
					'cols'	=> 100,
				],
				'require' => 0,
			],
			
			[
				'label'=>'产品描述',
				'name'=>'description',
				'display'=>[
					'type' => 'textarea',
					'lang' => true,
					'rows'	=> 14,
					'cols'	=> 100,
				],
				'require' => 0,
			],
		];
	}
	
	
	
	public function getCatalogInfo(){
		return [
		
		];
	}
	
}


?>