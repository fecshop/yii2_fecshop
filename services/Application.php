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
use yii\base\InvalidConfigException;

/**
 * 此对象就是Yii::$service,通过魔术方法__get ， 得到服务对象，服务对象是单例模式。
 * 对于fecshop服务的介绍，可以参看文档：http://www.fecshop.com/doc/fecshop-guide/develop/cn-1.0/guide-fecshop-service-abc.html
 *
 * For the convenience of jump of IDE, we declare all the services as follows:
 * @property \fecshop\services\AdminUser $adminUser adminUser service
 * @property \fecshop\services\Cart $cart cart service
 * @property \fecshop\services\Category $category category service
 * @property \fecshop\services\Cms $cms cms service
 * @property \fecshop\services\Coupon $coupon coupon service
 * @property \fecshop\services\Customer $customer customer service
 * @property \fecshop\services\Email $email email service
 * @property \fecshop\services\Event $event event service
 * @property \fecshop\services\Fecshoplang $fecshopLang fecshopLang service
 * @property \fecshop\services\Helper $helper helper service
 * @property \fecshop\services\Image $image image service
 * @property \fecshop\services\Order $order order service
 * @property \fecshop\services\Page $page page service
 * @property \fecshop\services\Payment $payment payment service
 * @property \fecshop\services\Point $point point service
 * @property \fecshop\services\Product $product product service
 * @property \fecshop\services\Request $request request service
 * @property \fecshop\services\Search $search search service
 * @property \fecshop\services\Session $session session service
 * @property \fecshop\services\Shipping $shipping shipping service
 * @property \fecshop\services\Sitemap $sitemap sitemap service
 * @property \fecshop\services\Store $store store service
 * @property \fecshop\services\Url $url url service
 *
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
                if (!isset($service['enableService']) || $service['enableService']) {
                    $this->_childService[$childServiceName] = Yii::createObject($service);
                } else {
                    throw new InvalidConfigException('Child Service ['.$childServiceName.'] is disable in '.get_called_class().', you must config it! ');
                }
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
