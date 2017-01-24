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
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use fec\helpers\CUrl;
use fecshop\app\appadmin\modules\Catalog\helper\Product as ProductHelper;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
/**
 * block cms\article
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
	protected $_copyUrl;
	/**
	 * init param function ,execute in construct
	 */
	public function init(){
		/**
		 * edit data url
		 */
		$this->_editUrl 	= CUrl::getUrl("catalog/productinfo/manageredit");
		/**
		 * delete data url
		 */
		$this->_deleteUrl 	= CUrl::getUrl("catalog/productinfo/managerdelete");
		$this->_copyUrl		= CUrl::getUrl("catalog/productinfo/manageredit",['operate'=>'copy']);
		/**
		 * service component, data provider
		 */
		$this->_service = Yii::$service->product;
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
				'value'=> ProductHelper::getStatusArr(),
			],
			[	# selecit的Int 类型
				'type'=>'select',	 
				'title'=>'库存状态',
				'name'=>'is_in_stock',
				'columns_type' =>'int',  # int使用标准匹配， string使用模糊查询
				'value'=> ProductHelper::getInStockArr(),
			],
			[	# 字符串类型
				'type'			=>'inputtext',
				'title'			=>'产品名称',
				'name'			=>'name' ,
				'columns_type' 	=>'string',
				'lang'			=> true,
			],
			[	# 字符串类型
				'type'=>'inputtext',
				'title'=>'Spu',
				'name'=>'spu' ,
				'columns_type' =>'string'
			],
			[	# 字符串类型
				'type'=>'inputtext',
				'title'=>'Sku',
				'name'=>'sku' ,
				'columns_type' =>'string'
			],
			[	# 时间区间类型搜索
				'type'=>'inputdatefilter',
				'name'=> 'updated_at',
				'columns_type' =>'int',
				'value'=>[
					'gte'=>'更新时间开始',
					'lt' =>'更新时间结束',
				]
			],
			[	# 时间区间类型搜索
				'type'=>'inputfilter',
				'name'=> 'qty',
				'columns_type' =>'int',
				'value'=>[
					'gte'=>'库存开始',
					'lt' =>'库存结束',
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
				'width'			=> '90',
				'align' 		=> 'center',
				
			],
			[	
				'orderField'	=> 'image_main',
				'label'			=> '图片',
				'width'			=> '50',
				'align' 		=> 'left',
				'lang'			=> true,
			],
			[	
				'orderField'	=> 'name',
				'label'			=> '标题',
				'width'			=> '250',
				'align' 		=> 'left',
				'lang'			=> true,
			],
			[	
				'orderField'	=> 'spu',
				'width'			=> '120',
				'align' 		=> 'center',
				
			],
			[	
				'orderField'	=> 'sku',
				'width'			=> '150',
				'align' 		=> 'center',
			],
			
			[	
				'orderField'	=> 'qty',
				'label'			=> '库存数',
				'width'			=> '50',
				'align' 		=> 'center',
			],
			
			[	
				'orderField'	=> 'weight',
				'label'			=> '重量',
				'width'			=> '50',
				'align' 		=> 'center',
			],
			
			[	
				'orderField'	=> 'status',
				'label'			=> '状态',
				'width'			=> '50',
				'align' 		=> 'center',
				'display'		=> ProductHelper::getStatusArr(),
			],
			
			[	
				'orderField'	=> 'cost_price',
				'label'			=> '成本价',
				'width'			=> '50',
				'align' 		=> 'center',
			],
			
			[	
				'orderField'	=> 'price',
				'label'			=> '销售价',
				'width'			=> '50',
				'align' 		=> 'center',
			],
			
			[	
				'orderField'	=> 'special_price',
				'label'			=> '特价',
				'width'			=> '50',
				'align' 		=> 'center',
			],
			
			[	
				'orderField'	=> 'created_user_id',
				'label'			=> '创建人',
				'width'			=> '50',
				'align' 		=> 'center',
			],
			[	
				'orderField'	=> 'created_at',
				'label'			=> '创建时间',
				'width'			=> '80',
				'align' 		=> 'center',
				'convert'		=> ['int' => 'datetime'],
			],
			[	
				'orderField'	=> 'updated_at',
				'label'			=> '更新时间',
				'width'			=> '80',
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
				$val = isset($one[$orderField]) ? $one[$orderField] : '';
				$display_title = '';
				if($orderField == 'created_user_id'){
					$val = isset($users[$val]) ? $users[$val] : $val;
					$display_title = $val;
					$str .= '<td><span title="'.$display_title.'">'.$val.'</span></td>';
					continue;
				}
				if($orderField == $this->_primaryKey){
					$display_title = $val;
					$str .= '<td><span style="width:60px;display:block;word-break:break-all;" title="'.$display_title.'">'.$val.'</span></td>';
					continue;
				}
				
				
				if($orderField == 'image_main'){
					if(isset($one['image']['main']['image'])){
						$val = $one['image']['main']['image'];
					}
					$imgUrl = Yii::$service->product->image->getUrl($val);
					$str .= '<td><span title="'.$imgUrl.'"><img style="width:100px;height:100px;" src="'.$imgUrl.'" /></span></td>';
					continue;
				}
				if($val){
					$display_title = $val;
					if(isset($field['display']) && !empty($field['display'])){
						$display = $field['display'];
						$val = $display[$val] ? $display[$val] : $val;
						$display_title = $val;
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
								$display_title = $val;
							}else if(strstr($origin,'date')){
								if($to == 'date'){
									$val = date('Y-m-d',strtotime($val));
								}else if($to == 'datetime'){
									$val = date('Y-m-d H:i:s',strtotime($val));
								}else if($to == 'int'){
									$val = strtotime($val);
								}
								$display_title = $val;
							}else if($origin == 'int'){
								if($to == 'date'){
									$val = date('Y-m-d',$val);
								}else if($to == 'datetime'){
									$val = date('Y-m-d H:i:s',$val);
								}else if($to == 'int'){
									$val = $val;
								}
								$display_title = $val;
							}else if($origin == 'string'){
								if($to == 'img'){
									
									$t_width = isset($field['img_width']) ? $field['img_width'] : '100';
									$t_height = isset($field['img_height']) ? $field['img_height'] : '100';
									$display_title = $val;
									$val = '<img style="width:'.$t_width.'px;height:'.$t_height.'px" src="'.$val.'" />';;
								}
							}
						}
					}
					
					if(isset($field['lang']) && !empty($field['lang'])){
						//var_dump($val);
						//var_dump($orderField);
						$val = Yii::$service->fecshoplang->getDefaultLangAttrVal($val,$orderField);
						$display_title = $val;
					}
				}
				$str .= '<td><span title="'.$display_title.'">'.$val.'</span></td>';
			}
			$str .= '<td>
						<a title="编辑" target="dialog" class="btnEdit" mask="true" drawable="true" width="1000" height="580" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" >编辑</a>
						<a title="删除" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$csrfString.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel">删除</a>
						<br/>
						<a title="复制" target="dialog" class="button" mask="true" drawable="true" width="1000" height="580" href="'.$this->_copyUrl.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" ><span>复制</span></a>
					</td>';
			$str .= '</tr>';
		}
		return $str ;
		
	}
	
	
	
}