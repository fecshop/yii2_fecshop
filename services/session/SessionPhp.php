<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\session;

use Yii;
use fecshop\services\Service;
use fecshop\models\mysqldb\SessionStorage;

/**
 * php session services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class SessionPhp extends Service implements SessionInterface
{
    public $timeout;
    
    public function init()
    {
        parent::init();
        $this->timeout = Yii::$app->session->timeout;
    }
    
    public function set($key, $val, $timeout)
    {
        if ($timeout) {
            $this->timeout = $timeout;
            Yii::$app->session->setTimeout($timeout);
        }
        return Yii::$app->session->set($key, $val);
    }

    public function get($key, $reflush)
    {
        return Yii::$app->session->get($key);
    }

    public function remove($key)
    {
        return Yii::$app->session->remove($key);
    }

    public function setFlash($key, $val, $timeout)
    {
        if ($timeout) {
            $this->timeout = $timeout;
            Yii::$app->session->setTimeout($timeout);
        }
        return Yii::$app->session->setFlash($key, $val);
    }
    
    public function getFlash($key)
    {
        return Yii::$app->session->getFlash($key);
    }
    
    public function destroy()
    {
        return Yii::$app->getSession()->destroy();
    }
}
