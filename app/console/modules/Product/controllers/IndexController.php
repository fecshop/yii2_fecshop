<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace fecshop\app\console\modules\Product\controllers;

use Yii;
use yii\base\InlineAction;
use yii\console\Controller;

class IndexController extends Controller
{
	
	public function actionIndex(){
		echo 'xxxxx';
		$article = Yii::$service->cms->article->coll();
		foreach($article as $d){
			var_dump($d);
		}
	}
	
	
	
		
}