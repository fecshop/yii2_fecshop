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
        $data = [
            [    // selecit的Int 类型
                'type'=>'select',
                'title'=>'状态',
                'name'=>'status',
                'columns_type' =>'int',  // int使用标准匹配， string使用模糊查询
                'value'=> $this->_productHelper->getStatusArr(),
            ],
            [    // selecit的Int 类型
                'type'=>'select',
                'title'=>'库存状态',
                'name'=>'is_in_stock',
                'columns_type' =>'int',  // int使用标准匹配， string使用模糊查询
                'value'=> $this->_productHelper->getInStockArr(),
            ],
            [    // 字符串类型
                'type'            =>'inputtext',
                'title'            =>'产品名称',
                'name'            =>'name',
                'columns_type'    =>'string',
                'lang'            => true,
            ],
            [    // 字符串类型
                'type'=>'inputtext',
                'title'=>'Spu',
                'name'=>'spu',
                'columns_type' =>'string',
            ],
            [    // 字符串类型
                'type'=>'inputtext',
                'title'=>'Sku',
                'name'=>'sku',
                'columns_type' =>'string',
            ],
            [    // 时间区间类型搜索
                'type'=>'inputdatefilter',
                'name'=> 'updated_at',
                'columns_type' =>'int',
                'value'=>[
                    'gte'=>'更新时间开始',
                    'lt' =>'更新时间结束',
                ],
            ],
            [    // 时间区间类型搜索
                'type'=>'inputfilter',
                'name'=> 'qty',
                'columns_type' =>'int',
                'value'=>[
                    'gte'=>'库存开始',
                    'lt' =>'库存结束',
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
                'orderField'    => $this->_primaryKey,
                'label'            => 'ID',
                'width'            => '90',
                'align'        => 'center',

            ],
            [
                'orderField'    => 'image_main',
                'label'            => '图片',
                'width'            => '150',
                'align'        => 'left',
                'lang'            => true,
            ],
            [
                'orderField'    => 'name',
                'label'            => '标题',
                'width'            => '250',
                'align'        => 'left',
                'lang'            => true,
            ],
            [
                'orderField'    => 'spu',
                'width'            => '120',
                'align'        => 'center',

            ],
            [
                'orderField'    => 'sku',
                'width'            => '150',
                'align'        => 'center',
            ],

            [
                'orderField'    => 'qty',
                'label'            => '库存数',
                'width'            => '50',
                'align'        => 'center',
            ],
            

            [
                'orderField'    => 'weight',
                'label'            => '重量',
                'width'            => '50',
                'align'        => 'center',
            ],

            [
                'orderField'    => 'status',
                'label'            => '状态',
                'width'            => '50',
                'align'        => 'center',
                'display'        => $this->_productHelper->getStatusArr(),
            ],

            [
                'orderField'    => 'cost_price',
                'label'            => '成本价',
                'width'            => '50',
                'align'        => 'center',
            ],

            [
                'orderField'    => 'price',
                'label'            => '销售价',
                'width'            => '50',
                'align'        => 'center',
            ],

            [
                'orderField'    => 'special_price',
                'label'            => '特价',
                'width'            => '50',
                'align'        => 'center',
            ],

            [
                'orderField'    => 'created_user_id',
                'label'            => '创建人',
                'width'            => '50',
                'align'        => 'center',
            ],
            [
                'orderField'    => 'created_at',
                'label'            => '创建时间',
                'width'            => '80',
                'align'        => 'center',
                'convert'        => ['int' => 'datetime'],
            ],
            [
                'orderField'    => 'updated_at',
                'label'            => '更新时间',
                'width'            => '80',
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
            $user_ids[] = $one['created_user_id'];
            $product_ids[] = (string)$one['_id'];
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
                    $str .= '<td><span style="display:block;padding:10px 5px;" title="'.$imgUrl.'"><img style="margin:auto;display:block;max-width:100px;max-height:100px;" src="'.$imgUrl.'" /></span></td>';
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
						<a title="编辑" target="dialog" class="btnEdit" mask="true" drawable="true" width="1000" height="580" href="'.$this->_editUrl.'?'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" >编辑</a>
						<a title="删除" target="ajaxTodo" href="'.$this->_deleteUrl.'?'.$csrfString.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" class="btnDel">删除</a>
						<br/>
						<a title="复制" target="dialog" class="button" mask="true" drawable="true" width="1000" height="580" href="'.$this->_copyUrl.'&'.$this->_primaryKey.'='.$one[$this->_primaryKey].'" ><span>复制</span></a>
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
            'orderBy'        => [$this->_param['orderField'] => (($this->_param['orderDirection'] == 'asc') ? SORT_ASC : SORT_DESC)],
            'where'            => $where,
            'asArray'        => $this->_asArray,
        ];
        $coll = $this->_service->coll($filter);
        $data = $coll['coll'];
        $this->_param['numCount'] = $coll['count'];

        return $this->getTableTbodyHtml($data);
    }
}
