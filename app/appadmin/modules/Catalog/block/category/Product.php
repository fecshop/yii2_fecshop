<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\category;

use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Product extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
    protected $_select_id_arr;

    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        $this->_service = Yii::$service->product;
        parent::init();
    }

    public function getPagerForm()
    {
        $str = '';
        if (is_array($this->_param) && !empty($this->_param)) {
            foreach ($this->_param as $k=>$v) {
                if ($k != '_csrf') {
                    $str .= '<input type="hidden" name="'.$k.'" value="'.$v.'">';
                }
            }
        }
        if (!isset($this->_param['productfiltertype'])) {
            $str .= '<input type="hidden" name="productfiltertype" value="">';
        }
        if (!isset($this->_param['product_select_info'])) {
            $str .= '<input class="product_select_info" type="hidden" name="product_select_info" value="">';
        }
        if (!isset($this->_param['product_unselect_info'])) {
            $str .= '<input class="product_unselect_info" type="hidden" name="product_unselect_info" value="">';
        }

        return $str;
    }

    public function getLastData()
    {

        // hidden section ,that storage page info
        $pagerForm = $this->getPagerForm();
        // search section
        $searchBar = $this->getSearchBar();
        // edit button, delete button,
        $editBar = $this->getEditBar();
        // table head
        $thead = $this->getTableThead();
        // table body
        $tbody = $this->getTableTbody();
        // paging section
        $toolBar = $this->getToolBar($this->_param['numCount'], $this->_param['pageNum'], $this->_param['numPerPage']);

        return [
            'pagerForm'        => $pagerForm,
            'searchBar'        => $searchBar,
            'editBar'        => $editBar,
            'thead'        => $thead,
            'tbody'        => $tbody,
            'toolBar'    => $toolBar,
        ];
    }

    /**
     * list pager, it contains  numPerPage , pageNum , totalNum.
     */
    public function getToolBar($numCount, $pageNum, $numPerPage)
    {
        return    '<div class="pages">
					<span>' . Yii::$service->page->translate->__('Show') . '</span>
					<select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value, rel:\"pagerForm2\"})">
						<option '.($numPerPage == 2 ? 'selected' : '').' value="2">2</option>
						<option '.($numPerPage == 6 ? 'selected' : '').' value="6">6</option>
						<option '.($numPerPage == 20 ? 'selected' : '').' value="20">20</option>
						<option '.($numPerPage == 50 ? 'selected' : '').'  value="50">50</option>
						<option '.($numPerPage == 100 ? 'selected' : '').'  value="100">100</option>
						<option '.($numPerPage == 200 ? 'selected' : '').'  value="200">200</option>
					</select>
					<span>' . Yii::$service->page->translate->__('Line, Total {numCount} Line', ['numCount' => $numCount]) . '</span>
				</div>
				<div class="pagination" targetType="navTab" totalCount="'.$numCount.'" numPerPage="'.$numPerPage.'" pageNumShown="10" currentPage="'.$pageNum.'"></div>
				';
    }

    /**
     * get search bar Arr config.
     */
    public function getSearchArr()
    {
        $data = [
            [    // 字符串类型
                'type'  => 'inputtext',
                'title' => 'Name',
                'name'  => 'name',
                'columns_type' => 'string',
                'lang'  => true,
            ],
            [    // 字符串类型
                'type'  => 'inputtext',
                'title' => Yii::$service->page->translate->__('Spu'),
                'name'  => 'spu',
                'columns_type' =>'string',
            ],
            [    // 字符串类型
                'type'  => 'inputtext',
                'title' => Yii::$service->page->translate->__('Sku'),
                'name'  => 'sku',
                'columns_type' =>'string',
            ],
            [    // 时间区间类型搜索
                'type'  => 'inputdatefilter',
                'name'  => 'updated_at',
                'title' => Yii::$service->page->translate->__('Updated At'),
                'columns_type' =>'int',
                'value' => [
                    'gte' => Yii::$service->page->translate->__('Updated Begin'),
                    'lt'  => Yii::$service->page->translate->__('Updated End'),
                ],
            ],
        ];

        return $data;
    }

    /**
     * config function ,return table columns config.
     */
    public function getTableFieldArr()
    {
        $table_th_bar = [
            /*
            [
                'orderField' 	=> $this->_primaryKey,
                'label'			=> 'ID',
                'width'			=> '50',
                'align' 		=> 'center',
            ],
            */
            [
                'orderField'    => 'image_main',
                'label'         => Yii::$service->page->translate->__('Image'),
                'width'         => '30',
                'align'         => 'left',
                'lang'          => true,
            ],
            [
                'orderField'    => 'name',
                'label'         => Yii::$service->page->translate->__('Title'),
                'width'         => '250',
                'align'         => 'left',
                'lang'          => true,
            ],
            [
                'orderField'    => 'spu',
                'label'         => Yii::$service->page->translate->__('Spu'),
                'width'         => '50',
                'align'         => 'center',
            ],
            [
                'orderField'    => 'sku',
                'label'         => Yii::$service->page->translate->__('Sku'),
                'width'         => '50',
                'align'         => 'center',
            ],
            [
                'orderField'    => 'qty',
                'label'         => Yii::$service->page->translate->__('Stock Qty'),
                'width'         => '50',
                'align'         => 'center',
            ],
            [
                'orderField'    => 'created_at',
                'label'         => Yii::$service->page->translate->__('Created At'),
                'width'         => '50',
                'align'         => 'center',
                'convert'       => ['int' => 'datetime'],
            ],
            [
                'orderField'    => 'updated_at',
                'label'         => Yii::$service->page->translate->__('Updated At'),
                'width'         => '50',
                'align'         => 'center',
                'convert'       => ['int' => 'datetime'],
            ],
        ];

        return $table_th_bar;
    }

    public function initDataWhere($searchArr)
    {
        $where = [];
        foreach ($searchArr as $field) {
            $type = $field['type'];
            $name = $field['name'];
            $lang = $field['lang'];
            $columns_type = isset($field['columns_type']) ? $field['columns_type'] : '';
            if ($this->_param[$name] || $this->_param[$name.'_get'] || $this->_param[$name.'_lt']) {
                if ($type == 'inputtext' || $type == 'select' || $type == 'chosen_select') {
                    if ($columns_type == 'string') {
                        if ($lang) {
                            $langname = $name.'.'.\Yii::$service->fecshoplang->getDefaultLangAttrName($name);
                            $where[] = ['like', $langname, $this->_param[$name]];
                        } else {
                            $where[] = ['like', $name, $this->_param[$name]];
                        }
                    } elseif ($columns_type == 'int') {
                        $where[] = [$name => (int) $this->_param[$name]];
                    } elseif ($columns_type == 'float') {
                        $where[] = [$name => (float) $this->_param[$name]];
                    } elseif ($columns_type == 'date') {
                        $where[] = [$name => $this->_param[$name]];
                    } else {
                        $where[] = [$name => $this->_param[$name]];
                    }
                } elseif ($type == 'inputdatefilter') {
                    $_gte = $this->_param[$name.'_gte'];
                    $_lt = $this->_param[$name.'_lt'];
                    if ($columns_type == 'int') {
                        $_gte = strtotime($_gte);
                        $_lt = strtotime($_lt);
                    }
                    if ($_gte) {
                        $where[] = ['>=', $name, $_gte];
                    }
                    if ($_lt) {
                        $where[] = ['<', $name, $_lt];
                    }
                } elseif ($type == 'inputfilter') {
                    $_gte = $this->_param[$name.'_gte'];
                    $_lt = $this->_param[$name.'_lt'];
                    if ($columns_type == 'int') {
                        $_gte = (int) $_gte;
                        $_lt = (int) $_lt;
                    } elseif ($columns_type == 'float') {
                        $_gte = (float) $_gte;
                        $_lt = (float) $_lt;
                    }
                    if ($_gte) {
                        $where[] = ['>=', $name, $_gte];
                    }
                    if ($_lt) {
                        $where[] = ['<', $name, $_lt];
                    }
                } else {
                    $where[] = [$name => $this->_param[$name]];
                }
            }
        }
        $where[] = ['status' => 1];
        if (CRequest::param('productfiltertype') == 'reset') {
        } else {
            if (Yii::$service->product->storage == 'ProductMongodb') {
                $where[] = ['category' => CRequest::param(Yii::$service->category->getPrimaryKey())];
            } else {
                $category_id = CRequest::param(Yii::$service->category->getPrimaryKey());
                $productPrimaryKey = Yii::$service->product->getPrimaryKey();
                $productIds = Yii::$service->product->getProductIdsByCategoryId($category_id);
                $where[] = ['in', $productPrimaryKey, $productIds];
            }
        }
        //var_dump($where);exit;
        return $where;
    }

    /**
     * list table body.
     */
    public function getTableTbody()
    {
        $searchArr = $this->getSearchArr();
        if (is_array($searchArr) && !empty($searchArr)) {
            $where = $this->initDataWhere($searchArr);
        }
        $filter = [
            'numPerPage'    => $this->_param['numPerPage'],
            'pageNum'       => $this->_param['pageNum'],
            'orderBy'       => [$this->_param['orderField'] => (($this->_param['orderDirection'] == 'asc') ? SORT_ASC : SORT_DESC)],
            'where'         => $where,
            'asArray'       => $this->_asArray,
        ];
        $coll = $this->_service->coll($filter);
        $data = $coll['coll'];
        $product_id_arr = [];
        foreach ($data as $one) {
            $product_id_arr[] = $one[\Yii::$service->product->getPrimaryKey()];
        }
        $category_id = CRequest::param(Yii::$service->category->getPrimaryKey());
        $this->_select_id_arr = \Yii::$service->product->getCategoryProductIds($product_id_arr, $category_id);
        // 如果选择
        $product_select_info = CRequest::param('product_select_info');
        $product_unselect_info = CRequest::param('product_unselect_info');

        if ($product_select_info) {
            $product_select_arr = explode(',', $product_select_info);
            $this->_select_id_arr = array_merge($this->_select_id_arr, $product_select_arr);
        }
        if ($product_unselect_info) {
            $product_unselect_arr = explode(',', $product_unselect_info);
            $this->_select_id_arr = array_diff($this->_select_id_arr, $product_unselect_arr);
        }
        $this->_param['numCount'] = $coll['count'];

        return $this->getTableTbodyHtml($data);
    }

    /**
     * rewrite parent getTableTbodyHtml($data).
     */
    public function getTableTbodyHtml($data)
    {
        $fileds = $this->getTableFieldArr();
        $str = '';
        $csrfString = \fec\helpers\CRequest::getCsrfString();
        $user_ids = [];
        foreach ($data as $one) {
            $user_ids[] = $one['created_user_id'];
        }
        $users = Yii::$service->adminUser->getIdAndNameArrByIds($user_ids);
        foreach ($data as $one) {
            $checked = '';
            if (in_array($one[$this->_primaryKey], $this->_select_id_arr)) {
                $checked = 'checked="checked"';
            }
            $str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
            $str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"  '.$checked.'></td>';
            foreach ($fileds as $field) {
                $orderField = $field['orderField'];
                $display = $field['display'];
                $val = $one[$orderField];
                if ($orderField == 'created_user_id') {
                    $val = isset($users[$val]) ? $users[$val] : $val;
                    $str .= '<td>'.$val.'</td>';
                    continue;
                }
                if ($orderField == 'image_main') {
                    if (isset($one['image']['main']['image'])) {
                        $val = $one['image']['main']['image'];
                    }
                    $imgUrl = Yii::$service->product->image->getUrl($val);
                    $str .= '<td><img style="width:100px;height:100px;" src="'.$imgUrl.'" /></td>';
                    continue;
                }
                if ($val) {
                    if (isset($field['display']) && !empty($field['display'])) {
                        $display = $field['display'];
                        $val = $display[$val] ? $display[$val] : $val;
                    }
                    if (isset($field['convert']) && !empty($field['convert'])) {
                        $convert = $field['convert'];
                        foreach ($convert as $origin =>$to) {
                            if (strstr($origin, 'mongodate')) {
                                if (isset($val->sec)) {
                                    $timestramp = $val->sec;
                                    if ($to == 'date') {
                                        $val = date('Y-m-d', $timestramp);
                                    } elseif ($to == 'datetime') {
                                        $val = date('Y-m-d H:i:s', $timestramp);
                                    } elseif ($to == 'int') {
                                        $val = $timestramp;
                                    }
                                }
                            } elseif (strstr($origin, 'date')) {
                                if ($to == 'date') {
                                    $val = date('Y-m-d', strtotime($val));
                                } elseif ($to == 'datetime') {
                                    $val = date('Y-m-d H:i:s', strtotime($val));
                                } elseif ($to == 'int') {
                                    $val = strtotime($val);
                                }
                            } elseif ($origin == 'int') {
                                if ($to == 'date') {
                                    $val = date('Y-m-d', $val);
                                } elseif ($to == 'datetime') {
                                    $val = date('Y-m-d H:i:s', $val);
                                } elseif ($to == 'int') {
                                    $val = $val;
                                }
                            } elseif ($origin == 'string') {
                                if ($to == 'img') {
                                    $t_width = isset($field['img_width']) ? $field['img_width'] : '100';
                                    $t_height = isset($field['img_height']) ? $field['img_height'] : '100';
                                    $val = '<img style="width:'.$t_width.'px;height:'.$t_height.'px" src="'.$val.'" />';
                                }
                            }
                        }
                    }
                    if (isset($field['lang']) && !empty($field['lang'])) {
                        //var_dump($val);
                        //var_dump($orderField);
                        $val = Yii::$service->fecshoplang->getDefaultLangAttrVal($val, $orderField);
                    }
                }
                $str .= '<td>'.$val.'</td>';
            }
            $str .= '</tr>';
        }

        return $str;
    }

    public function getTableTheadHtml($table_th_bar)
    {
        $table_th_bar = $this->getTableTheadArrInit($table_th_bar);
        $this->_param['orderField'] = $this->_param['orderField'] ? $this->_param['orderField'] : $this->_primaryKey;
        $this->_param['orderDirection'] = $this->_param['orderDirection'];
        foreach ($table_th_bar as $k => $field) {
            if ($field['orderField'] == $this->_param['orderField']) {
                $table_th_bar[$k]['class'] = $this->_param['orderDirection'];
            }
        }
        $str = '<thead><tr>';
        $str .= '<th width="22"><input type="checkbox" group="'.$this->_primaryKey.'s" class="checkboxCtrl"></th>';
        foreach ($table_th_bar as $b) {
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
     * @param $data|array
     */
    public function getDbSearchBarHtml($data)
    {
        $searchBar = '';
        if (!empty($data)) {
            $searchBar .= '<input type="hidden" name="search_type" value="search"  />';
            $searchBar .= '<table class="searchContent">
					<tr>';
            foreach ($data as $d) {
                $type = $d['type'];
                $name = $d['name'];
                $title = $d['title'];
                $value = $d['value'];
                if ($d['type'] == 'select') {
                    $searchBar .= '<td>
										'.$value.'
									</td>';
                } elseif ($d['type'] == 'chosen_select') {
                    $searchBar .= '<td>
										'.$value.'
									</td>';
                } elseif ($d['type'] == 'inputtext') {
                    $searchBar .= '<td>
										'.$title.':<input type="text" value="'.(is_array($this->_param[$name]) ? $this->_param[$name]['$regex'] : $this->_param[$name]).'" name="'.$name.'" />
									</td>';
                } elseif ($d['type'] == 'inputdate') {
                    $searchBar .= '<td>
										'.$title.'<input type="text" value="'.$this->_param[$name].'" name="'.$name.'"  class="date" readonly="true" />
									</td>';
                } elseif ($d['type'] == 'inputdatefilter') {
                    $value = $d['value'];
                    if (is_array($value)) {
                        foreach ($value as $t=>$title) {
                            $searchBar .= '<td>
								'.$title.'<input type="text" value="'.$this->_param[$name.'_'.$t].'" name="'.$name.'_'.$t.'"  class="date" readonly="true" />
							</td>';
                        }
                    }
                } elseif ($d['type'] == 'inputfilter') {
                    $value = $d['value'];
                    if (is_array($value)) {
                        foreach ($value as $t=>$title) {
                            $searchBar .= '<td>
								'.$title.'<input type="text" value="'.$this->_param[$name.'_'.$t].'" name="'.$name.'_'.$t.'"    />
							</td>';
                        }
                    }
                }
            }
            $customSearchHtml = $this->customSearchBarHtml();
            $searchBar .= $customSearchHtml;
            $searchBar .= '</tr>
				</table>
				<div class="subBar">
					<ul>
						<li><a class="button productReset" ><span>' . Yii::$service->page->translate->__('All Product List') . '</span></a></li>
						<li><a class="button productSearch" ><span>' . Yii::$service->page->translate->__('Current Cateory Product') . '</span></a></li>
					</ul>
				</div>';
        }

        return $searchBar;
    }
}
