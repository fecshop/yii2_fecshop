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
    
    const ADMIN_ROLEIDS_RESOURCES = 'admin_roleids_resources';
    
    public $numPerPage = 20;

    public $productViewAllRoleKey = 'catalog_product_view_all';
    public $productEditAllRoleKey = 'catalog_product_edit_all';
    public $productSaveAllRoleKey = 'catalog_product_save_all';
    public $productRemoveAllRoleKey = 'catalog_product_remove_all';

    protected $_roleModelName = '\fecshop\models\mysqldb\admin\Role';

    protected $_roleModel;

    protected $_current_role_resources;

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
                // delete user  role
                Yii::$service->admin->userRole->removeByRoleId($id);
                Yii::$service->admin->roleUrlKey->removeByRoleId($id);

            }
        } else {
            $id = $ids;
            $model = $this->_roleModel->findOne($id);
            $model->delete();
            // delete user  role
            Yii::$service->admin->userRole->removeByRoleId($id);
            Yii::$service->admin->roleUrlKey->removeByRoleId($id);
        }

        return true;
    }

    /**
     * @return array
     * 得到当前用户的可用的resources数组
     */
    public function getCurrentRoleResources(){
        if (!$this->_current_role_resources) {
            if (Yii::$app->user->isGuest) {
                return [];
            }
            $user = Yii::$app->user->identity;
            $userId = $user->Id;
            // 通过userId得到这个用户所在的用户组
            $userRoles = Yii::$service->admin->userRole->coll([
                'where' => [
                    [
                        'user_id' => $userId,
                    ]
                ],
                'fetchAll' => true,
            ]);
            $role_ids = [];
            if (is_array($userRoles['coll']) && !empty($userRoles['coll'])) {
                foreach ($userRoles['coll'] as $one) {
                    $role_ids[] = $one['role_id'];
                }
            }
            if (empty($role_ids)) {
                return [];
            }

            $this->_current_role_resources = $this->getRoleResourcesByRoleIds($role_ids);
        }

        return $this->_current_role_resources;
    }
    
    /**
     * @param array $role_ids
     * @return array , 包含url_key_id的数组
     *  通过$role_ids数组，获得相应的所有url_key_id数组
     */
    public function getRoleResourcesByRoleIds($role_ids){
        if (empty($role_ids)) {
            return [];
        }
        sort($role_ids);
        $role_ids_cache_str = self::ADMIN_ROLEIDS_RESOURCES . implode('-', $role_ids);
        $resources = Yii::$app->cache->get($role_ids_cache_str);
        if (!$resources) {
            // 通过role_ids 得到url_keys
            $roleUrlKeys = Yii::$service->admin->roleUrlKey->coll([
                'where'			=> [
                    ['in', 'role_id',  $role_ids]
                ],
                'fetchAll' => true,
            ]);
            $roleUrlKeyIds = [];
            if (is_array($roleUrlKeys['coll']) && !empty($roleUrlKeys['coll'])) {
                foreach ($roleUrlKeys['coll'] as $one) {
                    if (!isset($roleUrlKeyIds[$one['url_key_id']])) {
                        $roleUrlKeyIds[$one['url_key_id']] = $one['url_key_id'];
                    }
                }
            }
            $urlKeys = Yii::$service->admin->urlKey->coll([
                'where'			=> [
                    ['in', 'id',  $roleUrlKeyIds]
                ],
                'fetchAll' => true,
            ]);
            $urlKeyIds = [];
            if (is_array($urlKeys['coll']) && !empty($urlKeys['coll'])) {
                foreach ($urlKeys['coll'] as $one) {
                    if (!isset($urlKeyIds[$one['url_key']])) {
                        $urlKeyIds[$one['url_key']] = $one['url_key'];
                    }
                }
            }
            
            Yii::$app->cache->set($role_ids_cache_str, $urlKeyIds);
            
            return $urlKeyIds;
        }
        
        return $resources;
    }
}
