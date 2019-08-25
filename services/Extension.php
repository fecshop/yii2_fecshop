<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services;

//use fecshop\models\mysqldb\cms\StaticBlock;
use Yii;
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Extension extends Service
{
    public $numPerPage = 20;
    
    const INSTALLED_STATUS = 1;
    const INSTALL_INIT_STATUS = 2;
    const UNINSTALLED_STATUS = 3;
    
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;
    
    protected $warnings;
    protected $_modelName = '\fecshop\models\mysqldb\Extension';

    protected $_model;
    
    
    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = Yii::mapGet($this->_modelName);
    }
    
    public function getPrimaryKey()
    {
        return 'id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_model->findOne($primaryKey);
            
            return $one;
        } else {
            return new $this->_modelName();
        }
    }
    /**
     * @param $extension_name | string ， 插件名称（唯一）
     * @return model
     */
    public function getByNamespace($extension_namespace)
    {
        return $this->_model->findOne(['namespace' => $extension_namespace]);
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
        $query = $this->_model->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll = $query->all();
        
        //var_dump($one);
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * @param $one|array
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function save($one)
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        
        if ($primaryVal) {
            $model = $this->_model->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('extension: {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

                return;
            }
        } else {
            $model = new $this->_modelName();
            $model->created_at = time();
        }
        $model->updated_at = time();
        
        $primaryKey = $this->getPrimaryKey();
        $model      = Yii::$service->helper->ar->save($model, $one);
        $primaryVal = $model[$primaryKey];

        return true;
    }

    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_model->findOne($id);
                $model->delete();
            }
        } else {
            $id = $ids;
            $model = $this->_model->findOne($id);
            $model->delete();
        }

        return true;
    }
    
    // 新安装的插件，进行初始化
    public function newInstallInit($param)
    {
        $namespace = $param['namespace'];
        //$package = $param['package'];
        //$name = $param['name'];
        //$config_file_path = $param['config_file_path'];
        //$version = $param['version'];
        if (!$namespace) {
            Yii::$service->helper->errors->add('namespace is empty');

            return false;
        }
        // 查看namespace 是否存在
        $modelOne = $this->_model->findOne(['namespace' => $namespace]);
        if ($modelOne['id']) {
            Yii::$service->helper->errors->add('this namespace is exist');

            return false;
        }
        $param['config_file_path'] = '@addons/' . $param['package'] . '/' .  $param['config_file_path'] ;
        $model = new $this->_modelName();
        $model->attributes = $param;
        if (!$model->validate()) {
            $errors = $model->errors;
            Yii::$service->helper->errors->addByModelErrors($errors);
            return false;
        }
        $model->status = self::STATUS_ENABLE;
        $model->created_at = time();
        $model->updated_at = time();
        $model->installed_status = self::INSTALL_INIT_STATUS;
        $model->priority = 1;
        return $model->save();
    }
    
    // 从数据库中获取所有的namespace
    public function getAllNamespaces()
    {
        $filter = [
            'asArray' => true,
            'fetchAll' => true,
        ];
        $data = $this->coll($filter);
        $arr = [];
        if (is_array($data['coll'])) {
            foreach ($data['coll'] as $one) {
                $namespace = $one['namespace'];
                if ($namespace) {
                    $arr[] = $namespace;
                }
            }
        }
        
        return $arr;
    }
    
    
    
    /**
     * @param $installed_status | int 
     * @return boolean 插件是否已安装
     */
    public function isInstalledStatus($installed_status)
    {
        if ($installed_status == self::INSTALLED_STATUS) {
            
            return true;
        }
        
        return false;
    }
    // 添加警告信息
    public function addWarning($info)
    {
        $this->warnings[] = $info;
        
        return true;
    }
    // 得到警告信息
    public function getWarning()
    {
        return $this->warnings;
    }
    
    //protected $_installOb;
    /**
     * @param $installConfig | array
     * 进行应用的安装
     * 通过扩展配置中获取安装部分的配置，通过该函数执行安装。
     */
    public function installAddons($installConfig, $modelOne)
    {
        $installOb = Yii::createObject($installConfig);
        if (!$installOb->version) {
            Yii::$service->helper->errors->add("Extension Install Object must have property `version`");
            
            return false;
        }
        if (!($installOb instanceof \fecshop\services\extension\InstallInterface)) {
            Yii::$service->helper->errors->add("Extension install file must implements interface `\fecshop\services\extension\InstallInterface`");
            
            return false;
        }
        if (!$installOb->run()) {
            return false;
        }
        // 更新数据库-扩展的安装信息。
        $modelOne->installed_status = self::INSTALLED_STATUS;
        $modelOne->installed_version = $installOb->version;
        $modelOne->updated_at = time();
        return $modelOne->save();
        
    }
    
    
    /**
     * @param $installConfig | array
     * 进行应用的升级
     * 通过扩展配置中获取安装部分的配置，通过该函数执行安装。
     */
    public function upgradeAddons($upgradeConfig, $modelOne)
    {
        $upgradeOb = Yii::createObject($upgradeConfig);
        if (!($upgradeOb instanceof \fecshop\services\extension\UpgradeInterface)) {
            Yii::$service->helper->errors->add("Extension upgrade file must implements interface `\fecshop\services\extension\UpgradeInterface`");
            
            return false;
        }
        $versions = $upgradeOb->versions;
        if (!empty($versions) && !is_array($versions)) {
            Yii::$service->helper->errors->add("Upgrade Object property `versions` must be array");
            
            return false;
        }
        $installed_version = $modelOne['installed_version'];
        
        $count = count($versions);
        for ($i = 0; $i < $count; $i++) {
            // 如果当前版本号 小于 此版本号
            if (version_compare($installed_version, $versions[$i] ,'<')) {
                // 执行插件更新版本操作。
                if (!$upgradeOb->run($versions[$i])) {
                    return false;
                }
                // 更新数据库插件安装版本信息
                $modelOne->installed_version = $versions[$i];
                $modelOne->updated_at = time();
                if (!$modelOne->save()) {
                    return false;
                }
            }
        }
        return true;
    }
    
}
