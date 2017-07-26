<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\session;

use Yii;
//use fecshop\models\mysqldb\SessionStorage;

/**
 * mysql session services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class MysqldbSession implements SessionInterface
{
    protected $_sessionModelName = '\fecshop\models\mysqldb\SessionStorage';
    protected $_sessionModel;
    
    public function __construct(){
        list($this->_sessionModelName,$this->_sessionModel) = \Yii::mapGet($this->_sessionModelName);  
    }
    
    public function set($key,$val,$timeout){
        $uuid = Yii::$service->session->getUUID();
        $one = $this->_sessionModel->find()->where([
            'uuid' => $uuid,
            'key'  => $key,
        ])->one();
        if(!$one['id']){
            $one = new $this->_sessionModelName();
            $one['uuid'] = $uuid;
            $one['key']  = $key;
        }
        $one['value']       = $val;
        $one['timeout']     = $timeout;
        $one['updated_at']  = time();
        $one->save();
        return true;
    }

    public function get($key,$reflush){
        $uuid = Yii::$service->session->getUUID();
        $one = $this->_sessionModel->find()->where([
            'uuid' => $uuid,
            'key'  => $key,
        ])->one();
        if($one['id']){
            $timeout = $one['timeout'];
            $updated_at = $one['updated_at'];
            if($updated_at + $timeout > time()){
                if($reflush){
                    $one['updated_at']  = time();
                    $one->save();
                }
                return $one['value'];
            }
        }
    }

    public function remove($key){
        $uuid = Yii::$service->session->getUUID();
        $one = $this->_sessionModel->find()->where([
            'uuid' => $uuid,
            'key'  => $key,
        ])->one();
        if($one['id']){
            $one->delete();
            return true;
        }
        
    }

    public function setFlash($key,$val,$timeout){
        return $this->set($key,$val,$timeout);
    }
    
    public function getFlash($key){
        $uuid = Yii::$service->session->getUUID();
        $one = $this->_sessionModel->find()->where([
            'uuid' => $uuid,
            'key'  => $key,
        ])->one();
        if($one['id']){
            $timeout = $one['timeout'];
            $updated_at = $one['updated_at'];
            if($updated_at + $timeout > time()){
                
                $val = $one['value'];
                $one->delete();
                return $val;
            }
        }
    }
    
}