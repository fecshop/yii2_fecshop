<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block\newsletter;

use fecshop\app\appfront\helper\mailer\Email;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Index
{
    public function getLastData()
    {
        $email = Yii::$app->request->get('email');
        $email = \Yii::$service->helper->htmlEncode($email);
        
        $status = Yii::$service->customer->newsletter->subscribe($email);
        $message = Yii::$service->helper->errors->get();
        if (!$message) {
            $arr = ['urlB' => '<a href="'.Yii::$service->url->homeUrl() .'">',  'urlE' => '</a>'];
            $message = Yii::$service->page->translate->__('Your subscribed email was successful, You can {urlB} click Here to Home Page {urlE}, Thank You.', $arr);
            $param['email'] = $email;
            Yii::$service->email->customer->sendNewsletterSubscribeEmail($param);
        } else if (is_array($message)) {
            $message = implode(',', $message); 
        }

        return [
            'message' => $message,
        ];
    }
}
