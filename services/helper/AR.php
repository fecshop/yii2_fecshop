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
use yii\db\ActiveQuery;

/**
 * AR services.（Active Record）
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AR extends Service
{
    public $numPerPage = 20;

    public $pageNum = 1;

    /**
     * example filter:
     * [
     * 		'numPerPage' 	=> 20,
     * 		'pageNum'		=> 1,
     * 		'orderBy'	=> ['_id' => SORT_DESC, 'sku' => SORT_ASC ],
     * 		'where'			=> [
     *         ['>','price',1],
     *         ['<=','price',10]
     * 	        ['sku' => 'uk10001'],
     * 		],
     *      'asArray' => true,
     *      'fetchAll' => false,   // 是否获取所有数据,如果为true，则传递的numPerPage和pageNum将会无效。
     * ]
     * 查询方面使用的函数，根据传递的参数，进行query
     * @return ActiveQuery
     */
    public function getCollByFilter($query, $filter)
    {
        $fetchAll =  isset($filter['fetchAll']) ? $filter['fetchAll'] : false;
        $select     = isset($filter['select']) ? $filter['select'] : '';
        $asArray    = isset($filter['asArray']) ? $filter['asArray'] : true;
        $numPerPage = isset($filter['numPerPage']) ? $filter['numPerPage'] : $this->numPerPage;
        $pageNum    = isset($filter['pageNum']) ? $filter['pageNum'] : $this->pageNum;
        $orderBy    = isset($filter['orderBy']) ? $filter['orderBy'] : '';
        $where      = isset($filter['where']) ? $filter['where'] : '';
        if ($asArray) {
            $query->asArray();
        }
        if (is_array($select) && !empty($select)) {
            $query->select($select);
        }
        if ($where) {
            if (is_array($where)) {
                $i = 0;
                foreach ($where as $w) {
                    $i++;
                    if ($i == 1) {
                        $query->where($w);
                    } else {
                        $query->andWhere($w);
                    }
                }
            }
        }
        if (!$fetchAll) {
            $offset = ($pageNum - 1) * $numPerPage;
            $query->limit($numPerPage)->offset($offset);
        }
        if ($orderBy) {
            $query->orderBy($orderBy);
        }

        return $query;
    }

    /**
     * @param $model | Object , 数据库model
     * @param $one | Array ， 数据数组，对model进行赋值
     * 通过循环的方式，对$model对象的属性进行赋值。
     * 并保存，保存成功后，返回保存后的model对象
     */
    public function save($model, $one, $serialize = false)
    {
        if (!$model) {
            Yii::$service->helper->errors->add('ActiveRecord Save Error: $model is empty');

            return false;
        }
        $attributes = $model->attributes();
        if (is_array($attributes) && !empty($attributes)) {
            foreach ($attributes as $attr) {
                if (isset($one[$attr])) {
                    if ($serialize && is_array($one[$attr])) {
                        $model[$attr] = serialize($one[$attr]);
                    } else {
                        $model[$attr] = $one[$attr];
                    }
                }
            }
            if ($model->save()) {
                
                return $model;
            } else {
                Yii::$service->helper->errors->add('model save fail');

                return false;
            }
        } else {
            Yii::$service->helper->errors->add('$attribute is empty or is not array');

            return false;
        }
    }
}
