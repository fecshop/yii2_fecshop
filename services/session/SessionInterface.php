<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\session;

/**
 * Product services interface.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
interface SessionInterface
{
    public function set($key,$val,$timeout);

    public function get($key,$reflush);

    public function remove($key);

    public function setFlash($key,$val,$timeout);
    
    public function getFlash($key);
}
