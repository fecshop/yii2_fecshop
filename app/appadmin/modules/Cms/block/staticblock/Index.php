<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Cms\block\staticblock;
use Yii;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
/**
 * block cms\staticblock
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
		 * edit data url
		 */
		$this->_editUrl 	= CUrl::getUrl("cms/staticblock/manageredit");
		/**
		 * delete data url
		 */
		$this->_deleteUrl 	= CUrl::getUrl("cms/staticblock/managerdelete");
		/**
		 * service component, data provider
		 */
		$this->_service = Yii::$service->cms->staticblock;
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
			[	# 字符串类型
				'type'=>'inputtext',
				'title'=>'标识符',
				'name'=>'identify' ,
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
				'orderField'	=> 'identify',
				'label'			=> '标识符',
				'width'			=> '50',
				'align' 		=> 'left',
				'lang'			=> false,
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
			$str .= '<td>
						<a title="编辑" target="dialog" class="btnEdit" mask="true" drawable="true" width="1000" height="580" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" >编辑</a>
						<a title="删除" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$csrfString.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel">删除</a>
					</td>';
			$str .= '</tr>';
		}
		return $str ;
		
	}
	
	
	
}