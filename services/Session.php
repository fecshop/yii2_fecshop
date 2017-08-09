<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

use Yii;
use Ramsey\Uuid\Uuid;
use fecshop\services\session\PhpSession;
use fecshop\services\session\MongoDbSession;
use fecshop\services\session\MysqlDbSession;
use fecshop\services\session\RedisSession;

/**
 * Session services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Session extends Service
{
    // 设置session超时时间
    public $timeout;
    // 当过期时间+session创建时间 - 当前事件 < $updateTimeLimit ,则更新session创建时间
    public $updateTimeLimit = 600;
    // 设置 存储引擎
    public $storageEngine;
    // 生成的uuid唯一标识码
    protected $_uuid;
    
    private $_session;
    public  $fecshop_uuid = 'fecshop-uuid';
    /**
        1. \Yii::$app->user->enableSession = false;
            查看是否是false，如果是
    
     */
    public function init()
    {
        if(\Yii::$app->user->enableSession == true){
            $this->_session = new PhpSession; // phpsession
        }else {
            if ($this->storageEngine == 'mongodb') {
                $this->_session = new MongoDbSession;
            }else if ($this->storageEngine == 'mysqldb') {
                $this->_session = new MysqlDbSession;
            }else if ($this->storageEngine == 'redis') {
                $this->_session = new RedisSession;
            }
        }
    }
    
    /**
     * 访问端：
     * api访问接口，返回数据的时候，需要从 response headers 中 读取 uuid
     * api 如果获取了uuid，那么下次访问的时候，需要在request header 中附带uuid信息。
     *
     * 接收端： 也就是下面的函数
     * 先从 request headers 读取uuid
     * 读取不到，自己生成uuid
     * 最后将uuid写入response headers中
     */
    public function getUUID(){
        if(!$this->_uuid){
            $header = Yii::$app->request->getHeaders();
            $uuidName = $this->fecshop_uuid;
            // 1.从requestheader里面获取uuid，
            if(isset($header[$uuidName]) && !empty($header[$uuidName])){
                $this->_uuid = $header[$uuidName];
            }else{ // 2.如果获取不到uuid，就生成uuid
                $uuid1 = Uuid::uuid1();
                $this->_uuid = $uuid1->toString();
            }
            // 3.把 $this->_uuid 写入到 response 的header里面
            Yii::$app->response->getHeaders()->set($uuidName,$this->_uuid);
            
        }
        return $this->_uuid;
    }
    
    public function set($key,$val,$timeout=''){
        if(!$timeout && (Yii::$app->user->enableSession == false)){
            $timeout = $this->timeout;
        }
        return $this->_session->set($key,$val,$timeout);
    }
    
    public function get($key,$reflush=false){
        return $this->_session->get($key,$reflush);
        
    }
    
    public function remove($key){
        return $this->_session->remove($key);
    }
    
    
    
    public function setFlash($key,$val,$timeout=''){
        if(!$timeout && (Yii::$app->user->enableSession == false) ){
            $timeout = $this->timeout;
        }
        return $this->_session->setFlash($key,$val,$timeout);
    }
    
    public function getFlash($key){
        return $this->_session->getFlash($key);
    }
    
    
    
}
