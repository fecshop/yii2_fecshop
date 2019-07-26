<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules;

use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use Yii;
use yii\base\BaseObject;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AppadminbaseBlockEdit extends BaseObject
{
    public $_param;
    public $_primaryKey;
    public $_one;
    public $_service;
    public $_textareas;
    public $_lang_attr;
    /**
     * html input or text etc. ,  html name like: <input name="XXXX" />.
     */
    protected $_editFormData;

    public function init()
    {
        if (!($this instanceof AppadminbaseBlockEditInterface)) {
            echo  json_encode([
                    'statusCode'=>'300',
                    'message'=>'Manageredit must implements fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface',
            ]);
            exit;
        }
        $this->_editFormData = 'editFormData';
        $this->setService();
        $this->_param = CRequest::param();
        $this->_primaryKey = $this->_service->getPrimaryKey();
        $id = $this->_param[$this->_primaryKey];
        $this->_one = $this->_service->getByPrimaryKey($id);
    }
    
    public function getVal($name, $column)
    {
        return ($this->_one[$name] || $this->_one[$name] === 0) ? $this->_one[$name] : $column['default'];
    }  

    public function getEditBar($editArr = [])
    {
        $langs = Yii::$service->fecshoplang->getAllLangCode();
        $defaultLangCode = Yii::$service->fecshoplang->defaultLangCode;
        // xhEditor编辑器里面上传图片和其他的类型的url以及允许的文件类型
        // fecshop只实现了image的上传，其他类型的自己实现。
        $upImgUrl = Yii::$service->admin->getXhEditorUploadImgUrl();
        $upImgFormat = Yii::$service->admin->getXhEditorUploadImgForamt();
        $upFlashUrl = Yii::$service->admin->getXhEditorUploadFlashUrl();
        $upFlashFormat = Yii::$service->admin->getXhEditorUploadFlashFormat();
        $upLinkUrl = Yii::$service->admin->getXhEditorUploadLinkUrl();
        $upLinkFormat = Yii::$service->admin->getXhEditorUploadLinkFormat();
        $upMediaUrl = Yii::$service->admin->getXhEditorUploadMediaUrl();
        $upMediaFormat = Yii::$service->admin->getXhEditorUploadMediaFormat();

        if (empty($editArr)) {
            $editArr = $this->getEditArr();
        }
        $str = '';
        $langAndTextarea = '';
        if ($this->_param[$this->_primaryKey]) {
            $str = <<<EOF
			<input type="hidden"  value="{$this->_param[$this->_primaryKey]}" size="30" name="{$this->_editFormData}[{$this->_primaryKey}]" class="textInput ">
EOF;
        }
        $idsj = md5(time());
        $idsji = 0;
        foreach ($editArr as $column) {
            $name = $column['name'];
            $remark = Yii::$service->page->translate->__($column['remark']);
            $require = $column['require'] ? 'required' : '';
            $label = $column['label'] ? $column['label'] : $this->_one->getAttributeLabel($name);
            $display = isset($column['display']) ? $column['display'] : '';
            if (empty($display)) {
                $display = ['type' => 'inputString'];
            }
            //var_dump($this->_one['id']);
            
            $value = $this->getVal($name, $column);
            $display_type = isset($display['type']) ? $display['type'] : 'inputString';
            if ($display_type == 'inputString') {
                $isLang = isset($display['lang']) ? $display['lang'] : false;

                if ($isLang && is_array($langs) && !empty($langs)) {
                    $tabLangTitle = '';
                    $tabLangInput = '';
                    foreach ($langs as $lang) {
                        if ($require && $defaultLangCode === $lang) {
                            $inputStringLangRequire = 'required';
                        } else {
                            $inputStringLangRequire = 0;
                        }

                        $tabLangTitle .= '<li><a href="javascript:;"><span>'.$lang.'</span></a></li>';
                        $langAttrName = Yii::$service->fecshoplang->getLangAttrName($name, $lang);
                        $t_val = isset($value[$langAttrName]) ? $value[$langAttrName] : '';
                        // 对于含有 " 的字符串进行处理
                        $t_val =  str_replace('"', '&quot;', $t_val) ;
                        $tabLangInput .= '<div>
								<p class="edit_p">
									<label>'.$label.'['.$lang.']：</label>
									<input type="text"  value="'.$t_val.'" size="30" name="'.$this->_editFormData.'['.$name.']['.$langAttrName.']" class="textInput '.$inputStringLangRequire.' ">
                                    <span class="remark-text">'.$remark .'</span>
                                </p>

							</div>';
                    }
                    $this->_lang_attr .= <<<EOF
						<div class="tabs" currentIndex="0" eventType="click" style="margin:10px 0;">
							<div class="tabsHeader">
								<div class="tabsHeaderContent">
									<ul>
										{$tabLangTitle}
									</ul>
								</div>
							</div>
							<div class="tabsContent" style="">
								{$tabLangInput}
							</div>
							<div class="tabsFooter">
								<div class="tabsFooterContent"></div>
							</div>
						</div>
EOF;
                } else {
                    // 对于含有 " 的字符串进行处理
                    $value =  str_replace('"', '&quot;', $value) ;
                    $str .= <<<EOF
							<p class="edit_p">
								<label>{$label}：</label>
								<input type="text"  value="{$value}" size="30" name="{$this->_editFormData}[{$name}]" class="textInput {$require} ">
                                <span class="remark-text">{$remark}</span>
                            </p>
EOF;
                }
            } elseif ($display_type == 'inputDate') {
                if ($value && !is_numeric($value)) {
                    $value = strtotime($value);
                }
                $valueData = $value ? date('Y-m-d', $value) : '';
                $str .= <<<EOF
						<p class="edit_p">
							<label>{$label}：</label>
							<input type="text"  value="{$valueData}" size="30" name="{$this->_editFormData}[{$name}]" class="date textInput {$require} ">
                            <span class="remark-text">{$remark}</span>
                        </p>
EOF;
            } elseif ($display_type == 'inputDateTime') {
                if ($value && !is_numeric($value)) {
                    $value = strtotime($value);
                }
                $valueData = $value ? date('Y-m-d H:i:s', $value) : '';
                $str .= <<<EOF
						<p class="edit_p">
							<label>{$label}：</label>
							<input type="text" datefmt="yyyy-MM-dd HH:mm:ss"  value="{$valueData}" size="30" name="{$this->_editFormData}[{$name}]" class="date textInput {$require} ">
                            <span class="remark-text">{$remark}</span>
                        </p>
EOF;
            } elseif ($display_type == 'inputEmail') {
                $str .= <<<EOF
						<p class="edit_p">
							<label>{$label}：</label>
							<input type="text"  value="{$value}" size="30" name="{$this->_editFormData}[{$name}]" class="email textInput {$require} ">
                            <span class="remark-text">{$remark}</span>
                        </p>
EOF;
            } elseif ($display_type == 'stringText') {
                $str .= <<<EOF
						<p class="edit_p">
							<label>{$label}：</label>
							{$value}
                            <span class="remark-text">{$remark}</span>
						</p>
EOF;
            } elseif ($display_type == 'inputPassword') {
                $str .= <<<EOF
						<p class="edit_p">
							<label>{$label}：</label>
							<input type="password"  value="" size="30" name="{$this->_editFormData}[{$name}]" class=" textInput {$require} ">
                            <span class="remark-text">{$remark}</span>
                        </p>
EOF;
            } elseif ($display_type == 'select') {
                $data = isset($display['data']) ? $display['data'] : '';
                //var_dump($data);
                //echo $value;
                $select_str = '';
                if (is_array($data)) {
                    $select_str .= <<<EOF
								<select class="select_{$name} combox {$require}" name="{$this->_editFormData}[{$name}]" >
EOF;
                    $select_str .= '<option value="">'.$label.'</option>';
                    foreach ($data as $k => $v) {
                        if ($value == $k) {
                            //echo $value."#".$k;
                            $select_str .= '<option selected="selected" value="'.$k.'">'.$v.'</option>';
                        } else {
                            $select_str .= '<option value="'.$k.'">'.$v.'</option>';
                        }
                    }
                    $select_str .= '</select>';
                }

                $str .= <<<EOF
						<p class="edit_p">
							<label>{$label}：</label>
								{$select_str}
                                <span class="remark-text">{$remark}</span>
						</p>
EOF;
            } elseif ($display_type == 'editSelect') {
                $data = isset($display['data']) ? $display['data'] : '';
                //var_dump($data);
                //echo $value;
                $select_str = '';
                if (is_array($data)) {
                    $idsji++;
                    $selectId = $idsj.$idsji;
                    $select_str .= <<<EOF
								<select id="{$selectId}" class=" {$require}" name="{$this->_editFormData}[{$name}]" >
EOF;
                    $select_str .= '<option value="">'.$label.'</option>';
                    $editSelectChosen = false;
                    foreach ($data as $k => $v) {
                        if ($value == $k) {
                            //echo $value."#".$k;
                            $select_str .= '<option selected value="'.$k.'">'.$v.'</option>';
                            $editSelectChosen = true;
                        } else {
                            $select_str .= '<option value="'.$k.'">'.$v.'</option>';
                        }
                    }
                    if (!$editSelectChosen) {
                        $select_str .= '<option selected value="'.$value.'">'.$value.'</option>';
                    }
                    $select_str .= '</select>';
                }

                $str .= <<<EOF
						<p class="edit_p">
							<label>{$label}：</label>
								{$select_str}
                                <span class="remark-text">{$remark}</span>
						</p>
                        <script type="text/javascript">
                            $('#{$selectId}').editableSelect(
                                { filter: false }
                            );
                        </script>
EOF;
            } elseif ($display_type == 'textarea') {
                $notEditor = isset($display['notEditor']) ? $display['notEditor'] : false;
                $edittorClass='editor';
                if ($notEditor) {
                    $edittorClass='';
                }
                $rows = isset($display['rows']) ? $display['rows'] : 15;
                $cols = isset($display['cols']) ? $display['cols'] : 110;
                $isLang = isset($display['lang']) ? $display['lang'] : false;

                $uploadImgUrl = 'upimgurl="'.Yii::$service->url->getUrl($upImgUrl).'" upimgext="' . $upImgFormat . '"';
                $uploadFlashUrl = 'upflashurl="'.Yii::$service->url->getUrl($upFlashUrl).'" upflashext="' . $upFlashFormat . '"';
                $uploadLinkUrl = 'uplinkurl="'.Yii::$service->url->getUrl($upLinkUrl).'" uplinkext="' . $upLinkFormat . '"';
                $uploadMediaUrl = 'upmediaurl="'.Yii::$service->url->getUrl($upMediaUrl).'" upmediaext:"' . $upMediaFormat . '" ';

                if ($isLang && is_array($langs) && !empty($langs)) {
                    $tabLangTitle = '';
                    $tabLangTextarea = '';
                    foreach ($langs as $lang) {
                        $langAttrName = Yii::$service->fecshoplang->getLangAttrName($name, $lang);
                        if ($require && $defaultLangCode === $lang) {
                            $inputStringLangRequire = 'required';
                        } else {
                            $inputStringLangRequire = 0;
                        }
                        $tabLangTitle .= '<li><a href="javascript:;"><span>'.$lang.'</span></a></li>';
                        $tabLangTextarea .= '
						<div>
							<fieldset id="fieldset_table_qbe">
								<legend style="color:#009688">'.$label.'['.$lang.']：</legend>
								<div>
									<div class="unit">
										<textarea '.$uploadImgUrl.' '.$uploadFlashUrl.'  '.$uploadLinkUrl.'  '.$uploadMediaUrl.'  class="'.$edittorClass.' '.$inputStringLangRequire.'"  rows="'.$rows.'" cols="'.$cols.'" name="'.$this->_editFormData.'['.$name.']['.$langAttrName.']"  style="width:98%" >'.$value[$langAttrName].'</textarea>
									</div>
								</div>
							</fieldset>
						</div>';
                    }
                    $this->_textareas .= <<<EOF
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
                                <span class="remark-text">{$remark}</span>
							</div>
							<div class="tabsFooter">
								<div class="tabsFooterContent"></div>
							</div>
						</div>
EOF;
                } else {
                    $this->_textareas .= <<<EOF
						<fieldset id="fieldset_table_qbe">
							<legend style="color:#009688">{$label}：</legend>
							<div>
								<textarea  class="{$edittorClass}" name="{$this->_editFormData}[{$name}]" rows="{$rows}" cols="{$cols}" name="{$this->_editFormData}[{$name}]"  {$uploadImgUrl}  {$uploadFlashUrl}  {$uploadLinkUrl}   {$uploadMediaUrl} >{$value}</textarea>
                                <span class="remark-text">{$remark}</span>
                            </div>
						</fieldset>
EOF;
                }
            }
        }

        return $str;
    }
}
