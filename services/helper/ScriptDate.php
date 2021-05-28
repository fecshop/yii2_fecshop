<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\helper;

use fecshop\services\Service;
use Yii;

/**
 * Helper ScriptDate services.
 * 此部分功能主要用于：当我们通过脚本，按照时间更新数据（譬如更新远程的订单物流信息），我们通过一个开始和结束时间进行
 * 时间的区间过滤，脚本的每次执行，都是将上一次执行完成的时间，作为下一次脚本执行的开始时间，而脚本正在处理则不允许新的脚本执行
 * 如果当前处理的脚本停止，那么超过xx时间后，可以重新初始化执行脚本。
 * 本部分就是对这种情况的处理。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
 
class ScriptDate extends Service
{
    // 脚本初始状态
    public $status_init = 1;
    // 脚本处理状态
    public $status_processing = 2;
    // 脚本完成状态
    public $status_complete = 3;
    // 第一次执行脚本，默认的数据开始时间
    public $init_begin_before_days = 30;
    // 如果没有传递参数，则使用默认的时间间隔，默认一个小时的间隔
    public $date_interval = 3600;
    // 脚本结束时间，最大不能离当前时间多少s, 也就是说  脚本的end_at <= time() - $this->maxEndDateLimit, 如果超过这个值，那么强制让 end_at = time() - $this->maxEndDateLimit
    public $maxEndDateLimit = 300;
    // 脚本运行的超时时间，让超过3600，将会强制初始化数据，重新执行
    public $processTimeout = 3600;
    
    
    public $numPerPage = 20;

    protected $_modelName = '\fecshop\models\mysqldb\helper\ScriptDate';

    protected $_model;

    
    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = Yii::mapGet($this->_modelName);
    }
    /**
     * 如果不使用默认值，可以通过该函数设置默认值
     * @param $init_begin_before_days | int,  第一次执行脚本，默认的数据开始时间
     * @param $date_interval | int, 如果没有传递参数，则使用默认的时间间隔，默认一个小时的间隔， beginAt和endAt
     * @param $maxEndDateLimit ， 脚本结束时间，最大不能离当前时间多少s, 也就是说  脚本的end_at <= time() - $this->maxEndDateLimit, 如果超过这个值，那么强制让 end_at = time() - $this->maxEndDateLimit
     * @param $processTimeout， 脚本运行的超时时间，让超过3600，将会强制初始化数据，重新执行。
     */
    public function initParam($init_begin_before_days='', $date_interval='', $maxEndDateLimit='', $processTimeout='')
    {
        if ($init_begin_before_days) {
            $this->init_begin_before_days = $init_begin_before_days;
        }
        if ($date_interval) {
            $this->date_interval = $date_interval;
        }
        if ($maxEndDateLimit) {
            $this->maxEndDateLimit = $maxEndDateLimit;
        }
        if ($processTimeout) {
            $this->processTimeout = $processTimeout;
        }
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
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        
        if ($primaryVal) {
            $model = $this->_model->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('scriptDate {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

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
    /**
     * @param $scriptTypeName | string
     * @param $error_info | string or array
     * 更新脚本的错误信息
     */
    public function updateScriptErrorInfo($scriptTypeName, $error_info)
    {
        if (!$error_info) {
            
            return true;
        }
        $model = $this->_model->findOne([
            'status' => $this->status_processing,
            'type' => $scriptTypeName,
        ]);
        if (!$model['status']) {
            
            return false;
        }
        if (is_array($error_info)) {
            $error_info = implode(',', $error_info);
        }
        if ($model['error_info'] != '') {
            $error_info = $model['error_info'] . ' | ' . $error_info;
        }
        $model['error_info'] = $error_info;
        $model['script_updated_at'] = time();
        $model->save();
        
        return true;
    }
    
    /**
     * 得到正在进行的脚本，开始执行时间和结束执行时间。
     * @param $scriptTypeName | sring， 您的脚本类型名称
     */
    public function getProcessBeginAndEndAt($scriptTypeName)
    {
        
        $model = $this->_model->findOne([
            'status' => $this->status_processing,
            'type' => $scriptTypeName,
        ]);
        
        if ($model['begin_at'] && $model['end_at']) {
            
            return [$model['begin_at'], $model['end_at']];
        }
        
        return null;
    }
    
    /**
     * 2.开始执行脚本
     * @param $scriptTypeName | sring， 您的脚本类型名称
     */
    public function processScript($scriptTypeName)
    {
        $updateComules = $this->_model->updateAll(
            [
                'status' => $this->status_processing,
                'script_updated_at' => time(),
            ],
            [
                'type' => $scriptTypeName,
                'status' => $this->status_init,
            ]
        );
        if (empty($updateComules)) {
            Yii::$service->helper->errors->add('process script begin fail');
            
            return false;
        }
        
        return true;
    }
    
    /**
     * 3.完成脚本
     * 
     */
    public function completeScript($scriptTypeName)
    {
        $updateComules = $this->_model->updateAll(
            [
                'status' => $this->status_complete,
                'script_updated_at' => time(),
            ],
            [
                'type' => $scriptTypeName,
                'status' => $this->status_processing,
                'error_info' => null,  // 如果分页执行过程中存在报错，则不能complete
            ]
        );
        if (empty($updateComules)) {
            Yii::$service->helper->errors->add('complete script fail');
            
            return false;
        }
        
        return true;
    }
    
    /**
     * 1.初始化脚本信息
     * @param $scriptTypeName | sring， 您的脚本类型名称
     * 获取脚本的开始和数据时间
     * 本service的主要作用，是某些同步数据脚本，根据时间来进行同步数据，当脚本结束后，下一个脚本以上次脚本
     * 的结束时间作为下次脚本执行的开始时间。
     */
    public function initScript($scriptTypeName)
    {
        $model = $this->_model->findOne(['type' => $scriptTypeName]);
        $beginAt = '';
        if ($model) {
            // 
            if ($model['status'] == $this->status_complete ) {  // 判断当前的记录是否是完成状态？上个脚本是否已经完成
                $beginAt = $model['end_at']; 
            } else if ($model['script_updated_at'] - $model['script_created_at'] > $this->processTimeout ) {
                // 当脚本执行了xx时间，还是没有执行完成，则会强制init
                $model->script_created_at = time();
                $model->script_updated_at = time();
                $model->status = $this->status_init;
                $model->error_info = NULL;
                
                return $model->save();
            } else {
                // 此种情况，代表脚本已经初始化，或者脚本正在进行中
                // 更新 script_updated_at
                $model->script_updated_at = time();
                $model->save();
                 
                return false;
            }
        } else {
            $model = new $this->_modelName();
            $model->created_at = time();
        }
        list($beginAt, $endAt) = $this->getBeginAndEndDateTime($beginAt);
        
        $model->script_created_at = time();
        $model->script_updated_at = time();
        $model->type = $scriptTypeName;
        $model->status = $this->status_init;
        $model->begin_at = $beginAt;
        $model->end_at = $endAt;
        $model->error_info = NULL;
        
        return $model->save();
    }
   
    /** 
     * @param $beginAt | int, 开始时间戳
     * 得到脚本的开始和结束时间
     */
    protected function getBeginAndEndDateTime($beginAt='')
    {
        if (!$beginAt) {
            $beginAt = strtotime(' -'.$this->init_begin_before_days.' days' );
        }
        $beginAt = $this->correctDateTime($beginAt);
        
        $endAt = $beginAt + $this->date_interval;
        $endAt = $this->correctDateTime($endAt);
        
        return [$beginAt, $endAt];
    }
    /**
     *
     */
    protected function correctDateTime($dateTimestramp)
    {
        $maxDataTimestramp = time() - $this->maxEndDateLimit;
        if ($dateTimestramp > $maxDataTimestramp) {
            
            return $maxDataTimestramp;
        }
        
        return $dateTimestramp;
    }
    
    
    
}