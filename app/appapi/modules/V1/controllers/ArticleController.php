<?php

namespace fecshop\app\appapi\modules\V1\controllers;
use Yii;
use yii\web\Response;
use fecshop\app\appapi\modules\AppapiController;

class ArticleController extends AppapiController
{
	
	public $modelClass = 'fecshop\models\mysqldb\cms\Article';

	public function behaviors()
	{
		$behaviors = parent::behaviors();
		/*
		$behaviors['authenticator'] = [
				'class' => CompositeAuth::className(),
				'authMethods' => [
						# 下面是三种验证access_token方式
						//HttpBasicAuth::className(),
						//HttpBearerAuth::className(),
						# 这是GET参数验证的方式
						# http://10.10.10.252:600/user/index/index?access-token=xxxxxxxxxxxxxxxxxxxx
						QueryParamAuth::className(),
				],
		
		];
		*/

		#定义返回格式是：JSON
		$behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
		return $behaviors;
	}

	#       {"status":"2","collect_qty":666}

	public function actionTest(){
		echo 22222;
	}



}