<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\productbrand;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manager extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        /*
         * edit data url
         */
        $this->_editUrl = Yii::$service->url->getUrl('catalog/productbrand/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = Yii::$service->url->getUrl('catalog/productbrand/managerdelete');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->product->brand;
        parent::init();
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
            'pagerForm'     => $pagerForm,
            'searchBar'      => $searchBar,
            'editBar'          => $editBar,
            'thead'            => $thead,
            'tbody'            => $tbody,
            'toolBar'          => $toolBar,
        ];
    }
    /**
     * get search bar Arr config.
     */
    public function getSearchArr()
    {
        $data = [
            [    // selecit的Int 类型
                'type' => 'select',
                'title'  => Yii::$service->page->translate->__('Status'),
                'name' => 'status',
                'columns_type' => 'int',  // int使用标准匹配， string使用模糊查询
                'value' => [                    // select 类型的值
                    1 => Yii::$service->page->translate->__('Enable'),
                    2 => Yii::$service->page->translate->__('Disable'),
                ],
            ],
            
            [    // 时间区间类型搜索
                'type'  => 'inputdatefilter',
                'name' => 'created_at',
                'columns_type' =>'int',
                'value' => [
                    'gte' => Yii::$service->page->translate->__('Created Begin'),
                    'lt'    => Yii::$service->page->translate->__('Created End'),
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
        $brandCategorys = Yii::$service->product->brandcategory->getBrandCategoryIdAndNames(); 
        $brandStatus = Yii::$service->product->brandcategory->getStatusArr();
        
        $table_th_bar = [
            [
                'orderField'    => $this->_primaryKey,
                'label'           => Yii::$service->page->translate->__('Id'),
                'width'          => '50',
                'align'           => 'center',
            ],
            [
                'orderField'    => 'name',
                'label'           => Yii::$service->page->translate->__('Brand Name'),
                'width'          => '50',
                'align'           => 'left',
                'lang'            => true,
            ],
            [
                'orderField'    => 'brand_category_id',
                'label'           => Yii::$service->page->translate->__('Brand Category'),
                'width'          => '50',
                'align'           => 'center',
                'display'        => $brandCategorys,
            ],
            [
                'orderField'    => 'logo',
                'label'           => Yii::$service->page->translate->__('Logo'),
                'width'          => '110',
                'align'           => 'center',
                'convert'        => [ 'string' => 'img'],
                'img_height'    => 50,
            ],
            [
                'orderField'    => 'website_url',
                'label'           => Yii::$service->page->translate->__('Website Url'),
                'width'          => '110',
                'align'           => 'center',
            ],
            
            [
                'orderField'    => 'status',
                'label'           => Yii::$service->page->translate->__('Status'),
                'width'          => '50',
                'align'           => 'center',
                'display'        => $brandStatus,
            ],
            
            
            [
                'orderField'    => 'created_at',
                'label'           => Yii::$service->page->translate->__('Created At'),
                'width'          => '110',
                'align'           => 'center',
                'convert'       => ['int' => 'datetime'],
            ],
            [
                'orderField'    => 'updated_at',
                'label'           => Yii::$service->page->translate->__('Updated At'),
                'width'          => '110',
                'align'           => 'center',
                'convert'       => ['int' => 'datetime'],
            ],
        ];

        return $table_th_bar;
    }
    
    
    
    public function getTableTbodyHtml($data)
    {
        $fields = $this->getTableFieldArr();
        $str = '';
        $csrfString = CRequest::getCsrfString();
        foreach ($data as $one) {
            $str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
            $str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
            foreach ($fields as $field) {
                $orderField = $field['orderField'];
                $display = $field['display'];
                $translate = $field['translate'];
                $val = $one[$orderField];
                $display_title = '';
                if ($val) {
                    if (isset($field['display']) && !empty($field['display'])) {
                        $display = $field['display'];
                        $val = $display[$val] ? $display[$val] : $val;
                        $display_title = $val;
                    }
                    if (isset($field['convert']) && !empty($field['convert'])) {
                        $convert = $field['convert'];
                        foreach ($convert as $origin =>$to) {
                            if (strstr($origin, 'mongodate')) {
                                if (isset($val->sec)) {
                                    $timestamp = $val->sec;
                                    if ($to == 'date') {
                                        $val = date('Y-m-d', $timestamp);
                                    } elseif ($to == 'datetime') {
                                        $val = date('Y-m-d H:i:s', $timestamp);
                                    } elseif ($to == 'int') {
                                        $val = $timestamp;
                                    }
                                    $display_title = $val;
                                }
                            } elseif (strstr($origin, 'date')) {
                                if ($to == 'date') {
                                    $val = date('Y-m-d', strtotime($val));
                                } elseif ($to == 'datetime') {
                                    $val = date('Y-m-d H:i:s', strtotime($val));
                                } elseif ($to == 'int') {
                                    $val = strtotime($val);
                                }
                                $display_title = $val;
                            } elseif ($origin == 'int') {
                                if ($to == 'date') {
                                    $val = date('Y-m-d', $val);
                                } elseif ($to == 'datetime') {
                                    $val = date('Y-m-d H:i:s', $val);
                                } elseif ($to == 'int') {
                                    $val = $val;
                                }
                                $display_title = $val;
                            } elseif ($origin == 'string') {
                                $val = Yii::$service->category->image->getUrl($val);
                                if ($to == 'img') {
                                    $val = '<img style="max-height:50px" src="'.$val.'" />';
                                }
                            }
                        }
                    }
                    if (isset($field['lang']) && !empty($field['lang'])) {
                        $val = Yii::$service->fecshoplang->getDefaultLangAttrVal($val, $orderField);
                    }
                }
                if ($translate) {
                    $val = Yii::$service->page->translate->__($val);
                }
                $str .= '<td><span title="'.$display_title.'">'.$val.'</span></td>';
            }
            $str .= '<td>
						<a title="' . Yii::$service->page->translate->__('Edit') . '" target="dialog" class="btnEdit" mask="true" drawable="true" width="1200" height="680" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" ><i class="fa fa-pencil"></i></a>
						<a title="' . Yii::$service->page->translate->__('Delete') . '" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel"  csrfName="' .CRequest::getCsrfName(). '" csrfVal="' .CRequest::getCsrfValue(). '"  ><i class="fa fa-trash-o"></i></a>
					</td>';
            $str .= '</tr>';
        }

        return $str;
    }
    
    
}
