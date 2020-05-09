<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\category;

//use fecshop\models\mongodb\Category;
use fecshop\services\Service;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Menu extends Service
{
    public $rootCategoryId = '0';
    /**
     * @param $parentId | int
     * 得到分类的目录信息
     */
    public function getCategoryMenuArr($parentId = '')
    {
        $arr = [];
        if (!$parentId) {
            $parentId = $this->rootCategoryId;
        }
        $categoryPrimaryKey = Yii::$service->category->getPrimaryKey();
        $orderBy = ['sort_order' => SORT_DESC];
        $filter = [
            'select' => [$categoryPrimaryKey, 'parent_id', 'name', 'url_key', 'menu_custom',],
            'where' => [
                ['parent_id' => $parentId,],
                ['status'    => Yii::$service->category->getCategoryEnableStatus()],
                ['menu_show' => Yii::$service->category->getCategoryMenuShowStatus()],
            ],
            'orderBy' => $orderBy,
            'fetchAll' => true,
            'asArray' => true,
        ];
        $collData = Yii::$service->category->coll($filter);
        $data = $collData['coll'];
        if (is_array($data) && !empty($data)) {
            foreach ($data as $category) {
                $categoryOne = [
                    '_id'           => (string) $category[$categoryPrimaryKey],
                    'name'          => Yii::$service->store->getStoreAttrVal($category['name'], 'name'),
                    'menu_custom'   => Yii::$service->store->getStoreAttrVal($category['menu_custom'], 'menu_custom'),
                    'url'           => Yii::$service->url->getUrl($category['url_key']),
                ];
                $childMenu = $this->getCategoryMenuArr((string) $category[$categoryPrimaryKey]);
                if ($childMenu) {
                    $categoryOne['childMenu'] = $childMenu;
                }
                $arr[] = $categoryOne;
            }

            return $arr;
        }

        return '';
    }

    /**
     * @param $categoryId|array
     * check if cateogry has child .
     */
    protected function hasChild($categoryId)
    {
        return Yii::$service->category->hasChildCategory($categoryId);
    }
}
