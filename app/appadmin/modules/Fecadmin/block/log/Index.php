<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Fecadmin\block\log;

use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
	public function init()
    {
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->admin->systemLog;
        parent::init();
    }
	
	public function getLastData(){
		# 返回数据的函数
		# 隐藏部分
		$pagerForm = $this->getPagerForm();  
		# 搜索部分
		$searchBar = $this->getSearchBar();
		# 编辑 删除  按钮部分
		$editBar = $this->getEditBar();
		# 表头部分
		$thead = $this->getTableThead();
		# 表内容部分
		$tbody = $this->getTableTbody();
		# 分页部分
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
	# 定义搜索部分字段格式
	public function getSearchArr(){
		$data = [
			[	# 字符串类型
				'type' => 'inputtext',
				'title' => Yii::$service->page->translate->__('Account'),
				'name' => 'account' ,
				'columns_type' => 'string'
			],
			[	# 字符串类型
				'type' => 'inputtext',
				'title' => Yii::$service->page->translate->__('User Name'),
				'name' => 'person' ,
				'columns_type' => 'string'
			],
			[	# 字符串类型
				'type' => 'inputtext',
				'title' => Yii::$service->page->translate->__('Resource'),
				'name' => 'menu' ,
				'columns_type' => 'string'
			],
			[	# 时间区间类型搜索
				'type' => 'inputdatefilter',
				'name' => 'created_at',
				'columns_type' =>'datetime',
				'value' => [
					'gte' => Yii::$service->page->translate->__('Created Begin'),
					'lt' => Yii::$service->page->translate->__('Created End'),
				]
			],
		];
		return $data;
	}
	# 定义表格显示部分的配置
	public function getTableFieldArr(){
		$table_th_bar = [
			[	
				'orderField' 	=> 'id',
				'label'			=> Yii::$service->page->translate->__('Id'),
				'width'			=> '70',
				'align' 		    => 'center',
				
			],
			[	
				'orderField'	    => 'account',
				'label'			=> Yii::$service->page->translate->__('Account'),
				'width'			=> '70',
				'align' 		    => 'center',
			],
			[	
				'orderField'	    => 'person',
				'label'			=> Yii::$service->page->translate->__('User Name'),
				'width'			=> '70',
				'align' 		    => 'left',
			],
			[	
				'orderField'	    => 'menu',
				'label'			=> Yii::$service->page->translate->__('Resource'),
				'width'			=> '70',
				'align' 		    => 'left',
			],
			[	
				'orderField'	    => 'url',
				'label'			=> Yii::$service->page->translate->__('Url'),
				'width'			=> '220',
				'align' 		    => 'left',
			],
			[	
				'orderField'	    => 'created_at',
				'label'			=> Yii::$service->page->translate->__('Created At'),
				'width'			=> '130',
				'align' 		    => 'center',
				//'convert'		=> ['datetime' =>'date'],   # int  date datetime  显示的转换
			],
		];
		return $table_th_bar ;
	}
	
	# table 内容部分
	public function getTableTbodyHtml($data){
		$fileds = $this->getTableFieldArr();
		$str .= '';
		$csrfString = \fec\helpers\CRequest::getCsrfString();
        $primaryKey = $this->_service->getPrimaryKey();
		foreach ($data as $one) {
			$str .= '<tr target="sid_user" rel="'.$one[$primaryKey].'">';
			$str .= '<td><input name="'.$primaryKey.'s" value="'.$one[$primaryKey].'" type="checkbox"></td>';
			foreach ($fileds as $field) {
				$orderField = $field['orderField'];
				$display	= $field['display'];
				$val = $one[$orderField];
				$originVal = $one[$orderField];
				if ($val) {
					 if ($orderField == 'menu') {
                        $valArr = explode(" ", $val, 2);
                        $val1 = Yii::$service->page->translate->__($valArr[0]);
                        $val2 = Yii::$service->page->translate->__($valArr[1]);
                        $val = $val1 . ' ' . $val2;
                    }else if (isset($field['display']) && !empty($field['display'])) {
						$display = $field['display'];
						$val = $display[$val] ? $display[$val] : $val;
					}
					if (isset($field['convert']) && !empty($field['convert'])) {
						$convert = $field['convert'];
						foreach ($convert as $origin =>$to) {
							if (strstr($origin,'date')) {
								if ($to == 'date') {
									$val = date('Y-m-d',strtotime($val));
								} else if ($to == 'datetime') {
									$val = date('Y-m-d H:i:s',strtotime($val));
								} else if ($to == 'int') {
									$val = strtotime($val);
								}
							} else if ($origin == 'int') {
								if ($to == 'date') {
									$val = date('Y-m-d',$val);
								} else if ($to == 'datetime') {
									$val = date('Y-m-d H:i:s',$val);
								} else if ($to == 'int') {
									$val = $val;
								}
							}
						}
					}
				}
				$str .= '<td><span title='.$originVal.'>'.$val.'</span></td>';
			}
			$str .= '</tr>';
		}
        
		return $str ;
	}
	# table 表  标题  1
	public function getTableTheadHtml($table_th_bar){
        $primaryKey = $this->_service->getPrimaryKey();
		$table_th_bar = $this->getTableTheadArrInit($table_th_bar);
		$this->_param['orderField'] 	= $this->_param['orderField'] 		? $this->_param['orderField'] : $primaryKey;
		$this->_param['orderDirection'] = $this->_param['orderDirection'] 	? $this->_param['orderDirection'] :  $this->_defaultDirection;
		foreach ($table_th_bar as $k => $field) {
			if ($field['orderField'] == $this->_param['orderField']) {
				$table_th_bar[$k]['class'] = $this->_param['orderDirection'];
			}	
		}
		$str = '<thead><tr>';
		$str .= '<th width="22"><input type="checkbox" group="'.$primaryKey.'s" class="checkboxCtrl"></th>';
		foreach ($table_th_bar as $b) {
			$width = $b['width'];
			$label = $b['label'];
			$orderField = $b['orderField'];
			$class = isset($b['class']) ? $b['class'] : '';
			$align = isset($b['align']) ? 'align="'.$b['align'].'"' : '';
			$str .= '<th width="'.$width.'" '.$align.' orderField="'.$orderField.'" class="'.$class.'">'.$label.'</th>';
		}
		$str .= '</tr></thead>';
        
		return $str;
	}
	
}

