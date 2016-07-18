<?php
namespace fecshop\app\appadmin\modules;
use fec\helpers\CRequest;
use fec\helpers\CUrl;
use yii\base\Object;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
class AppadminbaseBlockEdit extends Object{
	
	public 		$_param;
	public 		$_primaryKey;
	public 		$_one;
	public 		$_service;
	public 		$_textareas;
	/**
	 * html input or text etc. ,  html name like: <input name="XXXX" />
	 */
	protected 	$_editFormData;
	
	public function init(){
		if(!($this instanceof AppadminbaseBlockEditInterface)){
			echo  json_encode(array(
					'statusCode'=>'300',
					'message'=>'Manageredit must implements fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface',
			));
			exit;
		}
		$this->_editFormData = 'editFormData';
		$this->setService();
		$this->_param		= CRequest::param();
		$this->_primaryKey  = $this->_service->getPrimaryKey();
		$id 				= $this->_param[$this->_primaryKey];
		
		$this->_one 		= $this->_service->getByPrimaryKey($id);
	}
	
	
	public function getEditBar(){
		
		$editArr = $this->getEditArr();
		$str = '';
		if($this->_param[$this->_primaryKey]){
			$str = <<<EOF
			<input type="hidden"  value="{$this->_param[$this->_primaryKey]}" size="30" name="{$this->_editFormData}[{$this->_primaryKey}]" class="textInput ">
EOF;
		}
		foreach($editArr as $column){  
			$name = $column['name'];
			$require = $column['require'] ? 'required' : '';
			$label = $column['label'] ? $column['label'] : $this->_one->getAttributeLabel($name);
			$display = isset($column['display']) ? $column['display'] : '';
			if(empty($display)){
				$display = ['type' => 'inputString'];
			}
			//var_dump($this->_one['id']);
			$value = $this->_one[$name] ? $this->_one[$name] : $column['default'];
			$display_type = isset($display['type']) ? $display['type'] : 'inputString';
			if($display_type == 'inputString'){
				$str .=<<<EOF
						<p>
							<label>{$label}：</label>
							<input type="text"  value="{$value}" size="30" name="{$this->_editFormData}[{$name}]" class="textInput {$require} ">
						</p>
EOF;
			}else if($display_type == 'inputDate'){
				$valueData = $value ? date("Y-m-d",strtotime($value)) : '';
				$str .=<<<EOF
						<p>
							<label>{$label}：</label>
							<input type="text"  value="{$valueData}" size="30" name="{$this->_editFormData}[{$name}]" class="date textInput {$require} ">
						</p>
EOF;
			}else if($display_type == 'inputEmail'){
				$str .=<<<EOF
						<p>
							<label>{$label}：</label>
							<input type="text"  value="{$value}" size="30" name="{$this->_editFormData}[{$name}]" class="email textInput {$require} ">
						</p>
EOF;
			}else if($display_type == 'inputPassword'){
				$str .=<<<EOF
						<p>
							<label>{$label}：</label>
							<input type="password"  value="" size="30" name="{$this->_editFormData}[{$name}]" class=" textInput {$require} ">
						</p>
EOF;
			}else if($display_type == 'select'){
				$data = isset($display['data']) ? $display['data'] : '';
				//var_dump($data);
				//echo $value;
				$select_str = '';
				if(is_array($data)){
					$select_str .= <<<EOF
								<select class="combox {$require}" name="{$this->_editFormData}[{$name}]" >
EOF;
					$select_str .='<option value="">'.$label.'</option>';
					foreach($data as $k => $v){
						if($value == $k){
							//echo $value."#".$k;
							$select_str .='<option selected="selected" value="'.$k.'">'.$v.'</option>';
						}else{
							$select_str .='<option value="'.$k.'">'.$v.'</option>';
						}
						
					}
					$select_str .= '</select>';
				}
				
				$str .=<<<EOF
						<p>
							<label>{$label}：</label>
								{$select_str}
						</p>
EOF;
			}else if($display_type == 'textarea'){
				$rows = isset($display['rows']) ? $display['rows'] : 15;
				$cols = isset($display['cols']) ? $display['cols'] : 110;
				$this->_textareas .= <<<EOF
						<fieldset id="fieldset_table_qbe">
							<legend style="color:#cc0000">{$label}：</legend>
							<div>
								<textarea  class="editor" name="{$this->_editFormData}[{$name}]" rows="{$rows}" cols="{$cols}" name="{$this->_editFormData}[{$name}]" >{$value}</textarea>
							</div>
						</fieldset>
EOF;
			}
		}
		return $str;
	}
	
	
	
	
	
}