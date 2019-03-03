<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Sales\block\orderinfo;

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
    protected $_exportExcelUrl;
    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        /*
         * edit data url
         */
        $this->_editUrl = CUrl::getUrl('sales/orderinfo/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('sales/orderinfo/managerdelete');
        
        $this->_exportExcelUrl = CUrl::getUrl('sales/orderinfo/managerexport');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->order;
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
            'pagerForm'   => $pagerForm,
            'searchBar'    => $searchBar,
            'editBar'        => $editBar,
            'thead'          => $thead,
            'tbody'          => $tbody,
            'toolBar'        => $toolBar,
        ];
    }

    /**
     * get search bar Arr config.
     */
    public function getSearchArr()
    {
        $data = [
            [    // 字符串类型
                'type' => 'inputtext',
                'title'  => Yii::$service->page->translate->__('Increment Id'),
                'name' => 'increment_id',
                'columns_type' => 'string',
            ],
            [    // selecit的Int 类型
                'type' => 'select',
                'title'  => Yii::$service->page->translate->__('Order Status')
                ,
                'name' => 'order_status',
                'columns_type' => 'string',  // int使用标准匹配， string使用模糊查询
                'value' => Yii::$service->order->getSelectStatusArr(),
            ],
            [    // 字符串类型
                'type' => 'inputtext',
                'title'  => Yii::$service->page->translate->__('Order Email'),
                'name' => 'customer_email',
                'columns_type' => 'string',
            ],
            [    // 时间区间类型搜索
                'type' => 'inputdatefilter',
                'title'  => Yii::$service->page->translate->__('Created At'),
                'name' => 'created_at',
                'columns_type' => 'int',
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
        $table_th_bar = [
            [
                'orderField'   => $this->_primaryKey,
                'label'          => Yii::$service->page->translate->__('Id'),
                'width'         => '50',
                'align'          => 'center',
            ],
            [
                'orderField'   => 'increment_id',
                'label'          => Yii::$service->page->translate->__('Increment Id'),
                'width'         => '50',
                'align'          => 'left',
                //'lang'		  => true,
            ],
            [
                'orderField'   => 'created_at',
                'label'          => Yii::$service->page->translate->__('Created At'),
                'width'         => '50',
                'align'          => 'left',
                'convert'      => ['int' => 'date'],
                //'lang'        => true,
            ],
            [
                'orderField'   => 'order_status',
                'label'          => Yii::$service->page->translate->__('Order Status'),
                'width'         => '50',
                'align'          => 'left',
                //'lang'        => true,
            ],
            [
                'orderField'   => 'items_count',
                'label'          => Yii::$service->page->translate->__('Itmes Count'),
                'width'         => '50',
                'align'          => 'left',
                //'lang'        => true,
            ],
            [
                'orderField'   => 'total_weight',
                'label'          => Yii::$service->page->translate->__('Total Weight') ,
                'width'         => '50',
                'align'          => 'left',
                //'lang'        => true,
            ],
            [
                'orderField'    => 'base_grand_total',
                'label'           => Yii::$service->page->translate->__('Base Grand Total'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
            ],
            [
                'orderField'    => 'payment_method',
                'label'           => Yii::$service->page->translate->__('Payment Method'),
                'width'          => '50',
                'align'           => 'left',
                'display'        => Yii::$service->payment->getPaymentLabels(),
                //'lang'		   => true,
            ],
            [
                'orderField'   => 'shipping_method',
                'label'          => Yii::$service->page->translate->__('Shipping Method'),
                'width'         => '50',
                'align'          => 'left',
                //'lang'		  => true,
            ],
            [
                'orderField'    => 'base_shipping_total',
                'label'           => Yii::$service->page->translate->__('Base Shipping Total'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
            ],
            [
                'orderField'   => 'customer_address_country',
                'label'          => Yii::$service->page->translate->__('Country'),
                'width'         => '50',
                'align'          => 'left',
                //'lang'		  => true,
            ],
            [
                'orderField'    => 'customer_email',
                'label'           => Yii::$service->page->translate->__('Customer Email'),
                'width'          => '50',
                'align'           => 'left',
                //'lang'		   => true,
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
        $str = '';
        $csrfString = CRequest::getCsrfString();
        $user_ids = [];
        foreach ($data as $one) {
            $user_ids[] = $one['created_person'];
        }
        $users = Yii::$service->adminUser->getIdAndNameArrByIds($user_ids);
        foreach ($data as $one) {
            $str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
            $str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
            foreach ($fileds as $field) {
                $orderField = $field['orderField'];
                $display = $field['display'];
                $val = $one[$orderField];
                if ($orderField == 'created_person') {
                    $val = isset($users[$val]) ? $users[$val] : $val;
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
						<!-- <a title="' . Yii::$service->page->translate->__('Delete') . '" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$csrfString.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel"><i class="fa fa-trash-o"></i></a>
						-->
					</td>';
            $str .= '</tr>';
        }

        return $str;
    }
    
    
    /**
     * get edit html bar, it contains  add ,eidt ,delete  button.
     */
    public function getEditBar()
    {
        /*
        if(!strstr($this->_currentParamUrl,"?")){
            $csvUrl = $this->_currentParamUrl."?type=export";
        }else{
            $csvUrl = $this->_currentParamUrl."&type=export";
        }
        target="dwzExport" targetType="navTab"  rel="'.$this->_primaryKey.'s"
        <li class="line">line</li>
        <li><a class="icon csvdownload"   href="'.$csvUrl.'" target="dwzExport" targetType="navTab" title="实要导出这些记录吗?"><span>导出EXCEL</span></a></li>
        */
        return '<ul class="toolBar">
					<li class="line">line</li>
                    <li><a class="icon exportOrderExcel" href="javascript:void()"  postType="string"  target="_blank" title="' . Yii::$service->page->translate->__('Are you sure you want to export the selected order') . '?"><span>' . Yii::$service->page->translate->__('Export Excel') . '</span></a></li>
				</ul>
                <script>
                    $(document).ready(function(){
                        $(".exportOrderExcel").click(function(){
                            var selectOrderIds = \'\';
                            $(\'.grid input:checkbox[name=order_ids]:checked\').each(function(k){
                                if(k == 0){
                                    selectOrderIds = $(this).val();
                                }else{
                                    selectOrderIds += \',\'+$(this).val();
                                }
                            });
                            if (!selectOrderIds) {
                                var message = "' . Yii::$service->page->translate->__('Choose at least one order') . '";
                                alertMsg.error(message);
                            } else {
                                url = "'.$this->_exportExcelUrl.'" ;
                                doPost(url, {"order_ids": selectOrderIds, "'.CRequest::getCsrfName().'": "'.CRequest::getCsrfValue() .'"});
                            }
                        });
                    });
                </script> 
        ';
    }
    
}
