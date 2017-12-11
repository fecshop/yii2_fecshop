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
use fecshop\services\Service;
//use fecshop\models\mysqldb\SessionStorage;

/**
 * mysql session services
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class SessionMysqldb extends Service implements SessionInterface
{
    protected $_sessionModelName = '\fecshop\models\mysqldb\SessionStorage';
    protected $_sessionModel;
    
    public function init(){
        parent::init();
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
    /**
     * 销毁所有
     */
    public function destroy(){
        if(!Yii::$app->user->isGuest){
            $identity = Yii::$app->user->identity;
            $identity->access_token = '';
            $identity->access_token_created_at = null;
            $identity->save();
        }
        $uuid = Yii::$service->session->getUUID();
        $result = $this->_sessionModel->deleteAll([
            'uuid' => $uuid,
        ]);
        $access_token_created_at = $identity->access_token_created_at;
        $timeout = Yii::$service->session->timeout;
        if($access_token_created_at + $timeout > time()){
            return $accessToken;
        } 
        return true;
       
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