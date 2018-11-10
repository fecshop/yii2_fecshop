<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Fecadmin\block\account;

use fec\helpers\CUrl;
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
    public function init()
    {
        /*
         * edit data url
         */
        $this->_editUrl = CUrl::getUrl('fecadmin/account/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('fecadmin/account/managerdelete');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->adminUser->adminUser;
        parent::init();
    }

    public function getLastData(){
        # 返回数据的函数
        # 隐藏部分
        $pagerForm = $this->getPagerForm();
        # 搜索部分
        $searchBar = $this->getSearchBar();
        # 编辑 删除  按钮部分
        $editBar = $this->getEditBar();
        # 表头部分
        $thead = $this->getTableThead();
        # 表内容部分
        $tbody = $this->getTableTbody();
        # 分页部分
        $toolBar = $this->getToolBar($this->_param['numCount'],$this->_param['pageNum'],$this->_param['numPerPage']);

        return [
            'pagerForm'	 	=> $pagerForm,
            'searchBar'		=> $searchBar,
            'editBar'		=> $editBar,
            'thead'		=> $thead,
            'tbody'		=> $tbody,
            'toolBar'	=> $toolBar,
        ];
    }




    # 定义搜索部分字段格式
    public function getSearchArr(){

        $data = [

            [	# selecit的Int 类型
                'type'=>'select',
                'title'=>'状态',
                'name'=>'status',
                'columns_type' =>'int',  # int使用标准匹配， string使用模糊查询
                'value'=> [					# select 类型的值
                    Yii::$service->adminUser->adminUser->getActiveStatus() => '激活',
                    Yii::$service->adminUser->adminUser->getDeleteStatus() => '关闭',
                ],
            ],
            [	# 字符串类型
                'type'=>'inputtext',
                'title'=>'用户名',
                'name'=>'username' ,
                'columns_type' =>'string'
            ],
            [	# 字符串类型
                'type'=>'inputtext',
                'title'=>'员工编号',
                'name'=>'code' ,
                'columns_type' =>'string'
            ],

            [	# 字符串类型
                'type'=>'inputtext',
                'title'=>'邮箱',
                'name'=>'email' ,
                'columns_type' =>'string'
            ],
            [	# 时间区间类型搜索
                'type'=>'inputdatefilter',
                'name'=> 'created_at_datetime',
                'columns_type' =>'datetime',
                'value'=>[
                    'gte'=>'用户创建时间开始',
                    'lt' =>'用户创建时间结束',
                ]
            ],


        ];
        return $data;
    }







    # 定义表格显示部分的配置
    public function getTableFieldArr(){
        $table_th_bar = [
            [
                'orderField' 	=> 'id',
                'label'			=> 'ID',
                'width'			=> '110',
                'align' 		=> 'center',

            ],
            [
                'orderField'	=> 'username',
                'label'			=> '用户名称',
                'width'			=> '110',
                'align' 		=> 'center',
            ],

            [
                'orderField'	=> 'person',
                'label'			=> '姓名',
                'width'			=> '110',
                'align' 		=> 'center',
            ],
            [
                'orderField'	=> 'code',
                'label'			=> '员工编号',
                'width'			=> '110',
                'align' 		=> 'center',
            ],
            /*
            [
                'orderField'	=> 'role',
                'width'			=> '110',
                'align' 		=> 'left',
                'display'		=> AdminRole::getAdminRoleArr(),
            ],
            */


            [
                'orderField'	=> 'email',
                'width'			=> '110',
                'align' 		=> 'center',
            ],
            [
                'orderField'	=> 'created_at_datetime',
                //'label'			=> '用户名称',
                'width'			=> '190',
                'align' 		=> 'center',
                //'convert'		=> ['datetime' =>'date'],
            ],

            [
                'orderField'	=> 'updated_at_datetime',
                //'label'			=> '用户名称',
                'width'			=> '190',
                'align' 		=> 'center',
                //'convert'		=> ['datetime' =>'date'],   # int  date datetime  显示的转换
            ],



            [
                'orderField'	=> 'status',
                //'label'			=> '用户名称',
                'width'			=> '60',
                'align' 		=> 'center',
                'display'		=> [       # 显示转换  ，譬如 值为1显示为激活，值为10显示为关闭
                    '1'		=> '激活',
                    '10'	=> '关闭',
                ],

            ],



            /*
            [
                'orderField'	=> 'allowance',
                //'label'			=> '用户名称',
                //'width'			=> '190',
                'align' 		=> 'center',

            ],


            [
                'orderField'	=> 'allowance_updated_at',
                //'label'			=> '用户名称',
                //'width'			=> '190',
                'align' 		=> 'center',

            ],
            */



        ];
        return $table_th_bar ;
    }











}