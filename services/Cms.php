<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

/**
 * @property \fecshop\services\customer\Article $article
 * @property \fecshop\services\customer\StaticBlock $staticblock
 *
 * Cms services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Cms extends Service
{
    /**
     * cms storage db, you can set value: mysqldb,mongodb.
     */
    public $storage = 'mysqldb';
}
