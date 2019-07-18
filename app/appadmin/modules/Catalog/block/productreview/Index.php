<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\productreview;

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
class Index extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
    public $_auditUrl;
    public $_auditRejectedUrl;

    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        $this->_auditUrl = CUrl::getUrl('catalog/productreview/manageraudit');
        $this->_auditRejectedUrl = CUrl::getUrl('catalog/productreview/managerauditrejected');
        /*
         * edit data url
         */
        $this->_editUrl = CUrl::getUrl('catalog/productreview/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('catalog/productreview/managerdelete');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->product->review;
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
            'pagerForm'        => $pagerForm,
            'searchBar'        => $searchBar,
            'editBar'        => $editBar,
            'thead'        => $thead,
            'tbody'        => $tbody,
            'toolBar'    => $toolBar,
        ];
    }

    /**
     * get search bar Arr config.
     */
    public function getSearchArr()
    {
        $activeStatus = Yii::$service->product->review->activeStatus();
        $refuseStatus = Yii::$service->product->review->refuseStatus();
        $noActiveStatus = Yii::$service->product->review->noActiveStatus();
        $data = [
            [    // selecit的Int 类型
                'type' => 'select',
                'title' => Yii::$service->page->translate->__('Review Status'),
                'name' => 'status',
                'columns_type' => 'int',  // int使用标准匹配， string使用模糊查询
                'value' => [                    // select 类型的值
                    $noActiveStatus => Yii::$service->page->translate->__('Pending Review'),
                    $activeStatus    => Yii::$service->page->translate->__('Approved'),
                    $refuseStatus    => Yii::$service->page->translate->__('Not Approved'),
                ],
            ],
            [    // 字符串类型
                'type' => 'inputtext',
                'title'  => Yii::$service->page->translate->__('Spu'),
                'name' => 'product_spu',
                'columns_type' => 'string',
            ],
            [    // 时间区间类型搜索
                'type' => 'inputdatefilter',
                'title'  => Yii::$service->page->translate->__('Review Date'),
                'name' => 'review_date',
                'columns_type' => 'int',
                'value'=>[
                    'gte' => Yii::$service->page->translate->__('Review Begin'),
                    'lt' => Yii::$service->page->translate->__('Review End'),
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
        $activeStatus = Yii::$service->product->review->activeStatus();
        $refuseStatus = Yii::$service->product->review->refuseStatus();
        $noActiveStatus = Yii::$service->product->review->noActiveStatus();
        $reviewPrimaryKey = Yii::$service->product->review->getPrimaryKey();
        $table_th_bar = [
            [
                'orderField'    => $reviewPrimaryKey,
                'label'           => Yii::$service->page->translate->__('Id'),
                'width'          => '50',
                'align'           => 'left',

            ],
            [
                'orderField'     => 'product_id',
                'label'            => Yii::$service->page->translate->__('Product Id'),
                'width'           => '180',
                'align'            => 'left',
            ],

            [
                'orderField'    => 'rate_star',
                'label'           => Yii::$service->page->translate->__('Rate Star'),
                'width'          => '110',
                'align'           => 'center',
                'width'          => '30',
            ],

            [
                'orderField'    => 'name',
                'label'           => Yii::$service->page->translate->__('Review Person'),
                'width'          => '110',
                'align'           => 'left',
            ],

            [
                'orderField'    => 'summary',
                'label'           => Yii::$service->page->translate->__('Summary'),
                'width'          => '110',
                'align'           => 'left',
            ],

            [
                'orderField'    => 'review_date',
                'label'           => Yii::$service->page->translate->__('Review Date'),
                'width'          => '110',
                'align'           => 'center',
                'convert'       => ['int' => 'datetime'],
            ],

            [
                'orderField'    => 'store',
                'label'           => Yii::$service->page->translate->__('Store'),
                'width'          => '110',
                'align'           => 'left',
            ],

            [
                'orderField'    => 'lang_code',
                'label'           => Yii::$service->page->translate->__('Lang Code'),
                'width'          => '65',
                'align'           => 'center',

            ],

            [
                'orderField'    => 'status',
                'label'           => Yii::$service->page->translate->__('Status'),
                'width'          => '120',
                'display'        => [
                    $noActiveStatus => Yii::$service->page->translate->__('Pending Review'),
                    $activeStatus     => Yii::$service->page->translate->__('Approved'),
                    $refuseStatus    => Yii::$service->page->translate->__('Not Approved'),
                ],   
            ],
            [
                'orderField'    => 'audit_user',
                'label'            => Yii::$service->page->translate->__('Audit Person'),
                'width'            => '110',
                'align'        => 'left',
            ],

            [
                'orderField'    => 'audit_date',
                'label'            => Yii::$service->page->translate->__('Audit Date'),
                'width'            => '110',
                'align'        => 'center',
                'convert'        => ['int' => 'datetime'],
            ],

        ];

        return $table_th_bar;
    }

    /**
     * rewrite parent getTableTbodyHtml($data).
     */
    public function getTableTbodyHtml($data)
    {
        $fileds = $this->getTableFieldArr();
        $str .= '';
        $csrfString = \fec\helpers\CRequest::getCsrfString();
        $user_ids = [];
        $product_ids = [];
        foreach ($data as $one) {
            $user_ids[] = $one['audit_user'];
            $product_ids[] = $one['product_id'];
        }
        $users = Yii::$service->adminUser->getIdAndNameArrByIds($user_ids);
        $product_skus = Yii::$service->product->getSkusByIds($product_ids);

        foreach ($data as $one) {
            $str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
            $str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
            foreach ($fileds as $field) {
                $orderField = $field['orderField'];
                $display = $field['display'];
                $val = $one[$orderField];
                if ($orderField == 'audit_user') {
                    //var_dump($users);
                    $val = isset($users[$val]) ? $users[$val] : $val;
                    $str .= '<td>'.$val.'</td>';
                    continue;
                }
                if ($orderField == 'product_id') {
                    //echo 11;
                    //var_dump($product_skus);
                    $val = isset($product_skus[$val]) ? $product_skus[$val] : $val;
                    $str .= '<td>'.$val.'</td>';
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
            $str .= '<td>
						<a title="' . Yii::$service->page->translate->__('Edit') . '" target="dialog" class="btnEdit" mask="true" drawable="true" width="1200" height="680" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" ><i class="fa fa-pencil"></i></a>
						<a title="' . Yii::$service->page->translate->__('Delete') . '" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel"  csrfName="' .CRequest::getCsrfName(). '" csrfVal="' .CRequest::getCsrfValue(). '"><i class="fa fa-trash-o"></i></a>
					</td>';
            $str .= '</tr>';
        }

        return $str;
    }

    public function getEditBar()
    {
        return '<ul class="toolBar">
					<li><a csrfName="' .CRequest::getCsrfName(). '" csrfVal="' .CRequest::getCsrfValue(). '" title="' . Yii::$service->page->translate->__('Are you sure you want to review these reviews in bulk') . '?" target="selectedTodo" rel="'.$this->_primaryKey.'s" postType="string" href="'.$this->_auditUrl.'" class="edit"><span>' . Yii::$service->page->translate->__('Bulk Approved') . '</span></a></li>
					<li><a csrfName="' .CRequest::getCsrfName(). '" csrfVal="' .CRequest::getCsrfValue(). '" title="' . Yii::$service->page->translate->__('Are you sure you want to reject these reviews in bulk') . '?" target="selectedTodo" rel="'.$this->_primaryKey.'s" postType="string" href="'.$this->_auditRejectedUrl.'" class="edit"><span>' . Yii::$service->page->translate->__('Bulk Not Approved') . '</span></a></li>
					
					<li><a target="dialog" height="680" width="1200" drawable="true" mask="true" class="edit" href="'.$this->_editUrl.'?'.$this->_primaryKey.'={sid_user}" ><span>' . Yii::$service->page->translate->__('Edit') . '</span></a></li>
					<li><a csrfName="' .CRequest::getCsrfName(). '" csrfVal="' .CRequest::getCsrfValue(). '" title="' . Yii::$service->page->translate->__('Are you sure you want to delete these reviews in bulk') . '?" target="selectedTodo" rel="'.$this->_primaryKey.'s" postType="string" href="'.$this->_deleteUrl.'" class="delete"  ><span>' . Yii::$service->page->translate->__('Bulk Delete') . '</span></a></li>
				</ul>';
    }
}
