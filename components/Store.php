<?php
/**
 * FecShop file.
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Store extends Component implements BootstrapInterface
{
    public $appName;
    
    public $_base_config;
    
    protected $_modelName = '\fecshop\models\mysqldb\StoreBaseConfig';

    protected $_model;
    
    //
    public $serviceMongodbName = 'mongodb'; 
    public $serviceMysqldbName = 'mysqldb'; 
    //
    public $enable = 1;
    public $disable = 2;
    
    
    // 初始化的bootstrap
    public function bootstrap($app)
    {
        if ($this->appName == 'appadmin') {
            Yii::$service->admin->bootstrap($app);
        } else {
            Yii::$service->store->bootstrap($app);
        }
    }
    // 得到配置值
    /**
     * @param $key | string, 配置的主Key
     * @param $subKey | string, 配置的子key
     */
    public function get($key, $subKey = '')
    {
        $this->initBaseConfig();
        if (!$subKey) {
            return isset($this->_base_config[$key]) ? $this->_base_config[$key] : null;
        }
        return isset($this->_base_config[$key][$subKey]) ? $this->_base_config[$key][$subKey] : null;
    }
    
    
    public function initBaseConfig()
    {
        if (!$this->_base_config) {
            $this->_base_config = Yii::$service->storeBaseConfig->getAllConfig();
        }
    }
    
    
    
    
}
