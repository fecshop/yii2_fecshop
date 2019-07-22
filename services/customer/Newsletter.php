<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\customer;

use fecshop\services\Service;
use Yii;

/**
 * Newsletter sub service of customer.
 * @method subscribe($email, $isRegister = true) see [[\fecshop\services\customer\Newsletter::actionSubscribe()]]
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Newsletter extends Service
{
    /**
     * $storagePrex , $storage , $storagePath 为找到当前的storage而设置的配置参数
     * 可以在配置中更改，更改后，就会通过容器注入的方式修改相应的配置值
     */
    public $storage; //     = 'NewsletterMysqldb';   // NewsletterMysqldb | NewsletterMongodb 当前的storage，如果在config中配置，那么在初始化的时候会被注入修改

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
        // 从数据库配置中得到值, 设置成当前service存储，是Mysqldb 还是 Mongodb
        $config = Yii::$app->store->get('service_db', 'newsletter');
        $this->storage = 'NewsletterMysqldb';
        if ($config == Yii::$app->store->serviceMongodbName) {
            $this->storage = 'NewsletterMongodb';
        }
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
    
    public function getPrimaryKey()
    {
        return $this->_newsletter->getPrimaryKey();
    }
    
    public function getByPrimaryKey($primaryKey)
    {
        return $this->_newsletter->getByPrimaryKey($primaryKey);
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
    public function coll($filter = '')
    {
        return $this->_newsletter->coll($filter);
    }

    /**
     * @param $emailAddress | String
     * @return bool
     *              检查邮件是否之前被订阅过
     */
    public function emailIsExist($emailAddress)
    {
        return $this->_newsletter->emailIsExist($emailAddress);
    }
    
    /**
     * @param $emailAddress | String
     * @return bool
     *              订阅邮件
     */
    public function subscribe($emailAddress, $isRegister = false)
    {
        return $this->_newsletter->subscribe($emailAddress, $isRegister);
    }
    
}
