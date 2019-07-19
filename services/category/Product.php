<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\category;

use fecshop\services\Service;
use Yii;

/**
 * 分类对应的产品的一些操作。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Product extends Service
{
    public $pageNum = 1;

    public $numPerPage = 50;

    public $allowedNumPerPage;

    /**
     * @param $filter | Array   example:
     * [
     *     'category_id'    => 1,
     *     'pageNum'        => 2,
     *     'numPerPage'     => 50,
     *     'orderBy'        => 'name',
     *     'where'          => [
     *         ['>','price',11],
     *         ['<','price',22],
     *     ],
     * ]
     * 通过搜索条件得到当类下的产品。
     */
    protected function actionColl($filter)
    {
        $category_id = isset($filter['category_id']) ? $filter['category_id'] : '';
        if (!$category_id) {
            Yii::$service->helper->errors->add('category id is empty');

            return;
        } else {
            unset($filter['category_id']);
            $filter['where'][] = ['category' => $category_id];
        }
        if (!isset($filter['pageNum']) || !$filter['pageNum']) {
            $filter['pageNum'] = 1;
        }
        if (!isset($filter['numPerPage']) || !$filter['numPerPage']) {
            $filter['numPerPage'] = $this->numPerPage;
        }
        if (isset($filter['orderBy']) && !empty($filter['orderBy'])) {
            if (!is_array($filter['orderBy'])) {
                Yii::$service->helper->errors->add('orderBy must be array');

                return;
            }
        }

        return Yii::$service->product->coll($filter);
    }

    /**
     * @param $filter | Array    和上面的函数 actionColl($filter) 类似。
     */
    protected function actionGetFrontList($filter)
    {
        $filter['group'] = '$spu';
        $coll = Yii::$service->product->getFrontCategoryProducts($filter);
        
        $collection = $coll['coll'];
        $count = $coll['count'];
        $arr = $this->convertToCategoryInfo($collection);
        return [
            'coll' => $arr,
            'count'=> $count,
        ];
    }

    /**
     * 将service取出来的数据，处理一下，然后前端显示。
     */
    protected function actionConvertToCategoryInfo($collection)
    {
        $arr = [];
        $defaultImg = Yii::$service->product->image->defautImg();
        if (is_array($collection) && !empty($collection)) {
            foreach ($collection as $one) {
                if (is_array($one['name']) && !empty($one['name'])) {
                    $name = Yii::$service->store->getStoreAttrVal($one['name'], 'name');
                } else {
                    $name = $one['name'];
                }
                $image = $one['image'];
                $url_key = $one['url_key'];
                if (isset($image['main']['image']) && !empty($image['main']['image'])) {
                    $image = $image['main']['image'];
                } else {
                    $image = $defaultImg;
                }
                list($price, $special_price) = $this->getPrices($one['price'], $one['special_price'], $one['special_from'], $one['special_to']);
                
                $product_id = '';
                if (isset($one['product_id']) && $one['product_id']) {
                    $product_id = (string)$one['product_id'];
                } else {
                    $productPrimaryKey = Yii::$service->product->getPrimaryKey();
                    $product_id = (string)$one[$productPrimaryKey];
                }
                $arr[] = [
                    'name'              => $name,
                    'sku'                 => $one['sku'],
                    'reviw_rate_star_average' => isset($one['reviw_rate_star_average']) ? $one['reviw_rate_star_average'] : 0,
                    'review_count'   => isset($one['review_count']) ? $one['review_count'] : 0,
                    '_id'                  => $product_id,
                    'image'              => $image,
                    'price'                => $price,
                    'special_price'     => $special_price,
                    'url'                   => Yii::$service->url->getUrl($url_key),
                    'product_id'        => $product_id,
                ];
            }
        }

        return $arr;
    }

    /**
     * 处理，得到产品价格信息.
     */
    protected function getPrices($price, $special_price, $special_from, $special_to)
    {
        if (Yii::$service->product->price->specialPriceisActive($price, $special_price, $special_from, $special_to)) {
            return [$price, $special_price];
        }

        return [$price, 0];
    }
}
