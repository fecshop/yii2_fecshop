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
class SearchController extends Controller
{
    protected $_numPerPage = 50;

    /**
     * 1.初始化mongodb表的索引。如果您已经有了text索引，然后想更改text索引
     * 您需要去mongodb的各个语言表中将text索引删除，或者将各个搜索表删除。
     * 当重新执行initmongoindex的时候就会重建text索引，否则会报错
     * 在mongodb中text可以是多个字段组合，但是只能有一个text索引。
     * 这也就是为什么要把各个语言分开成多个表的原因。
     * 2.初始化其他表的索引.
     */
    public function actionInitindex()
    {
        Yii::$service->search->initFullSearchIndex();
    }

    public function actionNowtime()
    {
        echo time();
    }

    /**
     * 通过脚本，把产品的相应语言信息添加到相应的新表中。
     * 然后在相应语言搜索的时候，自动去相应的表中查数据。
     * 为什么需要搞这么多表呢?因为mongodb的全文搜索（fullSearch）索引，在一个表中只能有一个。
     * 这个索引可以是多个字段的组合索引，
     * 因此，对于多语言的产品搜索就需要搞几个表了。
     * 下面的功能：
     * 1. 将产品的name description  price img score sku  spu等信息更新过来。
     * 2. 只会同步is_in_stock = 1 ，  status = 1  的产品。
     */
    public function actionSyncdata($pageNum)
    {
        $filter['select'] = ['_id'];
        $filter['where'][] = ['is_in_stock' => 1];
        $filter['where'][] = ['status' => 1];
        $filter['numPerPage'] = $this->_numPerPage;
        $filter['pageNum'] = $pageNum;
        $products = Yii::$service->product->coll($filter);
        $product_ids = [];
        foreach ($products['coll'] as $p) {
            $product_ids[] = $p['_id'];
        }

        //echo count($product_ids);
        Yii::$service->search->syncProductInfo($product_ids, $this->_numPerPage);
    }

    /**
     * 得到产品的总数。
     */
    public function actionSynccount()
    {
        $filter['select'] = ['_id'];
        $filter['where'][] = ['is_in_stock' => 1];
        $filter['where'][] = ['status' => 1];
        $count = Yii::$service->product->collCount($filter);
        echo $count;
    }

    public function actionSyncpagenum()
    {
        $filter['select'] = ['_id'];
        $filter['where'][] = ['is_in_stock' => 1];
        $filter['where'][] = ['status' => 1];
        $count = Yii::$service->product->collCount($filter);
        echo ceil($count / $this->_numPerPage);
    }

    /**
     *  @property $nowtime | Int 当前时间的时间戳
     *  经过上面的批量更新，会更新updated_at字段，因此小于$nowtime的数据，会认为是无效
     *  的字段了，因为上面的更新是批量更新。
     */
    public function actionDeletenotactiveproduct($nowTimeStamp)
    {
        Yii::$service->search->deleteNotActiveProduct($nowTimeStamp);
    }

    public function actionXundeleteallproduct($i)
    {
        Yii::$service->search->xunSearch->xunDeleteAllProduct($this->_numPerPage, $i);
    }
}
