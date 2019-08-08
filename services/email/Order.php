<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\email;

use Yii;
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Order extends Service
{
    /**
     * 邮件模板部分配置.
     */
    public $emailTheme;
    public function init()
    {
        parent::init();
        // init email config
        $this->emailTheme['guestCreate']['enable'] = (Yii::$app->store->get('email', 'orderGuestEnable') == Yii::$app->store->enable) ? true : false ;
        $this->emailTheme['guestCreate']['widget'] = Yii::$app->store->get('email', 'orderGuestWidget');
        $this->emailTheme['guestCreate']['viewPath'] = Yii::$app->store->get('email', 'orderGuestViewPath');
        
        $this->emailTheme['loginedCreate']['enable'] = (Yii::$app->store->get('email', 'orderLoginEnable') == Yii::$app->store->enable) ? true : false ;
        $this->emailTheme['loginedCreate']['widget'] = Yii::$app->store->get('email', 'orderLoginWidget');
        $this->emailTheme['loginedCreate']['viewPath'] = Yii::$app->store->get('email', 'orderLoginViewPath');
        
    }
    /**
     * @param $emailInfo | Array  ，数组格式格式如下：
     * [ 'emcustomer_emailail' => 'xx@xx.com' , [...] ] 其中customer_email是必须有的数组key，对于其他的，
     * 可以根据功能添加，添加后，可以在邮件模板的$params中调用，譬如调用customer_email为 $params['customer_email']
     * @return boolean , 如果发送成功，则返回true。
     * 新订单邮件
     */
    public function sendCreateEmail($orderInfo)
    {
        $toEmail = $orderInfo['customer_email'];
        if (Yii::$app->user->isGuest) {
            $emailThemeInfo = $this->emailTheme['guestCreate'];
        } else {
            $emailThemeInfo = $this->emailTheme['loginedCreate'];
        }
        if (isset($emailThemeInfo['enable']) && $emailThemeInfo['enable']) {
            $mailerConfigParam = '';
            if (isset($emailThemeInfo['mailerConfig']) && $emailThemeInfo['mailerConfig']) {
                $mailerConfigParam = $emailThemeInfo['mailerConfig'];
            }
            if (isset($emailThemeInfo['widget']) && $emailThemeInfo['widget']) {
                $widget = $emailThemeInfo['widget'];
            }
            if (isset($emailThemeInfo['viewPath']) && $emailThemeInfo['viewPath']) {
                $viewPath = $emailThemeInfo['viewPath'];
            }
            if ($widget && $viewPath) {
                list($subject, $htmlBody) = Yii::$service->email->getSubjectAndBody($widget, $viewPath, '', $orderInfo);
                $sendInfo = [
                    'to'        => $toEmail,
                    'subject'    => $subject,
                    'htmlBody' => $htmlBody,
                    'senderName'=> Yii::$service->store->currentStore,
                ];
                Yii::$service->email->send($sendInfo, $mailerConfigParam);

                return true;
            }
        }
    }
}
