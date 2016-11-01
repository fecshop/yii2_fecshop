<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\block\productfavorite;
use Yii;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
/**
 * block cms\article
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
	/**
	 * init param function ,execute in construct
	 */
	public function init(){
		/**
		 * service component, data provider
		 */
		$this->_service = Yii::$service->product->favorite;
		parent::init();
		
	}
	
	public function getLastData(){
		
		# hidden section ,that storage page info
		$pagerForm = $this->getPagerForm();  
		# search section
		$searchBar = $this->getSearchBar();
		# edit button, delete button,
		$editBar = $this->getEditBar();
		# table head
		$thead = $this->getTableThead();
		# table body
		$tbody = $this->getTableTbody();
		# paging section
		$toolBar = $this->getToolBar($this->_param['numCount'],$this->_param['pageNum'],$this->_param['numPerPage']); 
		return [
			'pagerForm'	 	=> $pagerForm,
			'searchBar'		=> $searchBar,
			'editBar'		=> $editBar,
			'thead'		=> $thead,
			'tbody'		=> $tbody,
			'toolBar'	=> $toolBar,
		];
	}
	
	
	/**
	 * get search bar Arr config
	 */
	public function getSearchArr(){
		$data = [
			/*
			[	# 字符串类型
				'type'=>'inputtext',
				'title'=>'product_id',
				'name'=>'product_id' ,
				'columns_type' =>'string'
			],
			*/
			[	# 时间区间类型搜索
				'type'=>'inputdatefilter',
				'name'=> 'created_at',
				'columns_type' =>'int',
				'value'=>[
					'gte'=>'用户创建时间开始',
					'lt' =>'用户创建时间结束',
				]
			],
		];
		return $data;
	}
	
	
	/**
	 * config function ,return table columns config.
	 * 
	 */
	public function getTableFieldArr(){
		$table_th_bar = [
			[	
				'orderField' 	=> $this->_primaryKey,
				'label'			=> 'ID',
				'width'			=> '50',
				'align' 		=> 'center',
				
			],
			[	
				'orderField'	=> 'product_id',
				'label'			=> 'product',
				'width'			=> '50',
				'align' 		=> 'left',
			],
			[	
				'orderField'	=> 'user_id',
				'label'			=> 'user',
				'width'			=> '110',
				'align' 		=> 'center',
			],
			[	
				'orderField'	=> 'store',
				'label'			=> 'store',
				'width'			=> '110',
				'align' 		=> 'center',
			],
			[	
				'orderField'	=> 'created_at',
				'label'			=> '创建时间',
				'width'			=> '110',
				'align' 		=> 'center',
				'convert'		=> ['int' => 'datetime'],
			],
			[	
				'orderField'	=> 'updated_at',
				'label'			=> '更新时间',
				'width'			=> '110',
				'align' 		=> 'center',
				'convert'		=> ['int' => 'datetime'],
			],
			
		];
		return $table_th_bar ;
	}
	
	
	/**
	 * rewrite parent getTableTbodyHtml($data)
	 */
	public function getTableTbodyHtml($data){
		$fileds = $this->getTableFieldArr();
		$str .= '';
		$csrfString = \fec\helpers\CRequest::getCsrfString();
		$user_ids = [];
		$product_ids = [];
		foreach($data as $one){
			$user_ids[] = $one['user_id'];
			$product_ids[] = $one['product_id'];
		}
		$users = Yii::$service->customer->getEmailByIds($user_ids);
		
		$products =  Yii::$service->product->getSkusByIds($product_ids);
		
		foreach($data as $one){
			$str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
			$str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
			foreach($fileds as $field){
				$orderField = $field['orderField'];
				$display	= $field['display'];
				$val = $one[$orderField];
				if($orderField == 'user_id'){
					$val = isset($users[$val]) ? $users[$val] : $val;
					$str .= '<td>'.$val.'</td>';
					continue;
				}
				if($orderField == 'product_id'){
					$val = isset($products[$val]) ? $products[$val] : $val;
					$str .= '<td>'.$val.'</td>';
					continue;
				}
				if($val){
					if(isset($field['display']) && !empty($field['display'])){
						$display = $field['display'];
						$val = $display[$val] ? $display[$val] : $val;
					}
					if(isset($field['convert']) && !empty($field['convert'])){
						$convert = $field['convert'];
						foreach($convert as $origin =>$to){
							if(strstr($origin,'mongodate')){
								if(isset($val->sec)){
									$timestramp = $val->sec;
									if($to == 'date'){
										$val = date('Y-m-d',$timestramp);
									}else if($to == 'datetime'){
										$val = date('Y-m-d H:i:s',$timestramp);
									}else if($to == 'int'){
										$val = $timestramp;
									}
								}
							}else if(strstr($origin,'date')){
								if($to == 'date'){
									$val = date('Y-m-d',strtotime($val));
								}else if($to == 'datetime'){
									$val = date('Y-m-d H:i:s',strtotime($val));
								}else if($to == 'int'){
									$val = strtotime($val);
								}
							}else if($origin == 'int'){
								if($to == 'date'){
									$val = date('Y-m-d',$val);
								}else if($to == 'datetime'){
									$val = date('Y-m-d H:i:s',$val);
								}else if($to == 'int'){
									$val = $val;
								}
							}else if($origin == 'string'){
								if($to == 'img'){
									
									$t_width = isset($field['img_width']) ? $field['img_width'] : '100';
									$t_height = isset($field['img_height']) ? $field['img_height'] : '100';
									$val = '<img style="width:'.$t_width.'px;height:'.$t_height.'px" src="'.$val.'" />';;
								}
							}
						}
					}
					
					if(isset($field['lang']) && !empty($field['lang'])){
						//var_dump($val);
						//var_dump($orderField);
						$val = Yii::$service->fecshoplang->getDefaultLangAttrVal($val,$orderField);
					}
				}
				$str .= '<td>'.$val.'</td>';
			}
			$str .= '<td>
						<a title="编辑" target="dialog" class="btnEdit" mask="true" drawable="true" width="1000" height="580" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" >编辑</a>
						<a title="删除" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$csrfString.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel">删除</a>
					</td>';
			$str .= '</tr>';
		}
		return $str ;
		
	}
	
	
	
}