<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appfront\modules\Customer\block\contacts;

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
        $contactsEmail = '';
        //$contactsCaptcha = false;
        //$contacts = Yii::$app->getModule('customer')->params['contacts'];
        $appName = Yii::$service->helper->getAppName();
        $contactsCaptcha = Yii::$app->store->get($appName.'_account', 'contactsCaptcha');
        $contactsCaptcha = ($contactsCaptcha == Yii::$app->store->enable)  ? true : false;
        
        if (isset($contacts['email']['address'])) {
            $contactsEmail = $contacts['email']['address'];
        }
        if (!$contactsEmail) {
            $contactsEmail = Yii::$service->email->contactsEmailAddress();
        }

        $editForm = Yii::$app->request->post('editForm');
        $editForm = \Yii::$service->helper->htmlEncode($editForm);
        $name = isset($editForm['name']) ? $editForm['name'] : '';
        $email = isset($editForm['email']) ? $editForm['email'] : '';
        $telephone = isset($editForm['telephone']) ? $editForm['telephone'] : '';
        $comment = isset($editForm['comment']) ? $editForm['comment'] : '';

        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            //var_dump($identity);
            if (!$name) {
                $name = $identity['firstname'].' '.$identity['lastname'];
            }
            if (!$email) {
                $email = $identity['email'];
            }
        }

        return [
            'name'            => $name,
            'email'            => $email,
            'telephone'        => $telephone,
            'comment'        => $comment,
            'contactsCaptcha' => $contactsCaptcha,
            'contactsEmail'    => $contactsEmail,
        ];
    }

    /**
     * 保存contacts 信息。
     */
    public function saveContactsInfo($param)
    {
        if (is_array($param) && !empty($param)) {
            $email = isset($param['email']) ? $param['email'] : '';
            $comment = isset($param['comment']) ? $param['comment'] : '';
            $name = isset($param['name']) ? $param['name'] : '';
            $telephone = isset($param['telephone']) ? $param['telephone'] : '';
            if (!$email || !$name) {
                Yii::$service->page->message->addError(['Field: name and email is required']);

                return;
            }
            if (!$comment) {
                Yii::$service->page->message->addError(['comment can not empty']);

                return;
            }
            if (!\fec\helpers\CEmail::email_validation($email)) {
                Yii::$service->page->message->addError(['email format is not right']);

                return;
            }
        } else {
            Yii::$service->page->message->addError(['post params is empty']);

            return;
        }

        $captcha = Yii::$app->request->post('sercrity_code');
        $captcha = \Yii::$service->helper->htmlEncode($captcha);
        //$contacts = Yii::$app->getModule('customer')->params['contacts'];
        //$contactsCaptcha = isset($contacts['contactsCaptcha']) ? $contacts['contactsCaptcha'] : false;
        $appName = Yii::$service->helper->getAppName();
        $contactsCaptcha = Yii::$app->store->get($appName.'_account', 'contactsCaptcha');
        $contactsCaptcha = ($contactsCaptcha == Yii::$app->store->enable)  ? true : false;
        
        if ($contactsCaptcha && !$captcha) {
            Yii::$service->page->message->addError(['Captcha can not empty']);

            return;
        } elseif ($captcha && $contactsCaptcha && !\Yii::$service->helper->captcha->validateCaptcha($captcha)) {
            Yii::$service->page->message->addError(['Captcha is not right']);

            return;
        }
        $paramData = [
            'name'        => $name,
            'telephone' => $telephone,
            'comment'    => $comment,
            'email'        => $email,
        ];
        if (Yii::$service->email->customer->sendContactsEmail($paramData)) {
            Yii::$service->page->message->addCorrect(['Contact us Send Success']);
        }
    }
}
