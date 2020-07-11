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
class MysqldatatomongoController extends Controller
{
    public $numPerPage = 10;
    
    
    public function init()
    {
        parent::init();
        Yii::$service->product->changeToMysqlStorage();
        
    }
    
    
    /**
     * 1.为什么要同步？
     * 对于fecshop的产品数据，是放到mongodb中的，而库存的操作，和订单一起是有事务性的
     * 因此，产品库存放到了mysql的表中，这是产品的真实库存数据
     * 而对于mongodb中，也有一个库存字段，这个字段是为了在分类页面排序，以及后台进行库存排序，过滤等等（mysql无法和mongodb的表进行join操作，另外join操作对内存的开销大，因此用mongodb中的库存字段进行这些查询排序操作）
     * 另外，在订单生成的时候，mongodb的库存字段是不扣除的。
     * mongodb的库存只是用来一些范围查询搜索，而对于获取产品库存用于下单等操作，请使用mysql表的库存。
     * 2.同步那些数据
     * 2.1将mysql表 product_flat_qty 的库存数据，同步到 mongodb product_flat表的qty字段
     */
    public function actionSync($pageNum = 1)
    {
        $filter = [
      		'numPerPage' 	=> $this->numPerPage,
      		'pageNum'		=> $pageNum,
      		'orderBy'	    => ['id' => SORT_ASC],
            'asArray' => false,
        ];
        
        $data = Yii::$service->product->coll($filter);
        $coll = $data['coll'];
        // 得到分类信息
        $productPrimaryKey = Yii::$service->product->getPrimaryKey();
        foreach ($coll as $k=>$product) {
            $product_id = $product[$productPrimaryKey];
            $categoryIds = Yii::$service->product->getCategoryIdsByProductId($product_id);
            
            $coll[$k]['category'] = is_array($categoryIds) ? $categoryIds : [];
        }
        
        // 切换mongodb storage
        Yii::$service->product->changeToMongoStorage();
        foreach ($coll as $product) {
            $arr = [];
            
            foreach ($product as $k => $v) {
                if ($k != 'attr_group_info') {
                    $arr[$k] = $v;
                } else if ($k == 'attr_group_info' && is_array($v)) {
                    foreach ($v as $attr_group_key => $attr_group_val) {
                        $arr[$attr_group_key] = $attr_group_val;
                    }
                } 
            }
            $arr['category'] = $product['category'];
            Yii::$service->product->sync($arr);
        }
        
    }
    // 得到个数
    public function actionSynccount()
    {
        $count = Yii::$service->product->collCount();
        echo $count ;
    }
    // 得到个数
    public function actionSyncpagenum()
    {
        $count = Yii::$service->product->collCount();
        echo ceil($count / $this->numPerPage);
    }
    
    
}
