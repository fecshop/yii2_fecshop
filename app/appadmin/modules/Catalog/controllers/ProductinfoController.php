<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

namespace fecshop\app\appadmin\modules\Catalog\controllers;

use fecshop\app\appadmin\modules\Catalog\CatalogController;
use Yii;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class ProductinfoController extends CatalogController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    public function actionManageredit()
    {
        // edit role,通过resource，判断当前用户是否有编辑（此处的编辑相当于查看产品的详细信息）所有产品的权限，默认，用户只有编辑查看自己发布的产品，而不能查看编辑其他用户的产品
        $resources = Yii::$service->admin->role->getCurrentRoleResources();
        $editAllKey = Yii::$service->admin->role->productEditAllRoleKey;
        $primaryKey = Yii::$service->product->getPrimaryKey();
        $product_id = Yii::$app->request->get($primaryKey);
        if ($product_id && (!is_array($resources) || !isset($resources[$editAllKey]) || !$resources[$editAllKey])) {
            $product = Yii::$service->product->getByPrimaryKey($product_id);
            if ($product['sku']) {
                $user = Yii::$app->user->identity;
                $created_user_id = $product['created_user_id'];
                if ($user->Id != $created_user_id) {
                    echo  json_encode([
                        'statusCode' => '300',
                        'message' => 'you donot have role to edit this product',
                    ]);
                    exit;
                }
            }
        }

        $data = $this->getBlock()->getLastData();

        return $this->render($this->action->id, $data);
    }

    // catalog
    public function actionImageupload()
    {
        $this->getBlock()->upload();
    }

    // catalog product
    public function actionGetproductcategory()
    {
        $this->getBlock()->getProductCategory();
    }

    public function actionManagereditsave()
    {
        // save role,通过resource，判断当前用户是否有保存所有产品的权限，默认，用户只有保存自己发布的产品，而不能保存其他用户创建的的产品
        $resources = Yii::$service->admin->role->getCurrentRoleResources();
        $saveAllKey = Yii::$service->admin->role->productSaveAllRoleKey;
        $primaryKey = Yii::$service->product->getPrimaryKey();
        $editFormData = Yii::$app->request->post('editFormData');
        $product_id = isset($editFormData[$primaryKey]) ? $editFormData[$primaryKey] : null;
        if ($product_id && (!is_array($resources) || !isset($resources[$saveAllKey]) || !$resources[$saveAllKey])) {
            $product = Yii::$service->product->getByPrimaryKey($product_id);
            if ($product['sku']) {
                $user = Yii::$app->user->identity;
                $created_user_id = $product['created_user_id'];
                if ($user->Id != $created_user_id) {
                    echo  json_encode([
                        'statusCode' => '300',
                        'message' => 'you donot have role to edit this product',
                    ]);
                    exit;
                }
            }
        }
        $data = $this->getBlock('manageredit')->save();

        return $this->render($this->action->id, $data);
    }

    public function actionManagerdelete()
    {
        // edit role,通过resource，判断当前用户是否有编辑（此处的编辑相当于查看产品的详细信息）所有产品的权限，默认，用户只有编辑查看自己发布的产品，而不能查看编辑其他用户的产品
        $resources = Yii::$service->admin->role->getCurrentRoleResources();
        $removeAllKey = Yii::$service->admin->role->productRemoveAllRoleKey;
        $primaryKey = Yii::$service->product->getPrimaryKey();
        $product_id = Yii::$app->request->get($primaryKey);
        $product_ids = Yii::$app->request->get($primaryKey.'s');
        if ($product_id && (!is_array($resources) || !isset($resources[$removeAllKey]) || !$resources[$removeAllKey])) {
            $product = Yii::$service->product->getByPrimaryKey($product_id);
            if ($product['sku']) {
                $user = Yii::$app->user->identity;
                $created_user_id = $product['created_user_id'];
                if ($user->Id != $created_user_id) {
                    echo  json_encode([
                        'statusCode' => '300',
                        'message' => 'you donot have role to edit this product',
                    ]);
                    exit;
                }
            }
        } else if ($product_ids && (!is_array($resources) || !isset($resources[$removeAllKey]) || !$resources[$removeAllKey])) {
            // 批量删除产品，各个产品的权限判断。
            $ids = explode(',', $product_ids);
            if (is_array($ids) && !empty($ids)) {
                foreach ($ids as $product_id) {
                    $product = Yii::$service->product->getByPrimaryKey($product_id);
                    if ($product['sku']) {
                        $user = Yii::$app->user->identity;
                        $created_user_id = $product['created_user_id'];
                        if ($user->Id != $created_user_id) {
                            echo json_encode([
                                'statusCode' => '300',
                                'message' => 'you donot have role to edit this product',
                            ]);
                            exit;
                        }
                    }
                }
            }
        }
        // 上面的代码都是权限判断，存在权限，才进行下面删除的操作。
        $data = $this->getBlock('manageredit')->delete();
    }
}
