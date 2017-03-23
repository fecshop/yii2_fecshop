<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\services\email;
use Yii;
use fec\helpers\CConfig;
use fec\controllers\FecController;
use yii\base\InvalidValueException;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Order
{
	
	/**
	 * 邮件模板部分配置
	 */
	public $emailTheme;
	
	
	
	/**
	 * @property $toEmail | String   send to email address.
	 * 新订单邮件
	 * 
	 */
	public function sendCreateEmail($orderInfo){
		$toEmail 		= $orderInfo['customer_email'];
		if(Yii::$app->user->isGuest){
			$emailThemeInfo 	= $this->emailTheme['guestCreate'];
		}else{
			$emailThemeInfo 	= $this->emailTheme['loginedCreate'];
		}
		if(isset($emailThemeInfo['enable']) && $emailThemeInfo['enable']){
			$mailerConfigParam = '';
			if(isset($emailThemeInfo['mailerConfig']) && $emailThemeInfo['mailerConfig']){
				$mailerConfigParam = $emailThemeInfo['mailerConfig'];	
			}
			if(isset($emailThemeInfo['widget']) && $emailThemeInfo['widget']){
				$widget = $emailThemeInfo['widget'];
			}
			if(isset($emailThemeInfo['viewPath']) && $emailThemeInfo['viewPath']){
				$viewPath = $emailThemeInfo['viewPath'];
			}
			if($widget && $viewPath){
				list($subject,$htmlBody) = Yii::$service->email->getSubjectAndBody($widget,$viewPath,'',$orderInfo);
				$sendInfo = [
					'to' 		=> $toEmail,
					'subject' 	=> $subject,
					'htmlBody' => $htmlBody,
					'senderName'=> Yii::$service->store->currentStore,
				];
				//var_dump($sendInfo);exit;
				Yii::$service->email->send($sendInfo,$mailerConfigParam);
				return true;
			}
		}
	}
	
	
}
