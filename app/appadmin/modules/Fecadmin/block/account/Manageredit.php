<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Fecadmin\block\account;

use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use Yii;

/**
 * block cms\article.
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
    public $_saveUrl;

    public function init()
    {
        $this->_saveUrl = CUrl::getUrl('fecadmin/account/managereditsave');
        parent::init();
    }
    public function setService()
    {
        $this->_service = Yii::$service->adminUser->adminUser;
    }
    # 传递给前端的数据 显示编辑form
    public function getLastData(){
        $role_ids = $this->getUserRoleIds();
        return [
            'editBar' => $this->getEditBar(),
            'role_ids'=>$role_ids,
            'saveUrl' => CUrl::getUrl('fecadmin/account/managereditsave'),
        ];
    }

    public function save()
    {
        $request_param = CRequest::param();
        $this->_param = $request_param[$this->_editFormData];
        $roles = $request_param['role'];
        /*
         * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
         * you must convert string datetime to time , use strtotime function.
         */
        $this->_service->saveUserAndRole($this->_param, $roles);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode' => '200',
                'message' => Yii::$service->page->translate->__('Save Success') ,
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode'=>'300',
                'message'=>$errors,
            ]);
            exit;
        }
    }
    // 批量删除
    public function delete()
    {
        $ids = '';
        if ($id = CRequest::param($this->_primaryKey)) {
            $ids = $id;
        } elseif ($ids = CRequest::param($this->_primaryKey.'s')) {
            $ids = explode(',', $ids);
        }
        $this->_service->removeUserAndRole($ids);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode'=>'200',
                'message'=> Yii::$service->page->translate->__('Remove Success') ,
            ]);
            exit;
        } else {
            echo  json_encode([
                'statusCode'=>'300',
                'message'=>$errors,
            ]);
            exit;
        }
    }

    public function getEditArr(){
        $activeStatus = Yii::$service->adminUser->adminUser->getActiveStatus();
        $deleteStatus = Yii::$service->adminUser->adminUser->getDeleteStatus();
        return [
            [
                'label'=> Yii::$service->page->translate->__('User Name'),
                'name'=>'username',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'=> Yii::$service->page->translate->__('Password'),
                'name'=>'password',
                'display'=>[
                    'type' => 'inputPassword',
                ],
                'require' => 0,
            ],
            [
                'label'=> Yii::$service->page->translate->__('Email'),
                'name'=>'email',
                'require' => 0,
                'display'=>[
                    'type' => 'inputEmail',
                ],
            ],
            [
                'label'=> Yii::$service->page->translate->__('Name'),
                'name'=>'person',
                'require' => 0,
                'display'=>[
                    'type' => 'inputString',
                ],
            ],
            [
                'label'=> Yii::$service->page->translate->__('Worker No'),
                'name'=>'code',
                'require' => 1,
                'display'=>[
                    'type' => 'inputString',
                ],
            ],
            [
                'label'=> Yii::$service->page->translate->__('Status'),
                'name'=>'status',
                'display'=>[
                    'type' => 'select',
                    'data' => [
                        $activeStatus 	=> Yii::$service->page->translate->__('Enable'),
                        $deleteStatus 	=> Yii::$service->page->translate->__('Disable'),
                    ]
                ],
                'require' => 1,
                'default' => $activeStatus,
            ],
            [
                'label'=> Yii::$service->page->translate->__('Birth Date'),
                'name'=>'birth_date',
                'display'=>[
                    'type' => 'inputDate',
                ],
            ],
            [
                'label'=> Yii::$service->page->translate->__('Auth Key'),
                'name'=>'auth_key',
                'display'=>[
                    'type' => 'inputString',
                ],
            ],
            [
                'label'=> Yii::$service->page->translate->__('Access Token'),
                'name'=>'access_token',
                'display'=>[
                    'type' => 'inputString',
                ],
            ],
        ];
    }

    public function getUserRoleIds(){
        $primaryKey = Yii::$service->adminUser->adminUser->getPrimaryKey();
        $user_id = Yii::$app->request->get($primaryKey);
        $filter = [
            'where' => [
                ['user_id' => $user_id]
            ],
            'asArray' => true,
            'fetchAll' => true,
        ];
        $data = Yii::$service->admin->userRole->coll($filter);
        $role_ids = [];
        if (is_array($data['coll']) && !empty($data['coll'])) {
            foreach ($data['coll'] as $r) {
                $role_ids[] = $r['role_id'];
            }
        }

        return $role_ids;
    }

    public function getEditBar($editArr = []){
        if (empty($editArr)) {
            $editArr = $this->getEditArr();
        }
        $str = '';
        if ($this->_param[$this->_primaryKey]) {
            $str = '<input type="hidden"  value="'.$this->_param[$this->_primaryKey].'" size="30" name="editFormData['.$this->_primaryKey
            .']" class="textInput ">';
        }
        foreach ($editArr as $column) {
            $name = $column['name'];
            $require = $column['require'] ? 'required' : '';
            $label = $column['label'] ? $column['label'] : $this->_one->getAttributeLabel($name);
            $display = isset($column['display']) ? $column['display'] : '';
            if (empty($display)) {
                $display = ['type' => 'inputString'];
            }
            $value = $this->_one[$name] ? $this->_one[$name] : $column['default'];
            $display_type = isset($display['type']) ? $display['type'] : 'inputString';
            if ($display_type == 'inputString') {
                $str .='<p>
							<label>'.$label.'：</label>
							<input type="text"  value="'.$value.'" size="30" name="editFormData['.$name.']" class="textInput '.$require.' ">
						</p>';
            } else if ($display_type == 'inputDate') {
                $str .='<p>
							<label>'.$label.'：</label>
							<input type="text"  value="'.($value ? date("Y-m-d",strtotime($value)) : '').'" size="30" name="editFormData['.$name.']" class="date textInput '.$require.' ">
						</p>';
            } else if ($display_type == 'inputEmail') {
                $str .='<p>
							<label>'.$label.'：</label>
							<input type="text"  value="'.$value.'" size="30" name="editFormData['.$name.']" class="email textInput '.$require.' ">
						</p>';
            } else if ($display_type == 'inputPassword') {
                $str .='<p>
							<label>'.$label.'：</label>
							<input type="password"  value="" size="30" name="editFormData['.$name.']" class=" textInput '.$require.' ">
						</p>';
            } else if ($display_type == 'select') {
                $data = isset($display['data']) ? $display['data'] : '';
                //var_dump($data);
                //echo $value;
                $select_str = '';
                if(is_array($data)) {
                    $select_str .= '<select class="combox '.$require.'" name="editFormData['.$name.']" >';
                    $select_str .='<option value="">'.$label.'</option>';
                    foreach($data as $k => $v){
                        if($value == $k){
                            //echo $value."#".$k;
                            $select_str .='<option selected="selected" value="'.$k.'">'.$v.'</option>';
                        }else{
                            $select_str .='<option value="'.$k.'">'.$v.'</option>';
                        }

                    }
                    $select_str .= '</select>';
                }
                $str .='<p>
							<label>'.$label.'：</label>
							'.$select_str.'
						</p>';
            }
        }
        return $str;
    }

}
















