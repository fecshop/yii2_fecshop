<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\console\modules\Product\controllers;

use Yii;
use yii\console\Controller;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class PriceController extends Controller
{
    protected $_numPerPage = 50;

    /**
     * 通过脚本，把产品的相应语言信息添加到相应的新表中。
     * 然后在相应语言搜索的时候，自动去相应的表中查数据。
     * 为什么需要搞这么多表呢?因为mongodb的全文搜索（fullSearch）索引，在一个表中只能有一个。
     * 这个索引可以是多个字段的组合索引，
     * 因此，对于多语言的产品搜索就需要搞几个表了。
     * 下面的功能：
     * 1. 将产品的name description  price img score sku  spu等信息更新过来。

     */
    public function actionComputefinalprice($pageNum)
    {
        $filter['numPerPage'] = $this->_numPerPage;
        $filter['pageNum'] = $pageNum;
        $filter['asArray'] = true;
        $products = Yii::$service->product->coll($filter);
        $product_ids = [];
        foreach ($products['coll'] as $one) {
            //var_dump($one);
            //exit;
            $price = $one['price'];
            $special_price = $one['special_price'];
            $special_from = $one['special_from'];
            $special_to = $one['special_to'];
            $one['final_price'] = Yii::$service->product->price->getFinalPrice($price, $special_price, $special_from, $special_to);
            Yii::$service->product->save($one);
        }
    }

    /**
     * 得到产品的总数。
     */
    public function actionProductcount()
    {
        //$filter['select'] 	= ['_id'];
        //$filter['where'][] 	= ['is_in_stock' => 1];
        //$filter['where'][] 	= ['status' => 1];
        $count = Yii::$service->product->collCount($filter);
        echo $count;
    }

    public function actionProductpagenum()
    {
        //$filter['select'] 	= ['_id'];
        //$filter['where'][] 	= ['is_in_stock' => 1];
        //$filter['where'][] 	= ['status' => 1];
        $count = Yii::$service->product->collCount($filter);
        echo ceil($count / $this->_numPerPage);
    }
}
