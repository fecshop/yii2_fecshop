<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\cms\staticblock;

//use fecshop\models\mongodb\cms\StaticBlock;
use Yii;
use fecshop\services\Service;

/**
 * staticBlock部分，mongodb的实现部分。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class StaticBlockMongodb extends Service implements StaticBlockInterface
{
    public $numPerPage = 20;

    protected $_staticBlockModelName = '\fecshop\models\mongodb\cms\StaticBlock';

    protected $_staticBlockModel;
    
    public function init()
    {
        parent::init();
        list($this->_staticBlockModelName, $this->_staticBlockModel) = Yii::mapGet($this->_staticBlockModelName);
    }

    public function getPrimaryKey()
    {
        return '_id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            
            return $this->_staticBlockModel->findOne($primaryKey);
        } else {
            
            return new $this->_staticBlockModelName();
        }
    }

    public function getByIdentify($identify)
    {
        return $this->_staticBlockModel->find()->asArray()->where([
            'identify' => $identify,
            'status'  => $this->getEnableStatus()
        ])->one();
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
        $query = $this->_staticBlockModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);

        return [
            'coll' => $query->all(),
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
        if (!($this->validateIdentify($one))) {
            Yii::$service->helper->errors->add('Static block: identify exit, You must define a unique identify');

            return;
        }
        if ($primaryVal) {
            $model = $this->_staticBlockModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('Static block {primaryKey} is not exist', ['primaryKey' => $this->getPrimaryKey()]);

                return;
            }
        } else {
            $model = new $this->_staticBlockModelName();
            $model->created_at = time();
            $model->created_user_id = \fec\helpers\CUser::getCurrentUserId();
            $primaryVal = new \MongoDB\BSON\ObjectId();
            $model->{$this->getPrimaryKey()} = $primaryVal;
        }

        $model->updated_at = time();
        unset($one['_id']);
        $saveStatus = Yii::$service->helper->ar->save($model, $one);

        return true;
    }

    protected function validateIdentify($one)
    {
        $identify   = $one['identify'];
        $id         = $this->getPrimaryKey();
        $primaryVal = isset($one[$id]) ? $one[$id] : '';
        $where      = ['identify' => $identify];
        $query      = $this->_staticBlockModel->find()->asArray();
        $query->where(['identify' => $identify]);
        if ($primaryVal) {
            $query->andWhere([$id => ['$ne'=> new \MongoDB\BSON\ObjectId($primaryVal)]]);
        }
        $one = $query->one();

        if (!empty($one)) {
            
            return false;
        }

        return true;
    }

    /**
     * remove Static Block.
     */
    public function remove($ids)
    {
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return false;
        }
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_staticBlockModel->findOne($id);
                $model->delete();
            }
        } else {
            $id = $ids;
            $model = $this->_staticBlockModel->findOne($id);
            $model->delete();
        }

        return true;
    }
    
    
    public function getEnableStatus()
    {
        $model = $this->_staticBlockModel;
        
        return $model::STATUS_ACTIVE;
    }
    
    public function getDisableStatus()
    {
        $model = $this->_staticBlockModel;
        
        return $model::STATUS_DISACTIVE;
    }
}
