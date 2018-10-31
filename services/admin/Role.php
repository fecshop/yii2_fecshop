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
use fecshop\services\Service;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Role extends Service
{
    public $numPerPage = 20;

    protected $_roleModelName = '\fecshop\models\mysqldb\admin\Role';

    protected $_roleModel;

    /**
     *  language attribute.
     */
    protected $_lang_attr = [
    ];

    public function init()
    {
        parent::init();
        list($this->_roleModelName, $this->_roleModel) = Yii::mapGet($this->_roleModelName);
    }
    public function getPrimaryKey()
    {
        return 'role_id';
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_roleModel->findOne($primaryKey);
            foreach ($this->_lang_attr as $attrName) {
                if (isset($one[$attrName])) {
                    $one[$attrName] = unserialize($one[$attrName]);
                }
            }

            return $one;
        } else {
            return new $this->_roleModelName();
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
        $query = $this->_roleModel->find();
        $query = Yii::$service->helper->ar->getCollByFilter($query, $filter);
        $coll = $query->all();
        if (!empty($coll)) {
            foreach ($coll as $k => $one) {
                foreach ($this->_lang_attr as $attr) {
                    $one[$attr] = $one[$attr] ? unserialize($one[$attr]) : '';
                }
                $coll[$k] = $one;
            }
        }
        //var_dump($one);
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * @property $one|array
     * save $data to cms model,then,add url rewrite info to system service urlrewrite.
     */
    public function saveRole($one)
    {
        $currentDateTime = \fec\helpers\CDate::getCurrentDateTime();
        $primaryVal = isset($one[$this->getPrimaryKey()]) ? $one[$this->getPrimaryKey()] : '';
        if (!($this->validateRoleName($one))) {
            Yii::$service->helper->errors->add('role name 存在，您必须定义一个唯一的role_name ');

            return null;
        }
        if ($primaryVal) {
            $model = $this->_roleModel->findOne($primaryVal);
            if (!$model) {
                Yii::$service->helper->errors->add('static block '.$this->getPrimaryKey().' is not exist');

                return null;
            }
        } else {
            $model = new $this->_roleModelName();
            $model->created_at = time();
        }
        $model->updated_at = time();
        foreach ($this->_lang_attr as $attrName) {
            if (is_array($one[$attrName]) && !empty($one[$attrName])) {
                $one[$attrName] = serialize($one[$attrName]);
            }
        }
        $primaryKey = $this->getPrimaryKey();
        $model      = Yii::$service->helper->ar->save($model, $one);
        $primaryVal = $model[$primaryKey];

        return $model;
    }

    /**
     * @param array $one ,example
     * [
     *      'role_id' => xx,
     *      'role_name' => 'xxxx',
     *      'role_description' => 'xxxx',
     *      'resources' => [3, 5, 76, 876, 999],
     * @return boolean
     * save role  info and resources
     * ]
     */
    public function saveRoleAndResources($one){
        $roleData = [];
        if (isset($one['role_id'])) {
            $roleData['role_id'] = $one['role_id'];
        }
        if (isset($one['role_name'])) {
            $roleData['role_name'] = $one['role_name'];
        } else {
            Yii::$service->helper->errors->add('role name can not empty');
            return false;
        }
        if (isset($one['role_description'])) {
            $roleData['role_description'] = $one['role_description'];
        }
        $primaryKey = $this->getPrimaryKey();
        $roleModel = $this->saveRole($roleData);
        if ($roleModel) {
            $roleId = $roleModel[$primaryKey];
            if ($roleId && isset($one['resources'])) {
                $resources = $one['resources'];
                if (is_array($resources) && !empty($resources)) {
                    Yii::$service->admin->roleUrlKey->repeatSaveRoleUrlKey($roleId, $resources);
                    
                    return true;
                }
            }
        }
        Yii::$service->helper->errors->add('save role and resource fail');

        return false;
    }

    protected function validateRoleName($one)
    {
        $role_name = $one['role_name'];
        $id = $this->getPrimaryKey();
        $primaryVal = isset($one[$id]) ? $one[$id] : '';
        $where = [
            'role_name' => $role_name,
        ];
        $query = $this->_roleModel->find()->asArray();
        $query->where($where);
        if ($primaryVal) {
            $query->andWhere(['<>', $id, $primaryVal]);
        }
        $one = $query->one();
        if (!empty($one)) {
            return false;
        }

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
                $model = $this->_roleModel->findOne($id);
                $model->delete();
            }
        } else {
            $id = $ids;
            foreach ($ids as $id) {
                $model = $this->_roleModel->findOne($id);
                $model->delete();
            }
        }

        return true;
    }
}
