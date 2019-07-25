<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\block\productattr;

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
        $this->_editUrl = CUrl::getUrl('catalog/productattr/manageredit');
        /*
         * delete data url
         */
        $this->_deleteUrl = CUrl::getUrl('catalog/productattr/managerdelete');
        /*
         * service component, data provider
         */
        $this->_service = Yii::$service->product->attr;
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
                'orderField'    => 'attr_type',
                'label'           => Yii::$service->page->translate->__('Attr Type'),
                'width'          => '100',
                'align'           => 'left',
                'translate'     => true,
            ],
            [
                'orderField'    => 'name',
                'label'           => Yii::$service->page->translate->__('Attr Name'),
                'width'          => '110',
                'align'           => 'left',
            ],
            
            [
                'orderField'    => 'status',
                'label'           => Yii::$service->page->translate->__('Status'),
                'width'          => '50',
                'align'           => 'center',
                'display'        => [
                    1 => Yii::$service->page->translate->__('Enable'),
                    2 => Yii::$service->page->translate->__('Disable'),
                ],
            ],
            
            [
                'orderField'    => 'db_type',
                'label'           => Yii::$service->page->translate->__('Db Type'),
                'width'          => '110',
                'align'           => 'center',
            ],
            
            [
                'orderField'    => 'show_as_img',
                'label'           => Yii::$service->page->translate->__('Show As Img'),
                'width'          => '50',
                'align'           => 'center',
                'display'        => [
                    1 => Yii::$service->page->translate->__('Yes'),
                    2 => Yii::$service->page->translate->__('No'),
                ],
            ],
            
            [
                'orderField'    => 'display_type',
                'label'           => Yii::$service->page->translate->__('Display Type'),
                'width'          => '110',
                'align'           => 'center',
            ],
            [
                'orderField'    => 'is_require',
                'label'           => Yii::$service->page->translate->__('Is Required'),
                'width'          => '50',
                'align'           => 'center',
                'display'        => [
                    1 => Yii::$service->page->translate->__('Yes'),
                    2 => Yii::$service->page->translate->__('No'),
                ],
            ],
            [
                'orderField'    => 'default',
                'label'           => Yii::$service->page->translate->__('Default Value'),
                'width'          => '110',
                'align'           => 'center',
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

}
