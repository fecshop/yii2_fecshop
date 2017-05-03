<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\apphtml5\modules\Customer\block\mailer\contacts;
use Yii;
use fec\helpers\CModule;
use fec\helpers\CRequest;
use yii\base\InvalidValueException;
use fecshop\app\apphtml5\helper\mailer\Email;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class EmailBody {
	
	public $params;
	
	public function getLastData(){
		$identity = $this->params;
		//echo Yii::$service->image->getImgUrl('mail/logo.png','apphtml5');exit;
		return [
			'email'		=> $identity['email'],
			'name'		=> $identity['name'],
			'telephone'	=> $identity['telephone'],
			'comment'	=> $identity['comment'],
			'store'		=> Yii::$service->store->currentStore,
			'identity'  => $identity,
		];
	}
	
	
}