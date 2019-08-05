<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
return [
    'session' => [
        'class' => 'fecshop\services\Session',
        // 【下面的三个参数，在使用php session的时候无效】
        //  只有 \Yii::$app->user->enableSession == false的时候才有效。
        //  说的更明确点就是：这些参数的设置是给无状态api使用的。
        //  实现了一个类似session的功能，供appserver端使用
        // 【对phpsession 无效】设置session过期时间,
        //  对于 appfront  apphtml5部分的session的设置，您需要到 @app/config/main.php 中设置 session 组件 的timeout时间
        // 'timeout' => 3600,
        // 【对phpsession 无效】更新access_token_created_at值的阈值
        // 当满足条件：`access_token_created_at`（token创建时间）`timeout(过期时间)` <= `time`（当前时间） < updateTimeLimit (更新access_token_created_at值的阈值)
        // 则会将用户在数据库表中的  `access_token_created_at` 的值设置成当前时间，这样可以在access_token快要过期的时候，更新 `access_token_created_at`,
        // 同时避免了每次访问都更新 `access_token_created_at` 的开销。
        // 'updateTimeLimit' => 600,
        
        // 【不可以设置phpsession】默认为php session，只有当 \Yii::$app->user->enableSession == false时，下面的设置才有效。
        // 存储引擎  mongodb mysqldb redis
        'storage' => 'SessionRedis',  //'SessionMysqldb',
        //'childService' => [
        //    'session' => [
        //        'class' => 'fecshop\services\session\Session',
        //    ],
        //],
    ],
];
