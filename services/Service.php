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
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\base\BaseObject;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Service extends BaseObject
{
    public $childService;

    public $enableService = true; // 该服务是否可用

    protected $_childService;

    protected $_beginCallTime;

    protected $_beginCallCode;

    protected $_callFuncLog;

    public function __get($attr)
    {
        return $this->getChildService($attr);
    }

    /**
     * 通过call函数，去调用actionXxxx方法。
     */
    public function __call($originMethod, $arguments)
    {
        if (isset($this->_callFuncLog[$originMethod])) {
            $method = $this->_callFuncLog[$originMethod];
        } else {
            $method = 'action'.ucfirst($originMethod);
            $this->_callFuncLog[$originMethod] = $method;
        }
        if (method_exists($this, $method)) {
            $this->beginCall($originMethod, $arguments);
            $return = call_user_func_array([$this, $method], $arguments);
            $this->endCall($originMethod, $arguments);

            return $return;
        } else {
            throw new InvalidCallException('fecshop service method is not exit.  '.get_class($this)."::$method");
        }
    }

    /**
     * 得到services 里面配置的子服务childService的实例.
     */
    public function getChildService($childServiceName)
    {
        //var_dump($this->childService['xunSearch']);exit;
        if (!isset($this->_childService[$childServiceName]) || !$this->_childService[$childServiceName]) {
            $childService = $this->childService;
            if (isset($childService[$childServiceName])) {
                $service = $childService[$childServiceName];
                if (!isset($service['enableService']) || $service['enableService'] !== false) {
                    $this->_childService[$childServiceName] = Yii::createObject($service);
                } else {
                    throw new InvalidConfigException('Child Service ['.$childServiceName.'] is disable in '.get_called_class().', you must config it! ');
                }
            } else {
                throw new InvalidConfigException('Child Service ['.$childServiceName.'] is not find in '.get_called_class().', you must config it! ');
            }
        } 

        return isset($this->_childService[$childServiceName]) ? $this->_childService[$childServiceName] : null;
    }

    /**
     * 得到所有的子服务
     * 如果子服务含有enableService字段，并且设置成false，则该子服务会被判定为关闭
     */
    public function getAllChildServiceName()
    {
        $childService = $this->childService;
        $arr = [];
        if (is_array($childService) && !empty($childService)) {
            foreach ($childService as $childName => $service) {
                if ($service['enableService'] !== false) {
                    $arr[] = $childName;
                }
            }
        }
        
        return $arr;
    }

    /**
     * 如果开启service log，则记录开始的时间。
     */
    protected function beginCall($originMethod, $arguments)
    {
        if (Yii::$app->serviceLog->isServiceLogEnable()) {
            $this->_beginCallTime = microtime(true);
        }
    }

    /**
     * @param $originMethod and $arguments,魔术方法传递的参数
     * 调用service后，调用endCall，目前用来记录log信息
     * 1. 如果service本身的调用，则不会记录，只会记录外部函数调用service
     * 2. 同一次访问的service_uid 的值是一样的，这样可以把一次访问调用的serice找出来。
     */
    protected function endCall($originMethod, $arguments)
    {
        if (Yii::$app->serviceLog->isServiceLogEnable()) {
            list($logTrace, $isCalledByThis) = $this->debugBackTrace();
            // if function is called by $this ,not log it to mongodb.
            if ($isCalledByThis) {
                return;
            }
            $begin_microtime = $this->_beginCallTime;
            $endCallTime = microtime(true);
            $used_time = round(($endCallTime - $begin_microtime), 4);
            if (is_object($arguments)) {
                $arguments = 'object';
            } elseif (is_array($arguments)) {
                $arguments = 'array';
            } else {
                $arguments = 'string or int or other';
            }
            $serviceLogUid = Yii::$app->serviceLog->getLogUid();
            $log_info = [
                'service_uid'               => $serviceLogUid,
                'current_url'               => Yii::$service->url->getCurrentUrl(),
                'home_url'                  => Yii::$service->url->homeUrl(),
                'service_file'              => get_class($this),
                'service_method'            => $originMethod,
                'service_method_argument'   => $arguments,
                'begin_microtime'           => $begin_microtime,
                'end_microtime'             => $endCallTime,
                'used_time'                 => $used_time,
                'process_date_time'         => date('Y-m-d H:i:s'),
                'log_trace'                 => $logTrace,
            ];

            Yii::$app->serviceLog->printServiceLog($log_info);
        }
    }

    /**
     * debug 追踪
     * 返回调用当前service的所有的文件。以及 行，类，类方法 。
     * 这几个方法将不会被记录：'__call','endCall','debugBackTrace','call_user_func_array'
     * 如果$file 不存在，也不会记录。
     * @return Array， $arr里面存储执行的记录，$isCalledByThis 代表是否是当前的service内部方法调用，
     *                  article 服务方法，在执行过程中，调用了一个内部的方法，追踪函数也会记录这个。
     */
    protected function debugBackTrace()
    {
        $arr = [];
        $isCalledByThis = false;
        $d = debug_backtrace();
        $funcNotContainArr = [
            '__call', 'endCall', 'debugBackTrace', 'call_user_func_array',
        ];
        $thisClass = get_class($this);
        //echo '**'.$thisClass.'**';
        $i = 0;
        $last_invoke_class = '';
        $last_sec_invoke_class = '';
        foreach ($d as $e) {
            $function = $e['function'];
            $class = $e['class'];
            //echo '**'.$class.'**';
            $file = $e['file'];
            $line = $e['line'];
            if ($file && !in_array($function, $funcNotContainArr)) {
                $arr[] = $file.'('.$line.'),'.$class.'::'.$function.'()';
                $i++;
                if ($i === 1) {
                    $last_invoke_class = $class;
                } elseif ($i === 2) {
                    $last_sec_invoke_class = $class;
                }
            }
        }
        if ($last_invoke_class === $last_sec_invoke_class) {
            $isCalledByThis = true;
        }

        return [$arr, $isCalledByThis];
    }
    
    /**
     * @param $object | Object , 调用该函数的对象
     * 注意：
     * 1. $object 必须存在属性storage，否则将会报错
     * 2. 根据该函数得到相应的Storage，该文件必须存在并设置好相应的namespace，否则将报错
     * 作用：
     * 作为services，同一个功能的实现，我们可能使用多种实现方式，譬如
     * search功能的实现，我们可以使用mysql，也可以使用mongodb，
     * 产品搜索，可以使用mongodb，也可以使用xunsearch，elasticSearch等
     * 因此一个功能可以有多种实现，我们通过设置$object->storage 来进行切换各种实现方式。
     * 譬如 searchStorage有2种，\fecshop\services\search\MongoSearch 和 \fecshop\services\search\XunSearch
     * 使用该函数返回相应的storage类，类似工厂的方式，易于后续的扩展。
     * 举例：
     * 在@fecshop\services\Product.php 这个类中设置类变量 $storage     = 'ProductMongodb';
     * 那么调用该函数返回的字符串为：'\fecshop\services\product\'+$storage，
     * 最终函数返回值为：\fecshop\services\product\ProductMongodb
     * 感谢：
     * @dionyang 提的建议：http://www.fecshop.com/topic/281
     */
    public function getStorageService($object)
    {
        $className = get_class($object);
        if (!isset($object->storage) || !$object->storage) {
            throw new InvalidConfigException('you must config class var $storage in '.$className);
            
            return false;
        }
        if ($object->storagePath) {
            $storagePath = '\\'.trim($object->storagePath, '\\').'\\';
        } else {
            $storagePath = '\\'.strtolower($className).'\\';
        }
        $storageServiceClass =  $storagePath.ucfirst($object->storage);
    
        if (!class_exists($storageServiceClass)) {
            throw new InvalidCallException('class ['.$storageServiceClass.'] is not exist , you must add the class before you use it');
            
            return false;
        }
        
        return $storageServiceClass;
    }
}
