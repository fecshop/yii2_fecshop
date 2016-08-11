<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\block\category;
use Yii;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
/**
 * block cms\article
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Product extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
	/**
	 * init param function ,execute in construct
	 */
	public function init(){
		/**
		 * edit data url
		 */
		$this->_editUrl 	= CUrl::getUrl("cms/article/manageredit");
		/**
		 * delete data url
		 */
		$this->_deleteUrl 	= CUrl::getUrl("cms/article/managerdelete");
		/**
		 * service component, data provider
		 */
		$this->_service = Yii::$service->cms->article;
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
			[	# selecit的Int 类型
				'type'=>'select',	 
				'title'=>'状态',
				'name'=>'status',
				'columns_type' =>'int',  # int使用标准匹配， string使用模糊查询
				'value'=> [					# select 类型的值
					1=>'激活',
					2=>'关闭',
				],
			],
			[	# 字符串类型
				'type'=>'inputtext',
				'title'=>'标题',
				'name'=>'title' ,
				'columns_type' =>'string'
			],
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
				'orderField'	=> 'title',
				'label'			=> '标题',
				'width'			=> '50',
				'align' 		=> 'left',
				'lang'			=> true,
			],
			[	
				'orderField'	=> 'created_user_id',
				'label'			=> '创建人',
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
		foreach($data as $one){
			$user_ids[]=$one['created_user_id'];
		}
		$users = Yii::$service->adminUser->getIdAndNameArrByIds($user_ids);
		foreach($data as $one){
			$str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
			$str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
			foreach($fileds as $field){
				$orderField = $field['orderField'];
				$display	= $field['display'];
				$val = $one[$orderField];
				if($orderField == 'created_user_id'){
					$val = isset($users[$val]) ? $users[$val] : $val;
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
			
			$str .= '</tr>';
		}
		return $str ;
		
	}
	
	
	public function getTableTheadHtml($table_th_bar){
		$table_th_bar = $this->getTableTheadArrInit($table_th_bar);
		$this->_param['orderField'] 	= $this->_param['orderField'] 		? $this->_param['orderField'] : $this->_primaryKey;
		$this->_param['orderDirection'] = $this->_param['orderDirection'] ;
		foreach($table_th_bar as $k => $field){
			if($field['orderField'] == $this->_param['orderField']){
				$table_th_bar[$k]['class'] = $this->_param['orderDirection'];
			}	
		}
		$str = '<thead><tr>';
		$str .= '<th width="22"><input type="checkbox" group="'.$this->_primaryKey.'s" class="checkboxCtrl"></th>';
		foreach($table_th_bar as $b){
			$width = $b['width'];
			$label = $b['label'];
			$orderField = $b['orderField'];
			$class = isset($b['class']) ? $b['class'] : '';
			$align = isset($b['align']) ? 'align="'.$b['align'].'"' : '';
			$str .= '<th width="'.$width.'" '.$align.' orderField="'.$orderField.'" class="'.$class.'">'.$label.'</th>';
		}
		//$str .= '<th width="80" >编辑</th>';
		$str .= '</tr></thead>';
		return $str;
	}
	
	
	/**
	 * @property $data|Array 
	 */
	public function getDbSearchBarHtml($data){
		$searchBar = '';
		if(!empty($data)){
			$searchBar .= '<input type="hidden" name="search_type" value="search"  />';	
			$searchBar .='<table class="searchContent">
					<tr>';
			foreach($data as $d){
				$type = $d['type'];
				$name = $d['name'];
				$title = $d['title'];
				$value = $d['value'];
				if($d['type'] == 'select'){
					$searchBar .=	'<td>
										'.$value.'
									</td>';
				}else if($d['type'] == 'chosen_select'){
					$searchBar .=	'<td>
										'.$value.'
									</td>';
				}else if($d['type'] == 'inputtext'){
					$searchBar .=	'<td>
										'.$title.':<input type="text" value="'.(is_array($this->_param[$name]) ? $this->_param[$name]['?regex'] : $this->_param[$name]).'" name="'.$name.'" />
									</td>';
				}else if($d['type'] == 'inputdate'){
					$searchBar .=	'<td>
										'.$title.'<input type="text" value="'.$this->_param[$name].'" name="'.$name.'"  class="date" readonly="true" />
									</td>';
				}else if($d['type'] == 'inputdatefilter'){
					$value = $d['value'];
					if(is_array($value)){
						foreach($value as $t=>$title){
							$searchBar .=	'<td>
								'.$title.'<input type="text" value="'.$this->_param[$name.'_'.$t].'" name="'.$name.'_'.$t.'"  class="date" readonly="true" />
							</td>';
						}
					}
				}else if($d['type'] == 'inputfilter'){
					$value = $d['value'];
					if(is_array($value)){
						foreach($value as $t=>$title){
							$searchBar .=	'<td>
								'.$title.'<input type="text" value="'.$this->_param[$name.'_'.$t].'" name="'.$name.'_'.$t.'"    />
							</td>';
						}
					}
				}
			}
			$customSearchHtml = $this->customSearchBarHtml();	
			$searchBar .= $customSearchHtml;
			$searchBar .=	'</tr>
				</table>
				<div class="subBar">
					<ul>
						<li><a class="button productSearch" ><span>检索</span></a></li>
					</ul>
				</div>';
		}	
		return $searchBar;	
	}
	
	
}