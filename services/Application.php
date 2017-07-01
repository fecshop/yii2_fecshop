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
use yii\base\InvalidConfigException;

/**
 * 此对象就是Yii::$service,通过魔术方法__get ， 得到服务对象，服务对象是单例模式。
 * 对于fecshop服务的介绍，可以参看文档：http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-service-abc.html
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Application
{
    public $childService;
    public $_childService;
    
    /**
     * @property $config | Array 注入的配置数组
     * 在@app/web/index.php 入口文件处。会调用 new fecshop\services\Application($config['services']); 
     * Yii::$service 就是该类实例化的对象，注入的配置保存到 $this->childService 中
     */
    public function __construct($config = [])
    {
        Yii::$service = $this;
        $this->childService = $config;
    }

    /**
     * 得到services 里面配置的子服务childService的实例.
     * 单例模式，懒加载，使用的时候才会被实例化。类似于Yii2的component原理。
     */
    public function getChildService($childServiceName)
    {
        if (!$this->_childService[$childServiceName]) {
            $childService = $this->childService;
            if (isset($childService[$childServiceName])) {
                $service = $childService[$childServiceName];
                $this->_childService[$childServiceName] = Yii::createObject($service);
            } else {
                throw new InvalidConfigException('Child Service ['.$childServiceName.'] is not find in '.get_called_class().', you must config it! ');
            }
        }

        return $this->_childService[$childServiceName];
    }
    /**
     * @property $attr | String ， service的name。
     * 魔术方法，当调用一个属性，对象不存在的时候就会执行该方法，然后
     * 根据构造方法注入的配置，实例化service对象。
     */
    public function __get($attr)
    {
        return $this->getChildService($attr);
    }
}
