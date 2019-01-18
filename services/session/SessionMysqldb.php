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
 * mysql session services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class SessionMysqldb extends Service implements SessionInterface
{
    public function init()
    {

    }
    
    public function set($key, $val, $timeout)
    {

    }

    public function get($key, $reflush)
    {

    }

    public function remove($key)
    {

    }

    /**
     * 销毁所有
     */
    public function destroy()
    {

    }

    public function setFlash($key, $val, $timeout)
    {
        
    }
    
    public function getFlash($key)
    {

    }
}
