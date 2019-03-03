<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\page;

use fecshop\services\Service;
use Yii;

/**
 * page message services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Message extends Service
{
    protected $_correctName = 'correct_message';

    protected $_errorName   = 'error_message';

    /**
     * @param $message | String
     * 增加 correct message. 添加一些操作成功的提示信息，譬如产品加入购物车成功
     */
    protected function actionAddCorrect($message)
    {
        if (empty($message)) {
            return;
        }
        if (is_string($message)) {
            $message = [$message];
        }
        $correct = $this->getCorrects();
        if (is_array($correct) && is_array($message)) {
            $message = array_merge($correct, $message);
        }

        return Yii::$service->session->setFlash($this->_correctName, $message);
    }

    /**
     * @param $message | String
     * 增加 error message.
     */
    protected function actionAddError($message)
    {
        if (empty($message)) {
            return;
        }
        if (is_string($message)) {
            $message = [$message];
        }
        $error = $this->getErrors();
        if (is_array($error) && is_array($message)) {
            $message = array_merge($error, $message);
        }
        if (is_array($message)) {
            $message = implode(',', $message);
        }

        return Yii::$service->session->setFlash($this->_errorName, $message);
    }

    /**
     * 对于Yii2 service的错误信息都是放到Yii::$service->helper->errors中
     * 该函数的作用为，从 Yii::$service->helper->errors 获取报错信息，然后把
     * errors信息添加到Yii::$service->page->message中的errors里面，
     * Yii::$service->page->message是要在前台页面显示的。
     * 而 Yii::$service->helper->errors 不会在前台显示，只是记录Yii Service执行过程中的报错信息。
     */
    protected function actionAddByHelperErrors()
    {
        $errors = Yii::$service->helper->errors->get();
        //var_dump($errors);
        if ($errors) {
            if (is_array($errors) && !empty($errors)) {
                foreach ($errors as $error) {
                    Yii::$service->page->message->addError($error);
                }
            }

            return true;
        }
    }

    /**
     * 获取 correct message.
     * @return array
     */
    protected function actionGetCorrects()
    {
        $corrects =  Yii::$service->session->getFlash($this->_correctName);
        if ($corrects && !is_array($corrects)) {
            return [$corrects];
        } else {
            return $corrects;
        }
    }

    /**
     * 获取 error message.
     * @return array
     */
    protected function actionGetErrors()
    {
        $errors = Yii::$service->session->getFlash($this->_errorName);
        if ($errors && !is_array($errors)) {
            return [$errors];
        } else {
            return $errors;
        }
    }
}
