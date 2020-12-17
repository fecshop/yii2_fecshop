<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\productinfo;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
//use fecshop\app\appadmin\modules\Catalog\helper\Product as ProductHelper;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends AppadminbaseBlock implements AppadminbaseBlockInterface
{
    protected $_copyUrl;
    
    /**
     * 为了可以使用rewriteMap，use 引入的文件统一采用下面的方式，通过Yii::mapGet()得到className和Object
     */
    protected $_productHelperName = '\fecshop\app\appadmin\modules\Catalog\helper\Product';
    protected $_productHelper;
    
    protected $_batchInsertUrl;
    
    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        /**
         * 通过Yii::mapGet() 得到重写后的class类名以及对象。Yii::mapGet是在文件@fecshop\yii\Yii.php中
         */
        list($this->_productHelperName,$this->_productHelper) = Yii::mapGet($this->_productHelperName);  
        
        /*
         * edit data url
         */
        $this->_editUrl = CUrl::getUrl('catalog/productinfo/manageredit');
        $this->_batchInsertUrl = CUrl::getUrl('catalog/productinfo/managerbatchedit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('catalog/productinfo/managerdelete');
        $this->_copyUrl = CUrl::getUrl('catalog/productinfo/manageredit', ['operate'=>'copy']);
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->product;
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
            'searchBar'         => $searchBar,
            'editBar'             => $editBar,
            'thead'               => $thead,
            'tbody'               => $tbody,
            'toolBar'             => $toolBar,
        ];
    }

    /**
     * get search bar Arr config.
     */
    public function getSearchArr()
    {
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        // 判断mongodb还是mysql存储product数据
        $nameIsLang = $productPrimaryKey == '_id' ? true : false;
        $data = [
            [    // selecit的Int 类型
                'type'  => 'select',
                'title'   => Yii::$service->page->translate->__('Status'),
                'name' => 'status',
                'columns_type' => 'int',  // int使用标准匹配， string使用模糊查询
                'value'  => $this->_productHelper->getStatusArr(),
            ],
            [    // selecit的Int 类型
                'type' => 'select',
                'title'  => Yii::$service->page->translate->__('Stock Status'),
                'name' => 'is_in_stock',
                'columns_type' => 'int',  // int使用标准匹配， string使用模糊查询
                'value' => $this->_productHelper->getInStockArr(),
            ],
            [    // 字符串类型
                'type'  => 'inputtext',
                'title'   => Yii::$service->page->translate->__('Product Name'),
                'name'  => 'name',
                'columns_type' => 'string',
                'lang' => $nameIsLang,
            ],
            [    // 字符串类型
                'type' => 'inputtext',
                'title'  => Yii::$service->page->translate->__('Spu'),
                'name' => 'spu',
                'columns_type' => 'string',
            ],
            [    // 字符串类型
                'type' => 'inputtext',
                'title'  => Yii::$service->page->translate->__('Sku'),
                'name' => 'sku',
                'columns_type' => 'string',
                
            ],
            [    // 时间区间类型搜索
                'type' => 'inputdatefilter',
                'name' => 'updated_at',
                'columns_type' => 'int',
                'value' => [
                    'gte' => Yii::$service->page->translate->__('Updated Begin'),
                    'lt'    => Yii::$service->page->translate->__('Updated End'),
                ],
            ],
            [    // 时间区间类型搜索
                'type'   => 'inputfilter',
                'name' => 'qty',
                'columns_type' => 'int',
                'value' => [
                    'gte' => Yii::$service->page->translate->__('Stock Qty Begin'),
                    'lt'    => Yii::$service->page->translate->__('Stock Qty End'),
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
                'orderField'      => $this->_primaryKey,
                'label'             => Yii::$service->page->translate->__('Id'),
                'width'            => '90',
                'align'             => 'center',
            ],
            [
                'orderField'      => 'image_main',
                'label'             => Yii::$service->page->translate->__('Image'),
                'width'            => '120',
                'align'             => 'left',
                'lang'              => true,
            ],
            [
                'orderField'     => 'name',
                'label'            => Yii::$service->page->translate->__('Title'),
                'width'           => '200',
                'align'            => 'left',
                'lang'             => true,
            ],
            [
                'orderField'     => 'spu',
                'label'            => Yii::$service->page->translate->__('Spu'),
                'width'           => '110',
                'align'            => 'left',
            ],
            [
                'orderField'    => 'sku',
                'label'            => Yii::$service->page->translate->__('Sku'), 
                'width'          => '110',
                'align'           => 'left',
            ],
            [
                'orderField'    => 'qty',
                'label'           => Yii::$service->page->translate->__('Stock Qty'),
                'width'          => '50',
                'align'           => 'left',
            ],
            //[
            //    'orderField'    => 'weight',
            //    'label'           => Yii::$service->page->translate->__('Weight'),
            //    'width'          => '50',
            //    'align'           => 'left',
            //],
            [
                'orderField'    => 'status',
                'label'           => Yii::$service->page->translate->__('Status'),
                'width'          => '50',
                'align'           => 'left',
                'display'        => $this->_productHelper->getStatusArr(),
            ],
            [
                'orderField'    => 'price',
                'label'           => Yii::$service->page->translate->__('Sale Price') ,
                'width'          => '50',
                'align'           => 'left',
            ],
            [
                'orderField'    => 'created_user_id',
                'label'           => Yii::$service->page->translate->__('Created Person'),
                'width'          => '90',
                'align'           => 'left',
            ],
            [
                'orderField'    => 'created_at',
                'label'           => Yii::$service->page->translate->__('Created At'),
                'width'          => '100',
                'align'           => 'left',
                'convert'       => ['int' => 'date'],
            ],
            [
                'orderField'    => 'updated_at',
                'label'           => Yii::$service->page->translate->__('Updated At'),
                'width'          => '100',
                'align'           => 'left',
                'convert'       => ['int' => 'date'],
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
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        foreach ($data as $one) {
            $user_ids[] = $one['created_user_id'];
            $product_ids[] = (string)$one[$productPrimaryKey];
        }
        //var_dump($product_ids);
        $users = Yii::$service->adminUser->getIdAndNameArrByIds($user_ids);
        $qtys  = Yii::$service->product->stock->getQtyByProductIds($product_ids);
        //var_dump($qtys );
        foreach ($data as $one) {
            $str .= '<tr target="sid_user" rel="'.$one[$this->_primaryKey].'">';
            $str .= '<td><input name="'.$this->_primaryKey.'s" value="'.$one[$this->_primaryKey].'" type="checkbox"></td>';
            $p_id = (string)$one[$this->_primaryKey];
            foreach ($fileds as $field) {
                $orderField = $field['orderField'];
                $display = $field['display'];
                $val = isset($one[$orderField]) ? $one[$orderField] : '';
                $display_title = '';
                if ($orderField == 'created_user_id') {
                    $val = isset($users[$val]) ? $users[$val] : $val;
                    $display_title = $val;
                    $str .= '<td><span title="'.$display_title.'">'.$val.'</span></td>';
                    continue;
                }else if($orderField == 'qty'){
                    $val = $qtys[$p_id];
                    $display_title = $val;
                    $str .= '<td><span title="'.$display_title.'">'.$val.'</span></td>';
                    continue;
                } else if($orderField == 'status'){
                    $valLabel = $display[$val] ? $display[$val] : $val;
                    if ($val == 1) {
                        $str .= '<td><span title="'.$valLabel.'" style="background: #5eb95e; color: #fff; padding:4px 6px;  width: auto; display: inline;">'.$valLabel.'</span></td>';
                    } else {
                        $str .= '<td><span title="'.$valLabel.'" style="background: #cc0000; color: #fff; padding:4px 6px;  width: auto; display: inline;">'.$valLabel.'</span></td>';
                    }
                    continue;
                    
                }
                if ($orderField == $this->_primaryKey) {
                    $display_title = $val;
                    $str .= '<td><span style="width:60px;display:block;word-break:break-all;" title="'.$display_title.'">'.$val.'</span></td>';
                    continue;
                }

                if ($orderField == 'image_main') {
                    if (isset($one['image']['main']['image'])) {
                        $val = $one['image']['main']['image'];
                    }
                    $imgUrl = Yii::$service->product->image->getUrl($val);
                    $str .= '<td><span style="display:block;padding:5px 5px;" title="'.$imgUrl.'"><img style="margin:auto;display:block;max-width:50px;max-height:50px;" src="'.$imgUrl.'" /></span></td>';
                    continue;
                }
                if ($val) {
                    $display_title = $val;
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
                                    $timestramp = $val->sec;
                                    if ($to == 'date') {
                                        $val = date('Y-m-d', $timestramp);
                                    } elseif ($to == 'datetime') {
                                        $val = date('Y-m-d H:i:s', $timestramp);
                                    } elseif ($to == 'int') {
                                        $val = $timestramp;
                                    }
                                }
                                $display_title = $val;
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
                                if ($to == 'img') {
                                    $t_width = isset($field['img_width']) ? $field['img_width'] : '100';
                                    $t_height = isset($field['img_height']) ? $field['img_height'] : '100';
                                    $display_title = $val;
                                    $val = '<img style="margin:auto;display:block;max-width:'.$t_width.'px;max-height:'.$t_height.'px" src="'.$val.'" />';
                                }
                            }
                        }
                    }

                    if (isset($field['lang']) && !empty($field['lang'])) {
                        //var_dump($val);
                        //var_dump($orderField);
                        $val = Yii::$service->fecshoplang->getDefaultLangAttrVal($val, $orderField);
                        $display_title = $val;
                    }
                }
                $str .= '<td><span title="'.$display_title.'">'.$val.'</span></td>';
            }
            $str .= '<td>
						<a style="width:17px;" title="' . Yii::$service->page->translate->__('Edit')  . '" target="dialog" class="btnEdit" mask="true" drawable="true" width="1200" height="680" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" ><i class="fa fa-pencil" style="font-size:16px;"></i></a>
						<a style="width:17px;" title="' . Yii::$service->page->translate->__('Delete')  . '" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel"  csrfName="' .CRequest::getCsrfName(). '" csrfVal="' .CRequest::getCsrfValue(). '" ><i style="font-size:16px;" class="fa fa-trash-o"></i></a>
						<a style="width:17px;margin:3px 0 0" title="' . Yii::$service->page->translate->__('Copy')  . '" target="dialog" class="btnEdit" mask="true" drawable="true" width="1200" height="680" href="'.$this->_copyUrl.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" ><span><i style="font-size:16px;" class="fa fa-copy"></i></span></a>
					</td>';
            $str .= '</tr>';
        }

        return $str;
    }
    
    public $productView;
    /**
     * list table body.
     */
    public function getTableTbody()
    {
        $searchArr = $this->getSearchArr();
        if (is_array($searchArr) && !empty($searchArr)) {
            $where = $this->initDataWhere($searchArr);
        }
        // 查看role,通过resource，判断当前用户是否有查看所有产品的权限，默认，用户只有查看自己发布的产品，而不能查看其他用户的产品
        $resources = Yii::$service->admin->role->getCurrentRoleResources();
        $viewAllKey = Yii::$service->admin->role->productViewAllRoleKey;
        if (!is_array($resources) || !isset($resources[$viewAllKey]) || !$resources[$viewAllKey]) {
            $user = Yii::$app->user->identity;
            $where[] = [
                'created_user_id' => $user->Id,
            ];
        }
        //var_dump($where);
        $filter = [
            'numPerPage'    => $this->_param['numPerPage'],
            'pageNum'        => $this->_param['pageNum'],
            'orderBy'          => [$this->_param['orderField'] => (($this->_param['orderDirection'] == 'asc') ? SORT_ASC : SORT_DESC)],
            'where'            => $where,
            'asArray'          => $this->_asArray,
        ];
        $coll = $this->_service->coll($filter);
        $data = $coll['coll'];
        $this->_param['numCount'] = $coll['count'];

        return $this->getTableTbodyHtml($data);
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
        <li class="line">line</li>
        <li><a class="icon csvdownload"   href="'.$csvUrl.'" target="dwzExport" targetType="navTab" title="实要导出这些记录吗?"><span>导出EXCEL</span></a></li>
        */
        return '<ul class="toolBar">
					<li><a class="add"   href="'.$this->_editUrl.'"  target="dialog" height="680" width="1200" drawable="true" mask="true"><span>' . Yii::$service->page->translate->__('Add') . '</span></a></li>
                    <li><a class="add"   href="'.$this->_batchInsertUrl.'"  target="dialog" height="680" width="1200" drawable="true" mask="true"><span>' . Yii::$service->page->translate->__('Batch Add') . '</span></a></li>
                    <li><a target="dialog" height="680" width="1200" drawable="true" mask="true" class="edit" href="'.$this->_editUrl.'?'.$this->_primaryKey.'={sid_user}" ><span>' . Yii::$service->page->translate->__('Update') . '</span></a></li>
					<li><a  csrfName="' .CRequest::getCsrfName(). '" csrfVal="' .CRequest::getCsrfValue(). '" title="' . Yii::$service->page->translate->__('Are you sure you want to delete these records?') . '" target="selectedTodo" rel="'.$this->_primaryKey.'s" postType="string" href="'.$this->_deleteUrl.'" class="delete"><span>' . Yii::$service->page->translate->__('Batch Delete') . '</span></a></li>
				</ul>';
    }
}
