<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\block\productreview;
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
	public $_auditUrl;
	public $_auditRejectedUrl;
	/**
	 * init param function ,execute in construct
	 */
	public function init(){
		
		$this->_auditUrl 	= CUrl::getUrl("catalog/productreview/manageraudit");
		$this->_auditRejectedUrl = CUrl::getUrl("catalog/productreview/managerauditrejected");
		/**
		 * edit data url
		 */
		$this->_editUrl 	= CUrl::getUrl("catalog/productreview/manageredit");
		/**
		 * delete data url
		 */
		$this->_deleteUrl 	= CUrl::getUrl("catalog/productreview/managerdelete");
		/**
		 * service component, data provider
		 */
		$this->_service = Yii::$service->product->review;
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
		$activeStatus = Yii::$service->product->review->activeStatus();
		$refuseStatus = Yii::$service->product->review->refuseStatus();
		$noActiveStatus = Yii::$service->product->review->noActiveStatus();
		$data = [
			[	# selecit的Int 类型
				'type'=>'select',	 
				'title'=>'审核状态',
				'name'=>'status',
				'columns_type' =>'int',  # int使用标准匹配， string使用模糊查询
				'value'=> [					# select 类型的值
					$noActiveStatus => '未审核',
					$activeStatus	=> '审核通过',
					$refuseStatus 	=> '审核拒绝',
				],
			],
			[	# 字符串类型
				'type'=>'inputtext',
				'title'=>'Spu',
				'name'=>'product_spu' ,
				'columns_type' =>'string'
			],
			[	# 时间区间类型搜索
				'type'=>'inputdatefilter',
				'name'=> 'review_date',
				'columns_type' =>'int',
				'value'=>[
					'gte'=>'评论时间开始',
					'lt' =>'评论时间结束',
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
		$activeStatus = Yii::$service->product->review->activeStatus();
		$refuseStatus = Yii::$service->product->review->refuseStatus();
		$noActiveStatus = Yii::$service->product->review->noActiveStatus();
		
		$table_th_bar = [
			[	
				'orderField' 	=> '_id',
				'label'			=> 'ID',
				'width'			=> '50',
				'align' 		=> 'left',
				
			],
			[	
				'orderField'	=> 'product_id',
				'label'			=> 'sku',
				'width'			=> '180',
				'align' 		=> 'left',
			],
			
			[	
				'orderField'	=> 'rate_star',
				'label'			=> '评星',
				'width'			=> '110',
				'align' 		=> 'center',
				'width'			=> '30',
			],
			
			[	
				'orderField'	=> 'name',
				'label'			=> '评论人',
				'width'			=> '110',
				'align' 		=> 'left',
			],
			
			
			[	
				'orderField'	=> 'summary',
				'label'			=> '评论标题',
				'width'			=> '110',
				'align' 		=> 'left',
			],
			
			
			[	
				'orderField'	=> 'review_date',
				'label'			=> '评论时间',
				'width'			=> '110',
				'align' 		=> 'center',
				'convert'		=> ['int' => 'datetime'],
			],
			
			
			[	
				'orderField'	=> 'store',
				'label'			=> 'Store',
				'width'			=> '110',
				'align' 		=> 'left',
			],
			
			
			[	
				'orderField'	=> 'lang_code',
				'label'			=> '语言',
				'width'			=> '35',
				'align' 		=> 'center',
				
			],
			
			[	
				'orderField'	=> 'status',
				'label'			=> '审核状态',
				'width'			=> '120',
				'display'		=> [
					$noActiveStatus => '未审核',
					$activeStatus	=> '审核通过',
					$refuseStatus 	=> '审核拒绝',
				],
			],
			[	
				'orderField'	=> 'audit_user',
				'label'			=> '审核人',
				'width'			=> '110',
				'align' 		=> 'left',
			],
			
			
			
			[	
				'orderField'	=> 'audit_date',
				'label'			=> '审核时间',
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
			$user_ids[]		= $one['audit_user'];
			$product_ids[] 	= $one['product_id'];
		}
		$users = Yii::$service->adminUser->getIdAndNameArrByIds($user_ids);
		$product_skus = Yii::$service->product->getSkusByIds($product_ids);
		
		foreach($data as $one){
			$str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
			$str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
			foreach($fileds as $field){
				$orderField = $field['orderField'];
				$display	= $field['display'];
				$val = $one[$orderField];
				if($orderField == 'audit_user'){
					//var_dump($users);
					$val = isset($users[$val]) ? $users[$val] : $val;
					$str .= '<td>'.$val.'</td>';
					continue;
				}
				if($orderField == 'product_id'){
					//echo 11;
					//var_dump($product_skus);
					$val = isset($product_skus[$val]) ? $product_skus[$val] : $val;
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
	
	
	public function getEditBar(){
			
		return '<ul class="toolBar">
					<li><a title="确实要批量审核这些记录吗?" target="selectedTodo" rel="'.$this->_primaryKey.'s" postType="string" href="'.$this->_auditUrl.'" class="edit"><span>批量审核通过</span></a></li>
					<li><a title="确实要批量审核拒绝这些记录吗?" target="selectedTodo" rel="'.$this->_primaryKey.'s" postType="string" href="'.$this->_auditRejectedUrl.'" class="edit"><span>批量审核拒绝</span></a></li>
					
					<li><a target="dialog" height="580" width="1000" drawable="true" mask="true" class="edit" href="'.$this->_editUrl.'?'.$this->_primaryKey.'={sid_user}" ><span>修改</span></a></li>
					<li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="'.$this->_primaryKey.'s" postType="string" href="'.$this->_deleteUrl.'" class="delete"><span>批量删除</span></a></li>
				</ul>';
	}
	
	
}