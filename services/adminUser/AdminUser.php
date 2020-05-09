<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\services\adminUser;

use Yii;
use fecshop\services\Service;

/**
 * AdminUser services. 用来给后台的用户提供数据。
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class AdminUser extends Service
{
    public $numPerPage = 20;
    /**
     *  language attribute.
     */
    protected $_lang_attr = [];
    protected $_modelName = '\fecshop\models\mysqldb\AdminUser';
    protected $_model;

    protected $_userFormModelName = '\fecshop\models\mysqldb\adminUser\AdminUserForm';
    protected $_userFormModel;
    
    protected $_userPassResetModelName = '\fecshop\models\mysqldb\adminUser\AdminUserResetPassword';
    protected $_userPassResetModel;

    public function init()
    {
        parent::init();
        list($this->_modelName, $this->_model) = \Yii::mapGet($this->_modelName);
        list($this->_userFormModelName, $this->_userFormModel) = \Yii::mapGet($this->_userFormModelName);
        list($this->_userPassResetModelName, $this->_userPassResetModel) = \Yii::mapGet($this->_userPassResetModelName);
    }
    /**
     * @param $data array
     * @return boolean
     * update current user password
     */
    public function resetCurrentPassword($data){
        $this->_userPassResetModel->attributes = $data;
        if ($this->_userPassResetModel->validate()) {
			$this->_userPassResetModel->updatePassword();
            
            return true;
        } else {
            $errors = $this->_userPassResetModel->errors;
			Yii::$service->helper->errors->addByModelErrors($errors);
            
            return false;
        }        
    }

    public function getPrimaryKey()
    {
        return 'id';
    }

    public function getActiveStatus(){
        $model = $this->_model;
        
        return $model::STATUS_ACTIVE;
    }
    public function getDeleteStatus(){
        $model = $this->_model;
        
        return $model::STATUS_DELETED;
    }

    public function getByPrimaryKey($primaryKey)
    {
        if ($primaryKey) {
            $one = $this->_model->findOne($primaryKey);
            foreach ($this->_lang_attr as $attrName) {
                if (isset($one[$attrName])) {
                    $one[$attrName] = unserialize($one[$attrName]);
                }
            }
            
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
        if (!empty($coll)) {
            foreach ($coll as $k => $one) {
                foreach ($this->_lang_attr as $attr) {
                    $one[$attr] = $one[$attr] ? unserialize($one[$attr]) : '';
                }
                $coll[$k] = $one;
            }
        }
        
        return [
            'coll' => $coll,
            'count'=> $query->limit(null)->offset(null)->count(),
        ];
    }

    /**
     * @param $data array, user form data
     * @param $roles array, role id array
     * @return boolean
     * 保存用户的信息，以及用户的role信息。
     */
    public function saveUserAndRole($data, $roles){
        $user_id = $this->save($data);
        if (!$user_id) {
            
            return false;
        }
        if (Yii::$service->admin->userRole->saveUserRole($user_id, $roles)) {
            
            return true;
        } 
        
        return false;
    }
    /**
     * @param $data array, user form data
     * @return mix ，return save user id | null
     * 保存用户的信息。
     */
    public function save($data) {
        $primaryKey = $this->getPrimaryKey();
        $user_id = 0;
        if ($data[$primaryKey]) {
            $this->_userFormModel = $this->_userFormModel->findOne($data[$primaryKey]);
        }
        $this->_userFormModel->attributes = $data;
        
        if (!$data['access_token']) {
            $this->_userFormModel->access_token = '';
        }
        if (!$data['auth_key']) {
            $this->_userFormModel->auth_key = '';
        }
        if (!$data['password'] && !$data['id']) {
            Yii::$service->helper->errors->add("password can not empty");
            
            return null;
        }
        if ($this->_userFormModel[$primaryKey]) {
            if ($this->_userFormModel->validate()) {
                $this->_userFormModel->save();
                $user_id = $this->_userFormModel[$primaryKey];
            } else {
                $errors = $this->_userFormModel->errors;
                Yii::$service->helper->errors->addByModelErrors($errors);
                
                return null;
            }
        } else {
            if ($this->_userFormModel->validate()) {
                $this->_userFormModel->save();
                $user_id = Yii::$app->db->getLastInsertID();
            } else {
                $errors = $this->_userFormModel->errors;
                Yii::$service->helper->errors->addByModelErrors($errors);
                
                return null;
            }
        }
        
        return $user_id;
    }
    
    public function removeUserAndRole($ids) {
        $removeIds = $this->remove($ids);
        if (is_array($removeIds) && !empty($removeIds)) {
            Yii::$service->admin->userRole->deleteByUserIds($removeIds);
            
            return true;
        } else {
            
            return false;
        }
    }
    

    public function remove($ids){
        if (!$ids) {
            Yii::$service->helper->errors->add('remove id is empty');

            return null;
        }
        $removeIds = [];
        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                $model = $this->_model->findOne($id);
                if ($model->username !== 'admin') {
                    $model->delete();
                    $removeIds[] = $id;
                } else {
                    Yii::$service->helper->errors->add('you can not delete admin user');
                }
            }
        } else {
            $id = $ids;
            $model = $this->_model->findOne($id);
            if ($model->username !== 'admin') {
                $model->delete();
                $removeIds[] = $id;
            } else {
                Yii::$service->helper->errors->add('you can not delete admin user');
            }
        }

        return $removeIds;
    }
    
    /**
     * @param $ids | Int Array
     * @return 得到相应用户的数组。
     */
    public function getIdAndNameArrByIds($ids)
    {
        $user_coll = $this->_model->find()
            ->asArray()
            ->select(['id', 'username'])
            ->where([
                'in', 'id', $ids,
            ])->all();
        $users = [];
        foreach ($user_coll as $one) {
            $users[$one['id']] = $one['username'];
        }

        return $users;
    }

}
