<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\console\modules\Category\controllers;

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
        Yii::$service->category->changeToMysqlStorage();
        
    }
    
    /**
     * mongodb 同步过来的分类信息，根据mongodb中的上下级分类关系，初始化上下级。
     */
    public function actionInitparentid($pageNum = 1)
    {
        Yii::$service->category->changeToMongoStorage();
        $filter = [
      		'numPerPage' 	=> $this->numPerPage,
      		'pageNum'		=> $pageNum,
      		'orderBy'	    => ['id' => SORT_ASC],
            'asArray' => false,
        ];
        $data = Yii::$service->category->apiColl($filter);
        $coll = $data['coll'];
        foreach ($coll as $category) {
            //$origin_mysql_id
            $origin_mysql_parent_id = $category['origin_mysql_parent_id'];
            $origin_mysql_id = $category['origin_mysql_id'];
            // 如果不是mongdb同步过来的数据，直接continue
            if (!$origin_mysql_id) {
                continue;
            }
            // 如果上级分类id为空，则代表是一级分类，parent_id 为0
            // 如果上级分类id不为空，则通过mongodbParentId查询到数据，找到mysql表中的parentId
            if ($origin_mysql_parent_id) {
                $categoryParent = Yii::$service->category->findOne([
                    'origin_mysql_id' => $origin_mysql_parent_id
                ]);
                if ($categoryParent && $categoryParent['_id']) {
                    $category->parent_id = (string)$categoryParent['_id'];
                    $category->updated_at = time();
                    $category->save();
                }
            } else {
                $category->parent_id = '0';
                $category->updated_at = time();
                $category->save();
                
            }
            
            
        }
    }
    
    
    /**
     * 同步数据
     */
    public function actionSync($pageNum = 1)
    {
        $filter = [
      		'numPerPage' 	=> $this->numPerPage,
      		'pageNum'		=> $pageNum,
      		'orderBy'	    => ['id' => SORT_ASC],
            'asArray' => false,
        ];
        
        $data = Yii::$service->category->coll($filter);
        $coll = $data['coll'];
        Yii::$service->category->changeToMongoStorage();
        foreach ($coll as $category) {
            $arr = [];
            $mongoId = '';
            foreach ($category as $k => $v) {
                $arr[$k] = $v;
            }
            
            Yii::$service->category->sync($arr);
        }
        
    }
    // 得到个数
    public function actionSynccount()
    {
        $count = Yii::$service->category->collCount();
        echo $count ;
    }
    // 得到个数
    public function actionSyncpagenum()
    {
        $count = Yii::$service->category->collCount();
        echo ceil($count / $this->numPerPage);
    }
    
    
}
