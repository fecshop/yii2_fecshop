<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block\productfavorite;

use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    public $pageNum;
    public $numPerPage = 10;
    public $_page = 'p';

    public function initFavoriteParam()
    {
        $pageNum = Yii::$app->request->get($this->_page);
        $this->pageNum = $pageNum ? $pageNum : 1;
    }

    public function getLastData()
    {
        $this->initFavoriteParam();
        $identity = Yii::$app->user->identity;
        $user_id = $identity->id;
        if (!$user_id) {
            Yii::$service->helper->errors->add('current user id is empty');

            return;
        }
        $filter = [
            'pageNum'    => $this->pageNum,
            'numPerPage'=> $this->numPerPage,
            'orderBy'    => ['updated_at' => SORT_DESC],
            'where'            => [
                ['user_id' => $user_id],
            ],
            'asArray' => true,
        ];
        $data = Yii::$service->product->favorite->list($filter);
        $coll = $data['coll'];
        $count = $data['count'];
        $pageToolBar = $this->getProductPage($count);
        $product_arr = $this->getProductInfo($coll);
        $this->breadcrumbs(Yii::$service->page->translate->__('Customer Product Favorite'));
        
        return [
            'coll' => $product_arr,
            'pageToolBar'    => $pageToolBar,
        ];
    }
    
    // 面包屑导航
    protected function breadcrumbs($name)
    {
        if (Yii::$app->controller->module->params['customer_product_favorite_breadcrumbs']) {
            Yii::$service->page->breadcrumbs->addItems(['name' => $name]);
        } else {
            Yii::$service->page->breadcrumbs->active = false;
        }
    }
    
    // 得到产品的一些信息，来显示Favorite 的产品列表。
    public function getProductInfo($coll)
    {
        $product_ids = [];
        $favorites = [];
        $favoritePrimaryKey = Yii::$service->product->favorite->getPrimaryKey();
        
        foreach ($coll as $one) {
            $p_id = (string)$one['product_id'];
            $product_ids[] = $one['product_id'];
            $favorites[$p_id] = [
                'updated_at' => $one['updated_at'],
                'favorite_id' => (string) $one[$favoritePrimaryKey],
            ];
        }
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        // 得到产品的信息
        $product_filter = [
            'where'            => [
                ['in', $productPrimaryKey, $product_ids],
            ],
            'select' => [
                $productPrimaryKey,
                'name', 'image',
                'price', 'special_price',
                'special_from', 'special_to',
                'url_key',
            ],
            'asArray' => true,
        ];
        
        $data = Yii::$service->product->coll($product_filter);
        $product_arr = [];
        if (is_array($data['coll']) && !empty($data['coll'])) {
            foreach ($data['coll'] as $one) {
                $p_id = (string) $one[$productPrimaryKey];
                
                $one['updated_at'] = $favorites[$p_id]['updated_at'];
                $one['favorite_id'] = $favorites[$p_id]['favorite_id'];
                $product_arr[] = $one;
            }
        }
        return \fec\helpers\CFunc::array_sort($product_arr, 'updated_at', 'desc');
    }

    /**
     * @param $favorite_id|string
     */
    public function remove($favorite_id)
    {
        Yii::$service->product->favorite->currentUserRemove($favorite_id);
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
