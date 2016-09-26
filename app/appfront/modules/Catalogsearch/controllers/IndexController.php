<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appfront\modules\Catalogsearch\controllers;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use fecshop\app\appfront\modules\AppfrontController;
use fecshop\models\xunsearch\Search;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class IndexController extends AppfrontController
{
    public function init(){
		parent::init();
	}
	# 网站信息管理
    public function actionIndex()
    {
		/*
		$model = new Search;
		$model->pid = 341234;
		$model->name = '小米手机';
		$model->description = '小米手机Air采用了全尺寸的背光键盘，在接口方面拥有不错的扩展性。因为采用了无logo设计，小米官方会推出名画以及当代艺术家的画作的贴纸，还推出了笔记本包。';
		$model->save();
		
		$model = new Search;
		$model->pid = 44444;
		$model->name = '北京小米科技有限责任公司';
		$model->description = '北京小米科技有限责任公司成立2010年4月，是一家专注于智能硬件和电子产品研发的移动互联网公司。“为发烧而生”是小米的产品概念。小米公司首创了用互联网模式开发手机操作系统、发烧友参与开发改进的模式。
2014年12月14日晚，美的集团发出公告称，已与小米科技签署战略合作协议，小米12.7亿元入股美的集团。2015年9月22日，小米在北京发布了新品小米4c，这款新品由小米4i升级而来，配备5英寸显示屏，搭载骁龙808处理器，号称安卓小王子。
2016年7月27日的发布会上小米笔记本终于正式亮相，这款产品叫做小米笔记本Air。';
		$model->save();
		
		*/
		echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
		$query = Search::find();
		$condition = '小米手机';
		$data = $query->asArray()->limit(100) //->where($condition)
		->all();
		foreach($data as $d){
			var_dump($d);
			echo '<br>####################<br>';
		}
		exit;
		
		
		/*
		Yii::$service->search->initFullSearchIndex();
		$filter['select'] = ['_id'];
		$count = Yii::$service->product->collCount($filter);
		//echo $count;
		$numPerPage = 10;
		$pageCount = ceil($count/10);
		for($i=1;$i<=$pageCount;$i++){
			$filter['numPerPage'] = $numPerPage;
			$filter['pageNum'] = $i;
			$products = Yii::$service->product->coll($filter);
			$product_ids = [];
			foreach($products['coll'] as $p){
				$product_ids[] = $p['_id'];
			}
			//var_dump($product_ids);exit;
			Yii::$service->search->syncProductInfo($product_ids);
			
		}
		*/
		
		$data = $this->getBlock()->getLastData();
		return $this->render($this->action->id,$data);
	}
	
	
}
















