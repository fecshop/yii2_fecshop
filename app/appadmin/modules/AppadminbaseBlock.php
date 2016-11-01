<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules;
use Yii;
use fec\helpers\CRequest;
use fec\helpers\CUrl;
use yii\base\Object;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AppadminbaseBlock extends Object{
	/**
	 * parameter storage front passed.
	 */
	public $_param = [];
	/**
	 * fecshop service
	 */
	public $_service;
	/**
	 * default pages number
	 */
	public $_pageNum = 1;
	/**
	 * collection default number displayed.
	 */
	public $_numPerPage = 50;
	/**
	 * collection primary key.
	 */ 
	public $_primaryKey ;
	
	/**
	 * collection sort direction , the default value is 'desc'.
	 */ 
	public $_sortDirection = 'desc';
	/**
	 * collection sort field , the default value is primary key.
	 */ 
	public $_orderField ; 
	
	public $_asArray = true;
	/**
	 * current url with param,like http://xxx.com?p=3&sort=desc
	 */ 
	public $_currentParamUrl;
	/**
	 * current url with no param,like http://xxx.com
	 */ 
	public $_currentUrlKey;
	/**
	 * data edit url, if you not set value ,it will be equal to current url.
	 */ 
	public $_editUrl;
	/**
	 * data delete url, if you not set value ,it will be equal to current url.
	 */ 
	public $_deleteUrl;
	public $_currentUrl;
	
	
	/**
	 * it will be execute during initialization ,the following object variables will be initialize.
	 * $_primaryKey , $_param , $_currentUrl ,
	 * $_currentParamUrl , $_addUrl , $_editUrl,
	 * $_deleteUrl.
	 */
	public function init(){
		if(!($this instanceof AppadminbaseBlockInterface)){
			echo  'Managere  must implements fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface';
			exit;
		}
		$param = \fec\helpers\CRequest::param();
		$this->_primaryKey  = $this->_service->getPrimaryKey();
		if(empty($param['pageNum']))  	$param['pageNum'] = $this->_pageNum ;
		if(empty($param['numPerPage'])) $param['numPerPage'] = $this->_numPerPage ;
		if(empty($param['orderField'])) $param['orderField'] = $this->_primaryKey ;
		if(empty($param['orderDirection'])) $param['orderDirection'] = $this->_sortDirection ;
		if(is_array($param) && !empty($param)){
			$this->_param = array_merge($this->_param, $param) ;
		}
		$currentUrl 		= CUrl::getCurrentUrlNoParam();
		$this->_currentParamUrl = CUrl::getCurrentUrl();
		$this->_editUrl = $this->_editUrl ? $this->_editUrl : $currentUrl;
		$this->_deleteUrl = $this->_deleteUrl ? $this->_deleteUrl : $currentUrl;
	}
	
	/**
	 * generate pager form html, it is important to j-ui js framework, which will storage current request param as hidden way.
	 * @return $str|String , html format string.
	 */
	public function getPagerForm(){
		$str = "";
		if(is_array($this->_param) && !empty($this->_param)){
			foreach($this->_param as $k=>$v){
				if($k != "_csrf"){
					$str .='<input type="hidden" name="'.$k.'" value="'.$v.'">';
				}
			}
		}
		return $str;
	}
	
	/**
	 * @property $data|Array, it was return by defined function getSearchArr();
	 * generate search section html,
	 */
	public function getSearchBarHtml($data){
		if(is_array($data) && !empty($data)){
			$r_data = [];
			$i = 0;
			foreach($data as $k=>$d){
				$type11 = $d['type'];
				if($type11 == 'select'){
					$value = $d['value'];
					$name = $d['name'];
					$title = $d['title'];
					$d['value'] = $this->getSearchBarSelectHtml($name,$value,$title);
				}else if($type11 == 'chosen_select'){
					$i++;
					$value = $d['value'];
					$name = $d['name'];
					$title = $d['title'];
					$d['value'] = $this->getSearchBarChosenSelectHtml($name,$value,$title,$i);
				}	
				$r_data[$k] = $d;
			}
		}
		$searchBar = $this->getDbSearchBarHtml($r_data);
		return $searchBar;
	}
	
	/**
	 * @property $name|String , html code select name.
	 * @property $data|Array,  select options key and value.
	 * @property $title|String , select title , as select default display.
	 * generate html select code .
	 * @return   String, select html code.
	 */
	public function getSearchBarSelectHtml($name,$data,$title){
		if(is_array($data) && !empty($data)){
			$html_chosen_select = '<select class="combox" name="'.$name.'">';
			$html_chosen_select .= '<option value="">'.$title.'</option>';
			$selected = $this->_param[$name];
			if(is_array($selected) ){
				$selected = $selected['$regex'];
			}
			foreach($data as $k=>$v){
				if($selected == $k){
					$html_chosen_select .= '<option selected="selected" value="'.$k.'">'.$v.'</option>';
				}else{
					$html_chosen_select .= '<option value="'.$k.'">'.$v.'</option>';
				}
			}
			$html_chosen_select .= '</select>';
			return $html_chosen_select;
		}else{
			return '';
		}
	}
	/**
	 * @property $name|String , html code select name.
	 * @property $data|Array,  select options key and value.
	 * @property $title|String , select title , as select default display.
	 * @property $id|Int , use for chosen select config, if you use this function muilt times , $id must be unique in each time
	 * for example ,first time use this function set $id = 1, next time ,you can set $id=2,because is must be unique in front html.
	 * generate html select code .
	 * @return   String, chosen select html code.
	 */
	public function getSearchBarChosenSelectHtml($name,$data,$title,$id=1){
		if(is_array($data) && !empty($data)){
			
			$html_chosen_select .=	'<script type="text/javascript">
				var config = {
				  \'.chosen-select'.$id.'\'           : {},
				  \'.chosen-select'.$id.'-deselect\'  : {allow_single_deselect:true},
				  \'.chosen-select'.$id.'-no-single\' : {disable_search_threshold:10},
				  \'.chosen-select'.$id.'-no-results\': {no_results_text:\'Oops, nothing found!\'},
				  \'.chosen-select'.$id.'-width\'     : {width:"95%"}
				}
				for (var selector in config) {
				  $(selector).chosen(config[selector]);
				}
			  </script>
			  ';
			$html_chosen_select .= '<select data-placeholder="Your Favorite Type of Bear" class="chosen-select'.$id.'" tabindex="7" name="'.$name.'">';
			$html_chosen_select .= '<option value="">'.$title.'</option>';
			$selected = $this->_param[$name];
			if(is_array($selected) ){
				$selected = $selected['$regex'];
			}
			foreach($data as $k=>$v){
					if($k){
						if($selected == $k){
							$html_chosen_select .= '<option selected value="'.$k.'">'.$v.'</option>';
						}else{
							$html_chosen_select .= '<option value="'.$k.'">'.$v.'</option>';
						}
					}
			}
			$html_chosen_select .= '</select>';
			return $html_chosen_select;
		}else{
			return '';
		}
	}
	/**
	 * custom html code at the end of Search Bar.
	 * your can rewrite this function in your block class.
	 */
	public function customSearchBarHtml(){
		return '';
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
										'.$title.':<input type="text" value="'.(is_array($this->_param[$name]) ? $this->_param[$name]['$regex'] : $this->_param[$name]).'" name="'.$name.'" />
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
						<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
						<!-- <li><a class="button" href="#" target="dialog" mask="true" title="查询框"><span>高级检索</span></a></li> -->
					</ul>
				</div>';
		}	
		return $searchBar;	
	}
	
	
	/**
	 * get search bar html code.
	 */
	public function getSearchBar(){
		$data = $this->getSearchArr();
		return $this->getSearchBarHtml($data);
	}
	
	/**
	 * @property $searchArr|Array.
	 * generate where Array by  $this->_param and $searchArr.
	 * foreach $searchArr , check each one if it is exist in this->_param.
	 */
	public function initDataWhere($searchArr){
		$where = [];
		foreach($searchArr as $field){
			$type = $field['type'];
			$name = $field['name'];
			$lang = $field['lang'];
			
			$columns_type = isset($field['columns_type']) ? $field['columns_type'] : '';
			if($this->_param[$name] || $this->_param[$name.'_get'] || $this->_param[$name.'_lt']){
				if($type == 'inputtext' || $type == 'select' || $type == 'chosen_select'){
					if($columns_type == 'string'){
						if($lang){
							$langname = $name.'.'.\Yii::$service->fecshoplang->getDefaultLangAttrName($name) ;
							$where[] = ['like', $langname, $this->_param[$name]];
						}else{
							$where[] = ['like', $name, $this->_param[$name]];
						}
					}else if($columns_type == 'int'){
						$where[] = [$name => (int)$this->_param[$name]];
					}else if($columns_type == 'float'){
						$where[] = [$name => (float)$this->_param[$name]];
					}else if($columns_type == 'date'){
						$where[] = [$name => $this->_param[$name]];
					}else{
						$where[] = [$name => $this->_param[$name]];
					}
				}else if($type == 'inputdatefilter'){
					$_gte 	= $this->_param[$name.'_gte'];
					$_lt 	= $this->_param[$name.'_lt'];
					if($columns_type == 'int'){
						$_gte 	= strtotime($_gte);
						$_lt	= strtotime($_lt);
					}
					if($_gte){
						$where[] = ['>=', $name, $_gte];
					}
					if($_lt){
						$where[] = ['<', $name, $_lt];
					}
				}else if($type == 'inputfilter'){
					$_gte 	= $this->_param[$name.'_gte'];
					$_lt 	= $this->_param[$name.'_lt'];
					if($columns_type == 'int'){
						$_gte 	= (int)$_gte;
						$_lt	= (int)$_lt;
					}else if($columns_type == 'float'){
						$_gte 	= (float)$_gte;
						$_lt	= (float)$_lt;
					}
					if($_gte){
						$where[] = ['>=', $name, $_gte];
					}
					if($_lt){
						$where[] = ['<', $name, $_lt];
					}
				}else{
					$where[] = [$name => $this->_param[$name]];
				}
			}
		}
		//var_dump($where);
		return $where;
	}
	
	/**
	 * get edit html bar, it contains  add ,eidt ,delete  button.
	 */
	public function getEditBar(){
		/*
		if(!strstr($this->_currentParamUrl,"?")){
			$csvUrl = $this->_currentParamUrl."?type=export";
		}else{
			$csvUrl = $this->_currentParamUrl."&type=export";
		}
		<li class="line">line</li>
		<li><a class="icon csvdownload"   href="'.$csvUrl.'" target="dwzExport" targetType="navTab" title="实要导出这些记录吗?"><span>导出EXCEL</span></a></li>
		*/	
		return '<ul class="toolBar">
					<li><a class="add"   href="'.$this->_editUrl.'"  target="dialog" height="580" width="1000" drawable="true" mask="true"><span>添加</span></a></li>

					<li><a target="dialog" height="580" width="1000" drawable="true" mask="true" class="edit" href="'.$this->_editUrl.'?'.$this->_primaryKey.'={sid_user}" ><span>修改</span></a></li>
					<li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="'.$this->_primaryKey.'s" postType="string" href="'.$this->_deleteUrl.'" class="delete"><span>批量删除</span></a></li>
				</ul>';
	}
	/**
	 * list pager, it contains  numPerPage , pageNum , totalNum.
	 */
	public function getToolBar($numCount,$pageNum,$numPerPage){
		return 	'<div class="pages">
					<span>显示</span>
					<select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
						<option '.($numPerPage == 2 ? 'selected': '' ).' value="2">2</option>
						<option '.($numPerPage == 6 ? 'selected': '' ).' value="6">6</option>
						<option '.($numPerPage == 20 ? 'selected': '' ).' value="20">20</option>
						<option '.($numPerPage == 50 ? 'selected': '' ).'  value="50">50</option>
						<option '.($numPerPage == 100 ? 'selected': '' ).'  value="100">100</option>
						<option '.($numPerPage == 200 ? 'selected': '' ).'  value="200">200</option>
					</select>
					<span>条，共'.$numCount.'条</span>
				</div>
				<div class="pagination" targetType="navTab" totalCount="'.$numCount.'" numPerPage="'.$numPerPage.'" pageNumShown="10" currentPage="'.$pageNum.'"></div>
				';
	}
	/**
	 * list table thead.
	 */
	public function getTableThead(){
		$table_th_bar = $this->getTableFieldArr();
		return $this->getTableTheadHtml($table_th_bar);
		
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
		$str .= '<th width="80" >编辑</th>';
		$str .= '</tr></thead>';
		return $str;
	}
	
	public function getTableTheadArrInit($table_columns){
		
		foreach($table_columns as $field){
			$d = [
				'orderField' 	=> $field['orderField'],
			//	'label'			=> $this->_obj->getAttributeLabel($field['orderField'])	,
				'width'			=> $field['width'],
				'align' 		=> $field['align'],
			];
			$d['label'] = $field['label'] ? $field['label'] : '';
			if(empty($d['label'] )){
				$d['label'] = $field['orderField'];
			}
			$table_th_bar[] = $d;
		}
		return $table_th_bar;
	
	}
	
	/**
	 * list table body.
	 */
	public function getTableTbody(){
		$searchArr = $this->getSearchArr();
		if(is_array($searchArr) && !empty($searchArr)){
			$where = $this->initDataWhere($searchArr);
		}
		$filter = [
	 		'numPerPage' 	=> $this->_param['numPerPage'],  	
	 		'pageNum'		=> $this->_param['pageNum'], 
	 		'orderBy'		=> [$this->_param['orderField'] => (($this->_param['orderDirection'] == 'asc') ? SORT_ASC : SORT_DESC  )],
	 		'where'			=> $where,
			'asArray' 		=> $this->_asArray,
		];
		$coll = $this->_service->coll($filter );
		$data = $coll['coll'];
		$this->_param['numCount'] = $coll['count'];
		return $this->getTableTbodyHtml($data);
	}
	
	public function getTableTbodyHtml($data){
		$fileds = $this->getTableFieldArr();
		$str .= '';
		$csrfString = \fec\helpers\CRequest::getCsrfString();
		foreach($data as $one){
			$str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
			$str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
			foreach($fileds as $field){
				$orderField = $field['orderField'];
				$display	= $field['display'];
				$val = $one[$orderField];
				$display_title = '';
				if($val){
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
									$display_title = $val;
								}
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
									$val = '<img style="width:'.$t_width.'px;height:'.$t_height.'px" src="'.$val.'" />';;
								}
							}
						}
					}
					if(isset($field['lang']) && !empty($field['lang'])){
						
						$val = Yii::$service->fecshoplang->getDefaultLangAttrVal($val,$orderField);
					}
				}
				$str .= '<td><span title="'.$display_title.'">'.$val.'</span></td>';
			}
			$str .= '<td>
						<a title="编辑" target="dialog" class="btnEdit" mask="true" drawable="true" width="1000" height="580" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" >编辑</a>
						<a title="删除" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$csrfString.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel">删除</a>
					</td>';
			$str .= '</tr>';
		}
		return $str ;
		
	}
	
	/*
	example : getTableFieldArr 
	detail refer function getTableTbodyHtml($data)。
	public function getTableFieldArr(){
		$table_th_bar = [
			[	
				'orderField' 	=> '_id', 	# db columns
				'label'			=> 'ID',	# db columns display in table head
				'width'			=> '40',	# columns width in table list
				'align' 		=> 'center',# columns position in table list td
				
			],
			[	
				'orderField'	=> 'keyword',
				'label'			=> '关键字',
				'width'			=> '110',
				'align' 		=> 'left',
			],
			#  select 选择类型  display 对应的是一个数组，通过key 对应值
			# 一般是状态，譬如  1 对应激活，2对应关闭等。
			[	
				'orderField'	=> 'unit',
				'label'			=> '站点',
				'width'			=> '110',
				'align' 		=> 'left',
				'display'		=> CConfig::param("channel_type"),  # Array
			],
			# 图片类型：
			[	
				'orderField'	=> 'img',
				'label'			=> '图片',
				'width'			=> '110',
				'align' 		=> 'left',
				'convert'		=> ['string' => 'img'],
				'img_width'		=> '100',	# 图片宽度
				'img_height'	=> '100',	# 图片高度						
			],
			[	
				'orderField'	=> 'created_at',
				'label'			=> '创建时间',
				'width'			=> '190',
				'align' 		=> 'center',
				//'convert'		=> ['datetime' =>'date'],
				# 把  datetime（Y-m-d H:i:s） 转化成datetime（Y-m-d）
			],
			
			[	
				'orderField'	=> 'updated_at',
				'label'			=> '更新时间',
				'width'			=> '190',
				'align' 		=> 'center',
				'convert'		=> ['datetime' =>'date'],   # int  date datetime  显示的转换
			],
			[	
				'orderField'	=> 'updated_at',
				'label'			=> '更新时间',
				'width'			=> '190',
				'align' 		=> 'center',
				'convert'		=> ['datetime' =>'int'],   # datetime格式转换成时间戳
			],
		];
		return $table_th_bar ;
	}
	*/
}