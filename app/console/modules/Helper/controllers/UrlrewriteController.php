<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\console\modules\Helper\controllers;

use Yii;
use yii\console\Controller;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class UrlrewriteController extends Controller
{
    protected $_numPerPage = 50;

    /**
     * 得到当前的时间。
     */
    public function actionNowtime()
    {
        echo time();
    }

    /**
     * 得到产品的页数。
     */
    public function actionProductpagenum()
    {
        $count = Yii::$service->product->collCount($filter);
        echo ceil($count / $this->_numPerPage);
    }

    /**
     * 得到产品的总数。
     */
    public function actionProductcount()
    {
        $count = Yii::$service->product->collCount($filter);
        echo $count;
    }

    /**
     * 处理产品的重写.
     */
    public function actionProduct($pageNum)
    {
        $filter['numPerPage'] = $this->_numPerPage;
        $filter['pageNum'] = $pageNum;
        $filter['asArray'] = true;
        $products = Yii::$service->product->coll($filter);
        $product_ids = [];
        foreach ($products['coll'] as $one) {
            Yii::$service->product->save($one);
        }
    }

    /**
     * 得到分类的页数。
     */
    public function actionCategorypagenum()
    {
        $count = Yii::$service->category->collCount($filter);
        echo ceil($count / $this->_numPerPage);
    }

    /**
     * 得到分类的总数。
     */
    public function actionCategorycount()
    {
        $count = Yii::$service->category->collCount($filter);
        echo $count;
    }

    /**
     * 处理分类的重写.
     */
    public function actionCategory($pageNum)
    {
        $filter['numPerPage'] = $this->_numPerPage;
        $filter['pageNum'] = $pageNum;
        $filter['asArray'] = true;
        $categorys = Yii::$service->category->coll($filter);
        $category_ids = [];
        foreach ($categorys['coll'] as $one) {
            Yii::$service->category->save($one);
        }
    }

    /**
     * 删除时间小于nowtime的.
     */
    public function actionClearnoactive($nowtime)
    {
        echo 'delete date gt '.$nowtime."\n";
        Yii::$service->url->rewrite->removeByUpdatedAt($nowtime);
    }
}
