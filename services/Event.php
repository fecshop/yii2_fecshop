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

//use yii\base\Component;
//use fecshop\services\event\FecshopEvent;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Event extends Service
{
    public $eventList; //Array

    /**
     * @param $eventName | String , 时间的名字
     * @param $data | Array ， 数据数组，将各个数据以数组的方式传递过来。
     * 从配置中找到相应的event，然后执行event。
     * event 相当于插代码的感觉。
     */
    public function trigger($eventName, $data)
    {
        if (!is_array($data)) {
            //Yii::$service->helper->errors->add('event data must array');
            return;
        }
        if (isset($this->eventList[$eventName]) && !empty($this->eventList[$eventName]) && is_array($this->eventList[$eventName])) {
            foreach ($this->eventList[$eventName] as $one) {
                if (is_array($one) && !empty($one)) {
                    list($class, $method) = $one;
                    if ($class && $method) {
                        $class::$method($data);
                    }
                }
            }
        }
    }
}
