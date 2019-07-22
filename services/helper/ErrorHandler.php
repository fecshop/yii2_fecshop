<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\helper;

use fecshop\services\Service;
use Yii;

/**
 * Helper Errors services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ErrorHandler extends Service
{
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage; //     = 'ErrorHandlerMysqldb';   // ErrorHandlerMysqldb | ErrorHandlerMongodb 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';
    protected $_errorHandler;
    
    public function init()
    {
        parent::init();
        // 从数据库配置中得到值, 设置成当前service存储，是Mysqldb 还是 Mongodb
        $config = Yii::$app->store->get('service_db', 'error_handle_log');
        $this->storage = 'ErrorHandlerMysqldb';
        if ($config == Yii::$app->store->serviceMongodbName) {
            $this->storage = 'ErrorHandlerMongodb';
        }
        $currentService = $this->getStorageService($this);
        $this->_errorHandler = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMongoStorage()
    {
        $this->storage     = 'ErrorHandlerMongodb';
        $currentService = $this->getStorageService($this);
        $this->_errorHandler = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMysqlStorage()
    {
        $this->storage     = 'ErrorHandlerMysqldb';
        $currentService = $this->getStorageService($this);
        $this->_errorHandler = new $currentService();
    }
    
    public function getPrimaryKey()
    {
        return $this->_errorHandler->getPrimaryKey();
    }
    

    /**
     * @param $code | Int, http 错误码
     * @param $message | String, 错误的具体信息
     * @param $file | string, 发生错误的文件
     * @param $line | Int, 发生错误所在文件的代码行
     * @param $created_at | Int, 发生错误的执行时间戳
     * @param $ip | string, 访问人的ip
     * @param $name | string, 错误的名字
     * @param $trace_string | string, 错误的追踪信息
     * @return 返回错误存储到mongodb的id，作为前端显示的错误编码
     * 该函数从errorHandler得到错误信息，然后保存到mongodb中。
     */
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
    ) 
    {
        return $this->_errorHandler->saveByErrorHandler($code,$message,$file,$line,$created_at,$ip,$name,$trace_string,$url,$req_info);
    }
    
    /**
     * 通过主键，得到errorHandler对象。
     */
    public function getByPrimaryKey($primaryKey)
    {
        return $this->_errorHandler->getByPrimaryKey($primaryKey);
    }
    
    /*
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
            'where'			=> [
                ['>','price',1],
                ['<=','price',10]
     * 			['sku' => 'uk10001'],
     * 		],
     * 	'asArray' => true,
     * ]
     */
    public function coll($filter)
    {
        return $this->_errorHandler->coll($filter);
    }
}
