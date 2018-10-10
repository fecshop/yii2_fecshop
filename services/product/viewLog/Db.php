<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\product\viewLog;

use fec\helpers\CDate;
use fec\helpers\CUser;
//use fecshop\models\db\product\ViewLog as DbViewLog;
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Db extends Service
{
    public $table;

    public $_defaultTable = 'log_product_view';

    public $_maxProductCount = 10;
    
    protected $_logModelName = '\fecshop\models\db\product\ViewLog';

    protected $_logModel;
    
    // init function
    public function init()
    {
        parent::init();
        list($this->_logModelName, $this->_logModel) = \Yii::mapGet($this->_logModelName);
        if (!$this->table) {
            $this->table = $this->_defaultTable;
        }
        $this->_logModel->setCurrentTableName($this->table);
    }

    /**
     *	get product history log.
     */
    public function getHistory($user_id = '', $count = '')
    {
        if (!$count) {
            $count = $this->_maxProductCount;
        }
        if (!$user_id) {
            $user_id = CUser::getCurrentUserId();
        }
        if (!$user_id) {
            return;
        }
        $coll = $this->_logModel->find()->where([
            'user_id' => $user_id,
        ])
            ->asArray()
            ->orderBy(['date_time' => SORT_DESC])
            ->limit($count)
            ->all();

        return $coll;
    }

    /**
     *	save product visit log.
     */
    public function setHistory($productOb)
    {
        $dbViewLog = new $this->_logModelName();
        if (isset($productOb['user_id']) && $productOb['user_id']) {
            $dbViewLog->user_id = $productOb['user_id'];
        } elseif ($currentUser = CUser::getCurrentUserId()) {
            $dbViewLog->user_id = $currentUser;
        } else {
            // if not give user_id, can not save history
            return;
        }
        $dbViewLog->date_time = CDate::getCurrentDateTime();
        $dbViewLog->product_id = $productOb['id'];
        $dbViewLog->sku = $productOb['sku'];
        $dbViewLog->image = $productOb['image'];
        $dbViewLog->name = $productOb['name'];
        $dbViewLog->save();
    }
}
