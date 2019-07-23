<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Config\block\appfrontstore;

use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlock;
use Yii;

/**
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
        $this->_editUrl = CUrl::getUrl('config/appfrontstore/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('config/appfrontstore/managerdelete');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->storeDomain;
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
            ]
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
                'label'           => Yii::$service->page->translate->__('Id'),
                'width'          => '50',
                'align'           => 'center',
            ],
            [
                'orderField'    => 'key',
                'label'           => Yii::$service->page->translate->__('Key'),
                'width'          => '50',
                'align'           => 'left',
            ],
            [
                'orderField'    => 'lang',
                'label'           => Yii::$service->page->translate->__('Language'),
                'width'          => '50',
                'align'           => 'left',
                'lang'            => false,
            ],
            [
                'orderField'    => 'currency',
                'label'           => Yii::$service->page->translate->__('Currency'),
                'width'          => '110',
                'align'           => 'center',
            ],
            [
                'orderField'    => 'status',
                'label'           => Yii::$service->page->translate->__('Status'),
                'width'          => '50',
                'align'           => 'center',
                'display'        => [                    // select 类型的值
                    1 => Yii::$service->page->translate->__('Enable'),
                    2 => Yii::$service->page->translate->__('Disable'),
                ],
            ],
            [
                'orderField'    => 'https_enable',
                'label'           => Yii::$service->page->translate->__('Https'),
                'width'          => '50',
                'align'           => 'center',
                'display'        => [                    // select 类型的值
                    1 => Yii::$service->page->translate->__('Enable'),
                    2 => Yii::$service->page->translate->__('Disable'),
                ],
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
    
    /**
     * list table body.
     */
    public function getTableTbody()
    {
        $searchArr = $this->getSearchArr();
        if (is_array($searchArr) && !empty($searchArr)) {
            $where = $this->initDataWhere($searchArr);
        }
        $where[] = ['app_name' => 'appfront'];
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
