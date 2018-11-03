<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\admin;

//use fecshop\models\mysqldb\cms\StaticBlock;
use Yii; 
use fec\helpers\CUrl;
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class SystemLog extends Service
{
    public $enableLog = true;
    
    protected $_modelName = '\fecshop\models\mysqldb\admin\SystemLog';

    protected $_model;
    
    /**
     *  language attribute.
     */
    protected $_lang_attr = [
    ];

    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = Yii::mapGet($this->_modelName);
    }
    
	# 保存系统日志。
	public function save(){
		if (!$this->enableLog) {
            return false;
        }
		$systemLog = $this->_model;
		$user = Yii::$app->user->identity;
		if($user){
			$url_key = '/' . Yii::$app->controller->module->id . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
			$username = $user['username'];
			$person 	= $user['person'];
			$currentData = date('Y-m-d H:i:s');
			$url = CUrl::getCurrentUrl();
			$systemLog->account = $username;
			$systemLog->person = $person;
			$systemLog->created_at = $currentData;
			$systemLog->url = $url;
			$systemLog->url_key = $url_key;
			$systemLog->menu = $this->getMenuByUrlKey($url_key);
			$systemLog->save();
		}	
	}
	
	public function getMenuByUrlKey($url_key){
		if(!$url_key)
			return null;
		$menuArr = Yii::$service->admin->urlKey->getUrlKeyAndLabelArr();
		return isset($menuArr[$url_key]) ? $menuArr[$url_key] : null;
	}
	
}
