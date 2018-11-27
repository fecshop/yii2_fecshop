<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Fecadmin\block\resource;

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
        $this->_editUrl = CUrl::getUrl('fecadmin/resource/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('fecadmin/resource/managerdelete');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->admin->urlKey;
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
                'type' => 'select',
                'title' => Yii::$service->page->translate->__('Tag'),
                'name' => 'tag',
                'columns_type' => 'string',  // int使用标准匹配， string使用模糊查询
                'value' => Yii::$service->admin->urlKey->getTags(),
            ],

            [    // 字符串类型
                'type' => 'inputtext',
                'title' => Yii::$service->page->translate->__('Tag Name'),
                'name' => 'name',
                'columns_type' => 'string',
            ],

            [    // 字符串类型
                'type' => 'inputtext',
                'title' => Yii::$service->page->translate->__('Resource'),
                'name' => 'url_key',
                'columns_type' => 'string',
            ],

            [    // 时间区间类型搜索
                'type' => 'inputdatefilter',
                'name' => 'created_at',
                'columns_type' => 'int',
                'value' => [
                    'gte' => Yii::$service->page->translate->__('Created Begin'),
                    'lt'  => Yii::$service->page->translate->__('Created End'),
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
                'label'            => Yii::$service->page->translate->__('Id'),
                'width'            => '50',
                'align'        => 'center',

            ],
            [
                'orderField'    => 'name',
                'label'            => Yii::$service->page->translate->__('Tag Name'),
                'width'            => '50',
                'align'        => 'left',

            ],
            [
                'orderField'    => 'tag',
                'label'            => Yii::$service->page->translate->__('Tag'),
                'width'            => '50',
                'align'        => 'left',
                'display'        => Yii::$service->admin->urlKey->getTags(),
            ],

            [
                'orderField'    => 'tag_sort_order',
                'label'            => Yii::$service->page->translate->__('Tag Sort Order'),
                'width'            => '50',
                'align'        => 'left',
            ],
            [
                'orderField'    => 'url_key',
                'label'            => Yii::$service->page->translate->__('Resource'),
                'width'            => '60',
                'align'        => 'left',
            ],
            [
                'orderField'      => 'created_at',
                'label'             => Yii::$service->page->translate->__('Created At'),
                'width'            => '110',
                'align'             => 'center',
                'convert'         => ['int' => 'datetime'],
            ],
            [
                'orderField'      => 'updated_at',
                'label'             => Yii::$service->page->translate->__('Updated At'),
                'width'            => '110',
                'align'             => 'center',
                'convert'         => ['int' => 'datetime'],
            ],

        ];

        return $table_th_bar;
    }

    
}
