<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;

/**
 * AdminUser services. 用来给后台的用户提供数据。
 *
 * @property \fecshop\services\customer\AdminUser $adminUser
 * @property \fecshop\services\customer\UserLogin $userLogin
 *
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AdminUser extends Service
{
    public function init()
    {
        parent::init();

    }

    /**
     * @param $data|array
     * 数组格式：['username'=>'xxx@xxx.com','password'=>'xxxx']
     */
    protected function actionLogin($data)
    {
        return Yii::$service->adminUser->userLogin->login($data);
    }

    /**
     * @param $ids | Int Array
     * @return 得到相应用户的数组。
     */
    public function getIdAndNameArrByIds($ids)
    {
        return Yii::$service->adminUser->adminUser->getIdAndNameArrByIds($ids);
    }

    /** Appapi 部分使用的函数
     * @param $username | String
     * @param $password | String
     * @return mix string|null
     * Appapi 和 第三方进行数据对接部分的用户登陆验证
     */
    public function loginAndGetAccessToken($username, $password)
    {
        return Yii::$service->adminUser->userLogin->loginAndGetAccessToken($username, $password);
    }



    public function setHeaderAccessToken($accessToken)
    {
        return Yii::$service->adminUser->userLogin->setHeaderAccessToken($accessToken);
    }

    /** AppServer 部分使用的函数
     * @param $type | null or  Object
     * 从request headers中获取access-token，然后执行登录
     * 如果登录成功，然后验证时间是否过期
     * 如果不过期，则返回identity
     * ** 该方法为appserver用户通过access-token验证需要执行的函数。
     */
    public function loginByAccessToken($type = null)
    {
        return Yii::$service->adminUser->userLogin->loginByAccessToken($type);
    }

    /**
     * 通过accessToek的方式，进行登出从操作。
     */
    public function logoutByAccessToken()
    {
        return Yii::$service->adminUser->userLogin->logoutByAccessToken();
    }
}
