<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Fecadmin\block\role;

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

    /**
     * init param function ,execute in construct.
     */
    public function init()
    {
        /*
         * edit data url
         */
        $this->_editUrl = CUrl::getUrl('fecadmin/role/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('fecadmin/role/managerdelete');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->admin->role;
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
            [	# 字符串类型
                'type' => 'inputtext',
                'title' => Yii::$service->page->translate->__('Role Name'),
                'name' => 'role_name' ,
                'columns_type' =>'string'
            ],
        ];
        return $data;
    }
    # 定义表格显示部分的配置
    public function getTableFieldArr(){
        $table_th_bar = [
            [
                'orderField' 	=> 'role_id',
                'label'			=> Yii::$service->page->translate->__('Role Id'),
                'width'			=> '110',
                'align' 		    => 'left',
            ],
            [
                'orderField'	    => 'role_name',
                'label'			=> Yii::$service->page->translate->__('Role Name'),
                'width'			=> '110',
                'align' 		    => 'left',
            ],
            [
                'label'			=> Yii::$service->page->translate->__('Status'),
                'orderField'	    => 'role_description',
                'width'			=> '110',
                'align' 		    => 'left',
            ],
        ];
        return $table_th_bar ;
    }

}