<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Fecadmin\block\myaccount;

use Yii;
use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecadmin\models\AdminUser\AdminUserResetPassword;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index extends \yii\base\BaseObject
{
	public function getLastData(){
		$data = CRequest::param("updatepass");
		if($data){
            // ajax update 
			$resetStatus = Yii::$service->adminUser->adminUser->resetCurrentPassword($data);
            if (!$resetStatus) {
                $errors = Yii::$service->helper->errors->get();
                echo  json_encode(["statusCode"=>"300",
					"message" => $errors,
				]);
            } else {
                echo  json_encode(["statusCode"=>"200",
					"message" => 'Update Password Success',
				]);
            }
            exit;
        }
        $adminUser = \Yii::$app->user->identity;
		$current_account = $adminUser->username;
		$editUrl = CUrl::getUrl("fecadmin/myaccount/index");
        
		return [
			'current_account' => $current_account,
			'editUrl'			=> $editUrl,
		];
	}
	
}