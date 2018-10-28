<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Fecadmin\block\role;

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
        $this->_saveUrl = CUrl::getUrl('fecadmin/role/managereditsave');
        parent::init();
    }


    # 传递给前端的数据 显示编辑form
    public function getLastData(){
        return [
            'editBar' => $this->getEditBar(),
            'saveUrl' => CUrl::getUrl('fecadmin/role/managereditsave'),
			'groupResources' => $this->getGroupResources(),
            //'menu'    => self::getMenuStr(),
        ];
    }
	public function getGroupResources(){
		$groupResource = Yii::$service->admin->urlKey->getGroupsResources();
		
		return $groupResource;
	}
    public function setService()
    {
        $this->_service = Yii::$service->admin->role;;
    }

    public function getEditArr(){
        return [
            [
                'label'=>'权限名称',
                'name'=>'role_name',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],
            [
                'label'=>'权限描述',
                'name'=>'role_description',
                'display'=>[
                    'type' => 'inputString',
                ],
                'require' => 1,
            ],

        ];

    }

    # 保存
    public function save(){
        $request_param 		= CRequest::param();
        $this->_param		= $request_param['editFormData'];
        $this->initParam();
        //$model = $this->_one;
        $this->_one->attributes = $this->_param;

        if($this->_one[$this->_paramKey]){
            if(CConfig::param("is_demo")){
                if($this->_one[$this->_paramKey] == 4){
                    echo  json_encode(array(
                        "statusCode"=>"300",
                        "message"=>"demo版本，不允许编辑admin role",
                    ));
                    exit;
                }
            }
            if ($this->_one->validate()) {
                $this->saveMenuAndRole();
                //$this->_one->save();
                echo  json_encode(array(
                    "statusCode"=>"200",
                    "message"=>"update",
                ));
                exit;
            }
        }else{
            if ($this->_one->validate()) {
                //$this->_one->save();
                $this->saveMenuAndRole();
                echo  json_encode(array(
                    "statusCode"=>"200",
                    "message"=>"insert",
                ));
                exit;
            }
        }
        $errors = $this->_one->errors;
        echo  json_encode(["statusCode"=>"300",
            "message" => CModel::getErrorStr($errors),
        ]);
        exit;

    }

    # 批量删除
    public function delete(){
        //$request_param 		= CRequest::param();
        //$this->_param		= $request_param;
        //$this->initParam();
        if($role_id = CRequest::param($this->_paramKey)){
            $model = AdminRole::findOne([$this->_paramKey => $role_id]);
            if($model->role_id){
                # 不允许删除admin
                if(CConfig::param("is_demo")){
                    if($model->role_id == 4){
                        echo  json_encode(["statusCode"=>"300",
                            "message" => 'demo版本，不允许编辑admin',
                        ]);
                        exit;
                    }
                }
                $innerTransaction = Yii::$app->db->beginTransaction();
                try {
                    $model->delete();
                    # 删除这个role 对应的所有关联的菜单
                    AdminRoleMenu::deleteAll(['role_id' => $role_id]);
                    AdminUserRole::deleteAll(['role_id' => $role_id]);
                    $innerTransaction->commit();
                } catch (Exception $e) {
                    $innerTransaction->rollBack();
                }
                echo  json_encode(["statusCode"=>"200",
                    "message" => 'Delete Success!',
                ]);
                exit;
            }else{
                echo  json_encode(["statusCode"=>"300",
                    "message" => "role_id => $role_id , is not exist",
                ]);
                exit;
            }
        }else if($ids = CRequest::param($this->_paramKey.'s')){
            $id_arr = explode(",",$ids);

            $innerTransaction = Yii::$app->db->beginTransaction();
            try {
                # 不允许删除admin
                if(CConfig::param("is_demo")){
                    if(in_array(4,$id_arr)){
                        echo  json_encode(["statusCode"=>"300",
                            "message" => 'demo版本，不允许删除admin',
                        ]);
                        $innerTransaction->rollBack();
                        exit;
                    }
                }
                AdminRole::deleteAll(['in','role_id',$id_arr]);
                # 删除这个role 对应的所有关联的菜单
                AdminUserRole::deleteAll(['in','role_id',$id_arr]);
                $innerTransaction->commit();
            } catch (Exception $e) {
                $innerTransaction->rollBack();
            }
            echo  json_encode(["statusCode"=>"200",
                "message" => "$ids Delete Success!",
            ]);
            exit;
        }
        echo  json_encode(["statusCode"=>"300",
            "message" => "role_id or ids Param is not Exist!",
        ]);
        exit;

    }



}
















