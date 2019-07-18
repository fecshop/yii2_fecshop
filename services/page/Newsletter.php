<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\page;

//use fecshop\models\mongodb\customer\Newsletter as MongoNewsletter;
use fecshop\services\Service;

/**
 * Page Newsletter services.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Newsletter extends Service
{
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage     = 'NewsletterMysqldb';   // NewsletterMysqldb | NewsletterMongodb 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

    /**
     * 设置storage的path路径，
     * 如果不设置，则系统使用默认路径
     * 如果设置了路径，则使用自定义的路径
     */
    public $storagePath = '';
    protected $_newsletter;
    
    public function init()
    {
        parent::init();
        $currentService = $this->getStorageService($this);
        $this->_newsletter = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMongoStorage()
    {
        $this->storage     = 'ReviewMongodb';
        $currentService = $this->getStorageService($this);
        $this->_newsletter = new $currentService();
    }
    
    // 动态更改为mongodb model
    public function changeToMysqlStorage()
    {
        $this->storage     = 'ReviewMysqldb';
        $currentService = $this->getStorageService($this);
        $this->_newsletter = new $currentService();
    }
    
    public function subscription($email)
    {
        return $this->_newsletter->subscription($email);
    }
    
    /**
     * @param $filter|array
     * get subscription email collection
     */
    public function getSubscriptionList($filter)
    {
        return $this->_newsletter->getSubscriptionList($filter);
    }
    
    
    
    
    
}
