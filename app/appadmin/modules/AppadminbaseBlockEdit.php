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
use fec\helpers\CConfig;
use yii\base\Object;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AppadminbaseBlockEdit extends Object{
	
	public 		$_param;
	public 		$_primaryKey;
	public 		$_one;
	public 		$_service;
	public 		$_textareas;
	public 		$_lang_attr;
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
	
	
	public function getEditBar($editArr=[]){
		$langs = Yii::$service->fecshoplang->getAllLangCode();
		$defaultLangCode = Yii::$service->fecshoplang->defaultLangCode;
		if(empty($editArr)){
			$editArr = $this->getEditArr();
		}
		$str = '';
		$langAndTextarea = '';
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
				$isLang = isset($display['lang']) ? $display['lang'] : false;
				
				if( $isLang && is_array($langs) && !empty($langs) ){
					$tabLangTitle = '';
					$tabLangInput = '';
					foreach($langs as $lang){
						
						if($require && $defaultLangCode === $lang){
							$inputStringLangRequire = 'required';
						}else{
							$inputStringLangRequire = 0;
						}
						
						$tabLangTitle .= '<li><a href="javascript:;"><span>'.$lang.'</span></a></li>';
						$langAttrName = Yii::$service->fecshoplang->getLangAttrName($name,$lang);
						$tabLangInput .= '<div>
								<p class="edit_p">
									<label>'.$label.'['.$lang.']：</label>
									<input type="text"  value="'.$value[$langAttrName].'" size="30" name="'.$this->_editFormData.'['.$name.']['.$langAttrName.']" class="textInput '.$inputStringLangRequire.' ">
								</p>

							</div>';
					}
					$this->_lang_attr .=<<<EOF
						<div class="tabs" currentIndex="0" eventType="click" style="margin:10px 0;">
							<div class="tabsHeader">
								<div class="tabsHeaderContent">
									<ul>
										{$tabLangTitle}
									</ul>
								</div>
							</div>
							<div class="tabsContent" style="height:30px;">
								{$tabLangInput}
							</div>
							<div class="tabsFooter">
								<div class="tabsFooterContent"></div>
							</div>
						</div>
EOF;
					
				}else{
					$str .=<<<EOF
							<p class="edit_p">
								<label>{$label}：</label>
								<input type="text"  value="{$value}" size="30" name="{$this->_editFormData}[{$name}]" class="textInput {$require} ">
							</p>
EOF;
				}
			}else if($display_type == 'inputDate'){
				if($value && !is_numeric($value)){
					$value = strtotime($value);
				}
				$valueData = $value ? date("Y-m-d",$value) : '';
				$str .=<<<EOF
						<p class="edit_p">
							<label>{$label}：</label>
							<input type="text"  value="{$valueData}" size="30" name="{$this->_editFormData}[{$name}]" class="date textInput {$require} ">
						</p>
EOF;
			}else if($display_type == 'inputEmail'){
				$str .=<<<EOF
						<p class="edit_p">
							<label>{$label}：</label>
							<input type="text"  value="{$value}" size="30" name="{$this->_editFormData}[{$name}]" class="email textInput {$require} ">
						</p>
EOF;
			}else if($display_type == 'inputPassword'){
				$str .=<<<EOF
						<p class="edit_p">
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
						<p class="edit_p">
							<label>{$label}：</label>
								{$select_str}
						</p>
EOF;
			}else if($display_type == 'textarea'){
				$rows = isset($display['rows']) ? $display['rows'] : 15;
				$cols = isset($display['cols']) ? $display['cols'] : 110;
				$isLang = isset($display['lang']) ? $display['lang'] : false;
				$uploadImgUrl = 'upimgurl="'.CUrl::getUrl('cms/staticblock/imageupload').'" upimgext="jpg,jpeg,gif,png"';
				$uploadFlashUrl = 'upflashurl="'.CUrl::getUrl('cms/staticblock/flashupload').'" upflashext="swf"';
				$uploadLinkUrl = 'uplinkurl="'.CUrl::getUrl('cms/staticblock/linkupload').'" uplinkext="zip,rar,txt"';
				$uploadMediaUrl = 'upmediaurl="'.CUrl::getUrl('cms/staticblock/mediaupload').'" upmediaext:"avi"="" ';
				
				
				
				
				if( $isLang && is_array($langs) && !empty($langs) ){
					$tabLangTitle = '';
					$tabLangTextarea = '';
					foreach($langs as $lang){
						$langAttrName = Yii::$service->fecshoplang->getLangAttrName($name,$lang);
						if($require && $defaultLangCode === $lang){
							$inputStringLangRequire = 'required';
						}else{
							$inputStringLangRequire = 0;
						}
						$tabLangTitle .= '<li><a href="javascript:;"><span>'.$lang.'</span></a></li>';
						$tabLangTextarea .= '
						<div>
							<fieldset id="fieldset_table_qbe">
								<legend style="color:#cc0000">'.$label.'['.$lang.']：</legend>
								<div>
									<div class="unit">
										<textarea '.$uploadImgUrl.' '.$uploadFlashUrl.'  '.$uploadLinkUrl.'  '.$uploadMediaUrl.'  class="editor '.$inputStringLangRequire.'"  rows="'.$rows.'" cols="'.$cols.'" name="'.$this->_editFormData.'['.$name.']['.$langAttrName.']" >'.$value[$langAttrName].'</textarea>
									</div>
								</div>
							</fieldset>
						</div>';
						
						
						
					}
					$this->_textareas .=<<<EOF
						<div class="tabs" currentIndex="0" eventType="click" style="margin:10px 0;">
							<div class="tabsHeader">
								<div class="tabsHeaderContent">
									<ul>
										{$tabLangTitle}
									</ul>
								</div>
							</div>
							<div class="tabsContent" style="">
								{$tabLangTextarea}
							</div>
							<div class="tabsFooter">
								<div class="tabsFooterContent"></div>
							</div>
						</div>
EOF;
					
				}else{
					$this->_textareas .= <<<EOF
						<fieldset id="fieldset_table_qbe">
							<legend style="color:#cc0000">{$label}：</legend>
							<div>
								<textarea  class="editor" name="{$this->_editFormData}[{$name}]" rows="{$rows}" cols="{$cols}" name="{$this->_editFormData}[{$name}]"  {$uploadImgUrl}  {$uploadFlashUrl}  {$uploadLinkUrl}   {$uploadMediaUrl} >{$value}</textarea>
							</div>
						</fieldset>
EOF;
				}
			}
		}
		return $str;
	}
	
	
	
	
	
}