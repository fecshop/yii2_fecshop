<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\helper\errorhandler;

/**
 * Product services interface.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
interface ErrorHandlerInterface
{
    public function getByPrimaryKey($primaryKey);

    public function coll($filter);

    public function saveByErrorHandler(
        $code,
        $message,
        $file,
        $line, 
        $created_at,
        $ip,
        $name,
        $trace_string,
        $url,
        $req_info=[]
    );

    //public function remove($ids);
}
