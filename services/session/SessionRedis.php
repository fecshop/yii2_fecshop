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

//use fecshop\models\mysqldb\SessionStorage;

/**
 * redis session services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class SessionRedis extends Service implements SessionInterface
{
    protected $storageSession;
    
    public function init()
    {
        parent::init();
        // SessionRedis 是单例模式，因此init只能执行一次，下面的也是只会执行一次。
        $this->storageSession = new \yii\redis\Session;
    }
    
    public function set($key, $val, $timeout)
    {
        $uuid = Yii::$service->session->getUUID();
        $r_id = $this->getUuidKey($uuid, $key);

        return $this->storageSession->set($r_id, $val, $timeout);
    }

    public function get($key, $reflush)
    {
        $uuid = Yii::$service->session->getUUID();
        $r_id = $this->getUuidKey($uuid, $key);

        return $this->storageSession->get($r_id);
    }

    public function remove($key)
    {
        $uuid = Yii::$service->session->getUUID();
        $r_id = $this->getUuidKey($uuid, $key);

        return $this->storageSession->remove($r_id);
    }
    
    public function getUuidKey($uuid, $key)
    {
        return $uuid.'###^^###'.$key;
    }

    /**
     * 销毁所有
     */
    public function destroy()
    {
        return $this->storageSession->destroy();
    }

    public function setFlash($key, $val, $timeout)
    {
        $uuid = Yii::$service->session->getUUID();
        $r_id = $this->getUuidKey($uuid, $key);

        return $this->storageSession->setFlash($r_id, $val, $timeout);
    }
    
    public function getFlash($key)
    {
        $uuid = Yii::$service->session->getUUID();
        $r_id = $this->getUuidKey($uuid, $key);

        return $this->storageSession->getFlash($r_id);
    }
}
