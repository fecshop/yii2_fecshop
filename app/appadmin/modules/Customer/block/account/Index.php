<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Customer\block\account;
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
		 * edit data url
		 */
		$this->_editUrl 	= CUrl::getUrl("customer/account/manageredit");
		/**
		 * delete data url
		 */
		$this->_deleteUrl 	= CUrl::getUrl("customer/account/managerdelete");
		/**
		 * service component, data provider
		 */
		$this->_service = Yii::$service->customer;
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
		$deleteStatus = Yii::$service->customer->getStatusDeleted();
		$activeStatus = Yii::$service->customer->getStatusActive();
		
		$data = [
			[	# selecit的Int 类型
				'type'=>'select',	 
				'title'=>'状态',
				'name'=>'status',
				'columns_type' =>'int',  # int使用标准匹配， string使用模糊查询
				'value'=> [					# select 类型的值
					$activeStatus =>'激活',
					$deleteStatus =>'关闭',
				],
			],
		
			
			[	# 字符串类型
				'type'=>'inputtext',
				'title'=>'邮箱',
				'name'=>'email' ,
				'columns_type' =>'string'
			],
			[	# selecit的Int 类型
				'type'=>'select',	 
				'title'=>'订阅',
				'name'=>'is_subscribed',
				'columns_type' =>'int',  # int使用标准匹配， string使用模糊查询
				'value'=> [					# select 类型的值
					1 =>'订阅',
					2 =>'不订阅',
				],
			],
			
			[	# 字符串类型
				'type'=>'inputtext',
				'title'=>'密码重置token',
				'name'=>'password_reset_token' ,
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
		$deleteStatus = Yii::$service->customer->getStatusDeleted();
		$activeStatus = Yii::$service->customer->getStatusActive();
		
		$table_th_bar = [
			[	
				'orderField' 	=> $this->_primaryKey,
				'label'			=> 'ID',
				'width'			=> '50',
				'align' 		=> 'center',
				
			],
			[	
				'orderField'	=> 'firstname',
				'label'			=> 'firstname',
				'width'			=> '50',
				'align' 		=> 'left',
				
			],
			[	
				'orderField'	=> 'lastname',
				'label'			=> 'lastname',
				'width'			=> '50',
				'align' 		=> 'left',
				
			],
			[	
				'orderField'	=> 'email',
				'label'			=> 'email',
				'width'			=> '50',
				'align' 		=> 'left',
				
			],
			[	
				'orderField'	=> 'favorite_product_count',
				'label'			=> '收藏个数',
				'width'			=> '50',
				'align' 		=> 'left',
				
			],
			[	
				'orderField'	=> 'password_reset_token',
				'label'			=> '重置密码token',
				'width'			=> '50',
				'align' 		=> 'left',
				
			],
			[	
				'orderField'	=> 'is_subscribed',
				'label'			=> '订阅邮件',
				'width'			=> '50',
				'align' 		=> 'center',
				'display'		=> [
					1 =>'是',
					2 =>'否',
				],
			],
			
			 
			[	
				'orderField'	=> 'status',
				'label'			=> '状态',
				'width'			=> '50',
				'align' 		=> 'center',
				'display'		=> [
					$activeStatus =>'激活',
					$deleteStatus =>'关闭',
				],
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
	
	
	
	
	
}