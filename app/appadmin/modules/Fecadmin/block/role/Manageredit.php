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
            'tags' => $this->getTagsArr(),
            //'menu'    => self::getMenuStr(),
        ];
    }
	public function getGroupResources(){
        $role_id = Yii::$app->request->get('role_id');
		$groupResource = Yii::$service->admin->urlKey->getResourcesWithGroupAndSelected($role_id);
		
		return $groupResource;
	}
    public function getTagsArr(){

        return Yii::$service->admin->urlKey->getTags();
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
        $param = Yii::$app->request->post('editFormData');
        $saveStatus = Yii::$service->admin->role->saveRoleAndResources($param);
        if ($saveStatus){
            echo  json_encode(array(
                "statusCode"=>"200",
                "message"=>"save success",
            ));
            exit;
        } else {
            $errors = Yii::$service->helper->errors->get();
            echo  json_encode(["statusCode"=>"300",
                "message" => $errors,
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
        $this->_service->remove($ids);
        $errors = Yii::$service->helper->errors->get();
        if (!$errors) {
            echo  json_encode([
                'statusCode'=>'200',
                'message'=>'remove data  success',
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
}
















