<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services;
use Yii;
use yii\base\InvalidValueException;
use yii\base\InvalidConfigException;
use fec\helpers\CSession;
use fec\helpers\CUrl;
/**
 * AdminUser services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AdminUser extends Service
{
	
	#Yii::$app->adminUser->getIdAndNameArrByIds($ids)
	public function getIdAndNameArrByIds($ids){
		
		$user_coll = \fecadmin\models\AdminUser::find()->asArray()->select(['id','username'])->where([
			'in','id',$ids
		])->all();
		$users = [];
		foreach($user_coll as $one){
			$users[$one['id']] = $one['username'];
		}
		return $users;
	}
	
}