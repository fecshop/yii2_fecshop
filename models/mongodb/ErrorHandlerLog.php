<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\models\mongodb;

use yii\mongodb\ActiveRecord;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ErrorHandlerLog extends ActiveRecord
{
    //const CATEGORY_APPSERVER    = 'appfront';
    //const CATEGORY_APPADMIN     = 'appadmin';
    //const CATEGORY_APPHTML5     = 'apphtml5';
    //const CATEGORY_APPSERVER    = 'appserver';
    //const CATEGORY_APPAPI       = 'appapi';
    //const CATEGORY_CONSOLE      = 'console';
    /**
     * mongodb collection 的名字，相当于mysql的table name
     */
    public static function collectionName()
    {
        return 'error_handler_log';
    }
    /**
     * mongodb是没有表结构的，因此不能像mysql那样取出来表结构的字段作为model的属性
     * 因此，需要自己定义model的属性，下面的方法就是这个作用
     */
    public function attributes()
    {
        return [
            '_id',
            'category',     // 入口名字
            'code',         // http 错误码
            'message',      // 错误信息
            'file',         // 发生错误的文件
            'line',         // 发生错误所在文件的代码行
            'created_at',   // 发生错误的执行时间
            'ip',           // 访问人的ip
            'name',         // 错误的名字
            'trace_string', // 错误的追踪信息
            'url',          // 
            'request_info', // request 信息
       ];
    }
    /**
     * "code": 500,
     * "message": "syntax error, unexpected '}'",
     * "file": "/www/web/develop/fecshop/vendor/fancyecommerce/fecshop/app/appserver/modules/Customer/controllers/TestController.php",
     * "line": 27,
     * "time": "2017-11-30 14:26:34",
     * "ip": "183.14.76.88"
     */

}
