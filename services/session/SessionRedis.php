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
    protected $valSeparator = '||####||';

    public function init()
    {
        parent::init();
    }

    public function set($key, $val, $timeout)
    {
        $key = $this->getSessionKey($key);
        $val = $val . $this->valSeparator . time();
        return (bool) Yii::$app->redis->executeCommand('SET', [$key, $val, 'EX', $timeout]);
    }
    public function get($originKey, $reflush)
    {
        $key = $this->getSessionKey($originKey);
        $data = Yii::$app->redis->executeCommand('GET', [$key]);
        $arr = explode($this->valSeparator, $data);
        if (count($arr) < 2) {
            return '';
        }
        $val = $arr[0];
        $timeout = $arr[1];
        if (Yii::$service->session->isUpdateTimeOut($timeout) && $val) {
            $this->set($originKey, $val, $timeout);
        }
        return $val === false || $val === null ? '' : $val;
    }
    public function remove($key)
    {
        $key = $this->getSessionKey($key);
        Yii::$app->redis->executeCommand('DEL', [$key]);
        // @see https://github.com/yiisoft/yii2-redis/issues/82
        return true;
    }

    public function getSessionKey($key)
    {
        $uuid = Yii::$service->session->getUUID();
        return $uuid.'###^^###'.$key;
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