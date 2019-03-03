<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block\order;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    protected $numPerPage = 10;
    protected $pageNum;
    protected $orderBy;
    protected $_page = 'p';

    /**
     * 初始化类变量.
     */
    public function initParam()
    {
        $this->pageNum = (int) Yii::$app->request->get('p');
        $this->pageNum = ($this->pageNum >= 1) ? $this->pageNum : 1;
        $this->orderBy = ['created_at' => SORT_DESC];
    }

    public function getLastData()
    {
        $this->initParam();
        $return_arr = [];
        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            $customer_id = $identity->id;
            $filter = [
                'numPerPage'    => $this->numPerPage,
                'pageNum'        => $this->pageNum,
                'orderBy'        => $this->orderBy,
                'where'            => [
                    ['customer_id' => $customer_id],
                ],
                'asArray' => true,
            ];

            $customer_order_list = Yii::$service->order->coll($filter);
            $return_arr['order_list'] = $customer_order_list['coll'];
            $count = $customer_order_list['count'];
            $pageToolBar = $this->getProductPage($count);
            $return_arr['pageToolBar'] = $pageToolBar;
        }
        $this->breadcrumbs(Yii::$service->page->translate->__('Customer Order'));
        return $return_arr;
    }
    
    // 面包屑导航
    protected function breadcrumbs($name)
    {
        if (Yii::$app->controller->module->params['customer_order_breadcrumbs']) {
            Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
        } else {
            Yii::$service->page->breadcrumbs->active = false;
        }
    }

    protected function getProductPage($countTotal)
    {
        if ($countTotal <= $this->numPerPage) {
            return '';
        }
        $config = [
            'class'        => 'fecshop\app\appfront\widgets\Page',
            'view'        => 'widgets/page.php',
            'pageNum'        => $this->pageNum,
            'numPerPage'    => $this->numPerPage,
            'countTotal'    => $countTotal,
            'page'            => $this->_page,
        ];

        return Yii::$service->page->widget->renderContent('category_product_page', $config);
    }
}
